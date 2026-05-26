<?php
session_start();
require_once '../models/Salidas.php';
if (isset($_GET['op'])){
    $salida = new Salidas();

    if($_GET['op'] == 'registrarSalidas'){
    $salida->registrarSalidas([
        "idproducto" => $_GET["idproducto"],
        "cantidadsalida" => $_GET["cantidadsalida"],
        "detalle" => $_GET["detalle"]
    ]);
    }

    if($_GET['op']  == 'listarSalidas'){              
      $clave = $salida->listarSalidas();
  
      if(count($clave) != 0){
        $i = 1;
        foreach($clave as $valor){
          echo "
            <tr>
              <td class='text-center'>$i</td>
              <td class='text-center'>$valor->categoria</td>
              <td class='text-center'>$valor->nombreproducto</td>
              <td class='text-center'>$valor->cantidadsalida</td>
              <td class='text-center'>$valor->detalle</td>
              <td class='text-center'>$valor->fechasalida</td>
              </tr>
          ";
          $i++;
        }
      }
    }
}
?>