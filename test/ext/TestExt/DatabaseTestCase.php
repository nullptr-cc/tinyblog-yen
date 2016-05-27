<?php

namespace TestExt;

use Yada\Driver as SqlDriver;

abstract class DatabaseTestCase extends \PHPUnit_Extensions_Database_TestCase
{
    private $conn;
    private $sql_driver;

    private function getDbOpts()
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

    final protected function getConnection()
    {
        if ($this->conn !== null) {
            return $this->conn;
        };

        try {
            $opts = $this->getDbOpts();
            $pdo = new \PDO($opts['dsn'], $opts['username'], $opts['password']);
            $this->conn = $this->createDefaultDBConnection($pdo, $opts['dbname']);
        } catch (\Exception $ex) {
            $this->conn = null;
        };

        return $this->conn;
    }

    protected function getDataSet()
    {
        return $this->createMySQLXMLDataSet(DBFIXT_PATH . '/whole-dump.xml');
    }

    protected function setUp()
    {
        if (!$this->getConnection()) {
            $this->markTestSkipped('Database connection not available');
        };

        return parent::setUp();
    }
}
