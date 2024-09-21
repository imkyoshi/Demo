<?php

namespace app\config;

use PDO;
use PDOException;

class Connection
{
    private string $host = "localhost";
    private string $user = "root";
    private string $password = "/Otosorb110";
    private string $dbname = "crime";
    private $pdo;

    public function connect()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}", 
                $this->user, 
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
