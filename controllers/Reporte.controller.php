<?php
session_start();
date_default_timezone_set("America/Lima");

require_once '../models/Movimiento.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Mpdf\Mpdf;

if (!isset($_GET['id'])) {
    die('ID no enviado');
}

$movimiento = new Movimiento();

$data = ["_id" => $_GET['id']];

$cabecera = $movimiento->obtenerMovimientoTicket($data);
$activos  = $movimiento->obtenerActivosMovimiento($data);

if (empty($cabecera)) {
    die("Movimiento no encontrado");
}

require_once '../view/MPDF/REPORTE/ticket_movimiento.php';
exit;
