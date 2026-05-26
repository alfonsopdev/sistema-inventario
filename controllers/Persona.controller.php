<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Persona.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $persona = new Persona();
  
  if($_GET['op'] == 'cargarPersona'){ 
      $datosObtenidos = $persona->cargarPersona();
        echo "<option value='' selected disabled>-- Seleccione --</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_persona'>$valor->npersona</option>
            ";
        } 
    // echo json_encode($data);
  }


 if ($_GET['op'] == 'registrarPersona'){

    $persona->registrarPersona([
      // "nombres"       => $_GET["nombres"],
      // "apellidos"     => $_GET["apellidos"],
      "p_nro" => $_GET["txt_nro"],
      "p_nom" => $_GET["txt_nom"],
      "p_apepa" => $_GET["txt_apepa"],
      "p_apema" => $_GET["txt_apema"],
      "p_fnac" => $_GET["txt_fnac"],
      "p_movil" => $_GET["txt_movil"],
      "p_dire" => $_GET["txt_dire"],
      "p_email" => $_GET["txt_email"],
      
    ]);
 }

  if ($_GET['op'] == 'listarPersonas') {
    $rows = $persona->listarPersona();
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
            <td class='text-center'>{$r->nro_documento}</td>
            <td class='text-center'>{$r->npersona}</td>
            <td class='text-center'>{$r->nro_movil}</td>
            <td class='text-center'>{$r->per_email}</td>
            <td class='text-center'>{$r->per_direccion}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center'>
              <a href='#' data-idpersona='{$r->id_persona}' class='btn btn-sm btn-outline-secondary modificar'>
                <i class='fas fa-edit'></i>
              </a>
            </td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'dniYaExiste'){
    $datosObtenidos = $persona->dniYaExiste(["nro_dni" => $_GET['nro_dni']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

  if($_GET['op'] == 'modificarPersona'){
    $resultado = $persona->modificarPersona([
      "p_id"     => $_GET["idpersona"],
      "p_nro"    => $_GET["txt_nro_editar"],
      "p_nom"    => $_GET["txt_nom_editar"],
      "p_apepa"  => $_GET["txt_apepa_editar"],
      "p_apema"  => $_GET["txt_apema_editar"],
      "p_fnac"   => $_GET["txt_fnac_editar"],
      "p_movil"  => $_GET["txt_movil_editar"],
      "p_dire"   => $_GET["txt_dire_editar"],
      "p_email"  => $_GET["txt_email_editar"],
      "p_esta"   => $_GET["select_estatus_editar"]
    ]);

    // El SP devuelve un array con "resultado" => 1 o 2
    echo json_encode($resultado[0]);
  }
  
  if($_GET['op'] == 'getPersona'){
    $data = $persona->getPersona(["idpersona" => $_GET['idpersona']]);

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
