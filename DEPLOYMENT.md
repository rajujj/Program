# College Management System - Deployment Guide

This guide will walk you through setting up the College Management System project on your local computer using XAMPP.

## Prerequisites

Before you begin, ensure you have **XAMPP** installed on your computer. You can download it from the [official Apache Friends website](https://www.apachefriends.org).

Make sure the **Apache** and **MySQL** modules are running from the XAMPP Control Panel.

## Step 1: Place the Project Files

1.  Unzip or place the project folder into your XAMPP installation's `htdocs` directory.
2.  For example, the final path might look like `C:\xampp\htdocs\college-management-system\`.

## Step 2: Set Up the MySQL Database

The application needs a MySQL database to store its data. The following script will create the database, tables, and a default admin user.

1.  Open a command prompt or terminal.
2.  Navigate to the project's `database` directory.
3.  Run the following command. It will use the `schema.sql` file that was originally created for MySQL. You may be prompted for your MySQL root password (if you have one set, the default for XAMPP is no password).

    ```bash
    mysql -u root -p < mysql_schema.sql
    ```
    *Note: The original schema has been saved below as `mysql_schema.sql`.*

### Required `database/mysql_schema.sql` file

You will need to create a file named `mysql_schema.sql` inside the `database` directory and paste the following content into it.

```sql
-- Initial Schema for the College Management System (MySQL)
CREATE DATABASE IF NOT EXISTS college_db;
USE college_db;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'Should be a hashed password',
  `role` ENUM('admin', 'hod', 'faculty', 'student') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `departments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) UNIQUE NOT NULL,
  `hod_id` INT NULL COMMENT 'FK to faculty table'
) ENGINE=InnoDB;

CREATE TABLE `faculty` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNIQUE NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `department_id` INT NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE `departments` ADD CONSTRAINT `fk_hod_id` FOREIGN KEY (`hod_id`) REFERENCES `faculty`(`id`) ON DELETE SET NULL;

CREATE TABLE `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_code` VARCHAR(20) UNIQUE NOT NULL,
  `course_name` VARCHAR(100) NOT NULL,
  `department_id` INT NOT NULL,
  `credits` INT NOT NULL,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNIQUE NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `department_id` INT NOT NULL,
  `enrollment_year` YEAR NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$3J2.F5X7iY9.doG3B2pG/..DSg2v3oVqjY.zaI9SjOFtGz0JpGjS.', 'admin');
```

## Step 3: Configure the Application for MySQL

The application was adapted to use SQLite for testing. You need to revert two files to use your MySQL database.

### 1. Configure `config/database.php`

Overwrite the contents of `config/database.php` with the following:

```php
<?php
// Database configuration settings for MySQL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Your MySQL root password, default is empty
define('DB_NAME', 'college_db');
```

### 2. Configure `src/Database.php`

Overwrite the contents of `src/Database.php` with the original MySQL-compatible version:

```php
<?php
class Database
{
    private static ?Database $_instance = null;
    private PDO $_connection;

    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $dbname = DB_NAME;

    private function __construct()
    {
        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
            $this->_connection = new PDO($dsn, $this->user, $this->pass);
            $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Database Connection Failed: ' . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$_instance === null) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    public function getConnection(): PDO
    {
        return $this->_connection;
    }

    private function __clone() {}
    public function __wakeup() {}
}
```

## Step 4: Run the Application

Open your web browser and navigate to the `public` directory of the project.

For example:
**`http://localhost/college-management-system/public/`**

You should see the login page.

## Default Login Credentials

-   **Username:** `admin`
-   **Password:** `admin_password`
