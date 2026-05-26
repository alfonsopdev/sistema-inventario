<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Activo.php';
require_once '../models/Serverside.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

if (isset($_GET['op'])){

  $activo = new Activo();

  // if($_GET['op'] == 'cargarCalidadMovFiltro'){ 
  //   $datosObtenidos = $activo->cargarCalidadMovFiltro();
  //   echo "<option value='' selected>Todos</option>";
  //     foreach($datosObtenidos as $valor){
  //       $nombre_estado = "";
  //       if($valor -> estado_mov == "PRESTADO"){
  //         $nombre_estado = "Préstamo";
  //       }else if($valor -> estado_mov == "TRANSFERIDO"){
  //         $nombre_estado = "Transeferencia";
  //       }else if($valor -> estado_mov == "DEVUELTO"){
  //         $nombre_estado = "Devolución";
  //       }else{
  //         $nombre_estado = "Sin movimiento";
  //       }
  //         echo"
  //         <option value='$valor->estado_mov'>$nombre_estado</option>
  //         ";
  //     }
  //   echo json_encode($datosObtenidos);
  // }

    if ($_GET['op'] == 'activoYaRegistrado'){
        $datosObtenidos = $activo->activoYaRegistrado(["_codpatrimonial" => $_GET['txt_patrimonial']]);
        if(count($datosObtenidos) == 0){
          echo 2;
          return true;
        }
        else{
          echo 1;
          return false;
        }
    }

    //if ($_GET['op'] == 'ListarProductoMedico'){
      //$serverSide->get('vista_listar_productos_farmacia', 'idproducto', array('idproducto', 'nombreproducto', 'principiosactivos', 'formafarmaceutica', 'descripcion', 'fechavencimiento'));
    //}

      
    //if ($_GET['op'] == 'ListarProductoFarmacia'){
      //$serverSide->get('vista_listar_productos_farmacia', 'idproducto', array('idproducto','categoria', 'nombreproducto', 'principiosactivos', 'fechavencimiento'));
    //}

    if ($_GET['op'] == 'listarActivo') {
      $rows = $activo->listarActivo();
      renderActivosTable($rows);
      exit;
    }


    if ($_GET['op'] == 'listarConGarantia') {
        $rows = $activo->listarConGarantia();
        renderActivosTable($rows);
        exit;
    }

    if ($_GET['op'] == 'filtrarConGarantia') {
        $filtro_categoria    = ($_GET["filtro_categoria"] === "" ? null : $_GET["filtro_categoria"]);
        $filtro_responsable  = ($_GET["filtro_responsable"] === "" ? null : $_GET["filtro_responsable"]);
        $filtro_calidad      = ($_GET["filtro_calidad"] === "" ? null : $_GET["filtro_calidad"]);
        $filtro_sede         = ($_GET["filtro_sede"] === "" ? null : $_GET["filtro_sede"]);
        $filtro_dependencia  = ($_GET["filtro_dependencia"] === "" ? null : $_GET["filtro_dependencia"]);
        $filtro_estado       = ($_GET["filtro_estado"] === "" ? null : $_GET["filtro_estado"]);
        $filtro_garantia     = ($_GET["filtro_garantia"] === "" ? null : $_GET["filtro_garantia"]);

        $rows = $activo->filtrarConGarantia([
            "_filtro_categoria"       => $filtro_categoria,
            "_filtro_responsable"     => $filtro_responsable,
            "_filtro_calidad"         => $filtro_calidad,
            "_filtro_sede"            => $filtro_sede,
            "_filtro_dependencia"     => $filtro_dependencia,
            "_filtro_estado"          => $filtro_estado,
            "_filtro_estado_garantia" => $filtro_garantia
        ]);
        renderActivosTable($rows);
        exit;
    }

    if ($_GET['op'] == 'descargarPlantilla') {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Plantilla Carga Activos');

        $headers = [
            'A1' => 'categoria (nombre o ID)',
            'B1' => 'marca',
            'C1' => 'modelo',
            'D1' => 'serie',
            'E1' => 'cod_patrimonial',
            'F1' => 'responsable (nombre o ID)',
            'G1' => 'sede (nombre o ID)',
            'H1' => 'dependencia (nombre o ID)',
            'I1' => 'estado',
            'J1' => 'fecha_adquisicion',
            'K1' => 'observacion'
        ];

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '001F3F']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="plantilla_carga_activos.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    if($_GET['op']== 'eliminarProducto'){
      $producto->eliminarProducto(["idproducto" => $_GET["idproducto"]]);
    }

    if($_GET['op'] == 'modificarProducto'){
      $producto->modificarProducto([
        "idproducto" => $_GET['idproducto'],
        "nombreproducto" => $_GET['nombreproducto']
      ]);
    }

    // if($_GET['op'] == 'getProducto'){
    //   $data = $producto->getProducto(["idproducto" => $_GET['idproducto']]);
    //   echo json_encode($data);
    // }
    
    if($_GET['op'] == 'cargarActivo'){
      $data = $activo->cargarActivo(["_idactivo" => $_GET['idactivo']]);
      echo json_encode($data);
    }


    if ($_GET['op'] == 'filtrarActivo'){
        $filtro_categoria    = ($_GET["filtro_categoria"] === "" ? null : $_GET["filtro_categoria"]);
        $filtro_responsable  = ($_GET["filtro_responsable"] === "" ? null : $_GET["filtro_responsable"]);
        $filtro_calidad      = ($_GET["filtro_calidad"] === "" ? null : $_GET["filtro_calidad"]);
        $filtro_sede         = ($_GET["filtro_sede"] === "" ? null : $_GET["filtro_sede"]);
        $filtro_dependencia  = ($_GET["filtro_dependencia"] === "" ? null : $_GET["filtro_dependencia"]);
        $filtro_estado       = ($_GET["filtro_estado"] === "" ? null : $_GET["filtro_estado"]);

        $rows = $activo->filtrarActivo([
            "_filtro_categoria"       => $filtro_categoria,
            "_filtro_responsable"     => $filtro_responsable,
            "_filtro_calidad"         => $filtro_calidad,
            "_filtro_sede"            => $filtro_sede,
            "_filtro_dependencia"     => $filtro_dependencia,
            "_filtro_estado"          => $filtro_estado
        ]);
        renderActivosTable($rows);
    }

    if($_GET['op'] == 'getActivo'){
      $data = $activo->getActivo(['_idactivo' => $_GET['idactivo']]);
      echo json_encode($data);
    }

    if($_GET['op'] == 'traerActivoDevolucion'){
      $data = $activo->traerActivoDevolucion(['_idactivo' => $_GET['idactivo']]);
      echo json_encode($data);
    }

    if ($_GET['op'] == 'consultarTimeline') {

        $data = $activo->consultarTimeline([
            "_idactivo" => $_GET["idactivo"]
        ]);

        echo json_encode($data);
        exit;
    }

    

    if ($_GET['op'] == 'registrarMovDevolucion'){

        $activo->registrarMovDevolucion([
            "_idactivo"         => $_GET["idactivo"],
            "_mov_idtipo"         => $_GET["mov_idtipo"],
            "_dev_responsable"         => $_GET["dev_responsable"],
            "_dev_motivo"         => $_GET["dev_motivo"]
        ]);
    }

}

if(isset($_POST['op'])){
  $activo = new Activo();

    if ($_POST['op'] == 'cargaMasivaActivos') {
        $archivo = $_FILES['archivo_carga'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            echo json_encode(["status" => "error", "message" => "Formato no válido. Use .xlsx, .xls o .csv"]);
            exit;
        }

        try {
            require_once '../models/Categoria.php';
            require_once '../models/Sede.php';
            require_once '../models/Dependencia.php';
            require_once '../models/Administrativo.php';

            $catModel = new Categoria();
            $sedModel = new Sede();
            $depModel = new Dependencia();
            $admModel = new Administrativo();

            $mapCategorias = [];
            foreach ($catModel->cargarCategoria() as $c) {
                $mapCategorias[strtolower(trim($c->nombre_categoria))] = $c->id_categoria;
            }
            $mapSedes = [];
            foreach ($sedModel->cargarSede() as $s) {
                $mapSedes[strtolower(trim($s->nombre_sede))] = $s->id_sede;
            }
            $mapDependencias = [];
            foreach ($depModel->cargarDependencia() as $d) {
                $mapDependencias[strtolower(trim($d->nombre_dependencia))] = $d->id_dependencia;
            }
            $mapAdministrativos = [];
            foreach ($admModel->cargarAdministrativo() as $a) {
                $mapAdministrativos[strtolower(trim($a->npersona))] = $a->id_administrativo;
            }

            $reader = IOFactory::createReader($extension === 'csv' ? 'Csv' : 'Xlsx');
            $spreadsheet = $reader->load($archivo['tmp_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $insertados = 0;
            $errores = [];

            foreach ($rows as $index => $row) {
                if ($index === 0) continue;

                $categoriaVal     = trim($row[0] ?? '');
                $marca            = trim($row[1] ?? '');
                $modelo           = trim($row[2] ?? '');
                $serie            = trim($row[3] ?? '');
                $codPatrimonial   = trim($row[4] ?? '');
                $responsableVal   = trim($row[5] ?? '');
                $sedeVal          = trim($row[6] ?? '');
                $dependenciaVal   = trim($row[7] ?? '');
                $estado           = trim($row[8] ?? '');
                $fechaAdquisicion = trim($row[9] ?? '');
                $observacion      = trim($row[10] ?? '');

                $filaNum = $index + 1;

                if (empty($categoriaVal) && empty($marca) && empty($codPatrimonial)) {
                    continue;
                }

                $erroresFila = [];

                if (empty($categoriaVal)) $erroresFila[] = "Categoría requerida";
                if (empty($marca)) $erroresFila[] = "Marca requerida";
                if (empty($codPatrimonial)) $erroresFila[] = "Código patrimonial requerido";
                if (empty($responsableVal)) $erroresFila[] = "Responsable requerido";
                if (empty($sedeVal)) $erroresFila[] = "Sede requerida";
                if (empty($dependenciaVal)) $erroresFila[] = "Dependencia requerida";
                if (empty($estado)) $erroresFila[] = "Estado requerido";

                $resolvedCategoriaId = null;
                if (!empty($categoriaVal)) {
                    if (is_numeric($categoriaVal)) {
                        $resolvedCategoriaId = intval($categoriaVal);
                    } else {
                        $resolvedCategoriaId = $mapCategorias[strtolower($categoriaVal)] ?? null;
                        if (!$resolvedCategoriaId) $erroresFila[] = "Categoría \"$categoriaVal\" no encontrada";
                    }
                }
                $resolvedResponsableId = null;
                if (!empty($responsableVal)) {
                    if (is_numeric($responsableVal)) {
                        $resolvedResponsableId = intval($responsableVal);
                    } else {
                        $resolvedResponsableId = $mapAdministrativos[strtolower($responsableVal)] ?? null;
                        if (!$resolvedResponsableId) $erroresFila[] = "Responsable \"$responsableVal\" no encontrado";
                    }
                }
                $resolvedSedeId = null;
                if (!empty($sedeVal)) {
                    if (is_numeric($sedeVal)) {
                        $resolvedSedeId = intval($sedeVal);
                    } else {
                        $resolvedSedeId = $mapSedes[strtolower($sedeVal)] ?? null;
                        if (!$resolvedSedeId) $erroresFila[] = "Sede \"$sedeVal\" no encontrada";
                    }
                }
                $resolvedDependenciaId = null;
                if (!empty($dependenciaVal)) {
                    if (is_numeric($dependenciaVal)) {
                        $resolvedDependenciaId = intval($dependenciaVal);
                    } else {
                        $resolvedDependenciaId = $mapDependencias[strtolower($dependenciaVal)] ?? null;
                        if (!$resolvedDependenciaId) $erroresFila[] = "Dependencia \"$dependenciaVal\" no encontrada";
                    }
                }

                if (!empty($erroresFila)) {
                    $errores[] = ["fila" => $filaNum, "codigo" => $codPatrimonial, "errores" => $erroresFila];
                    continue;
                }

                $existe = $activo->activoYaRegistrado(["_codpatrimonial" => $codPatrimonial]);
                if (count($existe) > 0) {
                    $errores[] = ["fila" => $filaNum, "codigo" => $codPatrimonial, "errores" => ["Código patrimonial ya registrado"]];
                    continue;
                }

                if (!empty($fechaAdquisicion) && preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fechaAdquisicion)) {
                    $partes = explode('/', $fechaAdquisicion);
                    $fechaAdquisicion = "{$partes[2]}-{$partes[1]}-{$partes[0]}";
                }

                try {
                    $result = $activo->cargaMasiva([
                        "_idcategoria"        => $resolvedCategoriaId,
                        "_marca"              => $marca,
                        "_modelo"             => $modelo,
                        "_serie"              => $serie,
                        "_cod_patrimonial"    => $codPatrimonial,
                        "_idsede"             => $resolvedSedeId,
                        "_iddependencia"      => $resolvedDependenciaId,
                        "_idadministrativo"   => $resolvedResponsableId,
                        "_estado"             => strtoupper($estado),
                        "_fecha_adquisicion"  => $fechaAdquisicion ?: null,
                        "_observacion"        => $observacion ?: null
                    ]);

                    if ($result && count($result) > 0) {
                        $insertados++;
                    } else {
                        $errores[] = ["fila" => $filaNum, "codigo" => $codPatrimonial, "errores" => ["Error al insertar en BD"]];
                    }
                } catch (Exception $e) {
                    $errores[] = ["fila" => $filaNum, "codigo" => $codPatrimonial, "errores" => [$e->getMessage()]];
                }
            }

            echo json_encode([
                "status"     => "success",
                "insertados" => $insertados,
                "errores"    => $errores
            ]);
        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (strpos($msg, 'SQLSTATE') !== false) {
                $msg = "Error en la base de datos. Asegúrese de haber ejecutado el script SQL que crea los nuevos procedimientos almacenados.";
            }
            echo json_encode(["status" => "error", "message" => $msg]);
        }
        exit;
    }

      if ($_POST['op'] == 'registrarActivo') {

    $nombreFoto = "";
    $ordenCompra = null;

    $imgDir = "../img/";
    $filesDir = "../archivos/";
    
    // Array para acumular errores y dar feedback
    $errores = [];

    // 1. VALIDAR Y PROCESAR LA FOTO (Obligatoria)
    if (!empty($_FILES['foto']['tmp_name'])) {
        $extFoto = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extensionesPermitidasFoto = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extFoto, $extensionesPermitidasFoto)) {
            // uniqid() asegura que no se repitan nombres si suben archivos al mismo tiempo
            $nombreFoto = uniqid('foto_', true) . "." . $extFoto;
            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $imgDir . $nombreFoto)) {
                $errores[] = "No se pudo guardar la foto en el servidor.";
            }
        } else {
            $errores[] = "Formato de foto no válido (Solo JPG, PNG, WEBP).";
        }
    } else {
        $errores[] = "La foto del activo es obligatoria.";
    }

    // 2. VALIDAR Y PROCESAR ORDEN DE COMPRA (Opcional)
    if (empty($errores) && !empty($_FILES['ordenCompra']['tmp_name'])) {
        $extOrden = strtolower(pathinfo($_FILES['ordenCompra']['name'], PATHINFO_EXTENSION));
        
        if ($extOrden === 'pdf') {
            $ordenCompra = uniqid('orden_', true) . ".pdf";
            if (!move_uploaded_file($_FILES['ordenCompra']['tmp_name'], $filesDir . $ordenCompra)) {
                $errores[] = "No se pudo guardar la orden de compra.";
            }
        } else {
            $errores[] = "La orden de compra debe ser un archivo PDF.";
        }
    }

    // 3. REGISTRAR EN LA BASE DE DATOS (Solo si no hubo errores en las subidas)
    if (empty($errores)) {
        try {
            $activo->registrarActivo([
                "_idcategoria"            => $_POST["idcategoria"] ?? null,
                "_txt_marca"              => $_POST["txt_marca"] ?? '',
                "_txt_modelo"             => $_POST["txt_modelo"] ?? '',
                "_txt_serie"              => $_POST["txt_serie"] ?? '',
                "_txt_patrimonial"        => $_POST["txt_patrimonial"] ?? '',
                "_select_responsable"     => $_POST["select_responsable"] ?? null,
                "_select_sede"            => $_POST["select_sede"] ?? null,
                "_select_dependencia"     => $_POST["select_dependencia"] ?? null,
                "_foto"                   => $nombreFoto,
                "_select_estado"          => $_POST["select_estado"] ?? null,
                "_date_fecha_adquisicion" => $_POST["date_fecha_adquisicion"] ?? null,
                "_orden_compra"           => $ordenCompra,
                "_observacion"            => $_POST["observacion"] ?? ''
            ]);
            echo json_encode(["status" => "success", "message" => "Activo registrado con éxito."]);
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "errores" => [$e->getMessage()]]);
        }
    } else {
        echo json_encode(["status" => "error", "errores" => $errores]);
    }
}

    if ($_POST['op'] == 'modificarActivo') {

        $idactivo        = $_POST["idactivo"];
        $idcategoria     = $_POST["idcategoria"];
        $marca           = $_POST["marca"];
        $modelo          = $_POST["modelo"];
        $serie           = $_POST["serie"];
        $codPatrimonial  = $_POST["codPatrimonial"];
        $idadministrativo = $_POST["idadministrativo"];
        $idsede          = $_POST["idsede"];
        $iddependencia   = $_POST["iddependencia"];
        $estado          = $_POST["estado"];
        $observacion     = $_POST["observacion"];
        $fotoActual      = $_POST["foto_actual"];

        $nombreFoto = $fotoActual; // por defecto mantiene la misma foto

        if (!empty($_FILES["foto"]["tmp_name"])) {
            $nombreFoto = date('YmdHis') . ".jpg";
            if (!move_uploaded_file($_FILES["foto"]["tmp_name"], "../img/" . $nombreFoto)) {
                echo json_encode(["resultado" => 0, "mensaje" => "Error al guardar imagen"]);
                exit;
            }
            if ($fotoActual != "" && file_exists("../img/" . $fotoActual)) {
                unlink("../img/" . $fotoActual); // elimina foto anterior
            }
        }

        $resultado = $activo->modificarActivo([
            "_idactivo"          => $idactivo,
            "_idcategoria"       => $idcategoria,
            "_txt_marca"         => $marca,
            "_txt_modelo"        => $modelo,
            "_txt_serie"         => $serie,
            "_txt_patrimonial"   => $codPatrimonial,
            "_select_responsable"=> $idadministrativo,
            "_select_sede"       => $idsede,
            "_select_dependencia"=> $iddependencia,
            "_foto"              => $nombreFoto,
            "_select_estado"     => $estado,
            "_observacion"       => $observacion
        ]);

        echo json_encode($resultado[0]);

        
      }

      // Prestamo de un solo activo
      if ($_POST['op'] === 'registrarMovPrestamo') {
            $nombreArchivo = null;

        /* ===============================
          VALIDAR ARCHIVO
          =============================== */
          if (!empty($_FILES['doc_sustento']['tmp_name'])) {

              // Validar tipo real
              $finfo = finfo_open(FILEINFO_MIME_TYPE);
              $mime  = finfo_file($finfo, $_FILES['doc_sustento']['tmp_name']);

              if ($mime !== 'application/pdf') {
                  echo json_encode(["error" => "El archivo debe ser PDF"]);
                  exit;
              }

              // Validar tamaño (5 MB)
              if ($_FILES['doc_sustento']['size'] > (5 * 1024 * 1024)) {
                  echo json_encode(["error" => "El archivo supera los 5 MB"]);
                  exit;
              }

              // Nombre único
              $nombreOriginal = basename($_FILES['doc_sustento']['name']);

              // Limpieza básica
              $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nombreOriginal);

              // Prefijo para evitar colisión
              $nombreArchivo = date('YmdHis') . "_" . $nombreLimpio;

              // Ruta final
              $ruta = "../docs/movimientos/" . $nombreArchivo;

              if (!move_uploaded_file($_FILES['doc_sustento']['tmp_name'], $ruta)) {
                  echo json_encode(["error" => "No se pudo guardar el archivo"]);
                  exit;
              }
          }

        // REGISTRAR MOVIMIENTO
          $result = $activo->registrarMovPrestamo([
              "_idactivo"            => $_POST["idactivo"],
              "_tipo"                => $_POST["mov_idtipo"],
              "_responsable_actual"  => $_POST["pres_responsable"],
              "_custodio_temporal"   => $_POST["resp_temporal"],
              "_tiempo"              => $_POST["prestamo_tiempo"],
              "_motivo"              => $_POST["prestamo_motivo"],
              "_doc_sustento"       =>  $nombreArchivo
          ]);

        if (!$result || empty($result[0])) {
            echo json_encode(["error" => "No se pudo registrar el movimiento"]);
            exit;
        }
        
        echo json_encode($result[0]);
        exit;
      }


      //PRESTAMO DE VARIOS ACTIVOS
      if ($_POST['op'] === 'registrarMovPrestamoActivos') {
          $nombreArchivo = null;

          /* ===============================
            VALIDAR ARCHIVO
            =============================== */
            if (!empty($_FILES['doc_sustento_ac']['tmp_name'])) {

                // Validar tipo real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $_FILES['doc_sustento_ac']['tmp_name']);

                if ($mime !== 'application/pdf') {
                    echo json_encode(["error" => "El archivo debe ser PDF"]);
                    exit;
                }

                // Validar tamaño (5 MB)
                if ($_FILES['doc_sustento_ac']['size'] > (5 * 1024 * 1024)) {
                    echo json_encode(["error" => "El archivo supera los 5 MB"]);
                    exit;
                }

                // Nombre único
                $nombreOriginal = basename($_FILES['doc_sustento_ac']['name']);

                // Limpieza básica
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nombreOriginal);

                // Prefijo para evitar colisión
                $nombreArchivo = date('YmdHis') . "_" . $nombreLimpio;

                // Ruta final
                $ruta = "../docs/movimientos/" . $nombreArchivo;

                if (!move_uploaded_file($_FILES['doc_sustento_ac']['tmp_name'], $ruta)) {
                    echo json_encode(["error" => "No se pudo guardar el archivo"]);
                    exit;
                }
            }

            $result = $activo->registrarMovPrestamoActivos([
                  "_ids_activos"          => $_POST["ids_activos"], // "1,2,3"
                  "_tipo"                 => $_POST["tipo"],
                  "_responsable_actual"   => $_POST["responsable_actual"],
                  "_custodio_temporal"    => $_POST["custodio_temporal"],
                  "_tiempo"               => $_POST["tiempo"],
                  "_motivo"               => $_POST["motivo"],
                  "_doc"       =>  $nombreArchivo

              ]);

            echo json_encode($result[0]); // { id_movimiento, codigo_mov }
            exit;
      }

      // TRANSFERENCIA DE UN ACTIVO
      if ($_POST['op'] == 'registrarMovTransferencia'){
          $nombreArchivo = null;

          /* ===============================
            VALIDAR ARCHIVO
            =============================== */
            if (!empty($_FILES['doc']['tmp_name'])) {

                // Validar tipo real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $_FILES['doc']['tmp_name']);

                if ($mime !== 'application/pdf') {
                    echo json_encode(["error" => "El archivo debe ser PDF"]);
                    exit;
                }

                // Validar tamaño (5 MB)
                if ($_FILES['doc']['size'] > (5 * 1024 * 1024)) {
                    echo json_encode(["error" => "El archivo supera los 5 MB"]);
                    exit;
                }

                // Nombre único
                $nombreOriginal = basename($_FILES['doc']['name']);

                // Limpieza básica
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nombreOriginal);

                // Prefijo para evitar colisión
                $nombreArchivo = date('YmdHis') . "_" . $nombreLimpio;

                // Ruta final
                $ruta = "../docs/movimientos/" . $nombreArchivo;

                if (!move_uploaded_file($_FILES['doc']['tmp_name'], $ruta)) {
                    echo json_encode(["error" => "No se pudo guardar el archivo"]);
                    exit;
                }
            }

          $result = $activo->registrarMovTransferencia([
              "_idactivo"         => $_POST["idactivo"],
              "_tipo"             => $_POST["mov_idtipo"],

              // Responsable actual (quien tiene el activo)
              "_transf_responsable_actual" => $_POST["transf_responsable_actual"],

              // responsable destino
              "_mov_responsable" => $_POST["mov_responsable"],

              // sede destino
              "_sede_destino"            => $_POST["sede_destino"],

              // Motivo
              "_transferencia_motivo"            => $_POST["transferencia_motivo"],

              // dependencia_destino
              "_dependencia_destino"            => $_POST["dependencia_destino"],
              "_doc"       =>  $nombreArchivo
          ]);

          if (!$result || empty($result[0])) {
              echo json_encode(["error" => "No se pudo registrar el movimiento"]);
              exit;
          }
          
          echo json_encode($result[0]);
          exit;
      }


      // TRANSFERENCIA DE VARIOS ACTIVOS
      if ($_POST['op'] === 'registrarMovTransferenciaActivos') {
          $nombreArchivo = null;

          /* ===============================
            VALIDAR ARCHIVO
            =============================== */
            if (!empty($_FILES['doc_ac']['tmp_name'])) {

                // Validar tipo real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $_FILES['doc_ac']['tmp_name']);

                if ($mime !== 'application/pdf') {
                    echo json_encode(["error" => "El archivo debe ser PDF"]);
                    exit;
                }

                // Validar tamaño (5 MB)
                if ($_FILES['doc_ac']['size'] > (5 * 1024 * 1024)) {
                    echo json_encode(["error" => "El archivo supera los 5 MB"]);
                    exit;
                }

                // Nombre único
                $nombreOriginal = basename($_FILES['doc_ac']['name']);

                // Limpieza básica
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nombreOriginal);

                // Prefijo para evitar colisión
                $nombreArchivo = date('YmdHis') . "_" . $nombreLimpio;

                // Ruta final
                $ruta = "../docs/movimientos/" . $nombreArchivo;

                if (!move_uploaded_file($_FILES['doc_ac']['tmp_name'], $ruta)) {
                    echo json_encode(["error" => "No se pudo guardar el archivo"]);
                    exit;
                }
            }

            $result = $activo->registrarMovTransferenciaActivos([
                  "_ids_activos"          => $_POST["ids_activos"], // "1,2,3"
                  "_tipo"                 => $_POST["tipo"],
                  "_transf_responsable_actual"   => $_POST["transf_responsable_actual"],
                  "_mov_responsable"    => $_POST["mov_responsable"],
                  "_sede_destino"               => $_POST["sede_destino"],
                  "_dependencia_destino"               => $_POST["dependencia_destino"],
                  "_transferencia_motivo"               => $_POST["transferencia_motivo"],
                  "_doc"       =>  $nombreArchivo
            ]);

            if (!$result || empty($result[0])) {
              echo json_encode(["error" => "No se pudo registrar el movimiento"]);
              exit;
            }
            
            echo json_encode($result[0]);
            exit;
            
      }
}

function renderActivosTable($rows) {
    if (!empty($rows)) {
        $i = 1;
        foreach ($rows as $r) {
            $rowStyle = "";
            if ($r->ultimo_movimiento == "PRESTAMO") {
                $rowStyle = "style='background:#e8f4ff'";
            } elseif ($r->ultimo_movimiento == "TRANSFERENCIA") {
                $rowStyle = "style='background:#fff7e6'";
            }

            switch ($r->estado) {
                case 'BUENO':  $badge = "<span class='badge bg-success'>BUENO</span>"; break;
                case 'REGULAR': $badge = "<span class='badge bg-warning text-dark'>REGULAR</span>"; break;
                case 'MALO':   $badge = "<span class='badge bg-danger'>MALO</span>"; break;
                default:       $badge = "<span class='badge bg-secondary'>DESCONOCIDO</span>"; break;
            }

            $estadoGarantia = property_exists($r, 'estado_garantia') ? $r->estado_garantia : 'SIN_GARANTIA';
            switch ($estadoGarantia) {
                case 'VIGENTE':
                    $badgeGarantia = "<span class='badge bg-success'>Vigente</span>";
                    break;
                case 'PROXIMO_A_VENCER':
                    $badgeGarantia = "<span class='badge bg-warning text-dark'>Próximo a vencer</span>";
                    break;
                case 'VENCIDA':
                    $badgeGarantia = "<span class='badge bg-danger'>Vencida</span>";
                    break;
                default:
                    $badgeGarantia = "<span class='badge bg-secondary'>Sin garantía</span>";
                    break;
            }

            $chekbox = "";
            $estado_mov = "";
            switch ($r->estado_mov) {
                case 'PRESTADO':
                    $estado_mov = "<a class='dropdown-item mover asignar' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-exchange-alt text-secondary'></i>&nbsp; Mover</a>
                                   <a class='dropdown-item devolucion' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-undo text-success'></i>&nbsp; Devolución</a>";
                    $chekbox = "<input type='checkbox' class='chk-activo' value='{$r->id_activo}' disabled>";
                    break;
                case 'TRANSFERIDO':
                    $estado_mov = "<a class='dropdown-item mover asignar' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-exchange-alt text-secondary'></i>&nbsp; Mover</a>
                                   <a class='dropdown-item devolucion asignar' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-undo text-success'></i>&nbsp; Devolución</a>";
                    $chekbox = "<input type='checkbox' class='chk-activo' value='{$r->id_activo}' disabled>";
                    break;
                case 'DEVUELTO':
                    $estado_mov = "<a class='dropdown-item mover' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-exchange-alt text-secondary'></i>&nbsp; Mover</a>
                                   <a class='dropdown-item devolucion asignar' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-undo text-success'></i>&nbsp; Devolución</a>";
                    $chekbox = "<input type='checkbox' class='chk-activo' value='{$r->id_activo}' data-codigo='{$r->cod_patrimonial}' data-foto='{$r->foto}' data-responsable='{$r->id_administrativo}' data-dependencia='{$r->id_dependencia}' data-nombre='{$r->npersona}'>";
                    break;
                default:
                    $estado_mov = "<a class='dropdown-item mover' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-exchange-alt text-secondary'></i>&nbsp; Mover</a>
                                   <a class='dropdown-item devolucion asignar' href='#' data-idactivo='{$r->id_activo}'><i class='fa fa-undo text-success'></i>&nbsp; Devolución</a>";
                    $chekbox = "<input type='checkbox' class='chk-activo' value='{$r->id_activo}' data-codigo='{$r->cod_patrimonial}' data-foto='{$r->foto}' data-responsable='{$r->id_administrativo}' data-dependencia='{$r->id_dependencia}' data-nombre='{$r->npersona}'>";
                    break;
            }

            echo "
                <tr class='row-activo' $rowStyle>
                    <td class='text-center'>{$i}</td>
                    <td class='text-center'>{$chekbox}</td>
                    <td class='text-center'><img style='width:30px' src='img/$r->foto'/></td>
                    <td class='text-center'>{$r->cod_patrimonial}</td>
                    <td class='text-center'>{$r->nombre_categoria}</td>
                    <td class='text-center'>{$r->marca_modelo}</td>
                    <td class='text-center'>{$r->nombre_sede}</td>
                    <td class='text-center'>{$r->nombre_dependencia}</td>
                    <td class='text-center'>{$r->npersona}</td>
                    <td class='text-center'>{$badge}</td>
                    <td class='text-center'>{$badgeGarantia}</td>
                    <td class='text-center'>
                        <div class='btn-group'>
                            <button type='button' class='btn btn-sm btn-outline-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                <i class='fa fa-cog'></i> Acciones
                            </button>
                            <div class='dropdown-menu dropdown-menu-right'>
                                <a class='dropdown-item info' href='#' data-idactivo='{$r->id_activo}' data-cod='{$r->cod_patrimonial}'>
                                    <i class='fa fa-info-circle text-primary'></i>&nbsp; Más info
                                </a>
                                <a class='dropdown-item ver' href='#' data-idactivo='{$r->id_activo}'>
                                    <i class='fa fa-eye text-info'></i>&nbsp; Ver Detalle
                                </a>
                                <a class='dropdown-item modificar' href='#' data-idactivo='{$r->id_activo}'>
                                    <i class='fa fa-edit text-warning'></i>&nbsp; Editar
                                </a>
                                {$estado_mov}
                                <div class='dropdown-divider'></div>
                                <a class='dropdown-item text-danger eliminar' href='#' data-idactivo='{$r->id_activo}'>
                                    <i class='fa fa-trash'></i>&nbsp; Eliminar
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            ";
            $i++;
        }
    }
}
?>
