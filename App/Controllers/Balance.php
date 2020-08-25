<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\ExpenseCategoriesAssignedToUser;
use \App\Models\IncomeCategoriesAssignedToUser;
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
  public $incomes_in_categories;
  public $expenses_in_categories;

  protected function before()
  {
      parent::before();
      $this->setDateRange();
      $this->incomes = Income::getIncomesAssignedToUser($this->start_date, $this->end_date);
      $this->expenses = Expense::getExpensesAssignedToUser($this->start_date, $this->end_date);
      $this->incomes_in_categories = $this->getIncomesInCategories();
      $this->expenses_in_categories = $this->getExpensesInCategories();
      $this->sum_of_incomes = $this->sumUpGenerally($this->incomes);
      $this->sum_of_expenses = $this->sumUpGenerally($this->expenses);
  }

  public function showAction()
  {
    View::renderTemplate('Balance/show.html', [
      'start_date' => $this->start_date,
      'end_date' => $this->end_date,
      'incomes' => $this->incomes_in_categories,
      'expenses' => $this->expenses_in_categories,
      'sum_of_incomes' => $this->sum_of_incomes,
      'sum_of_expenses' => $this->sum_of_expenses
    ]);
  }

  private function setDateRange()
  {
    if(isset($_POST["start_date"]))
    {
      $this->start_date = $_POST["start_date"];
      $this->end_date = $_POST["end_date"];
    }
    elseif(isset($_POST['time_period']))
    {
      $this->start_date = DateHelper::setStartDate($_POST['time_period']);
      $this->end_date = DateHelper::setEndDate($_POST['time_period']);
    }
    else
    {
      $this->start_date = DateHelper::setStartDate("total_time");
      $this->end_date = DateHelper::setEndDate("total_time");
    }
  }

  public function getIncomesInCategories()
  {
    $income_categories = IncomeCategoriesAssignedToUser::getCategories();
    $incomes_in_categories = [];
    for ($i = 0; $i < sizeof($income_categories); $i++) {
      array_push(
        $incomes_in_categories,
        [
          $income_categories[$i][1] => $this->sumUpInTheCategory(
            $income_categories[$i][0],
            $this->incomes,
            'income'
          )
        ]
      );
    }
    return $incomes_in_categories;
  }

  public function getExpensesInCategories()
  {
    $expense_categories = ExpenseCategoriesAssignedToUser::getCategories();
    $expenses_in_categories = [];
    for ($i = 0; $i < sizeof($expense_categories); $i++) {
      array_push(
        $expenses_in_categories,
        [
          $expense_categories[$i][1] => $this->sumUpInTheCategory(
            $expense_categories[$i][0],
            $this->expenses,
            'expense'
          )
        ]
      );
    }
    return $expenses_in_categories;
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