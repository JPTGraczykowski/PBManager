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
      $sql = 'SELECT * FROM ' . $transaction_type . 's_category_assigned_to_users WHERE name = :name';

      $db = static::getDB();
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);

      $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

      $stmt->execute();

      return $stmt->fetch();
  }
}