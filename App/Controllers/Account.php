<?php

namespace App\Controllers;

use \App\Models\User;
use \App\Auth;
use \App\Flash;

class Account extends Authenticated
{
    protected function before()
    {
        parent::before();
        $this->user = Auth::getUser();
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