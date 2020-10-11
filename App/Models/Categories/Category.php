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
      $name = static::setNameToCamelCase($name);
      
      $sql = 'SELECT * FROM ' . $transaction_type . 's_category_assigned_to_users
              WHERE name = :name AND user_id = :user_id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

      $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

      $stmt->execute();

      return $stmt->fetch();
  }

  public static function getCategoryIdByName($name, $transaction_type)
  {
      $sql = 'SELECT id FROM ' . $transaction_type . 's_category_assigned_to_users
              WHERE name = :name AND user_id = :user_id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

      $stmt->execute();

      return $stmt->fetch(PDO::FETCH_COLUMN, 0);
  }

  public static function editCategoryName($name, $edited_category_id, $transaction_type)
  {
    if (static::categoryNameExists($name, $transaction_type) || $name == '') {

      return false;

    } else {

      $sql = 'UPDATE '.$transaction_type.'s_category_assigned_to_users
              SET name = :name
              WHERE id = :category_id';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':name', $name, PDO::PARAM_STR);
      $stmt->bindValue(':category_id', $edited_category_id, PDO::PARAM_INT);

      return $stmt->execute();

    }
  }

  public static function analyseDeletingCategory($category_id, $transaction_type)
  {
    $number_of_usage_this_category = static::findNumberOfUsageTheCategory($category_id, $transaction_type);

    if ($number_of_usage_this_category == 0) {

      return static::deleteCategory($category_id, $transaction_type);

    } else {

      if (!static::categoryNameExists('Default', $transaction_type)) {

        static::createTheDefaultCategory($transaction_type);

      }

      $the_default_category_id = static::getCategoryIdByName('Default', $transaction_type);
      static::assignToTheDefaultCategory($category_id, $transaction_type, $the_default_category_id);
      return static::deleteCategory($category_id, $transaction_type);

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
            WHERE id = :category_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public static function getTheDefaultCategoryId($transaction_type)
  {
    $sql = 'SELECT id FROM ' . $transaction_type . 's_category_assigned_to_users
            WHERE name = Default';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
  }

  public static function assignToTheDefaultCategory($category_id, $transaction_type, $the_default_category_id)
  {
    $sql = 'UPDATE ' . $transaction_type . 's
            SET ' . $transaction_type . '_category_assigned_to_user_id = :the_default_category_id
            WHERE ' . $transaction_type . '_category_assigned_to_user_id = :category_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':the_default_category_id', $the_default_category_id, PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);

    return $stmt->execute();
  }

  public static function createTheDefaultCategory($transaction_type)
  {
    $default_name = 'Default';
    $sql = 'INSERT INTO ' . $transaction_type . 's_category_assigned_to_users
            VALUES (NULL, :user_id, :name, NULL)';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindParam(':name', $default_name, PDO::PARAM_STR);

    $stmt->execute();
  }

  public static function isTheOnlyCategory($category_id, $transaction_type)
  {
    $sql = 'SELECT * FROM ' . $transaction_type . 's_category_assigned_to_users
            WHERE user_id = :user_id';

    $db = static::getDB();
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }

  public static function setNameToCamelCase($name)
  {
    return $name = ucwords($name);
  }
}