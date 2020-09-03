<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\ExpenseCategoriesAssignedToUser;
use \App\Models\IncomeCategoriesAssignedToUser;
use \App\Models\PaymentMethodsAssignedToUser;
use \App\Models\User;

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

  public function updateNameAction()
  {
    if ($this->user->updateName($_POST['name'])) {
      Flash::addMessage('Name changed successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful changing name', Flash::WARNING);
    }
  }

  public function updateEmailAction()
  {
    if ($this->user->updateEmail($_POST['email'])) {
      Flash::addMessage('Email changed successfully');
      $this->redirect('/Settings/show');
    }  else {
      Flash::addMessage('Unsuccessful changing email', Flash::WARNING);
    }
  }

  public function updatePasswordAction()
  {
    if ($this->user->updatePassword($_POST['old_password'], $_POST['new_password'], $_POST['password_confirmation'])) {
      Flash::addMessage('Password changed successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful changing password', Flash::WARNING);
    }
  }
}