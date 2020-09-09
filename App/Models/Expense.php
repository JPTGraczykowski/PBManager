<?php

namespace App\Models;

use PDO;

class Expense extends \Core\Model
{
  public $errors = [];
  
  public function __construct($data = [])
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  public function save()
  {
    $this->validate();

    if (empty($this->errors)) {
      $sql = 'INSERT INTO expenses (user_id, expense_category_assigned_to_user_id, amount, date_of_expense, expense_comment)
              VALUES (:user_id, :expense_category_id, :amount, :date_of_expense, :expense_comment)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':expense_category_id', $this->expense_category_id, PDO::PARAM_INT);
      $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
      $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
      $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);

      return $stmt->execute();
    }
  }

  public function validate()
    {
        // ammount
        if ($this->amount == '') {
            $this->errors[] = 'Amount is required';
        }

        if ($this->amount <= 0 || $this->amount > 99999999.99) {
            $this->errors[] = 'Please input amount between 0 and 100 mln.';
        }

        $this->amount = str_replace(',', '.', $this->amount);

        if (filter_var($this->amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) === false) {
            $this->errors[] = 'Invalid amount';
        }

        // date
        if ($this->date == '') {
            $this->errors[] = 'Date is required';
        }

        // expense category
        if ($this->expense_category_id == '') {
            $this->errors[] = 'Expense category is required';
        }

        // comment
        if (strlen($this->comment) > 100) {
            $this->errors[] = 'Comment must be shorter than 100 characters';
        }   
    }

    public static function getExpensesAssignedToUser($start_date, $end_date)
    {
    $sql = 'SELECT * FROM expenses WHERE user_id = :user_id AND date_of_expense BETWEEN :start_date AND :end_date';

    $db = static::getDB();
    $stmt = $db->prepare($sql);

    $start_date = date($start_date);
    $end_date = date($end_date);

    $stmt->bindValue('user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue('start_date', $start_date, PDO::PARAM_STR);
    $stmt->bindValue('end_date', $end_date, PDO::PARAM_STR);

    $stmt->execute();

    return $stmt->fetchAll();
    }

    public static function addNewCategory($name)
    {
      $sql = 'INSERT INTO expenses_category_assigned_to_users
              VALUES (NULL, :user_id, :name)';

      $db = static::getDB();
      $stmt = $db->prepare($sql);

      $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
      $stmt->bindValue(':name', $name, PDO::PARAM_STR);

      $stmt->execute();
    }
}