$(document).ready(function () {

    // ===============================
    // 1. KPIs
    // ===============================
    function cargarKPIs() {
        $.ajax({
            url: 'controllers/Dashboard.controller.php',
            type: 'GET',
            data: { op: 'kpis' },
            dataType: 'json',
            success: function (data) {
                $("#totalActivos").text(data.total_activos);
                $("#activosOperativos").text(data.disponibles);
                $("#prestamosActivos").text(data.prestados);
                $("#prestamosTransferidos").text(data.transferidos);
            }
        });
    }

    // ===============================
    // 2. Gráfico: Estados de Activos
    // ===============================
    let chartEstados = null;

    function cargarEstadosActivos() {
        $.ajax({
            url: 'controllers/Dashboard.controller.php',
            type: 'GET',
            data: { op: 'estados' },
            dataType: 'json',
            success: function (data) {

                let labels = [];
                let valores = [];

                data.forEach(e => {
                    labels.push(e.estado);
                    valores.push(e.total);
                });

                if (chartEstados) {
                    chartEstados.destroy();
                }

                chartEstados = new Chart($("#chartEstados"), {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: valores,
                            backgroundColor: [
                                '#17a2b8',
                                '#28a745',
                                '#ffc107',
                                '#dc3545',
                                '#6f42c1'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        });
    }

    // ===============================
    // 3. Gráfico: Movimientos por mes
    // ===============================
    let chartMovimientos = null;

function cargarMovimientosMes() {
    $.ajax({
        url: 'controllers/Dashboard.controller.php',
        type: 'GET',
        data: { op: 'movimientos_mes' },
        dataType: 'json',
        success: function (data) {

            const mesesBase = [
                "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ];

            let totales = new Array(12).fill(0);

            data.forEach(m => {
                // mes viene como 1–12
                totales[m.mes - 1] = m.total_movimientos;
            });

            if (chartMovimientos) {
                chartMovimientos.destroy();
            }

            chartMovimientos = new Chart($("#chartMovimientos"), {
                type: 'line',
                data: {
                    labels: mesesBase,
                    datasets: [{
                        label: 'Movimientos',
                        data: totales,
                        fill: true,
                        tension: 0.4,
                        backgroundColor: 'rgba(0,123,255,0.15)',
                        borderColor: '#007bff',
                        pointBackgroundColor: '#007bff',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
    });
}

    // ===============================
    // 4. Tabla: Últimos Movimientos
    // ===============================
    function cargarUltimosMovimientos() {
        $.ajax({
            url: 'controllers/Dashboard.controller.php',
            type: 'GET',
            data: { op: 'ultimos_movimientos' },
            dataType: 'json',
            success: function (data) {

                let html = `
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Tipo</th>
                            <th>Responsable</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                `;

                data.forEach((m, i) => {
                    html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${m.codigo_mov}</td>
                            <td>${m.tipo_movimiento}</td>
                            <td>${m.responsable}</td>
                            <td>${m.fecha}</td>
                        </tr>
                    `;
                });

                html += '</tbody>';

                $("#tablaDashboard").html(html);

                $("#tablaDashboard").DataTable({
                    destroy: true,
                    ordering: false,
                    pageLength: 5,
                    language: {
                        sLengthMenu: "Mostrar _MENU_",
                        sZeroRecords: "No hay datos",
                        sInfo: "Mostrando _START_ a _END_",
                        sSearch: "Buscar:",
                        oPaginate: {
                            sNext: "Siguiente",
                            sPrevious: "Anterior"
                        }
                    }
                });
            }
        });
    }

    // ===============================
    // 5. CARGA INICIAL
    // ===============================
    cargarKPIs();
    cargarEstadosActivos();
    cargarMovimientosMes();
    cargarUltimosMovimientos();

});
