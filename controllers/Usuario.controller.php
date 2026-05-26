<?php
session_start(); 

require_once '../models/Usuario.php';

if (isset($_GET['op'])){

  $usuario = new Usuario();

  if ($_GET['op'] == 'login'){

    //Array asociativo
    $datos = ["nombreusuario" => $_GET['nombreusuario']]; // Se esta trayendo mediante get solo 'nombreusuario' y se les va a estar guardande en el array "nombreusuario"
    $resultado = $usuario->login($datos); // Llamando al metodo login de la clase $usuario, pasandole los $datos del usuario.
    
    if ($resultado){
      //Acceso al sistema
      //var_dump($resultado);

      $registro = $resultado[0];
      //var_dump($registro);

      //Sabemos que el usuario existe, entonces verificamos que su clave es CORRECTA
      $claveValidar = $_GET['clave'];

      //Validando la contraseña
      if (!password_verify($claveValidar, $registro['usu_contra'])) {
        // clave incorrecta
        $_SESSION['acceso'] = false;
        echo "La clave es incorrecta";
        exit;
      }

      // ⬇️ Nuevo: chequear estado
      if (isset($registro['usu_estado']) && $registro['usu_estado'] !== 'ACTIVO') {
        $_SESSION['acceso'] = false;
        echo "El usuario <b>{$registro['usu_usuario']}</b> está INACTIVO. Contacte al administrador.";
        exit;
      }

      // OK: crear sesión
      $_SESSION['acceso']       = true;
      $_SESSION['idusuario']    = $registro['id_usuario'];
      $_SESSION['apellidos']    = $registro['apellidos'];
      $_SESSION['nombres']      = $registro['per_nombre'];
      $_SESSION['nombreusuario']= $registro['usu_usuario'];
      $_SESSION['clave']        = $registro['usu_contra'];
      $_SESSION['nivelacceso']  = $registro['usu_nivel_acceso'];
      echo ""; // front redirige a main.php
      exit;

    } else {
      $_SESSION['acceso'] = false;
      echo "El usuario no existe";
      exit;
    }
  }

  if ($_GET['op'] == 'cerrar-sesion'){
    session_destroy();
    session_unset();
    header('Location:../');
  }

  if ($_GET['op'] == 'registrarUsuario'){
    // Hashear la contraseña antes de guardcarla
    $claveHasheada = password_hash($_GET["contrasena"],PASSWORD_DEFAULT);

    $usuario->registrarUsuario([
      // "nombres"       => $_GET["nombres"],
      // "apellidos"     => $_GET["apellidos"],
      "p_usu_usuario" => $_GET["nombreusuario"],
      "p_usu_contra" => $claveHasheada,
      "p_usu_acceso" => $_GET["nivelacceso"],
      "p_persona_id" => $_GET["idpersona"],
    ]);
  }

  if($_GET['op']  == 'reporteAsistencia'){              
    $clave = $usuario->reporteAsistencia();

    if(count($clave) != 0){
      $i = 1;
      foreach($clave as $valor){
        $estado="";
        echo "
          <tr>
            <td class='text-center'>$i</td>
            <td class='text-center'>$valor->nombres</td>
            <td class='text-center'>$valor->apellidos</td>
            <td class='text-center'>$valor->nombreusuario</td>
            <td class='text-center'>$valor->ingreso</td>
          </tr>
        ";
        $i++;
      }
    }
  }

  if ($_GET['op'] == 'listarUsuarios') {
    $rows = $usuario->listarUsuarios();
    if (!empty($rows)) {
      $i = 1;
      foreach ($rows as $r) {
        $activo = ($r->usu_estado === 'ACTIVO');

        $badge = $activo
          ? "<span class='badge bg-success'>ACTIVO</span>"
          : "<span class='badge bg-secondary'>INACTIVO</span>";

        $botones = $activo
          ? "<button class='modificar btn btn-primary btn-sm' data-idusuariomod='{$r->id_usuario}' title='Editar'><i class='fa fa-edit'></i></button>&nbsp;
            <button class='contra btn btn-warning btn-sm' data-id='{$r->id_usuario}' title='Cambiar clave'><i class='fa fa-key'></i></button>&nbsp;
            <button class='activar btn btn-success btn-sm' data-id='{$r->id_usuario}' title='Activar' disabled><i class='fa fa-check'></i></button>&nbsp;
            <button class='desactivar btn btn-danger btn-sm' data-id='{$r->id_usuario}' title='Desactivar'><i class='fa fa-times-circle'></i></button>"
          : "<button class='modificar btn btn-primary btn-sm' data-idusuariomod='{$r->id_usuario}' title='Editar'><i class='fa fa-edit'></i></button>&nbsp;
            <button class='contra btn btn-warning btn-sm' data-id='{$r->id_usuario}' title='Cambiar clave'><i class='fa fa-key'></i></button>&nbsp;
            <button class='activar btn btn-success btn-sm' data-id='{$r->id_usuario}' title='Activar'><i class='fa fa-check'></i></button>&nbsp;
            <button class='desactivar btn btn-danger btn-sm' data-id='{$r->id_usuario}' title='Desactivar' disabled><i class='fa fa-times-circle'></i></button>";

        echo "
          <tr>
            <td class='text-center'>{$i}</td>
            <td class='text-center'>{$r->usu_usuario}</td>
            <td class='text-center'>{$r->usu_nivel_acceso}</td>
            <td class='text-center'>{$r->nombre_dependencia}</td>
            <td class='text-center'>{$r->npersona}</td>
            <td class='text-center'>{$badge}</td>
            <td class='text-center d-flex'>{$botones}</td>
          </tr>";
        $i++;
      }
    }
    exit;
  }

  if ($_GET['op'] == 'actualizarClave'){

    $claveActual = $_GET['claveActual'];
    $claveNueva = $_GET['claveNueva'];

    if(password_verify($claveActual, $_SESSION['clave'])){
      $datos = [
        "idusuario" => $_SESSION['idusuario'],
        "clave"     => password_hash($claveNueva, PASSWORD_BCRYPT)
      ];

      $usuario->actualizarClave($datos);

      echo "OK";
    }else{
      echo "La clave actual no es correcta";
    }
  }

  if($_GET['op'] == 'eliminarUsuario'){
    $usuario->eliminarUsuario(["idusuario" => $_GET['idusuario']]);
  }

  if($_GET['op'] == 'reactivarUsuario'){
    $usuario->reactivarUsuario([
      "idusuario" => $_GET['idusuario']
    ]);
  }

  if($_GET['op'] == 'modificarUsuario'){
    $usuario->modificarUsuario([
      "p_usu_id" => $_GET['idusuario'],
      "p_usu_usuario" => $_GET['nombreusuario'],
      "p_persona_id" => $_GET['idpersona'],
      "p_usu_acceso" => $_GET['nivelacceso'],
    ]);
  }

  if($_GET['op'] == 'getUsuario'){
    $data = $usuario->getUsuario(["idusuario" => $_GET['idusuario']]);

    echo json_encode($data);
  }

  if ($_GET['op'] == 'nombreusuarioYaRegistrado'){
    $datosObtenidos = $usuario->nombreusuarioYaRegistrado(["nombreusuario" => $_GET['nombreusuario']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }

  if ($_GET['op'] == 'personaYaAsignada'){
    $datosObtenidos = $usuario->personaYaAsignada(["p_persona_id" => $_GET['idpersona']]);

    if(count($datosObtenidos) == 0){
      echo 2;
      return true;
    }
    else{
      echo 1;
      return false;
    }
  }
  
  if($_GET['op'] == 'codverificacion'){
    $usuario->codverificacion([
      "idusuario" => $_GET['idusuario'], 
      "codverificacion" => $_GET['codverificacion']
    ]);
  }

  if($_GET['op'] == 'eliminarCodverificacion'){
    $usuario->eliminarCodverificacion(["idusuario" => $_GET['idusuario']]);
  }

  if ($_GET['op'] == 'recuperarClave'){

      $clavenueva = $_GET['clave'];

      $datos = [
        "idusuario" => $_GET['idusuario'],
        "clave"     => password_hash($clavenueva, PASSWORD_BCRYPT)
      ];

      $usuario->actualizarClave($datos);
  }

  if($_GET['op'] == 'validarCorreoContraseña'){
    $clave = $usuario->validarCorreoContraseña(["email" => $_GET["email"]]);

    echo json_encode($clave);
  }

  if ($_GET['op'] == 'emailNoRegistrado'){
      $datosObtenidos = $usuario->emailNoRegistrado([
          "email"           => $_GET['email']
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
}
?>