$(document).ready(function(){    
    const urlParams = new URLSearchParams(window.location.search);
    const id_movimiento = urlParams.get('id');

    $.ajax({
        url: 'controllers/Movimiento.controller.php',
        type: 'GET',
        data: { op: 'cargarActivos', id_movimiento: id_movimiento },
        dataType: 'json',
        success: function(response){
            $("#fecha").text(response.fecha_mov);
            $("#cod_movimiento").text(response.codigo_mov);
            $("#responsable_origen").text(response.npersona1);
            $("#responsable_destino").text(response.npersona2);
            $("#tipo_mov").text(response.tipo_mov);
            $("#mov_estado").text(response.mov_estado);

            $("#motivo").text(response.motivo);
            if (response.informe_doc) {
                $("#informe_doc")
                    .text(response.informe_doc)
                    .attr("href", "docs/movimientos/" + response.informe_doc)
                    .attr("target", "_blank");
            } else {
                $("#informe_doc")
                    .text("No adjunto")
                    .removeAttr("href");
            }
            $("#ubicacion_origen").text(response.ubicacion_origen);
            $("#ubicacion_destino").text(response.ubicacion_destino);

            if (response.tipo_mov === 'PRESTAMO') {
            $('#tipo_responsable')
                .text('Temporal')
                .removeClass()
                .addClass('badge badge-warning');
            $("#fecha_devolucion").text(response.fecha_devolucion);
            $("#tiempo_mov").text(response.tiempo_mov + " días");
            }

            if (response.tipo_mov === 'TRANSFERENCIA') {
            $('#tipo_responsable')
                .text('Definitivo')
                .removeClass()
                .addClass('badge badge-success');
            $("#fecha_devolucion").text("NO APLICA");
            $("#tiempo_mov").text(response.tiempo_mov);
            }

            if (response.tipo_mov === 'DEVOLUCION') {
            $('#tipo_responsable')
                .text('Restituido')
                .removeClass()
                .addClass('badge badge-info');
            $("#fecha_devolucion").text("NO APLICA");
            $("#tiempo_mov").text(response.tiempo_mov + " días");
            }

            let html = "";
            response.activos.forEach((item, index) => {
                html += `
                <tr>
                    <td class="text-center">${index+1}</td>
                    <td class="text-center">${item.cod_patrimonial}</td>
                    <td class="text-center">${item.marca_modelo}</td>
                    <td class="text-center">${item.observacion}</td>
                    <td class="text-center">
                        <img src="img/${item.foto}" width="50" class="img-activo" style="cursor:pointer">
                    </td>
                    <td class="text-center">${item.badge}</td>
                    <td class="text-center">
                        <button class="btn btn-outline-info btn-sm ir" data-id="${item.id_activo}">Ver detalle</button>
                    </td>
                </tr>
                `;
            });

            $("#datosActivosMovimiento").html(html);

                $("#tablaActivosMovimiento").DataTable({
                    language: {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "No hay datos disponibles en la tabla",
                        "sInfo":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando 0 a 0 de 0 registros",
                        "sInfoFiltered":   "(filtrado de _MAX_ registros totales)",
                        "sSearch":         "Buscar:",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        }
                    },
                    columnDefs: [
                    {
                        visible: true,
                        searchable: true
                    }
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy', 'pdf', 'excel', 
                        {
                            extend: 'print',
                            text: "Imprimir",
                            title: "",
                            footer: true,
                            exportOptions: {
                                columns: [ 0, 1, 2, 3,4,5 ],
                                stripHtml: false, /* Aquí indicamos que no se eliminen las imágenes */
                            }
                        }
                    ]
                });
        }
    });

    $(document).on('click', '.ir', function () {
        const idActivo = $(this).data('id');

        // URL actual completa (para poder regresar)
        const returnUrl = encodeURIComponent(window.location.href);

        window.location.href =
            `main.php?view=activo/view_detalle.php&id=${idActivo}&return=${returnUrl}`;
    });

    // function calcularDiasRestantes(fechaDevolucion) {

    //     // Convierte la fecha de devolución a Date
    //     const limite = new Date(fechaDevolucion);

    //     // Fecha actual
    //     const hoy = new Date();

    //     // Diferencia en milisegundos
    //     const diffTime = limite.getTime() - hoy.getTime();

    //     // Diferencia en días (redondeando hacia arriba)
    //     const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    //     return diffDays;
    // }


    // function listarActivosMovimiento(id_activo) {
    //     $.ajax({
    //         url: "controllers/Activo.controller.php",
    //         type: "GET",
    //         data: { 
    //         op: "consultarTimeline",
    //         idactivo: id_activo 
    //         },
    //         dataType: "JSON",
    //         success: function (data) {

    //         let html = "";

    //         if (data.length === 0) {
    //             html = `<p class="text-muted text-center">No hay movimientos registrados.</p>`;
    //         } else {

    //             // 1. Encontrar el último movimiento PRESTAMO
    //             let ultimoPrestamoIndex = data.map(m => m.tipo_mov).lastIndexOf("PRESTAMO");

    //             // 2. Buscar devolucion posterior al último préstamo
    //             let devolucionPosterior = false;

    //             if (ultimoPrestamoIndex !== -1) {
    //                 for (let i = ultimoPrestamoIndex + 1; i < data.length; i++) {
    //                     if (data[i].tipo_mov === "DEVOLUCION") {
    //                         devolucionPosterior = true;
    //                         break;
    //                     }
    //                 }
    //             }
                
    //             data.forEach((mov, index) => {

    //                 switch(mov.tipo_mov){

    //                     case "PRESTAMO":
    //                         let mov_tipo = "<span class='bg-suave-azul'>Préstamo</span>";
    //                         let diasRestantesHtml = "";

    //                         if (mov.mov_estado !== "CERRADO") {

    //                             let diasRestantes = calcularDiasRestantes(mov.fecha_devolucion);

    //                             let colorDias = "text-success";
    //                             if (diasRestantes <= 3 && diasRestantes > 0) colorDias = "text-warning";
    //                             if (diasRestantes <= 0) colorDias = "text-danger";

    //                             diasRestantesHtml = `
    //                                 <span class="ml-5 font-weight-bold ${colorDias}">
    //                                     Días restantes: ${diasRestantes >= 0 ? diasRestantes : 0}
    //                                 </span>
    //                             `;
    //                         }


    //                         html += `
    //                         <div class="time-label">
    //                             <span class="bg-danger">${mov.fecha}</span>
    //                         </div>
                            
    //                         <div>
    //                             <i class="fas fa-exchange-alt bg-primary"></i>
    //                             <div class="timeline-item sin-fondo">
    //                                 <span class="time" style="font-size:15px"><b><i class="fas fa-clock"></i> ${mov.hora}</b></span>
    //                                 <h3 class="timeline-header"><b>En calidad de: ${mov_tipo}</b>
    //                                 <span class="ml-5 font-weight-bold">
    //                                     Fecha Devolución: ${mov.fecha_devolucion}
    //                                 </span>
    //                                 ${diasRestantesHtml}<br><br>
    //                                 <span><b>Motivo: </b> ${mov.motivo}</span></h3>

    //                                 <div class="timeline-body">
    //                                 <div class="d-flex">
    //                                     <div class="callout callout-warning col-md-6 mr-3">
    //                                         <h4 class="text-warning"><b>Origen</b></h4>
    //                                         <span class="">Responsable: <b>${mov.responsable_origen ?? "—"}</b></span><br>
    //                                         Sede: <b>${mov.sede_origen ?? "—"}</b><br>
    //                                         Área: <b>${mov.dependencia_origen ?? "—"}</b>
    //                                     </div>
    //                                     <div class="callout callout-info col-md-6">
    //                                         <h4 class="text-info"><b>Destino</b></h4>
    //                                         Responsable Temporal: <b>${mov.responsable_destino ?? "—"}</b><br>
    //                                         Sede destino: <b>${mov.sede_destino ?? "—"}</b><br>
    //                                         Área destino: <b>${mov.dependencia_destino ?? "—"}</b>
    //                                     </div>
    //                                 </div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                         `;
    //                     break

    //                     case "TRANSFERENCIA":
    //                         let mov_tipo_trans = "<span class='bg-suave-ambar'>Transferencia</span>";
    //                         html += `
    //                             <div class="time-label">
    //                             <span class="bg-danger">${mov.fecha}</span>
    //                             </div>
            
    //                             <div>
    //                             <i class="fas fa-exchange-alt bg-primary"></i>
    //                             <div class="timeline-item sin-fondo">
    //                                 <span class="time" style="font-size:15px"><b><i class="fas fa-clock"></i> ${mov.hora}</b></span>
            
    //                                 <h3 class="timeline-header"><b>En calidad de: ${mov_tipo_trans}</b><br><br>
    //                                                                     <span><b>Motivo: </b> ${mov.motivo}</span></h3>
            
    //                                 <div class="timeline-body">
    //                                 <div class="d-flex">
    //                                     <div class="callout callout-warning col-md-6 mr-3">
    //                                         <h4 class="text-warning"><b>Origen</b></h4>
    //                                         <span class="">Responsable: <b>${mov.responsable_origen ?? "—"}</b></span><br>
    //                                         Sede: <b>${mov.sede_origen ?? "—"}</b><br>
    //                                         Área: <b>${mov.dependencia_origen ?? "—"}</b>
    //                                     </div>
    //                                     <div class="callout callout-info col-md-6">
    //                                         <h4 class="text-info"><b>Destino</b></h4>
    //                                         Responsable Actual: <b>${mov.responsable_destino ?? "—"}</b><br>
    //                                         Sede destino: <b>${mov.sede_destino ?? "—"}</b><br>
    //                                         Área destino: <b>${mov.dependencia_destino ?? "—"}</b>
    //                                     </div>
    //                                 </div>
    //                                 </div>
    //                             </div>
    //                             </div>
    //                         `;
    //                     break

    //                     default:
    //                         var mov_tipo_dev = "<span class='bg-suave-verde'>Devolución</span>";
    //                         html += `
    //                             <div class="time-label">
    //                             <span class="bg-danger">${mov.fecha}</span>
    //                             </div>
            
    //                             <div>
    //                             <i class="fas fa-exchange-alt bg-primary"></i>
    //                             <div class="timeline-item sin-fondo">
    //                                 <span class="time" style="font-size:15px"><b><i class="fas fa-clock"></i> ${mov.hora}</b></span>
            
    //                                 <h3 class="timeline-header"><b>En calidad de: ${mov_tipo_dev}</span></b><br><br>
    //                                                                     <span><b>Motivo: </b> ${mov.motivo}</span></h3>
            
    //                                 <div class="timeline-body">
    //                                 <div class="d-flex">
    //                                     <div class="callout callout-warning col-md-6 mr-3">
    //                                         <h4 class="text-warning"><b>Origen</b></h4>
    //                                         <span class="">Responsable: <b>${mov.responsable_origen ?? "—"}</b></span><br>
    //                                         Sede: <b>${mov.sede_origen ?? "—"}</b><br>
    //                                         Área: <b>${mov.dependencia_origen ?? "—"}</b>
    //                                     </div>
    //                                     <div class="callout callout-info col-md-6">
    //                                         <h4 class="text-info"><b>Destino</b></h4>
    //                                         Responsable Actual: <b>${mov.responsable_destino ?? "—"}</b><br>
    //                                         Sede destino: <b>${mov.sede_destino ?? "—"}</b><br>
    //                                         Área destino: <b>${mov.dependencia_destino ?? "—"}</b>
    //                                     </div>
    //                                 </div>
    //                                 </div>
    //                             </div>
    //                             </div>
    //                         `;
    //                     break    
    //                 }
                        
    //             });
    //         }

    //         $("#timeline_movimientos").html(html);
    //         }
    //     });
    // }

});