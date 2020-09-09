<?php

namespace App\Controllers;

use \Core\View;
use \App\Auth;
use \App\Flash;
use \App\Models\PaymentMethodsAssignedToUser;

class PaymentMethod extends Authenticated
{
  public function addPaymentMethodAction()
  {
    PaymentMethodsAssignedToUser::addNewMethod($_POST['method_name']);
  }
}