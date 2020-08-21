<?php

namespace App\Models;

use \App\Auth;
use PDO;

class IncomeCategoryAssignedToUser extends \Core\Model
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
}