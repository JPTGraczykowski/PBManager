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
      Flash::addMessage('The income category added successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful adding the new category', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function addExpenseCategoryAction()
  {
    if (ExpenseCategoriesAssignedToUser::addNewCategory($_POST['category_name'])) {
      Flash::addMessage('The expense category added successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful adding the new category', Flash::WARNING);
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
      Flash::addMessage('The category name editted successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful editting the category name', Flash::WARNING);
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
      Flash::addMessage('The category deleted successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful deleting the category', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function setLimitAction()
  {
    if (ExpenseCategoriesAssignedToUser::setLimit($_POST['limit'], $_POST['category_id'])) {
      Flash::addMessage('The limit has been set');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful setting the limit', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function unsetLimitAction()
  {
    if (ExpenseCategoriesAssignedToUser::unsetLimit($_POST['category_id'])) {
      return Flash::addMessage('The limit has been unset');
    } else {
      return Flash::addMessage('Unsuccessful unsetting the limit', Flash::WARNING);
    }
  }

  public function limitExistsAction()
  {
    echo json_encode(ExpenseCategoriesAssignedToUser::limitExists($_GET['category_id']));
  }

  public function getCategoryLimit()
  {
    echo json_encode(ExpenseCategoriesAssignedToUser::getCategoryLimit($_GET['category_id']));
  }
}