<?php
session_start();
date_default_timezone_set("America/Lima");
require_once '../models/Mantenimiento.php';

$mantenimiento = new Mantenimiento();

if (isset($_GET['op'])) {

    if ($_GET['op'] == 'listarPorActivo') {
        $data = $mantenimiento->listarPorActivo(["_idactivo" => $_GET['idactivo']]);
        echo json_encode($data);
        exit;
    }

    if ($_GET['op'] == 'cargar') {
        $data = $mantenimiento->cargar(["_id_mantenimiento" => $_GET['idmantenimiento']]);
        echo json_encode($data);
        exit;
    }

    if ($_GET['op'] == 'eliminar') {
        $mantenimiento->eliminar(["_id_mantenimiento" => $_GET['idmantenimiento']]);
        echo json_encode(["status" => "success"]);
        exit;
    }
}

if (isset($_POST['op'])) {

    if ($_POST['op'] == 'registrar') {
        $documentoPdf = null;

        if (!empty($_FILES['documento_pdf']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['documento_pdf']['name'], PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                $documentoPdf = uniqid('manto_', true) . ".pdf";
                move_uploaded_file($_FILES['documento_pdf']['tmp_name'], "../archivos/" . $documentoPdf);
            }
        }

        $result = $mantenimiento->registrar([
            "_id_activo"                => $_POST['id_activo'],
            "_tipo_mantenimiento"       => $_POST['tipo_mantenimiento'],
            "_fecha_mantenimiento"      => $_POST['fecha_mantenimiento'],
            "_descripcion"              => $_POST['descripcion'] ?? null,
            "_responsable"              => $_POST['responsable'] ?? null,
            "_costo"                    => $_POST['costo'] ?? null,
            "_documento_pdf"            => $documentoPdf,
            "_proveedor"                => $_POST['proveedor'] ?? null,
            "_fecha_proximo_mantenimiento" => $_POST['fecha_proximo_mantenimiento'] ?? null
        ]);

        echo json_encode(["status" => "success", "id" => $result[0]->id_mantenimiento]);
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
                $documentoPdf = uniqid('manto_', true) . ".pdf";
                move_uploaded_file($_FILES['documento_pdf']['tmp_name'], "../archivos/" . $documentoPdf);
            }
        }

        $mantenimiento->editar([
            "_id_mantenimiento"         => $_POST['id_mantenimiento'],
            "_tipo_mantenimiento"       => $_POST['tipo_mantenimiento'],
            "_fecha_mantenimiento"      => $_POST['fecha_mantenimiento'],
            "_descripcion"              => $_POST['descripcion'] ?? null,
            "_responsable"              => $_POST['responsable'] ?? null,
            "_costo"                    => $_POST['costo'] ?? null,
            "_documento_pdf"            => $documentoPdf,
            "_proveedor"                => $_POST['proveedor'] ?? null,
            "_fecha_proximo_mantenimiento" => $_POST['fecha_proximo_mantenimiento'] ?? null
        ]);

        echo json_encode(["status" => "success"]);
        exit;
    }
}
