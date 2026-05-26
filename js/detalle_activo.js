$(document).ready(function(){    
    const urlParams = new URLSearchParams(window.location.search);
    const idactivo = urlParams.get('id');

    $.ajax({
        url: 'controllers/Activo.controller.php',
        type: 'GET',
        data: { op: 'cargarActivo', idactivo: idactivo },
        dataType: 'json',
        success: function(data){

            $("#foto_activo").attr("src", "img/" + data[0].foto);
            
            $("#nombre_activo").text(data[0].marca_modelo);
            $("#categoria_activo").text(data[0].nombre_categoria);
            
            $("#codigo_activo").text(data[0].cod_patrimonial);
            $("#numeroserie").text(data[0].serie);
            $("#estado_activo").text(data[0].estado);
            $("#sede_activo").text(data[0].nombre_sede);
            $("#dependencia_activo").text(data[0].nombre_dependencia);
            $("#responsable_activo").text(data[0].npersona);
            $("#fecha_adquisicion_activo").text(data[0].fecha_adquisicion || '—');
            $("#fecha_activo").text(data[0].fecha_registro);

            var ordenCompra = data[0].orden_compra;
            if (ordenCompra && ordenCompra !== '') {
                $("#orden_compra_activo").html('<a href="archivos/' + ordenCompra + '" target="_blank"><i class="fas fa-file-pdf"></i> ' + ordenCompra + '</a>');
            } else {
                $("#orden_compra_activo").text('—');
            }

            $("#descripcion_activo").text(data[0].observacion);

            cargarGarantiaActivo(idactivo);
        }
    });

    function cargarGarantiaActivo(idactivo) {
        $.ajax({
            url: 'controllers/Garantia.controller.php',
            type: 'GET',
            data: { op: 'listarPorActivo', idactivo: idactivo },
            dataType: 'json',
            success: function (data) {
                if (data && data.length > 0) {
                    var g = data[0];
                    var badge = 'secondary';
                    if (g.estado === 'VIGENTE') badge = 'success';
                    else if (g.estado === 'PROXIMO_A_VENCER') badge = 'warning';
                    else if (g.estado === 'VENCIDA') badge = 'danger';
                    $("#garantia_activo").html(
                        '<span class="badge badge-' + badge + '">' + g.estado + '</span>' +
                        ' <small class="text-muted">(hasta ' + g.fecha_fin + ')</small>'
                    );
                } else {
                    $("#garantia_activo").html('<span class="badge badge-secondary">Sin garantía</span>');
                }
            },
            error: function () {
                $("#garantia_activo").html('<span class="badge badge-secondary">Sin garantía</span>');
            }
        });
    }

    function calcularDiasRestantes(fechaDevolucion) {

        // Convierte la fecha de devolución a Date
        const limite = new Date(fechaDevolucion);

        // Fecha actual
        const hoy = new Date();

        // Diferencia en milisegundos
        const diffTime = limite.getTime() - hoy.getTime();

        // Diferencia en días (redondeando hacia arriba)
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        return diffDays;
    }


    function cargarMovimientosActivos(id_activo) {
        $.ajax({
            url: "controllers/Activo.controller.php",
            type: "GET",
            data: { 
            op: "consultarTimeline",
            idactivo: id_activo 
            },
            dataType: "JSON",
            success: function (data) {

            let html = "";

            if (data.length === 0) {
                html = `<p class="text-muted text-center">No hay movimientos registrados.</p>`;
            } else {

                // 1. Encontrar el último movimiento PRESTAMO
                let ultimoPrestamoIndex = data.map(m => m.tipo_mov).lastIndexOf("PRESTAMO");

                // 2. Buscar devolucion posterior al último préstamo
                let devolucionPosterior = false;

                if (ultimoPrestamoIndex !== -1) {
                    for (let i = ultimoPrestamoIndex + 1; i < data.length; i++) {
                        if (data[i].tipo_mov === "DEVOLUCION") {
                            devolucionPosterior = true;
                            break;
                        }
                    }
                }
                
                data.forEach((mov, index) => {

                    switch(mov.tipo_mov){

                        case "PRESTAMO":
                            let mov_tipo = "<span class='bg-suave-azul'>Préstamo</span>";
                            let diasRestantesHtml = "";

                            if (mov.mov_estado !== "CERRADO") {

                                let diasRestantes = calcularDiasRestantes(mov.fecha_devolucion);

                                let colorDias = "text-success";
                                if (diasRestantes <= 3 && diasRestantes > 0) colorDias = "text-warning";
                                if (diasRestantes <= 0) colorDias = "text-danger";

                                diasRestantesHtml = `
                                    <span class="ml-5 font-weight-bold ${colorDias}">
                                        Días restantes: ${diasRestantes >= 0 ? diasRestantes : 0}
                                    </span>
                                `;
                            }


                            html += `
                            <div class="time-label">
                                <span class="bg-danger">${mov.fecha}</span>
                            </div>
                            
                            <div>
                                <i class="fas fa-exchange-alt bg-primary"></i>
                                <div class="timeline-item sin-fondo">
                                    <span class="time" style="font-size:15px"><b><i class="fas fa-clock"></i> ${mov.hora}</b></span>
                                    <h3 class="timeline-header"><b>En calidad de: ${mov_tipo}</b>
                                    <span class="ml-5 font-weight-bold">
                                        Fecha Devolución: ${mov.fecha_devolucion}
                                    </span>
                                    ${diasRestantesHtml}<br><br>
                                    <span><b>Motivo: </b> ${mov.motivo}</span></h3>

                                    <div class="timeline-body">
                                    <div class="d-flex">
                                        <div class="callout callout-warning col-md-6 mr-3">
                                            <h4 class="text-warning"><b>Origen</b></h4>
                                            <span class="">Responsable: <b>${mov.responsable_origen ?? "—"}</b></span><br>
                                            Sede: <b>${mov.sede_origen ?? "—"}</b><br>
                                            Área: <b>${mov.dependencia_origen ?? "—"}</b>
                                        </div>
                                        <div class="callout callout-info col-md-6">
                                            <h4 class="text-info"><b>Destino</b></h4>
                                            Responsable Temporal: <b>${mov.responsable_destino ?? "—"}</b><br>
                                            Sede destino: <b>${mov.sede_destino ?? "—"}</b><br>
                                            Área destino: <b>${mov.dependencia_destino ?? "—"}</b>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            `;
                        break

                        case "TRANSFERENCIA":
                            let mov_tipo_trans = "<span class='bg-suave-ambar'>Transferencia</span>";
                            html += `
                                <div class="time-label">
                                <span class="bg-danger">${mov.fecha}</span>
                                </div>
            
                                <div>
                                <i class="fas fa-exchange-alt bg-primary"></i>
                                <div class="timeline-item sin-fondo">
                                    <span class="time" style="font-size:15px"><b><i class="fas fa-clock"></i> ${mov.hora}</b></span>
            
                                    <h3 class="timeline-header"><b>En calidad de: ${mov_tipo_trans}</b><br><br>
                                                                        <span><b>Motivo: </b> ${mov.motivo}</span></h3>
            
                                    <div class="timeline-body">
                                    <div class="d-flex">
                                        <div class="callout callout-warning col-md-6 mr-3">
                                            <h4 class="text-warning"><b>Origen</b></h4>
                                            <span class="">Responsable: <b>${mov.responsable_origen ?? "—"}</b></span><br>
                                            Sede: <b>${mov.sede_origen ?? "—"}</b><br>
                                            Área: <b>${mov.dependencia_origen ?? "—"}</b>
                                        </div>
                                        <div class="callout callout-info col-md-6">
                                            <h4 class="text-info"><b>Destino</b></h4>
                                            Responsable Actual: <b>${mov.responsable_destino ?? "—"}</b><br>
                                            Sede destino: <b>${mov.sede_destino ?? "—"}</b><br>
                                            Área destino: <b>${mov.dependencia_destino ?? "—"}</b>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            `;
                        break

                        default:
                            var mov_tipo_dev = "<span class='bg-suave-verde'>Devolución</span>";
                            html += `
                                <div class="time-label">
                                <span class="bg-danger">${mov.fecha}</span>
                                </div>
            
                                <div>
                                <i class="fas fa-exchange-alt bg-primary"></i>
                                <div class="timeline-item sin-fondo">
                                    <span class="time" style="font-size:15px"><b><i class="fas fa-clock"></i> ${mov.hora}</b></span>
            
                                    <h3 class="timeline-header"><b>En calidad de: ${mov_tipo_dev}</span></b><br><br>
                                                                        <span><b>Motivo: </b> ${mov.motivo}</span></h3>
            
                                    <div class="timeline-body">
                                    <div class="d-flex">
                                        <div class="callout callout-warning col-md-6 mr-3">
                                            <h4 class="text-warning"><b>Origen</b></h4>
                                            <span class="">Responsable: <b>${mov.responsable_origen ?? "—"}</b></span><br>
                                            Sede: <b>${mov.sede_origen ?? "—"}</b><br>
                                            Área: <b>${mov.dependencia_origen ?? "—"}</b>
                                        </div>
                                        <div class="callout callout-info col-md-6">
                                            <h4 class="text-info"><b>Destino</b></h4>
                                            Responsable Actual: <b>${mov.responsable_destino ?? "—"}</b><br>
                                            Sede destino: <b>${mov.sede_destino ?? "—"}</b><br>
                                            Área destino: <b>${mov.dependencia_destino ?? "—"}</b>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            `;
                        break    
                    }
                        
                });
            }

            $("#timeline_movimientos").html(html);
            }
        });
    }

    cargarMovimientosActivos(idactivo);
});