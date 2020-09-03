<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeCategoriesAssignedToUser;
use \App\Models\ExpenseCategoriesAssignedToUser;
use \App\Models\PaymentMethodsAssignedToUser;

class Signup extends \Core\Controller
{

    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

    
    public function createAction()
    {
        $user = new User($_POST);
        $new_user_email = $user->email;

        if ($user->save()) {
            IncomeCategoriesAssignedToUser::assignDefaultCategories($new_user_email);
            ExpenseCategoriesAssignedToUser::assignDefaultCategories($new_user_email);
            PaymentMethodsAssignedToUser::assignDefaultPaymentMethods($new_user_email);
            $this->redirect('/Signup/success');
        } else {
            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
        }
    }


    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }
}