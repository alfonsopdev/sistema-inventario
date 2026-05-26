<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/DependenciaSede.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $dependenciasede = new DependenciaSede();
  
  if($_GET['op'] == 'cargarDependencias'){ 
      $datosObtenidos = $dependencia->cargarDependencia();
        echo "<option value='' selected disabled>-- Seleccione --</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_dependencia'>$valor->nombre_dependencia</option>
            ";
        } 
    // echo json_encode($data);
  }


  if ($_GET['op'] == 'listarDependenciasPorSede') {
      $datos = $dependencia->listarDependenciasPorSede([
          "idSede" => $_GET['idSede']
      ]);
      echo "<option value='' selected disabled>-- Seleccione --</option>";
      foreach ($datos as $fila) {
          echo "<option value='{$fila->id_dependencia_sede}'>{$fila->nombre_dependencia}</option>";
      }
  }

  // if ($_GET['op'] == 'cargarUbicacionFiltro') {
  //     $datos = $dependenciasede->cargarUbicacionFiltro();
  //     echo "<option value='' selected>Todos</option>";
  //     foreach ($datos as $fila) {
  //         echo "<option value='{$fila->id_dependencia_sede}'>{$fila->nombre_sede_dependencia}</option>";
  //     }
  // }

 if ($_GET['op'] == 'registrarDependenciaSede'){

    $dependenciasede->registrarDependenciaSede([

      "select_sede" => $_GET["select_sede"],
      "select_dependencia" => $_GET["select_dependencia"],
      
    ]);
 }

  if ($_GET['op'] == 'listarDependenciaSede') {
    $rows = $dependenciasede->listarDependenciaSede();
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
            <td class='text-center'>{$r->nombre_dependencia}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
              <a href='#' data-iddependenciasede='{$r->id_dependencia_sede}' class='btn btn-sm btn-outline-secondary modificar'>
                <i class='fas fa-edit'></i>
              </a>
            </td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'relacionDependenciaSedeExistente'){
    $datosObtenidos = $dependenciasede->relacionDependenciaSedeExistente([
        "_idsede" => $_GET['idsede'],
        "_iddependencia" => $_GET['iddependencia']
    ]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

  if($_GET['op'] == 'modificarDependenciaSede'){
    $resultado = $dependenciasede->modificarDependenciaSede([
      "_iddependenciasede"     => $_GET["iddependenciasede"],
      "_select_sede"    => $_GET["select_sede"],
      "_select_dependencia"    => $_GET["select_dependencia"],
      "_select_estado"  => $_GET["select_estado"]
    ]);

    // El SP devuelve un array con "resultado" => 1 o 2
    echo json_encode($resultado[0]);
  }
  
  if($_GET['op'] == 'getDependenciaSede'){
    $data = $dependenciasede->getDependenciaSede(["_iddependenciasede" => $_GET['iddependenciasede']]);

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
