<?php

namespace app\config;

use PDO;
use PDOException;

class Connection
{
    private $host = "localhost";
    private $user = "root";
    private $password = "/Otosorb110";
    private $dbname = "crime";
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
