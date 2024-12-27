<?php

class PersistanceManager
{
    private $pdo;

    public function __construct()
    {
        try {
            // Database connection using PDO
            $this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            // Create necessary tables
            $this->createTables();
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection failed.");
        }
    }

    public function createTables()
    {
        // Users table (admin and members)
        $query_users = "CREATE TABLE IF NOT EXISTS `members` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(200) NOT NULL UNIQUE,
            `email` VARCHAR(200) NOT NULL UNIQUE,
            `password` VARCHAR(240) NOT NULL,
            `role` ENUM('admin', 'member') NOT NULL DEFAULT 'member',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->pdo->exec($query_users);

        // Books table
        $query_books = "CREATE TABLE IF NOT EXISTS `books` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `author` VARCHAR(255) NOT NULL,
            `category` VARCHAR(100) NOT NULL,
            `isbn` VARCHAR(20) UNIQUE NOT NULL,
            `quantity` INT NOT NULL DEFAULT 0,
            `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `photo`VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->pdo->exec($query_books);

        // Borrowed books table
        $query_borrowed_books = "CREATE TABLE IF NOT EXISTS `borrowedbooks` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `book_id` INT NOT NULL,
            `book_status` ENUM('borrowed', 'returned', 'due time over') NOT NULL,
            `borrowed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `due_date` DATE NOT NULL,
            `returned_at` TIMESTAMP NULL,
            `fine` DECIMAL(10, 2) DEFAULT 0.00,
            `fine_status`  ENUM('paid', 'pending', 'no fine') NOT NULL,
            `paid_date` DATE NOT NULL,
            FOREIGN KEY (`user_id`) REFERENCES `members`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        $this->pdo->exec($query_borrowed_books);

       
    }

    // Run a query and fetch results
    public function run($query, $params = null, $fetchFirstRecOnly = false)
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);

            if ($fetchFirstRecOnly) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            error_log("Query execution failed: " . $e->getMessage());
            return -1;
        }
    }

    // Insert a record and get the last inserted ID
    public function insertAndGetLastRowId($query, $params = null)
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Insert failed: " . $e->getMessage());
            return -1;
        }
    }

    // Count records
    public function getCount($query, $params = null)
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['c'] ?? 0;
        } catch (PDOException $e) {
            error_log("Count query failed: " . $e->getMessage());
            return 0;
        }
    }
}
