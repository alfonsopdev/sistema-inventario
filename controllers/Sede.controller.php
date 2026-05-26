<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Sede.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $sede = new Sede();
  
  if($_GET['op'] == 'cargarSede'){ 
      $datosObtenidos = $sede->cargarSede();
        echo "<option value='' selected disabled>-- Seleccione --</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_sede'>$valor->nombre_sede</option>
            ";
        } 
    // echo json_encode($data);
  }

  if($_GET['op'] == 'cargarSedeFiltro'){ 
      $datosObtenidos = $sede->cargarSedeFiltro();
        echo "<option value='' selected>Todos</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_sede'>$valor->nombre_sede</option>
            ";
        } 
    // echo json_encode($data);
  }


  if ($_GET['op'] == 'listarDependenciasPorSede') {
      $datos = $dependencia->listarDependenciasPorSede($_GET['idSede']);
      echo "<option value='' selected disabled>-- Seleccione --</option>";
      foreach ($datos as $fila) {
          echo "<option value='{$fila->id_dependencia_sede}'>{$fila->nombre_dependencia}</option>";
      }
  }

 if ($_GET['op'] == 'registrarSede'){

    $sede->registrarSede([

      "p_nombresede" => $_GET["nombresede"],
      "p_ubicacion" => $_GET["ubicacion"],
      
    ]);
 }

  if ($_GET['op'] == 'listarSede') {
    $rows = $sede->listarSede();
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
            <td class='text-center'>{$r->nombre_sede}</td>
            <td class='text-center'>{$r->ubicacion}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
              <a href='#' data-idsede='{$r->id_sede}' class='btn btn-sm btn-outline-secondary modificar'>
                <i class='fas fa-edit'></i>
              </a>
            </td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'sedeExistente'){
    $datosObtenidos = $sede->sedeExistente(["_nombresede" => $_GET['nombresede']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

  if($_GET['op'] == 'modificarSede'){
    $resultado = $sede->modificarSede([
      "_idsede"     => $_GET["idsede"],
      "_nombresede"    => $_GET["nombresede"],
      "_ubicacion"    => $_GET["ubicacion"],
      "_estado"  => $_GET["select_estado"]
    ]);

    // El SP devuelve un array con "resultado" => 1 o 2
    echo json_encode($resultado[0]);
  }
  
  if($_GET['op'] == 'getSede'){
    $data = $sede->getSede(["idsede" => $_GET['idsede']]);

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
