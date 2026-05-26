<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Administrativo.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $administrativo = new Administrativo();

  if($_GET['op'] == 'cargarAdministrativo'){ 
    $datosObtenidos = $administrativo->cargarAdministrativo();
      echo "<option value='' selected disabled>--Seleccione Administrativo--</option>";
      foreach($datosObtenidos as $valor){
          echo"
          <option value='$valor->id_administrativo'>$valor->npersona</option>
          ";
      }
    //   echo json_encode($data);
  }

  if($_GET['op'] == 'cargarAdministrativosFiltro'){ 
    $datosObtenidos = $administrativo->cargarAdministrativosFiltro();
      echo "<option value='' selected>Todos</option>";
      foreach($datosObtenidos as $valor){
          echo"
          <option value='$valor->id_administrativo'>$valor->npersona</option>
          ";
      }
    //   echo json_encode($data);
  }

  if($_GET['op'] == 'cargarOtrosAdministrativos'){ 
    $datosObtenidos = $administrativo->cargarOtrosAdministrativos([
      '_idadministrativo' => $_GET['idadministrativo']
    ]);
      echo "<option value='' selected disabled>-- Seleccione Administrativo --</option>";
      foreach($datosObtenidos as $valor){
          echo"
          <option value='$valor->id_administrativo'>$valor->npersona</option>
          ";
      }
    //   echo json_encode($data);
  }

  if($_GET['op'] == 'listarSedePorAdministrativo'){ 
    $datosObtenidos = $administrativo->listarSedePorAdministrativo([
      '_idadministrativo' => $_GET['idAdministrativo']
    ]);
      foreach($datosObtenidos as $valor){
          echo"
          <option value='$valor->id_sede'>$valor->nombre_sede</option>
          ";
      }
    //   echo json_encode($data);
  }

  if($_GET['op'] == 'listarDependenciaPorAdministrativo'){ 
    $datosObtenidos = $administrativo->listarDependenciaPorAdministrativo([
      '_idadministrativo' => $_GET['idAdministrativo']
    ]);
      foreach($datosObtenidos as $valor){
          echo"
          <option value='$valor->id_dependencia'>$valor->nombre_dependencia</option>
          ";
      }
    //   echo json_encode($data);
  }
  
//   if($_GET['op'] == 'cargarPersona'){ 
//       $datosObtenidos = $persona->cargarPersona();
//         echo "<option value='' selected disabled>-- Seleccione --</option>";
//         foreach($datosObtenidos as $valor){
//             echo"
//             <option value='$valor->id_persona'>$valor->npersona</option>
//             ";
//         } 
//     // echo json_encode($data);
//   }


 if ($_GET['op'] == 'registrarAdministrativo'){

    $administrativo->registrarAdministrativo([
      // "nombres"       => $_GET["nombres"],
      // "apellidos"     => $_GET["apellidos"],
      "id_persona" => $_GET["idpersona"],
      "id_dependencia_sede" => $_GET["iddependencia"],
      "id_cargo" => $_GET["idcargo"],
    ]);
 }

  if ($_GET['op'] == 'listarAdministrativos') {
    $rows = $administrativo->listarAdministrativo();
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
            <td class='text-center'>{$r->npersona}</td>
            <td class='text-center'>{$r->nombre_sede}</td>
            <td class='text-center'>{$r->nombre_dependencia}</td>
            <td class='text-center'>{$r->nombre_cargo}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
              <a href='#' data-idadministrativo='{$r->id_administrativo}' class='btn btn-sm btn-outline-secondary modificar'>
                <i class='fas fa-edit'></i>
              </a>
            </td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'personaYaAsignada'){
    $datosObtenidos = $administrativo->personaYaAsignada(["_idpersona" => $_GET['idpersona']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

 if ($_GET['op'] == 'modificarAdministrativo'){

    $administrativo->modificarAdministrativo([
      // "nombres"       => $_GET["nombres"],
      // "apellidos"     => $_GET["apellidos"],
      "idadministrativo" => $_GET["idadministrativo"],
      "id_persona" => $_GET["select_persona"],
      "id_dependencia_sede" => $_GET["select_dependencia"],
      "id_cargo" => $_GET["select_cargo"],
      "estado" => $_GET["estado"],
    ]);
 }
  
  if($_GET['op'] == 'getAdministrativo'){
    $data = $administrativo->getAdministrativo(["idadministrativo" => $_GET['idadministrativo']]);

    echo json_encode($data);
  }

  if($_GET['op'] == 'getAdministrativoActivo'){
    $data = $administrativo->getAdministrativoActivo(["_idactivo" => $_GET['idactivo']]);

    echo json_encode($data);
  }

  if($_GET['op'] == 'getJefeOficina'){
    $data = $administrativo->getJefeOficina(["_iddependencia" => $_GET['iddependencia']]);

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
