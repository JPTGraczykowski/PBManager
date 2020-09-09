<?php

namespace App\Models;

use \App\Auth;
use \App\Models\User;
use PDO;

class PaymentMethodsAssignedToUser extends \Core\Model
{

  public static function getPaymentMethods()
  {
    $sql = 'SELECT id, name FROM payment_methods_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
  }

  public static function assignDefaultPaymentMethods($new_user_email)
  {
    $default_payment_methods = static::getDefaultPaymentMethods();
    $new_user_id = User::getUserIdByEmail($new_user_email);

    $sql = 'INSERT INTO payment_methods_assigned_to_users
            VALUES (NULL, :user_id, :name)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    for ($i=0; $i<count($default_payment_methods); $i++)
    { 
      $stmt->bindValue(':user_id', $new_user_id, PDO::PARAM_INT);
      $stmt->bindValue(':name', $default_payment_methods[$i], PDO::PARAM_STR);
      $stmt->execute();
    }
  }

  public static function getDefaultPaymentMethods()
  {
    $sql = 'SELECT name FROM payment_methods_default';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  public static function addNewMethod($name)
  {
    $sql = 'INSERT INTO payment_methods_assigned_to_users
            VALUES (NULL, :user_id, :name)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);

    $stmt->execute();
  }
}