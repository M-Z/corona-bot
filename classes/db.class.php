<?php

namespace corona_bot;

use InvalidArgumentException;
use PDOException;
use Exception;

/**
* responsiable for db connect, sanitization of fields, sql queries, and view any modal
* @method __construct
*/
abstract class dbQuery
{
    private $dbh;

    protected function connect()
    {
        require_once(__DIR__."/../config/db.php");
        try {
            $db = new \PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);
            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->dbh = $db;
            return true;
        } catch (PDOException $e) {
            return "We will be back right away.<br>" . $e->getMessage();
        } catch (Exception $e) {
            return "We will be back right away.";
        }
    }

    /**
    * Executes a PDO sql query statement
    **/
    protected function query(/* $sql [, ... ] */)
    {
        $sql = func_get_arg(0);
        $parameters = array_slice(func_get_args(), 1);

        $statement = $this->dbh->prepare($sql);
        if ($statement == false) {
            $err = $this->dbh->errorInfo();
            trigger_error($err[2], E_USER_ERROR);
            exit;
        }

        $results = $statement->execute($parameters);
        if ($results !== false) {
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return implode(" - ", $statement->errorInfo());
        }
    }

}
