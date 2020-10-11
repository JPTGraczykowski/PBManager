<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\categories\Category;
use \App\Models\Categories\ExpenseCategoriesAssignedToUser;
use \App\Models\Categories\IncomeCategoriesAssignedToUser;
use \App\Models\PaymentMethodsAssignedToUser;
use \App\DateHelper;

class Balance extends Authenticated
{
  public $expense_categories = [];
  public $income_categories = [];
  public $start_date;
  public $end_date;
  public $incomes;
  public $expenses;
  public $incomes_grouped_to_categories;
  public $expenses_grouped_to_categories;

  protected function before()
  {
      parent::before();
      $this->setDateRange();
      $this->incomes = Income::getIncomesAssignedToUser($this->start_date, $this->end_date);
      $this->expenses = Expense::getExpensesAssignedToUser($this->start_date, $this->end_date);
      $this->incomes_grouped_to_categories = $this->getIncomesGroupedToCategories();
      $this->expenses_grouped_to_categories = $this->getExpensesGroupedToCategories();
      $this->sum_of_incomes = $this->sumUpGenerally($this->incomes);
      $this->sum_of_expenses = $this->sumUpGenerally($this->expenses);
  }

  public function showAction()
  {
    View::renderTemplate('Balance/show.html', [
      'start_date' => $this->start_date,
      'end_date' => $this->end_date,
      'incomes' => $this->incomes_grouped_to_categories,
      'expenses' => $this->expenses_grouped_to_categories,
      'sum_of_incomes' => $this->sum_of_incomes,
      'sum_of_expenses' => $this->sum_of_expenses
    ]);
  }

  public function showDetailedIncomesAction()
  {
    $response = [];
    $category_name = $_GET['category_name'];
    $category_id = Category::getCategoryIdByName($category_name, 'income');

    foreach($this->incomes as &$income) {
      if ($income['income_category_assigned_to_user_id'] == $category_id) {
        array_push($response, array('date_of_income' => $income['date_of_income'],
                                'amount' => $income['amount'],
                                'income_comment' => $income['income_comment']));
      }
    }
    
    echo json_encode($response);
  }

  public function showDetailedExpensesAction()
  {
    $response = [];
    $category_name = $_GET['category_name'];
    $category_id = Category::getCategoryIdByName($category_name, 'expense');
    
    foreach($this->expenses as &$expense) {
      if ($expense['expense_category_assigned_to_user_id'] == $category_id) {
        array_push($response, array('date_of_expense' => $expense['date_of_expense'],
                                'amount' => $expense['amount'],
                                'payment_method' => PaymentMethodsAssignedToUser::getPaymentMethodNameById($expense['payment_method_assigned_to_user_id']),
                                'expense_comment' => $expense['expense_comment']));
      }
    }
    
    echo json_encode($response);
  }

  private function setDateRange()
  {
    if(isset($_POST["start_date"]))
    {
      $this->start_date = $_POST['start_date'];
      $this->end_date = $_POST['end_date'];
    }
    elseif(isset($_POST['time_period']))
    {
      $this->start_date = DateHelper::setStartDate($_POST['time_period']);
      $this->end_date = DateHelper::setEndDate($_POST['time_period']);
    }
    else
    {
      $this->start_date = DateHelper::setStartDate('total_time');
      $this->end_date = DateHelper::setEndDate('total_time');
    }
  }

  public function getIncomesGroupedToCategories()
  {
    $income_categories = IncomeCategoriesAssignedToUser::getCategories();
    $incomes_grouped_to_categories = [];
    for ($i = 0; $i < sizeof($income_categories); $i++) {

      $sum_in_the_category = $this->sumUpInTheCategory(
                                      $income_categories[$i][0],
                                      $this->incomes,
                                      'income'
                                    );

      if ($sum_in_the_category > 0) {
        array_push(
          $incomes_grouped_to_categories, 
          [
            $income_categories[$i][1] => $sum_in_the_category
          ]
        );
      }
    }

    return $incomes_grouped_to_categories;
  }

  public function getExpensesGroupedToCategories()
  {
    $expense_categories = ExpenseCategoriesAssignedToUser::getCategories();
    $expenses_grouped_to_categories = [];
    for ($i = 0; $i < sizeof($expense_categories); $i++) {

      $sum_in_the_category = $this->sumUpInTheCategory(
                                      $expense_categories[$i][0],
                                      $this->expenses,
                                      'expense'
                                    );

      if ($sum_in_the_category > 0) {
        array_push(
          $expenses_grouped_to_categories,
          [
            $expense_categories[$i][1] => $sum_in_the_category
          ]
        );
      }
    }

    return $expenses_grouped_to_categories;
  }

  public function sumUpInTheCategory($category_id, $current_user_tansactions, $transaction_prefix)
  {
    $sum = 0;
    $transaction_categories = $transaction_prefix.'_category_assigned_to_user_id';
    foreach($current_user_tansactions as &$transaction)
    {
      if($transaction[$transaction_categories] == $category_id)
      {
        $sum += floatval($transaction['amount']);
      }
    }
    return $sum;
  }

  public function sumUpGenerally($transactions_from_time_period)
  {
    $sum = 0;
    foreach($transactions_from_time_period as &$transaction)
    {
      $sum += floatval($transaction['amount']);
    }
    return $sum;
  }
}