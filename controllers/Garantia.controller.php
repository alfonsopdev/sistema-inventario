<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Garantia.php';

$garantia = new Garantia();

if (isset($_GET['op'])) {

    if ($_GET['op'] == 'listarPorActivo') {
        $data = $garantia->listarPorActivo(["_idactivo" => $_GET['idactivo']]);
        echo json_encode($data);
        exit;
    }

    if ($_GET['op'] == 'cargar') {
        $data = $garantia->cargar(["_id_garantia" => $_GET['idgarantia']]);
        echo json_encode($data);
        exit;
    }

    if ($_GET['op'] == 'eliminar') {
        $garantia->eliminar(["_id_garantia" => $_GET['idgarantia']]);
        echo json_encode(["status" => "success"]);
        exit;
    }

    if ($_GET['op'] == 'listarTodos') {
        $data = $garantia->listarTodos();
        echo json_encode($data);
        exit;
    }

    if ($_GET['op'] == 'resumen') {
        $data = $garantia->resumen();
        echo json_encode($data);
        exit;
    }
}

if (isset($_POST['op'])) {

    if ($_POST['op'] == 'registrar') {
        $documentoPdf = null;

        if (!empty($_FILES['documento_pdf']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['documento_pdf']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $documentoPdf = uniqid('garantia_', true) . ".pdf";
                move_uploaded_file($_FILES['documento_pdf']['tmp_name'], "../archivos/" . $documentoPdf);
            }
        }

        $result = $garantia->registrar([
            "_id_activo"      => $_POST['id_activo'],
            "_proveedor"      => $_POST['proveedor'] ?? null,
            "_tipo_garantia"  => $_POST['tipo_garantia'] ?? null,
            "_fecha_inicio"   => $_POST['fecha_inicio'] ?? null,
            "_fecha_fin"      => $_POST['fecha_fin'],
            "_documento_pdf"  => $documentoPdf,
            "_observacion"    => $_POST['observacion'] ?? null
        ]);

        echo json_encode(["status" => "success", "id" => $result[0]->id_garantia]);
        exit;
    }

    if ($_POST['op'] == 'editar') {
        $documentoPdf = $_POST['documento_actual'] ?? null;

        if (!empty($_FILES['documento_pdf']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['documento_pdf']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                if ($documentoPdf && file_exists("../archivos/" . $documentoPdf)) {
                    unlink("../archivos/" . $documentoPdf);
                }
                $documentoPdf = uniqid('garantia_', true) . ".pdf";
                move_uploaded_file($_FILES['documento_pdf']['tmp_name'], "../archivos/" . $documentoPdf);
            }
        }

        $garantia->editar([
            "_id_garantia"    => $_POST['id_garantia'],
            "_proveedor"      => $_POST['proveedor'] ?? null,
            "_tipo_garantia"  => $_POST['tipo_garantia'] ?? null,
            "_fecha_inicio"   => $_POST['fecha_inicio'] ?? null,
            "_fecha_fin"      => $_POST['fecha_fin'],
            "_documento_pdf"  => $documentoPdf,
            "_observacion"    => $_POST['observacion'] ?? null
        ]);

        echo json_encode(["status" => "success"]);
        exit;
    }
}
