<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] == 'Médico') {
    echo "<strong>No tiene el nivel de acceso requerido</strong>";
    exit();
}
?>

<style>
    .card-mantenimiento {
        border-radius: 10px;
        transition: transform .2s;
    }
    .card-mantenimiento:hover {
        transform: translateY(-3px);
    }
    .numero-grande {
        font-size: 2.5rem;
        font-weight: bold;
    }
</style>

<div class="row mb-3">
    <div class="col-md-12">
        <h3 class="mt-3" style="font-size: 25px"><b>Dashboard de Mantenimientos</b></h3>
    </div>
</div>

<div class="row mb-4" id="resumenMantenimientos">
    <div class="col-md-3">
        <div class="card card-mantenimiento bg-info text-white">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_total">0</div>
                <span>Total Mantenimientos</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-mantenimiento bg-success text-white">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_preventivos">0</div>
                <span>Preventivos</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-mantenimiento bg-warning text-dark">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_correctivos">0</div>
                <span>Correctivos</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-mantenimiento bg-danger text-white">
            <div class="card-body text-center">
                <div class="numero-grande" id="cnt_costo_total">0</div>
                <span>Costo Total (S/)</span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-navy"><b>Mantenimientos por Tipo</b></h3>
            </div>
            <div class="card-body">
                <canvas id="chartTipo" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-navy"><b>Estado de Próximos Mantenimientos</b></h3>
            </div>
            <div class="card-body">
                <canvas id="chartEstado" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title text-navy"><b>Todos los Mantenimientos</b></h3>
        <div class="card-tools">
            <select id="filtroTipoMantenimiento" class="form-control form-control-sm" style="width: 200px;">
                <option value="">Todos los tipos</option>
                <option value="PREVENTIVO">Preventivo</option>
                <option value="CORRECTIVO">Correctivo</option>
                <option value="PREDICTIVO">Predictivo</option>
            </select>
        </div>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover" id="tablaMantenimientosGlobal">
            <thead class="bg-navy">
                <tr>
                    <th>N°</th>
                    <th>Cód. Patrimonial</th>
                    <th>Activo</th>
                    <th>Categoría</th>
                    <th>Tipo</th>
                    <th>Fecha Manto.</th>
                    <th>Proveedor</th>
                    <th>Costo</th>
                    <th>Próx. Manto.</th>
                    <th>Documento</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="plugins/chart.js/Chart.min.js"></script>
<script>
$(document).ready(function () {

    var chartTipo = null;
    var chartEstado = null;

    function cargarResumen() {
        $.ajax({
            url: 'controllers/Mantenimiento.controller.php',
            type: 'GET',
            data: { op: 'resumen' },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    var d = data[0];
                    $('#cnt_total').text(d.total_mantenimientos || 0);
                    $('#cnt_preventivos').text(d.preventivos || 0);
                    $('#cnt_correctivos').text(d.correctivos || 0);
                    $('#cnt_costo_total').text(parseFloat(d.costo_total || 0).toFixed(2));

                    if (chartTipo) chartTipo.destroy();
                    chartTipo = new Chart(document.getElementById('chartTipo'), {
                        type: 'bar',
                        data: {
                            labels: ['Preventivo', 'Correctivo', 'Predictivo'],
                            datasets: [{
                                data: [d.preventivos || 0, d.correctivos || 0, d.predictivos || 0],
                                backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                            }]
                        },
                        options: {
                            responsive: true,
                            legend: { display: false },
                            scales: {
                                yAxes: [{
                                    ticks: { beginAtZero: true, stepSize: 1 }
                                }]
                            }
                        }
                    });
                }
            }
        });
    }

    function cargarEstadoChart(data) {
        var programado = 0, proximo = 0, vencido = 0, sinProg = 0;
        data.forEach(function (m) {
            if (m.estado_proximo === 'PROGRAMADO') programado++;
            else if (m.estado_proximo === 'PROXIMO') proximo++;
            else if (m.estado_proximo === 'VENCIDO') vencido++;
            else sinProg++;
        });

        if (chartEstado) chartEstado.destroy();
        chartEstado = new Chart(document.getElementById('chartEstado'), {
            type: 'doughnut',
            data: {
                labels: ['Programado', 'Próximo (30 días)', 'Vencido', 'Sin programar'],
                datasets: [{
                    data: [programado, proximo, vencido, sinProg],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                legend: { position: 'bottom' }
            }
        });
    }

    function cargarTabla(filtro) {
        $.ajax({
            url: 'controllers/Mantenimiento.controller.php',
            type: 'GET',
            data: { op: 'listarTodos' },
            dataType: 'json',
            success: function (data) {
                cargarEstadoChart(data);

                var tabla = $('#tablaMantenimientosGlobal').DataTable();
                tabla.destroy();

                var html = '';
                var filtered = data;
                if (filtro) {
                    filtered = data.filter(function (m) { return m.tipo_mantenimiento === filtro; });
                }

                if (filtered.length === 0) {
                    html = '<tr><td colspan="11" class="text-center text-muted">No hay mantenimientos registrados</td></tr>';
                } else {
                    filtered.forEach(function (m, i) {
                        var badgeTipo = '';
                        switch (m.tipo_mantenimiento) {
                            case 'PREVENTIVO':  badgeTipo = '<span class="badge bg-success">Preventivo</span>'; break;
                            case 'CORRECTIVO':  badgeTipo = '<span class="badge bg-warning text-dark">Correctivo</span>'; break;
                            case 'PREDICTIVO':  badgeTipo = '<span class="badge bg-danger">Predictivo</span>'; break;
                            default:            badgeTipo = '<span class="badge bg-secondary">' + (m.tipo_mantenimiento || '—') + '</span>';
                        }

                        var badgeProx = '';
                        switch (m.estado_proximo) {
                            case 'PROGRAMADO':   badgeProx = '<span class="badge bg-success">Programado</span>'; break;
                            case 'PROXIMO':      badgeProx = '<span class="badge bg-warning text-dark">Próximo</span>'; break;
                            case 'VENCIDO':      badgeProx = '<span class="badge bg-danger">Vencido</span>'; break;
                            default:             badgeProx = '<span class="badge bg-secondary">Sin programar</span>';
                        }

                        var docLink = m.documento_pdf
                            ? '<a href="archivos/' + m.documento_pdf + '" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-file-pdf"></i></a>'
                            : '<span class="text-muted">—</span>';

                        html += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td class="text-center">' + (m.cod_patrimonial || '—') + '</td>' +
                            '<td>' + (m.activo_nombre || '—') + '</td>' +
                            '<td>' + (m.nombre_categoria || '—') + '</td>' +
                            '<td class="text-center">' + badgeTipo + '</td>' +
                            '<td class="text-center">' + (m.fecha_mantenimiento || '—') + '</td>' +
                            '<td>' + (m.proveedor || '—') + '</td>' +
                            '<td class="text-center">' + (m.costo ? 'S/ ' + parseFloat(m.costo).toFixed(2) : '—') + '</td>' +
                            '<td class="text-center">' + (m.fecha_proximo_mantenimiento || '—') + ' ' + (m.fecha_proximo_mantenimiento ? badgeProx : '') + '</td>' +
                            '<td class="text-center">' + docLink + '</td>' +
                            '<td class="text-center">' +
                            '  <a href="main.php?view=activo/view_detalle.php&id=' + m.id_activo + '" class="btn btn-sm btn-outline-primary"><i class="fa fa-eye"></i></a>' +
                            '</td></tr>';
                    });
                }

                $('#tablaMantenimientosGlobal tbody').html(html);

                $('#tablaMantenimientosGlobal').DataTable({
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
                    buttons: [
                        { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] } },
                        { extend: 'pdf', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] } },
                        { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9] } }
                    ]
                });
            }
        });
    }

    $('#filtroTipoMantenimiento').change(function () {
        cargarTabla($(this).val());
    });

    cargarResumen();
    cargarTabla('');
});
</script>
