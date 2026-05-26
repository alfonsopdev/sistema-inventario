<?php

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use Mpdf\Mpdf;

// Seguridad básica
if (empty($cabecera)) {
    die("No se encontraron datos del movimiento");
}
$mov = $cabecera[0];

$html = '
<style>
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9px;
    }
    h3{
        margin: 0;
        padding: 0;
        text-align: center;
    }
    hr{
        border: 0.5px dashed #000;
        margin: 6px 0;
    }
    .center{text-align:center;}
</style>

<img src="../images/oti-inventario.png" style="width:80px; height:auto; margin:10px auto; display:block;">
<div class="center"><b>TICKET DE MOVIMIENTO</b></div>
<hr>

<b>Código:</b> '.$mov->codigo_mov.'<br>
<b>Tipo:</b> '.$mov->tipo_mov.'<br>
<b>Fecha:</b> '.date('d/m/Y H:i', strtotime($mov->fecha_mov)).'<br>
';

if (!empty($mov->fecha_devolucion)) {
    $html .= '<b>Fecha devolución:</b> '.date('d/m/Y', strtotime($mov->fecha_devolucion)).'<br>';
}

$html .= '
<hr>

<b>Responsable:</b>
'.$mov->responsable.'<br>

<b>Dirigido a:</b>
'.$mov->dirigido.'<br>

<hr>

<b>Activos:</b><br>
';


foreach ($activos as $a) {
    $html .= '* ' . $a->cod_patrimonial . '<br>';
}


$html .= '
<hr>

<b>Motivo / Sustento:</b><br>
'.$mov->motivo.'

<br><br>

<div class="center">
    <barcode code="'.$mov->codigo_mov.'" type="QR" size="1.2"/>
</div>

<div class="center" style="font-size:8px;margin-top:5px">
    Conserve este ticket como constancia
</div>
'
;

$footer = '
<table width="100%" style="font-size:8px; border-top:0.5px dashed #000;">
    <tr>
        <td width="50%" align="left">
            <b>Número movil:</b> '.$mov->nro_movil.'
        </td>
        <td width="50%" align="right">
            <b>Email:</b> '.$mov->per_email.'
        </td>
    </tr>
</table>
';


$mpdf = new Mpdf([
    'format' => [80, 140],   // tamaño ticket
    'margin_left'   => 5,
    'margin_right'  => 5,
    'margin_top'    => 5,
    'margin_bottom' => 10
]);


$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML($html);
$mpdf->Output("ticket_{$mov->codigo_mov}.pdf", "I");
