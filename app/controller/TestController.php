<?php

namespace app\controllers;

use app\model\TestDAL;

class TestController
{
    private $model;

    public function __construct()
    {
        $this->model = new TestDAL();
    }

    public function getSalesChartData($year)
    {
        $data = $this->model->getSalesData($year);
        echo json_encode($data); // Will return month, year, sales, and purchase amounts
    }
}

// Example of routing for AJAX calls
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['year'])) {
    $controller = new TestController();
    $controller->getSalesChartData($_POST['year']);
}
