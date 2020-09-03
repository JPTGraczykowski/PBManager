<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Auth;

class Account extends \Core\Controller
{
    public function validateEmailAction()
    {
        $is_valid = ! User::emailExists($_GET['email'], $_GET['ignore_id'] ?? null);

        header('Content-Type: application/json');
       
        echo json_encode($is_valid);
    }

    public function checkPasswordAction()
    {
        $is_correct = false;
        $user = Auth::getUser();
        $email = $user->getEmail();
  
        if (User::authenticate($email, $_GET['old_password'])) {
          $is_correct = true;
        }
  
        header('Content-Type: application/json');
        echo json_encode($is_correct);
    }
}