<?php

namespace app\model;

use app\config\Connection;
use PDO;

class TestDAL
{
    private $pdo;

    public function __construct()
    {
        $db = new Connection();
        $this->pdo = $db->connect();
    }

    public function getSalesData($year)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE_FORMAT(date, '%M') AS month, 
                DATE_FORMAT(date, '%Y') AS year,
                sales_amount, 
                purchase_amount 
            FROM sales 
            WHERE YEAR(date) = :year
        ");
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
