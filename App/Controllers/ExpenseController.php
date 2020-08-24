<?php

namespace App\Controllers;

use Core\View;
use App\Models\Expense;
use App\Models\ExpenseCategoriesAssignedToUser;
use App\Models\PaymentMethodsAssignedToUser;
use App\Flash;

class ExpenseController extends Authenticated
{
  public $expense_categories;

  protected function before()
  {
      parent::before();

      $this->expense_categories = ExpenseCategoriesAssignedToUser::getCategories();
      $this->payment_methods = PaymentMethodsAssignedToUser::getPaymentMethods();
  }

  public function newAction()
  {
    View::renderTemplate('Expense/new.html', [
      'expense_categories' => $this->expense_categories,
      'payment_methods' => $this->payment_methods
      ]);
  }

  public function createAction()
  {
    $expense = new Expense($_POST);

    if ($expense->save()) {
      Flash::addMessage('Expense added successfully');
      $this->redirect('/ExpenseController/new');
    } else {
      Flash::addMessage('Unsuccessful adding expense', Flash::WARNING);
      View::renderTemplate('/Expense/new.html', [
          'expense_categories' => $this->expense_categories,
          'payment_methods' => $this->payment_methods,
          'expense' => $expense
      ]);
  }
  }
}