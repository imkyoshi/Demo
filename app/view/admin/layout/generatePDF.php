<?php
session_start();
require '../../../../vendor/autoload.php'; // Autoload classes via Composer
require_once '../../../../app/config/Connection.php';

use app\config\Connection;
use app\model\UserDAL;

$options = new Dompdf\Options();
$options->set('defaultFont', 'Poppins');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf\Dompdf($options);

// Establish Database Connection
$connection = new Connection();
$pdo = $connection->connect();

$userDAL = new UserDAL($pdo);
$users = $userDAL->getAllUser(); // Fetch all users

$html = '
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }
    h1 {
        text-align: center;
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th {
        background-color: #007BFF;
        color: white;
        padding: 10px;
        text-align: left;
    }
    td {
        padding: 10px; /* Padding for data cells */
        text-align: left;
        border-bottom: 1px solid #ccc; /* Bottom border for data cells */
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #e9e9e9;
    }
</style>
';

$imageUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/public/img/st 3.png'; // Absolute URL
$html .= '<img src="' . $imageUrl . '" style="width: 100px; display: block; margin: 0 auto;"/>';
$html .= '<h1>Student Information</h1>';
$html .= '<table>';
$html .= '<tr><th>Student No</th><th>Full Name</th><th>Address</th><th>Birthday</th><th>Gender</th><th>Phone Number</th><th>Email</th></tr>';

foreach ($users as $user) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($user['student_no']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['fullname']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['address']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['dateOfBirth']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['gender']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['phone_number']) . '</td>';
    $html .= '<td>' . htmlspecialchars($user['email']) . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("student_information.pdf", ["Attachment" => true]);
