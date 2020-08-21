<?php

namespace App\Models;

use PDO;

class Income extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $sql = 'INSERT INTO incomes (user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
             VALUES (:user_id, :income_category_id, :amount, :date_of_income, :income_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':income_category_id', $this->income_category_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate()
    {
        // ammount
        if ($this->amount == '') {
            $this->errors[] = 'Amount is required';
        }

        $this->amount = str_replace(',', '.', $this->amount);

        if (filter_var($this->amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) === false) {
            $this->errors[] = 'Invalid amount';
        }

        // date
        if ($this->date == '') {
            $this->errors[] = 'Date is required';
        }

        // income category
        if ($this->income_category_id == '') {
            $this->errors[] = 'Income category is required';
        }

        // comment
        if (strlen($this->comment) > 100) {
            $this->errors[] = 'Comment must be shorter than 100 characters';
        }
        
    }
}