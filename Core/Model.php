<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
<<<<<<< HEAD
 * PHP version 7.0
=======
 * PHP version 5.4
>>>>>>> 40af4e4a56966a0ccb88f2ec1ebf37dd27385227
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
