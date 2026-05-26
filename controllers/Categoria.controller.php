<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Categoria.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){
  $categoria = new Categoria();

    if($_GET['op'] == 'cargarCategoria'){ 
      $datosObtenidos = $categoria->cargarCategoria();
        echo "<option value='' selected disabled>--Seleccione--</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_categoria'>$valor->nombre_categoria</option>
            ";
        }
    //   echo json_encode($data);
    }

    if($_GET['op'] == 'cargarCategoriasFiltro'){ 
      $datosObtenidos = $categoria->cargarCategoriasFiltro();
        echo "<option value=''>Todos</option>";
        foreach($datosObtenidos as $valor){
            echo"
            <option value='$valor->id_categoria'>$valor->nombre_categoria</option>
            ";
        }
    //   echo json_encode($data);
    }
  

  if($_GET['op']  == 'ListarCategorias'){              
    $rows = $categoria->listarCategorias();
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
              <td class='text-center'>{$r->fecha_registro}</td>
              <td class='text-center'>{$r->nombre_categoria}</td>
              <td class='text-center'>{$r->descripcion}</td>
              <td class='text-center'>{$badge}</td>
              <td class='text-center'>
                <a href='#' data-idcategoria='{$r->id_categoria}' class='btn btn-sm btn-outline-secondary modificar'>
                  <i class='fas fa-edit'></i>
                </a>
              </td>
            </tr>";
          $i++;
        }
      }
      exit;
  }
    

 if ($_GET['op'] == 'registrarCategoria'){

    $categoria->registrarCategoria([

      "_nombrecategoria" => $_GET["nombre_categoria"],
      "_descripcion_categoria" => $_GET["descripcion_categoria"],
      
    ]);
 }

  if ($_GET['op'] == 'categoriaYaExiste'){
      $datosObtenidos = $categoria->categoriaExistente(["_nombrecategoria" => $_GET['nombrecategoria']]);

      if(count($datosObtenidos) == 0){
        echo 2;
        return true;
      }
      else{
        echo 1;
        return false;
      }
  }

  if($_GET['op'] == 'getCategoria'){
    $data = $categoria->getCategoria(["_idcategoria" => $_GET['idcategoria']]);

    echo json_encode($data);
  }

  if($_GET['op'] == 'modificarCategoria'){
    $resultado = $categoria->modificarCategoria([
      "_idcategoria"     => $_GET["idcategoria"],
      "_nombrecategoria"    => $_GET["nombrecategoria"],
      "_descripcion"    => $_GET["descripcion"],
      "_select_estado"  => $_GET["select_estado"]
    ]);

    // El SP devuelve un array con "resultado" => 1 o 2
    echo json_encode($resultado[0]);
  }

}

?>
