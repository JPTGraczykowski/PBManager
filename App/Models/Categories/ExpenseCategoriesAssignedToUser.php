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
            VALUES (NULL, :user_id, :name, NULL)';

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
    $name = static::setNameToCamelCase($name);

    if (static::categoryNameExists($name, 'expense') || $name == '') {
      return false;
    } else {
      $sql = 'INSERT INTO expenses_category_assigned_to_users
              VALUES (NULL, :user_id, :name, NULL)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);

      return $stmt->execute();
    }
  }

  public static function setLimit($limit, $category_id)
  {
    if (static::validateLimit($limit)) {
      $sql = 'UPDATE expenses_category_assigned_to_users
            SET `limit` = :limit
            WHERE id = :category_id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':limit', $limit, PDO::PARAM_STR);
      $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);

      return $stmt->execute();
    }
  }

  public static function unsetLimit($category_id)
  {
    $sql = 'UPDATE expenses_category_assigned_to_users
            SET `limit` = NULL
            WHERE id = :category_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);

    return $stmt->execute();
  }

  public static function validateLimit($limit)
  {
    if ($limit == '') {
      return false;
    }
    if ($limit <= 0 || $limit > 99999999.99) {
      return false;
    }

    $limit = str_replace(',', '.', $limit);

    if (filter_var($limit, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) === false) {
        return false;
    }

    return true;
  }

  public static function limitExists($category_id)
  {
    $sql = 'SELECT `limit` FROM expenses_category_assigned_to_users WHERE id = :category_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->fetch(PDO::FETCH_COLUMN, 0) != NULL) {
      return true;
    } else {
      return false;
    }
  }

  public static function getCategoryLimit($category_id)
  {
    $sql = 'SELECT `limit` FROM expenses_category_assigned_to_users WHERE id = :category_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_STR);

    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN, 0);
  }
}