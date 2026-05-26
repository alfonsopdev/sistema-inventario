<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Dependencia.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $dependencia = new Dependencia();
  
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

  if($_GET['op'] == 'cargarDependenciaFiltro'){ 
      $datosObtenidos = $dependencia->cargarDependenciaFiltro();
        echo "<option value='' selected>Todos</option>";
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

 if ($_GET['op'] == 'registrarDependencia'){

    $dependencia->registrarDependencia([

      "p_nombredependencia" => $_GET["nombredependencia"],
      "p_descripcion" => $_GET["descripcion"],
      
    ]);
 }

  if ($_GET['op'] == 'listarDependencia') {
    $rows = $dependencia->listarDependencia();
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
            <td class='text-center'>{$r->nombre_dependencia}</td>
            <td class='text-center'>{$r->descripcion}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
              <a href='#' data-iddependencia='{$r->id_dependencia}' class='btn btn-sm btn-outline-secondary modificar'>
                <i class='fas fa-edit'></i>
              </a>
            </td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'dependenciaExistente'){
    $datosObtenidos = $dependencia->dependenciaExistente(["_nombredependencia" => $_GET['nombredependencia']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

  if($_GET['op'] == 'modificarDependencia'){
    $resultado = $dependencia->modificarDependencia([
      "_iddependencia"     => $_GET["iddependencia"],
      "_nombredependencia"    => $_GET["nombredependencia"],
      "_descripcion"    => $_GET["descripcion"],
      "_select_estado"  => $_GET["select_estado"]
    ]);

    // El SP devuelve un array con "resultado" => 1 o 2
    echo json_encode($resultado[0]);
  }
  
  if($_GET['op'] == 'getDependencia'){
    $data = $dependencia->getDependencia(["_iddependencia" => $_GET['iddependencia']]);

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
