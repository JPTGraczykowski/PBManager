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
    if (static::paymentMethodExists($name) || $name == '') {

      return false;

    } else {

      $sql = 'INSERT INTO payment_methods_assigned_to_users
              VALUES (NULL, :user_id, :name)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);

      return $stmt->execute();
    }
  }

  public static function paymentMethodExists($name)
  {
    return static::findByPaymentMethodName($name) !== false;
  }

  public static function findByPaymentMethodName($name)
  {
      $sql = 'SELECT * FROM payment_methods_assigned_to_users WHERE name = :name';

      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);

      $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

      $stmt->execute();

      return $stmt->fetch();
  }

  public static function editMethodName($name, $edited_method_id)
  {
    if (static::paymentMethodExists($name) || $name == '') {

      return false;

    } else {

      $sql = 'UPDATE payment_methods_assigned_to_users
              SET name = :name
              WHERE id = :id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':id', $edited_method_id, PDO::PARAM_INT);

      return $stmt->execute();

    }
  }

  public static function analyseDeletingPaymentMethod($method_id)
  {
    $number_of_usage_this_method = static::findNumberOfUsageTheMethod($method_id);
    if ($number_of_usage_this_method == 0) {
      return static::deleteMethod($method_id);
    }
    else {
      return false;
    }
  }

  public static function findNumberOfUsageTheMethod($method_id)
  {
    $sql = 'SELECT * FROM expenses
            WHERE payment_method_assigned_to_user_id = :method_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':method_id', $method_id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }

  public static function deleteMethod($method_id)
  {
    $sql = 'DELETE FROM payment_methods_assigned_to_users
            WHERE id = :method_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':method_id', $method_id, PDO::PARAM_INT);

    return $stmt->execute();
  }
}