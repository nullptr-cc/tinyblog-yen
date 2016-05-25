<?php

namespace TestExt;

use Yada\Driver as SqlDriver;

abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    private $conn;
    private $sql_driver;

    final protected function getDbOpts()
    {
        $opts = parse_ini_file(DBOPTS_PATH . '/connection.ini');
        $opts['dsn'] = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $opts['host'], $opts['dbname']);

        return $opts;
    }

    final protected function getSqlDriver()
    {
        if ($this->sql_driver !== null) {
            return $this->sql_driver;
        };

        $opts = $this->getDbOpts();
        $this->sql_driver = new SqlDriver($opts['dsn'], $opts['username'], $opts['password']);

        return $this->sql_driver;
    }

    final public function getConnection()
    {
        if ($this->conn !== null) {
            return $this->conn;
        };

        $opts = $this->getDbOpts();
        $pdo = new \PDO($opts['dsn'], $opts['username'], $opts['password']);
        $this->conn = $this->createDefaultDBConnection($pdo, $opts['dbname']);

        return $this->conn;
    }

    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(DBFIXT_PATH . '/whole-dump.xml');
    }
}
