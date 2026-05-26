<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] == 'Médico') {
    echo "<strong>No tiene el nivel de acceso requerido</strong>";
    exit();
}
?>

<style>
    .card-garantia {
        border-radius: 10px;
        transition: transform .2s;
    }
    .card-garantia:hover {
        transform: translateY(-3px);
    }
    .numero-grande {
        font-size: 2.5rem;
        font-weight: bold;
    }
</style>

<div class="row mb-3">
    <div class="col-md-12">
        <h3 class="mt-3" style="font-size: 25px"><b>Dashboard de Garantías</b></h3>
    </div>
</div>

<div class="row mb-4" id="resumenGarantias">
    <div class="col-md-3">
        <div class="card card-garantia bg-success text-white">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_vigentes">0</div>
                <span>Vigentes</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-garantia bg-warning text-dark">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_proximas">0</div>
                <span>Próximas a vencer</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-garantia bg-danger text-white">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_vencidas">0</div>
                <span>Vencidas</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-garantia bg-secondary text-white">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_sin_garantia">0</div>
                <span>Sin garantía</span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title text-navy"><b>Todas las Garantías</b></h3>
        <div class="card-tools">
            <select id="filtroEstadoGarantia" class="form-control form-control-sm" style="width: 200px;">
                <option value="">Todos los estados</option>
                <option value="VIGENTE">Vigente</option>
                <option value="PROXIMO_A_VENCER">Próximo a vencer</option>
                <option value="VENCIDA">Vencida</option>
            </select>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover" id="tablaGarantiasGlobal">
            <thead class="bg-navy">
                <tr>
                    <th>N°</th>
                    <th>Cód. Patrimonial</th>
                    <th>Activo</th>
                    <th>Categoría</th>
                    <th>Responsable</th>
                    <th>Proveedor</th>
                    <th>Tipo</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Documento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function () {

    function cargarResumen() {
        $.ajax({
            url: 'controllers/Garantia.controller.php',
            type: 'GET',
            data: { op: 'resumen' },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    $('#cnt_vigentes').text(data[0].vigentes || 0);
                    $('#cnt_proximas').text(data[0].proximas_a_vencer || 0);
                    $('#cnt_vencidas').text(data[0].vencidas || 0);
                    $('#cnt_sin_garantia').text(data[0].sin_garantia || 0);
                }
            }
        });
    }

    function cargarTabla(filtro) {
        $.ajax({
            url: 'controllers/Garantia.controller.php',
            type: 'GET',
            data: { op: 'listarTodos' },
            dataType: 'json',
            success: function (data) {
                var tabla = $('#tablaGarantiasGlobal').DataTable();
                tabla.destroy();

                var html = '';
                var filtered = data;
                if (filtro) {
                    filtered = data.filter(function (g) { return g.estado === filtro; });
                }

                if (filtered.length === 0) {
                    html = '<tr><td colspan="12" class="text-center text-muted">No hay garantías registradas</td></tr>';
                } else {
                    filtered.forEach(function (g, i) {
                        var badge = '';
                        switch (g.estado) {
                            case 'VIGENTE':          badge = '<span class="badge bg-success">Vigente</span>'; break;
                            case 'PROXIMO_A_VENCER': badge = '<span class="badge bg-warning text-dark">Próximo a vencer</span>'; break;
                            case 'VENCIDA':          badge = '<span class="badge bg-danger">Vencida</span>'; break;
                            default:                 badge = '<span class="badge bg-secondary">' + (g.estado || '—') + '</span>';
                        }

                        var docLink = g.documento_pdf
                            ? '<a href="archivos/' + g.documento_pdf + '" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-file-pdf"></i></a>'
                            : '<span class="text-muted">—</span>';

                        html += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td class="text-center">' + (g.cod_patrimonial || '—') + '</td>' +
                            '<td>' + (g.activo_nombre || '—') + '</td>' +
                            '<td>' + (g.nombre_categoria || '—') + '</td>' +
                            '<td>' + (g.responsable || '—') + '</td>' +
                            '<td>' + (g.proveedor || '—') + '</td>' +
                            '<td class="text-center">' + (g.tipo_garantia || '—') + '</td>' +
                            '<td class="text-center">' + (g.fecha_inicio || '—') + '</td>' +
                            '<td class="text-center">' + (g.fecha_fin || '—') + '</td>' +
                            '<td class="text-center">' + badge + '</td>' +
                            '<td class="text-center">' + docLink + '</td>' +
                            '<td class="text-center">' +
                            '  <a href="main.php?view=activo/view_detalle.php&id=' + g.id_activo + '" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>' +
                            '</td></tr>';
                    });
                }

                $('#tablaGarantiasGlobal tbody').html(html);

                $('#tablaGarantiasGlobal').DataTable({
                    language: {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                        "sSearch": "Buscar:",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: ['copy', 'pdf', 'excel']
                });
            }
        });
    }

    $('#filtroEstadoGarantia').change(function () {
        cargarTabla($(this).val());
    });

    cargarResumen();
    cargarTabla('');
});
</script>
