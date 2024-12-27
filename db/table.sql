-- create table members
 CREATE TABLE IF NOT EXISTS `members` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(200) NOT NULL UNIQUE,
            `email` VARCHAR(200) NOT NULL UNIQUE,
            `password` VARCHAR(240) NOT NULL,
            `role` ENUM('admin', 'member') NOT NULL DEFAULT 'member',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        
-- create books table
 CREATE TABLE IF NOT EXISTS `books` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `author` VARCHAR(255) NOT NULL,
            `category` VARCHAR(100) NOT NULL,
            `isbn` VARCHAR(20) UNIQUE NOT NULL,
            `quantity` INT NOT NULL DEFAULT 0,
            `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `photo`VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- create borrowed books table
 CREATE TABLE IF NOT EXISTS `borrowedbooks` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;