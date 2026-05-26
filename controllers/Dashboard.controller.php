<?php
session_start();
date_default_timezone_set("America/Lima");

require_once '../models/Dashboard.php';

if (!isset($_GET['op'])) exit;

$dashboard = new Dashboard();

switch ($_GET['op']) {

  case 'kpis':
    echo json_encode($dashboard->kpis()[0]);
    break;

  case 'estados':
    echo json_encode($dashboard->estadosActivos());
    break;

  case 'movimientos_mes':
    echo json_encode($dashboard->movimientosMes());
    break;

  case 'ultimos_movimientos':
    echo json_encode($dashboard->ultimosMovimientos());
    break;
}
