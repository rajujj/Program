<?php

class Database
{
    private static ?Database $_instance = null;
    private PDO $_connection;

    private function __construct()
    {
        try {
            // The DSN for SQLite is much simpler.
            $dsn = 'sqlite:' . DB_PATH;
            $this->_connection = new PDO($dsn);

            // Set PDO attributes for error handling and fetch mode.
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Enable foreign key constraints for SQLite
            $this->_connection->exec('PRAGMA foreign_keys = ON;');

        } catch (PDOException $e) {
            // For a real application, this should be logged, not displayed.
            die('Database Connection Failed: ' . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$_instance === null) {
            // Ensure config is loaded. This makes the class more self-contained.
            if (!defined('DB_PATH')) {
                require_once __DIR__ . '/../config/database.php';
            }
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    public function getConnection(): PDO
    {
        return $this->_connection;
    }

    // Prevent cloning and unserialization of the instance.
    private function __clone() {}
    public function __wakeup() {}
}
