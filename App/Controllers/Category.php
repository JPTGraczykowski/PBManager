<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Categories\ExpenseCategoriesAssignedToUser;
use \App\Models\Categories\IncomeCategoriesAssignedToUser;

class Category extends Authenticated
{
  public function addIncomeCategoryAction()
  {
    if (IncomeCategoriesAssignedToUser::addNewCategory($_POST['category_name'])) {
      Flash::addMessage('Income category added successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful adding new category', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function addExpenseCategoryAction()
  {
    if (ExpenseCategoriesAssignedToUser::addNewCategory($_POST['category_name'])) {
      Flash::addMessage('Expense category added successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful adding new category', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }
}