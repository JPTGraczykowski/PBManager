<?php

namespace App\Controllers;

use Core\View;
use App\Models\Income;
use App\Models\Categories\IncomeCategoriesAssignedToUser;
use App\Flash;

class IncomeController extends Authenticated
{
  public $income_categories;

  protected function before()
    {
        parent::before();

        $this->income_categories = IncomeCategoriesAssignedToUser::getCategories();
    }

  public function newAction()
  {
    View::renderTemplate('Income/new.html', [
      'income_categories' => $this->income_categories
      ]);
  }

  public function createAction()
  {
    $income = new Income($_POST);

    if ($income->save()) {
      Flash::addMessage('Income added successfully');
      $this->redirect('/IncomeController/new');
    } else {
      Flash::addMessage('Unsuccessful adding income', Flash::WARNING);
      View::renderTemplate('/Income/new.html', [
          'income_categories' => $this->income_categories,
          'income' => $income
      ]);
  }
  }
}