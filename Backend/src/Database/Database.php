<?php$

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

    public function createUserDatabase()
    {
        $this->pdo->exec('CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )');
    }
}
