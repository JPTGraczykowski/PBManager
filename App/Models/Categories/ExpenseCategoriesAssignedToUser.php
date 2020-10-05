<?php

namespace App\Models\Categories;

use \App\Auth;
use \App\Models\User;
use PDO;

class ExpenseCategoriesAssignedToUser extends Category
{

  public static function getCategories()
  {
    $sql = 'SELECT id, name FROM expenses_category_assigned_to_users WHERE user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll();
  }

  public static function assignDefaultCategories($new_user_email)
  {
    $default_expense_categories = static::getDefaultCategories('expense');
    $new_user_id = User::getUserIdByEmail($new_user_email);

    $sql = 'INSERT INTO expenses_category_assigned_to_users
            VALUES (NULL, :user_id, :name)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    for ($i=0; $i<count($default_expense_categories); $i++)
    { 
      $stmt->bindValue(':user_id', $new_user_id, PDO::PARAM_INT);
      $stmt->bindValue(':name', $default_expense_categories[$i], PDO::PARAM_STR);
      $stmt->execute();
    }
  }

  public static function addNewCategory($name)
  {
    if (static::categoryNameExists($name, 'expense') || $name == '') {

      return false;

    } else {

      $sql = 'INSERT INTO expenses_category_assigned_to_users
              VALUES (NULL, :user_id, :name)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);

      return $stmt->execute();

    }
  }
}