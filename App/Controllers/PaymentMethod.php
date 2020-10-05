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
    if (PaymentMethodsAssignedToUser::addNewMethod($_POST['method_name'])) {
      Flash::addMessage('Payment method added successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful adding new payment method', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function validatePaymentMethodNameAction()
  {
    $is_valid = ! PaymentMethodsAssignedToUser::paymentMethodExists($_GET['method_name'], $_GET['ignore_id'] ?? null);

    header('Content-Type: application/json');
    
    echo json_encode($is_valid);
  }

  public function editPaymentMethodNameAction()
  {
    if (PaymentMethodsAssignedToUser::editMethodName($_POST['method_name'], $_POST['method_id'])) {
      Flash::addMessage('Method name editted successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful editting method name', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }

  public function deleteMethod()
  {
    if (PaymentMethodsAssignedToUser::isTheOnlyPaymentMethod($_POST['method_id']) == 1) {
      Flash::addMessage('This is the only payment method. It can not be deleted', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
    elseif (PaymentMethodsAssignedToUser::analyseDeletingPaymentMethod($_POST['method_id'])) {
      Flash::addMessage('Payment method deleted successfully');
      $this->redirect('/Settings/show');
    } else {
      Flash::addMessage('Unsuccessful deleting payment method', Flash::WARNING);
      $this->redirect('/Settings/show');
    }
  }
}