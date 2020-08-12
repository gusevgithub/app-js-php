<?php
namespace Core;

use PDO;
//require_once('Config.php');
//require_once("Config.php");

/**
 * Base model
 *
 * PHP version 7.1
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
            $dsn = 'mysql:host=' . \Core\Config::DB_HOST . ';dbname=' . \Core\Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, \Core\Config::DB_USER, \Core\Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
