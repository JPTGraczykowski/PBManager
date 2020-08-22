<?php

namespace App\Models;

use \App\Auth;
use \App\Models\User;
use PDO;

class IncomeCategoriesAssignedToUser extends \Core\Model
{

  public static function getCategories()
  {
    $sql = 'SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
  }

  public static function assignDefaultCategories($new_user_email)
  {
    $default_income_categories = static::getDefaultCategories();
    $new_user_id = User::getUserIdByEmail($new_user_email);

    $sql = 'INSERT INTO incomes_category_assigned_to_users
            VALUES (NULL, :user_id, :name)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    for ($i=0; $i<count($default_income_categories); $i++)
    { 
      $stmt->bindValue(':user_id', $new_user_id, PDO::PARAM_INT);
      $stmt->bindValue(':name', $default_income_categories[$i], PDO::PARAM_STR);
      $stmt->execute();
    }
  }

  public static function getDefaultCategories()
  {
    $sql = 'SELECT name FROM incomes_category_default';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }
}