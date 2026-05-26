<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Cargo.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $cargo = new Cargo();
  
  if($_GET['op'] == 'cargarCargos'){ 
      $datosObtenidos = $cargo->cargarCargo();
        echo "<option value='' selected disabled>-- Seleccione --</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_cargo'>$valor->nombre_cargo</option>
            ";
        } 
    // echo json_encode($data);
  }


 if ($_GET['op'] == 'registrarCargo'){

    $cargo->registrarCargo([
      // "nombres"       => $_GET["nombres"],
      // "apellidos"     => $_GET["apellidos"],
      "_nombrecargo" => $_GET["nombrecargo"]
      
    ]);
 }

  if ($_GET['op'] == 'listarCargo') {
    $rows = $cargo->listarCargo();
    if (!empty($rows)) {
      $i = 1;
      foreach ($rows as $r) {
        $activo = ($r->estado === 'ACTIVO');

        $badge = $activo
          ? "<span class='badge bg-success'>ACTIVO</span>"
          : "<span class='badge bg-secondary'>INACTIVO</span>";

        echo "
          <tr>
            <td class='text-center'>{$i}</td>
            <td class='text-center'>{$r->nombre_cargo}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
              <a href='#' data-idcargo='{$r->id_cargo}' class='btn btn-sm btn-outline-secondary modificar'>
                <i class='fas fa-edit'></i>
              </a>
            </td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'cargoExistente'){
    $datosObtenidos = $cargo->cargoExistente(["_nombrecargo" => $_GET['nombrecargo']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

  if($_GET['op'] == 'modificarCargo'){
    $resultado = $cargo->modificarCargo([
      "_idcargo"     => $_GET["idcargo"],
      "_nombrecargo"    => $_GET["nombrecargo"],
      "_select_estado"    => $_GET["select_estado"]
    ]);

    // El SP devuelve un array con "resultado" => 1 o 2
    echo json_encode($resultado[0]);
  }
  
  if($_GET['op'] == 'getCargo'){
    $data = $cargo->getCargo(["_idcargo" => $_GET['idcargo']]);

    echo json_encode($data);
  }

// if(isset($_POST['op'])){
//   $categoria = new Categoria();

//   if($_POST['op'] == 'registrarCategoria'){
//     $nombre = "";
//     if ($_FILES['fotografia']['tmp_name'] != ''){
//       $nombre = date('YmdhGs') . ".jpg";
//       if (move_uploaded_file($_FILES['fotografia']['tmp_name'], "../img/" . $nombre)){
//         $categoria->registrarCategoria([
//           'categoria' => $_POST['nombre_categoria'],
//           'descripcion' => $_POST['descripcion_categoria'],
//           'fotografia' => $nombre,
//         ]);
//       }
//     }
//   }
// }
}

?>
