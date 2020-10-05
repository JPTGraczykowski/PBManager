<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\Categories\Category;
use \App\Models\Categories\ExpenseCategoriesAssignedToUser;
use \App\Models\Categories\IncomeCategoriesAssignedToUser;

class CategoryController extends Authenticated
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

  public function validateIncomeCategoryNameAction()
  {
    $is_valid = ! Category::categoryNameExists($_GET['category_name'], 'income', $_GET['ignore_id'] ?? null);

    header('Content-Type: application/json');
    
    echo json_encode($is_valid);
  }

  public function validateExpenseCategoryNameAction()
  {
    $is_valid = ! Category::categoryNameExists($_GET['category_name'], 'expense', $_GET['ignore_id'] ?? null);

    header('Content-Type: application/json');
    
    echo json_encode($is_valid);
  }

  public function editCategoryName()
  {
    if (Category::editCategoryName($_POST['category_name'], $_POST['category_id'], $_POST['transaction_type'])) {
      Flash::addMessage('Category name editted successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful editting category name', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function deleteCategory()
  {
    if (Category::isTheOnlyCategory($_POST['category_id'], $_POST['transaction_type']) == 1) {
      Flash::addMessage('This is the only category. It can not be deleted', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
    elseif (Category::analyseDeletingCategory($_POST['category_id'], $_POST['transaction_type'])) {
      Flash::addMessage('Category deleted successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful deleting category', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }
}