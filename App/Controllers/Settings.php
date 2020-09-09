<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Categories\ExpenseCategoriesAssignedToUser;
use \App\Models\Categories\IncomeCategoriesAssignedToUser;
use \App\Models\PaymentMethodsAssignedToUser;

class Settings extends Authenticated
{
  public $expense_categories = [];
  public $income_categories = [];
  public $payment_methods = [];

  protected function before()
  {
    parent::before();
    $this->expense_categories = ExpenseCategoriesAssignedToUser::getCategories();
    $this->income_categories = IncomeCategoriesAssignedToUser::getCategories();
    $this->payment_methods = PaymentMethodsAssignedToUser::getPaymentMethods();
    $this->user = Auth::getUser();
  }

  public function showAction()
  {
    View::renderTemplate(
      'Settings/show.html', [
        'expense_categories' => $this->expense_categories,
        'income_categories' => $this->income_categories,
        'payment_methods' => $this->payment_methods,
        'user' => $this->user
      ]
    );
  }
}