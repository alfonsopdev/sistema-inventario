<?php
session_start();
require_once '../models/Restock.php';
if (isset($_GET['op'])){
    $restock = new Restock();

    if($_GET['op'] == 'registrarRestock'){
        $restock->registrarRestock([
            "idproducto" => $_GET["idproducto"],
            "cantidad" => $_GET["cantidad"],
            "detallereestock" => $_GET["detallereestock"]
        ]);
    }

    if($_GET['op']  == 'listarRestock'){              
        $clave = $restock->listarRestock();
    
        if(count($clave) != 0){
          $i = 1;
          foreach($clave as $valor){
            echo "
              <tr>
                <td class='text-center'>$i</td>
                <td class='text-center'>$valor->categoria</td>
                <td class='text-center'>$valor->nombreproducto</td>
                <td class='text-center'>$valor->cantidad</td>
                <td class='text-center'>$valor->detallereestock</td>
                <td class='text-center'>$valor->fechareestock</td>
                </tr>
            ";
            $i++;
          }
        }
      }
}
?>