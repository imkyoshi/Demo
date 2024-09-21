<?php
session_start();
require '../../../../vendor/autoload.php'; // Autoload classes via Composer
require_once '../../../../app/config/Connection.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Poppins');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Get the HTML content from the POST request
$html = isset($_POST['html']) ? $_POST['html'] : '';

// Load the HTML content
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("student_information.pdf", ["Attachment" => true]);
