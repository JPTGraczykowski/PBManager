<?php

namespace App\Controllers;

use Core\View;
use App\Auth;

class UserMenu extends Authenticated
{
  protected function before()
  {
      parent::before();

      $this->user = Auth::getUser();
  }


  public function showAction()
  {
    View::renderTemplate('UserMenu/show.html', [
      'user' => $this->user
      ]);
  }
}