<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;

class Database
{
    private $pdo;

    public function __construct()
    {
        $dbhost = $_ENV['DBHOST'];
        $dbname = $_ENV['DBNAME'];
        $dbuser = $_ENV['DBUSER'];
        $dbpass = $_ENV['DBPASS'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];

        try {
            $this->pdo = new PDO($dsn, $dbuser, $dbpass, $options);
        } catch (PDOException $e) {
            die('Error database' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    public function createUserTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )');
    }

    public function createTasksTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME,
            priority VARCHAR(255),
            status VARCHAR(255) NOT NULL DEFAULT 'todo',
            user_id INT,
            CONSTRAINT fk_user_tasks
            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE
        )");
    }

    public function createTasksDataTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS tasks_data (
            id INT AUTO_INCREMENT PRIMARY KEY,
            deadline DATETIME,
            task_id INT,
            CONSTRAINT fk_tasks
            FOREIGN KEY (task_id)
            REFERENCES tasks(id)
            ON DELETE CASCADE
        )');
    }

    public function createHabitsTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS habits (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description VARCHAR(255),
            created_at DATETIME,
            status VARCHAR(255),
            user_id INT,
            CONSTRAINT fk_user_habits
            FOREIGN KEY (user_id)
            REFERENCES users(id)
            ON DELETE CASCADE
        )');
    }

    public function createHabitsDataTable()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS habits_data (
            id INT AUTO_INCREMENT PRIMARY KEY,
            streak_days INT,
            check_current_day DATETIME,
            amount_days_done INT,
            habit_id INT,
            CONSTRAINT fk_habits_data
            FOREIGN KEY (habit_id)
            REFERENCES habits(id)
            ON DELETE CASCADE
        )');
    }
}
