$(document).ready(function(){    
    var txt_idactivo_mov_dev = document.querySelector("#mov_idactivo_dev");
    var cod_patrimonial_dev = document.querySelector("#cod_patrimonial_dev");

    var activosDevolucion = [];
    
    function listarMovimientos(){
        $.ajax({
            url: 'controllers/Movimiento.controller.php',
            type: 'GET',
            data: 'op=listarMovimiento',
            success: function(e){
                var tabla = $("#tablaMovimiento").DataTable();
                tabla.destroy();
                $("#datosMovimiento").html(e);
                $("#tablaMovimiento").DataTable({
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
                    buttons: [
                        { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } },
                        { extend: 'pdf', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } },
                        { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } },
                        { extend: 'print', text: "Imprimir", title: "", footer: true,
                          exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } }
                    ]
                });
            }
        });
    }


    function renderActivosDevolucion(activos) {

        let cont = $("#contenedorSeleccionadosDev");
        cont.empty();

        activos.forEach(a => {
            cont.append(`
                <div class="card activos-card mb-2 shadow-sm">
                    <div class="card-body p-2">
                        <div class="row align-items-center">

                            <!-- IMAGEN -->
                            <div class="col-auto">
                                <div class="activos-img-box">
                                    <img src="img/${a.foto ?? 'no-image.png'}"
                                        onerror="this.src='img/no-image.png'">
                                </div>
                            </div>

                            <!-- TEXTO -->
                            <div class="col">
                                <div class="text-primary font-weight-bold">
                                    ${a.cod_patrimonial}
                                </div>
                                <div class="text-muted" style="font-size:13px;">
                                    ${a.observacion ?? 'Sin descripción'}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            `);
        });

        if (activos.length === 0) {
            cont.html("<span class='text-muted'>No hay activos asociados</span>");
        }
    }






    $("#tablaMovimiento").on("click", ".devolucion, .eliminar, .ver", function (e) {
        e.preventDefault();   // evita comportamiento por defecto
        e.stopPropagation();  // evita que afecte checkbox / fila
    });


    $("#tablaMovimiento").on("click", ".devolucion", function(){

        let idmovimiento = $(this).data("idmovimiento");

        $("#mov_idactivos_dev").val(idmovimiento); // id del movimiento
        $("#mov_fecha_dev_ac").val(new Date().toISOString().slice(0, 10)); // Fecha hoy
        $("#modalMovimientoDevActivos").modal("show");

        var datos = {
            'op' : 'traerActivosDevolucion',
            'idmovimiento' : idmovimiento
        }

        $.ajax({
            url: 'controllers/Movimiento.controller.php',
            type: 'GET',
            data: datos,
            success: function(resultado){                        
                if ($.trim(resultado) != ""){
                    
                    console.log("RESPUESTA:", resultado);
                    
                    let data = JSON.parse(resultado); // ← IMPORTANTE
                    $("#idresponsable_dev_ac").val(data[0].id_administrativo);
                    $("#pres_responsable_dev_ac").val(data[0].npersona);
                    
                    activosDevolucion = data; // GUARDAMOS LOS ACTIVOS
                    // LISTA DE ACTIVOS
                    renderActivosDevolucion(data);


                } else {
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });


    $("#tablaMovimiento").on("click", ".eliminar", function(){

        let idactivo = $(this).data("idactivo");

        Swal.fire({
        title: "Estás segur@ de eliminar este activo?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "Eliminado!",
                icon: "success"
                });
            }
        });
    });

        function aplicarFiltros() {

        let reporte_fecha_inicio = $("#reporte_fecha_inicio").val() || null;
        let reporte_fecha_fin = $("#reporte_fecha_fin").val() || null;

        let filtro_mov_responsable  = $("#filtro_mov_responsable").val() || null;
        let filtro_calidad     = $("#filtro_calidad").val() || null;

        $.ajax({
            url: 'controllers/Movimiento.controller.php',
            type: 'GET',
            data: {
                'op': 'filtrarMovimiento',
                'reporte_fecha_inicio': reporte_fecha_inicio,
                'reporte_fecha_fin': reporte_fecha_fin,
                'filtro_mov_responsable': filtro_mov_responsable,
                'filtro_calidad': filtro_calidad
            },
            success: function(result){
                var tabla = $("#tablaMovimiento").DataTable();
                tabla.destroy();
                $("#datosMovimiento").html(result);
                $("#tablaMovimiento").DataTable({
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
                    buttons: [
                        { extend: 'copy', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } },
                        { extend: 'pdf', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } },
                        { extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } },
                        { extend: 'print', text: "Imprimir", title: "", footer: true,
                          exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6, 7, 9] } }
                    ]
                });
            }
        });
    }

    // Ejecutar cuando cambia cualquiera
    $("#reporte_fecha_inicio, #reporte_fecha_fin, #filtro_mov_responsable, #filtro_calidad")
    .change(function () {
        // console.log("Cambio detectado en:", this.id, "=>", $(this).val());
        aplicarFiltros();
    });

    function registrarMovimientoDevolucion() {

        let id_movimiento_prestamo = $("#mov_idactivos_dev").val();
        let mov_idtipo             = $("#mov_idtipo_dev").val(); // DEVOLUCION
        let dev_responsable        = $("#idresponsable_dev_ac").val();
        let dev_motivo             = $("#devolucion_motivo_ac").val();
        let archivo            = $("#doc_dev_ac")[0].files[0];

        if (!id_movimiento_prestamo || !mov_idtipo || !dev_responsable || !dev_motivo) {
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
            return;
        }

        if (activosDevolucion.length === 0) {
            mostrarAlerta("warning", "No hay activos para devolver");
            return;
        }

        // OBTENER IDS DE ACTIVOS
        let ids_activos = activosDevolucion.map(a => a.id_activo).join(',');

        // VALIDACIÓN ARCHIVO
        if (!archivo) {
            mostrarAlerta("warning", "Debe adjuntar el documento PDF de sustento");
            return;
        }
        
        // VALIDACIÓN TIPO
        if (archivo.type !== "application/pdf") {
            mostrarAlerta("warning", "El documento debe ser un archivo PDF");
            return;
        }
            
        // VALIDACIÓN PESO (5 MB)
        const maxSizeMB = 5;
        const maxSize   = maxSizeMB * 1024 * 1024;

        if (archivo.size > maxSize) {
            mostrarAlerta("warning", `El archivo no debe superar ${maxSizeMB} MB`);
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: '¿Confirma registrar la devolución de los activos?',
            text: `Se devolverán ${activosDevolucion.length} activos`,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (!result.isConfirmed) return;

                // FORM DATA (CLAVE)
                let formData = new FormData();
                formData.append("op", "registrarMovDevolucionActivos");
                formData.append("id_movimiento_prestamo", id_movimiento_prestamo);
                formData.append("ids_activos", ids_activos);
                formData.append("dev_responsable", dev_responsable);
                formData.append("dev_motivo", dev_motivo);
                formData.append("doc_dev_ac", archivo);

            $.ajax({
                url: 'controllers/Movimiento.controller.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log("RESPUESTA:", response);

                    let data = JSON.parse(response);

                            Swal.fire({
                                icon: "success",
                                title: "Devolución registrado",
                                html: `
                                    <b>Código de movimiento:</b><br>
                                    <span style="font-size:18px">${data.codigo_mov}</span>
                                `,
                                confirmButtonText: "Ver ticket"
                            }).then(() => {
                                window.open(
                                    "controllers/Reporte.controller.php?op=ticket_movimiento&id=" + data.id_movimiento,
                                    "_blank"
                                );
                            });

                    $("#formMovimientoDev")[0].reset();
                    $("#modalMovimientoDevActivos").modal("hide");

                    activosDevolucion = [];
                    listarMovimientos();
                },
                error: function () {
                    mostrarAlerta("error", "No se pudo registrar la devolución");
                }
            });

        });
    }


    function cargarAdministrativosFiltro(select){ 
        var datos ={
            'op': 'cargarAdministrativosFiltro'
        };
        $.ajax({
            url : 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }



    listarMovimientos();

    $("#registrar_mov_dev_ac").click(registrarMovimientoDevolucion);

    cargarAdministrativosFiltro("#filtro_mov_responsable");


});