<?php

namespace App\Models\Categories;

use \App\Auth;
use \App\Models\User;
use PDO;

class Category extends \Core\Model
{
  public static function getDefaultCategories($transaction_type)
  {
    $sql = 'SELECT name FROM ' . $transaction_type . 's_category_default';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  public static function categoryNameExists($name, $transaction_type)
  {
    return static::findByCategoryName($name, $transaction_type) !== false;
  }

  public static function findByCategoryName($name, $transaction_type)
  {
      $sql = 'SELECT * FROM ' . $transaction_type . 's_category_assigned_to_users
              WHERE name = :name';

      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);

      $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

      $stmt->execute();

      return $stmt->fetch();
  }

  public static function editCategoryName($name, $edited_category_id, $transaction_type)
  {
    if (static::categoryNameExists($name, $transaction_type) || $name == '') {

      return false;

    } else {

      $sql = 'UPDATE '.$transaction_type.'s_category_assigned_to_users
              SET name = :name
              WHERE id = :id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':id', $edited_category_id, PDO::PARAM_INT);

      return $stmt->execute();

    }
  }

  public static function analyseDeletingCategory($category_id, $transaction_type)
  {
    $number_of_usage_this_category = static::findNumberOfUsageTheCategory($category_id, $transaction_type);
    if ($number_of_usage_this_category == 0) {
      return static::deleteCategory($category_id, $transaction_type);
    }
    else {
      return false;
    }
  }

  public static function findNumberOfUsageTheCategory($category_id, $transaction_type)
  {
    $sql = 'SELECT * FROM ' . $transaction_type . 's
            WHERE ' . $transaction_type . '_category_assigned_to_user_id = :category_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }

  public static function deleteCategory($category_id, $transaction_type)
  {
    $sql = 'DELETE FROM ' . $transaction_type . 's_category_assigned_to_users
            WHERE id = :id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);

    return $stmt->execute();
  }
}