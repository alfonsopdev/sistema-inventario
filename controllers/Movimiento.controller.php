<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Movimiento.php';
require_once '../models/Serverside.php';

if (isset($_GET['op'])){

    $movimiento = new Movimiento();

    if ($_GET['op'] == 'listarMovimiento') {
        $rows = $movimiento->listarMovimiento();
        if (!empty($rows)) {
        $i = 1;
          foreach ($rows as $r) {
              $mov_tipo = "";
              $tiempo = "";
              $estado_mov = ""; // por defecto no mostrar nada
              $documento = "";

              switch ($r->tipo_mov) {
                case 'PRESTAMO':
                  $mov_tipo = "<span class='bg-suave-azul'>Préstamo</span>";
                  $tiempo = "{$r->tiempo} días";
                  // SOLO SI NO ESTÁ CERRADO
                  if ($r->mov_estado !== 'CERRADO') {
                      $estado_mov = "
                          <a class='dropdown-item devolucion' href='#' data-idmovimiento='{$r->id_movimiento}'>
                              <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                          </a>";
                  }else{
                    $estado_mov = "
                          <a class='dropdown-item devolucion asignar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                              <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                          </a>";
                  }
                  break;
                case 'TRANSFERENCIA':
                  $mov_tipo = "<span class='bg-suave-ambar'>Transferencia</span>";
                  $tiempo = "NO DEFINIDO";
                  $estado_mov = "<a class='dropdown-item devolucion asignar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                                  <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                                </a>";
                  break;
                default:
                  $mov_tipo = "<span class='bg-suave-verde'>Devolución</span>";
                  $tiempo = "{$r->tiempo} días";
                  $estado_mov = "<a class='dropdown-item devolucion asignar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                                  <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                                </a>";
                  break;
              }

              if (!empty($r->informe_doc)) {
                $documento = "<a href='controllers/Movimiento.controller.php?op=verDocumento&idmovimiento={$r->id_movimiento}'
                                target='_blank'
                                title='Ver documento'>
                                  <i class='fas fa-paperclip text-warning' style='font-size:24px'></i>
                              </a>";
              }

              echo "
              <tr>
                  <td class='text-center'>{$i}</td>
                  <td class='text-center'>{$r->codigo_mov}</td>
                  <td class='text-center'>{$mov_tipo}</td>
                  <td class='text-center'>{$r->ubi_antes}</td>
                  <td class='text-center'>{$r->ubi_actual}</td>
                  <td class='text-center'>{$r->responsable_mov}</td>
                  <td class='text-center'>{$r->responsable_actual}</td>
                  <td class='text-center'>{$r->fecha_mov}</td>
                  <td class='text-center'>{$documento}</td>
                  <td class='text-center'>{$tiempo}</td>
                  <td class='text-center'>
                    <div class='btn-group'>
                      <button type='button' class='btn btn-sm btn-outline-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-cog'></i>&nbsp; Acción
                      </button>
                      <div class='dropdown-menu dropdown-menu-right'>
                        <a class='dropdown-item ver' href='#' data-idmovimiento='{$r->id_movimiento}'>
                          <i class='fa fa-eye text-info'></i>&nbsp; Ver detalles
                        </a>
                        {$estado_mov}
                        <div class='dropdown-divider'></div>
                        <a class='dropdown-item text-danger eliminar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                          <i class='fa fa-trash'></i>&nbsp; Eliminar
                        </a>
                      </div>
                    </div>
                  </td>
              </tr>";
              $i++;
          }
        }
        exit;
    }

    if($_GET['op'] == 'cargarActivos'){
        $data = $movimiento->cargarActivos(["_id" => $_GET['id_movimiento']]);
        
        foreach ($data as &$r) {
            switch ($r->estado) {
                case 'BUENO':  $r->badge = "<span class='badge bg-success'>BUENO</span>"; break;
                case 'REGULAR': $r->badge = "<span class='badge bg-warning text-dark'>REGULAR</span>"; break;
                case 'MALO':    $r->badge = "<span class='badge bg-danger'>MALO</span>"; break;
                default:        $r->badge = "<span class='badge bg-secondary'>DESCONOCIDO</span>"; break;
            }
        }

        // Enviar fecha y responsable junto con los activos
        $response = [
            "fecha_mov" => !empty($data) ? $data[0]->fecha_mov : "",
            "codigo_mov"  => !empty($data) ? $data[0]->codigo_mov : "",
            "npersona1"  => !empty($data) ? $data[0]->npersona1 : "",
            "npersona2"  => !empty($data) ? $data[0]->npersona2 : "",
            "tipo_mov"  => !empty($data) ? $data[0]->tipo_mov : "",
            "mov_estado"  => !empty($data) ? $data[0]->mov_estado : "",
            "motivo"  => !empty($data) ? $data[0]->motivo : "",
            "informe_doc"  => !empty($data) ? $data[0]->informe_doc : "",
            "tiempo_mov"  => !empty($data) ? $data[0]->tiempo_mov : "",
            "ubicacion_origen"  => !empty($data) ? $data[0]->ubicacion_origen : "",
            "ubicacion_destino"  => !empty($data) ? $data[0]->ubicacion_destino : "",
            "fecha_devolucion"  => !empty($data) ? $data[0]->fecha_devolucion : "",
            "activos"   => $data
        ];

        echo json_encode($response);
    }



    if($_GET['op'] == 'traerActivosDevolucion'){
      $data = $movimiento->traerActivosDevolucion(['_idmovimiento' => $_GET['idmovimiento']]);
      echo json_encode($data);
    }


    if ($_GET['op'] == 'filtrarMovimiento'){
        $reporte_fecha_inicio = ($_GET["reporte_fecha_inicio"] === "" ? null : $_GET["reporte_fecha_inicio"]);
        $reporte_fecha_fin    = ($_GET["reporte_fecha_fin"] === "" ? null : $_GET["reporte_fecha_fin"]);
        $filtro_mov_responsable  = ($_GET["filtro_mov_responsable"]       === "" ? null : $_GET["filtro_mov_responsable"]);
        $filtro_calidad     = ($_GET["filtro_calidad"]          === "" ? null : $_GET["filtro_calidad"]);
        // Llamar al modelo con todos los filtros
        $rows = $movimiento->filtrarMovimiento([
            "_reporte_fecha_inicio" => $reporte_fecha_inicio,
            "_reporte_fecha_fin"    => $reporte_fecha_fin,
            "_filtro_mov_responsable"  => $filtro_mov_responsable,
            "_filtro_calidad"     => $filtro_calidad
        ]);


        // Pintar tabla
        $i = 1;
        foreach ($rows as $r) {
              $mov_tipo = "";
              $tiempo = "";
              $estado_mov = ""; // por defecto no mostrar nada
              $documento = "";

              switch ($r->tipo_mov) {
                case 'PRESTAMO':
                  $mov_tipo = "<span class='bg-suave-azul'>Préstamo</span>";
                  $tiempo = "{$r->tiempo} días";
                  // SOLO SI NO ESTÁ CERRADO
                  if ($r->mov_estado !== 'CERRADO') {
                      $estado_mov = "
                          <a class='dropdown-item devolucion' href='#' data-idmovimiento='{$r->id_movimiento}'>
                              <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                          </a>";
                  }else{
                    $estado_mov = "
                          <a class='dropdown-item devolucion asignar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                              <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                          </a>";
                  }
                  break;
                case 'TRANSFERENCIA':
                  $mov_tipo = "<span class='bg-suave-ambar'>Transferencia</span>";
                  $tiempo = "NO DEFINIDO";
                  $estado_mov = "<a class='dropdown-item devolucion asignar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                                  <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                                </a>";
                  break;
                default:
                  $mov_tipo = "<span class='bg-suave-verde'>Devolución</span>";
                  $tiempo = "{$r->tiempo} días";
                  $estado_mov = "<a class='dropdown-item devolucion asignar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                                  <i class='fa fa-undo text-success'></i>&nbsp; Devolver
                                </a>";
                  break;
              }

              if (!empty($r->informe_doc)) {
                $documento = "<a href='controllers/Movimiento.controller.php?op=verDocumento&idmovimiento={$r->id_movimiento}'
                                target='_blank'
                                title='Ver documento'>
                                  <i class='fas fa-paperclip text-warning' style='font-size:24px'></i>
                              </a>";
              }

              echo "
              <tr>
                  <td class='text-center'>{$i}</td>
                  <td class='text-center'>{$r->codigo_mov}</td>
                  <td class='text-center'>{$mov_tipo}</td>
                  <td class='text-center'>{$r->ubi_antes}</td>
                  <td class='text-center'>{$r->ubi_actual}</td>
                  <td class='text-center'>{$r->responsable_mov}</td>
                  <td class='text-center'>{$r->responsable_actual}</td>
                  <td class='text-center'>{$r->fecha_mov}</td>
                  <td class='text-center'>{$documento}</td>
                  <td class='text-center'>{$tiempo}</td>
                  <td class='text-center'>
                    <div class='btn-group'>
                      <button type='button' class='btn btn-sm btn-outline-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-cog'></i>&nbsp; Acción
                      </button>
                      <div class='dropdown-menu dropdown-menu-right'>
                        <a class='dropdown-item ver' href='#' data-idmovimiento='{$r->id_movimiento}'>
                          <i class='fa fa-eye text-info'></i>&nbsp; Ver detalles
                        </a>
                        {$estado_mov}
                        <div class='dropdown-divider'></div>
                        <a class='dropdown-item text-danger eliminar' href='#' data-idmovimiento='{$r->id_movimiento}'>
                          <i class='fa fa-trash'></i>&nbsp; Eliminar
                        </a>
                      </div>
                    </div>
                  </td>
              </tr>";
              $i++;
          }
        exit;
    }


    if ($_GET['op'] === 'verDocumento') {

        $docArray = $movimiento->obtenerDocumentoMovimiento(['_idmovimiento' => $_GET['idmovimiento']]);

        if (empty($docArray) || empty($docArray[0]->informe_doc)) {
            http_response_code(404);
            exit("Documento no encontrado");
        }

        $doc = $docArray[0]; // ahora tenemos el objeto real

        $ruta = "../docs/movimientos/" . $doc->informe_doc;

        if (!file_exists($ruta)) {
            http_response_code(404);
            exit("Archivo no existe");
        }

        header("Content-Type: application/pdf");
        header("Content-Disposition: inline; filename=\"{$doc->informe_doc}\"");
        header("Content-Length: " . filesize($ruta));
        readfile($ruta);
        exit;

    }


}

if (isset($_POST['op'])){
    $movimiento = new Movimiento();
  
    if ($_POST['op'] == 'registrarMovDevolucionActivos'){
            $nombreArchivo = null;

            /* ===============================
            VALIDAR ARCHIVO
            =============================== */
            if (!empty($_FILES['doc_dev_ac']['tmp_name'])) {

                // Validar tipo real
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = finfo_file($finfo, $_FILES['doc_dev_ac']['tmp_name']);

                if ($mime !== 'application/pdf') {
                    echo json_encode(["error" => "El archivo debe ser PDF"]);
                    exit;
                }

                // Validar tamaño (5 MB)
                if ($_FILES['doc_dev_ac']['size'] > (5 * 1024 * 1024)) {
                    echo json_encode(["error" => "El archivo supera los 5 MB"]);
                    exit;
                }

                // Nombre único
                $nombreOriginal = basename($_FILES['doc_dev_ac']['name']);

                // Limpieza básica
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nombreOriginal);

                // Prefijo para evitar colisión
                $nombreArchivo = date('YmdHis') . "_" . $nombreLimpio;

                // Ruta final
                $ruta = "../docs/movimientos/" . $nombreArchivo;

                if (!move_uploaded_file($_FILES['doc_dev_ac']['tmp_name'], $ruta)) {
                    echo json_encode(["error" => "No se pudo guardar el archivo"]);
                    exit;
                }
            }


        $result = $movimiento->registrarMovDevolucionActivos([
            "_id_movimiento_prestamo"         => $_POST["id_movimiento_prestamo"],
            "_ids_activos"         => $_POST["ids_activos"],
            "_dev_responsable"         => $_POST["dev_responsable"],
            "_dev_motivo"         => $_POST["dev_motivo"],
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

