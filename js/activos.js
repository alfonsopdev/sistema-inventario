$(document).ready(function(){

    idactivo = "";
    var div_sede_dependencia = document.querySelector("#div_sede_dependencia");
    var div_sede_dependencia_editar = document.querySelector("#div_sede_dependencia_editar");
    var div_transferencia = document.querySelector("#div_transferencia");
    var div_prestamo = document.querySelector("#div_prestamo");
    var div_transferencia_ac = document.querySelector("#div_transferencia_ac");
    var div_prestamo_ac = document.querySelector("#div_prestamo_ac");
    var txt_idactivo = document.querySelector("#txt_idactivo");
    var txt_idactivo_mov = document.querySelector("#mov_idactivo");
    var txt_idactivo_mov_dev = document.querySelector("#mov_idactivo_dev");

    var cod_patrimonial_dev = document.querySelector("#cod_patrimonial_dev");

    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");

    var activosSeleccionados = {};  

    const btn_mov_registrar = document.getElementById("btn_mov_registrar");


    function registrarActivo() {
    var idcategoria = $("#idcategoria").val();
    var txt_marca = $("#txt_marca").val();
    var txt_modelo = $("#txt_modelo").val();
    var txt_serie = $("#txt_serie").val();
    var txt_patrimonial = $("#txt_patrimonial").val();
    var select_responsable = $("#select_responsable").val();
    var select_sede = $("#select_sede").val();
    var select_dependencia = $("#select_dependencia").val();
    var foto = $("#foto")[0].files[0];
    var select_estado = $("#select_estado").val();
    var date_fecha_adquisicion = $("#fecCompra").val();
    var orden_compra = $("#ordenCompra")[0].files[0];
    var observacion = $("#txt_observacion").val();

    if (
        idcategoria == "" ||
        txt_marca == "" ||
        txt_modelo == "" ||
        txt_serie == "" ||
        txt_patrimonial == "" ||
        !select_responsable ||
        !select_sede ||
        !select_dependencia ||
        !foto ||
        !select_estado ||
        observacion == ""
    ) {
        mostrarAlerta("warning", "¡Completar los campos necesarios!");
    } else {
        Swal.fire({
            icon: "question",
            title: "¿Está seguro de registrar?",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Aceptar"
        }).then((result) => {
            if (result.isConfirmed) {
                // ✅ Crear FormData para enviar archivo
                var formData = new FormData();
                formData.append("op", "registrarActivo");
                formData.append("idcategoria", idcategoria);
                formData.append("txt_marca", txt_marca);
                formData.append("txt_modelo", txt_modelo);
                formData.append("txt_serie", txt_serie);
                formData.append("txt_patrimonial", txt_patrimonial);
                formData.append("select_responsable", select_responsable);
                formData.append("select_sede", select_sede);
                formData.append("select_dependencia", select_dependencia);
                formData.append("foto", foto);
                formData.append("select_estado", select_estado);
                formData.append("date_fecha_adquisicion", date_fecha_adquisicion);
                formData.append("ordenCompra", orden_compra);
                formData.append("observacion", observacion);
                 $.ajax({
                    url: "controllers/Activo.controller.php",
                    type: "POST",              // DEBE SER POST
                    data: formData,            // Usar FormData
                    contentType: false,        // Obligatorio
                    processData: false,        // Obligatorio
                    success: function (e) {
                        Swal.fire("¡Activo registrado con éxito!", "", "success");
                        $("#formularioActivo")[0].reset();
                        $("#select_responsable").val(null).trigger('change');
                        $("#select_sede").val(null).trigger('change');
                        $("#select_dependencia").val(null).trigger('change');
                        $("#idcategoria").val(null).trigger('change');
                        $("#foto").next(".custom-file-label").text("Eliga imagen");
                        $("#foto").val("");
                        div_sede_dependencia.classList.add('asignar');
                        $("#modal_registrar").modal("hide");
                        listarActivos();
                    },
                    error: function (xhr, status, error) {
                        console.error("Error AJAX:", error);
                    }
                });
            }
        });
    }
}


    function activoYaExiste(){
        // let nombreproductoYaExiste = $("#nombreproducto").val();
        // var nombreproductoYaExiste = $("#nombreproducto").val();

        let txt_patrimonial = $("#txt_patrimonial").val(); 

        if(txt_patrimonial == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            var datos = {
                'op' : 'activoYaRegistrado',
                'txt_patrimonial' : txt_patrimonial
            };
            $.ajax({
                type: "GET",
                url:  "controllers/Activo.controller.php",
                data: datos,
                success: function(resp){
                    if(resp == 1){
                        mostrarAlerta("error", '¡Ya existe este activo con código patrimonial: '+txt_patrimonial+'!');
                    }
                    else if(resp == 2){
                        registrarActivo();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    function cargarCategorias(select){ 
        var datos ={
            'op': 'cargarCategoria'
        };
        $.ajax({
            url : 'controllers/Categoria.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }

    function cargarCategoriasFiltro(select){ 
        var datos ={
            'op': 'cargarCategoriasFiltro'
        };
        $.ajax({
            url : 'controllers/Categoria.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }

    function cargarAdministrativos(select){ 
        var datos ={
            'op': 'cargarAdministrativo'
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

    function cargarSedeFiltro(select){ 
        var datos ={
            'op': 'cargarSedeFiltro'
        };
        $.ajax({
            url : 'controllers/Sede.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }

    function cargarDependenciaFiltro(select){ 
        var datos ={
            'op': 'cargarDependenciaFiltro'
        };
        $.ajax({
            url : 'controllers/Dependencia.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }

    function cargarOtrosAdministrativos(select){ 
        let idadministrativo = $("#idresponsable").val();
        var datos ={
            'op': 'cargarOtrosAdministrativos',
            'idadministrativo' : idadministrativo
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

    function cargarOtrosAdministrativosActivos(select){ 
        let idadministrativo = $("#idresponsable_activos").val();
        var datos ={
            'op': 'cargarOtrosAdministrativos',
            'idadministrativo' : idadministrativo
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

    function traerAdministrativoPorId(id_administrativo, callback) {
        $.ajax({
            url: 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: {
                op: 'getAdministrativoById',
                idadministrativo: id_administrativo
            },
            dataType: 'json',
            success: function (resp) {
                if (!resp || resp.length === 0) {
                    callback(null);
                    return;
                }
                callback(resp[0]);
            }
        });
    }


    function cargarDependencias(select){
        return $.ajax({
            url : 'controllers/Dependencia.controller.php',
            type: 'GET',
            data: { op: 'cargarDependencias' },
            success:function(e){
                $(select).html(e);
            }
        });
    }
    
    // Cargar los nombres de las dependencias
    function cargarSede(select){
        return $.ajax({
            url : 'controllers/Sede.controller.php',
            type: 'GET',
            data: { op: 'cargarSede' },
            success:function(e){
                $(select).html(e);
            }
        });
    }

    // Cargar id_dependencia_sede
    function cargarSedePorAdministrativo(idAdministrativo){ 
        $.ajax({
            url : 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: {
                'op': 'listarSedePorAdministrativo',
                'idAdministrativo': idAdministrativo
            },
            success:function(e){
                $('#select_sede').html(e);
                $('#select_sede_editar').html(e);
                $('#sede_destino').html(e);
                $('#sede_destino_ac').html(e);
                // if(idDependenciaSeleccionada){
                //     $('#select_dependencia').val(idDependenciaSeleccionada);
                // }
            }
        });
    }

    // Cargar id_dependencia_sede
    function cargarDependenciaPorAdministrativo(idAdministrativo){ 
        $.ajax({
            url : 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: {
                'op': 'listarDependenciaPorAdministrativo',
                'idAdministrativo': idAdministrativo
            },
            success:function(e){
                $('#select_dependencia').html(e);
                $('#select_dependencia_editar').html(e);
                $('#dependencia_destino').html(e);
                $('#dependencia_destino_ac').html(e);
                // if(idDependenciaSeleccionada){
                //     $('#select_dependencia').val(idDependenciaSeleccionada);
                // }
            }
        });
    }

    function listarActivos(){
        $.ajax({
            url: 'controllers/Activo.controller.php',
            type: 'GET',
            data: 'op=listarActivo',
            success: function(e){
                var tabla = $("#tablaActivo").DataTable();
                tabla.destroy();
                $("#datosActivo").html(e);
                $("#tablaActivo").DataTable({
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
                        {
                            extend: 'copy',
                            exportOptions: { columns: [0, 3, 4, 5, 6, 7, 8, 9, 10] }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: { columns: [0, 3, 4, 5, 6, 7, 8, 9, 10] }
                        },
                        {
                            extend: 'excel',
                            exportOptions: { columns: [0, 3, 4, 5, 6, 7, 8, 9, 10] }
                        },
                        {
                            extend: 'print',
                            text: "Imprimir",
                            title: "",
                            footer: true,
                            exportOptions: {
                                columns: [0, 3, 4, 5, 6, 7, 8, 9, 10]
                            }
                        }
                    ]
                });
            }
        });
    }


    $("#tablaProducto").on("click", ".eliminar", function(){
        let idproducto = $(this).attr('data-idproducto');
        Swal.fire({
            icon: 'question',
            title: 'Esta seguro de eliminar?',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Confirmar',
        }).then((result)=>{
            if(result.isConfirmed){
                var datos = {
                    'op' : 'eliminarProducto',
                    'idproducto' : idproducto
                };
                $.ajax({
                    url: 'controllers/Producto.controller.php',
                    type: 'GET',
                    data: datos,
                    success: function(e){
                        mostrarAlerta("success", "¡Eliminado correctamente!");
                        listarProductosFarmaciaPrueba();
                    }
                });
            }
        });
    });

    $("#tablaActivo").on('click', ".modificar", function(){
        let idactivo = $(this).attr('data-idactivo');

        var datos = {
            'op' : 'getActivo',
            'idactivo' : idactivo
        };
        console.log(datos);
        $.ajax({
            url: 'controllers/Activo.controller.php',
            type: 'GET',
            data: datos,
            success: function(result){                        
                if ($.trim(result) != ""){

                    // $("#idcategoria").prop('disabled', true);
                    // $("#stock").prop('disabled', true);
                    // $("#fotografia").prop('disabled', true);

                    // $("#descripcion").prop('disabled', true);
                    
                    div_sede_dependencia_editar.classList.remove('asignar');
                    

                    var resultado = JSON.parse(result);
                    // console.log(resultado);
                    
                     Promise.all([
                        cargarSede("#select_sede_editar"),
                        cargarDependencias("#select_dependencia_editar"),
                    ]).then(() => {

                    $("#idcategoria_editar").val(resultado[0].id_categoria).trigger('change');
                    $("#txt_marca_editar").val(resultado[0].marca);
                    
                    $("#txt_modelo_editar").val(resultado[0].modelo);
                    $("#txt_serie_editar").val(resultado[0].serie);
                    $("#txt_patrimonial_editar").val(resultado[0].cod_patrimonial);
                    $("#select_responsable_editar").val(resultado[0].id_administrativo).trigger('change');
                    $("#select_sede_editar").val(resultado[0].id_sede);
                    $("#select_dependencia_editar").val(resultado[0].id_dependencia);
                    $("#select_estado_editar").val(resultado[0].estado);
                    $("#fecCompra_editar").val(resultado[0].fecha_adquisicion || '');
                    if (resultado[0].orden_compra && resultado[0].orden_compra !== "") {
                        $("#ordenCompra_editar").next(".custom-file-label").text(resultado[0].orden_compra);
                    } else {
                        $("#ordenCompra_editar").next(".custom-file-label").text("Elegir archivo");
                    }
                    $("#txt_observacion_editar").val(resultado[0].observacion);

                    // limpiar input file
                    $("#foto_editar").val("");

                    // actualizar label del input file
                    if (resultado[0].foto && resultado[0].foto !== "") {
                        $("#foto_editar").next(".custom-file-label").text(resultado[0].foto);
                        $("#foto_actual").val(resultado[0].foto);
                    } else {
                        $("#foto_editar").next(".custom-file-label").text("Seleccione una imagen");
                    }

                    // tinymce.get("nombreproducto").setContent(resultado[0].nombreproducto);
                    txt_idactivo.setAttribute("data-idactivo", resultado[0].id_activo);

                    // $("#fechavencimiento").val(resultado[0].fechavencimiento);
                    $("#modal_editar").modal("show");
                    });
                }else{
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });


    $("#tablaActivo").on("click", ".mover", function(){
        let idactivo = $(this).data("idactivo");

        $("#mov_idactivo").val(idactivo);
        $("#mov_fecha").val(new Date().toISOString().slice(0, 10)); // Fecha hoy

        // Cargar lista de responsables (si deseas desde AJAX)
        
        
        $("#modalMovimiento").modal("show");

        var datos = {
            'op' : 'getAdministrativoActivo',
            'idactivo' : idactivo
        };

        // console.log(datos);
        $.ajax({
            url: 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: datos,
            success: function(resultado){                        
                if ($.trim(resultado) != ""){
                    
                    // console.log("RESPUESTA:", resultado);
                    
                    let data = JSON.parse(resultado); // ← IMPORTANTE
                    $("#idresponsable").val(data[0].id_administrativo);
                    $("#pres_responsable").val(data[0].npersona);
                    $("#transf_responsable_actual").val(data[0].npersona);

                    txt_idactivo_mov.setAttribute("data-idactivo", data[0].id_activo);

                    cargarOtrosAdministrativos("#resp_temporal");
                    cargarOtrosAdministrativos("#mov_responsable");
                } else {
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });


    });

    $("#tablaActivo").on("click", ".devolucion", function(){

        let idactivo = $(this).data("idactivo");

        $("#mov_idactivo_dev").val(idactivo);
        $("#mov_fecha_dev").val(new Date().toISOString().slice(0, 10)); // Fecha hoy

        $("#modalMovimientoDev").modal("show");

        var datos = {
            'op' : 'traerActivoDevolucion',
            'idactivo' : idactivo
        }

        $.ajax({
            url: 'controllers/Activo.controller.php',
            type: 'GET',
            data: datos,
            success: function(resultado){                        
                if ($.trim(resultado) != ""){
                    
                    console.log("RESPUESTA:", resultado);
                    
                    let data = JSON.parse(resultado); // ← IMPORTANTE
                    $("#idresponsable_dev").val(data[0].id_administrativo);
                    $("#pres_responsable_dev").val(data[0].npersona);

                    txt_idactivo_mov_dev.setAttribute("data-idactivo", data[0].id_activo);
                    cod_patrimonial_dev.innerHTML = data[0].cod_patrimonial;

                } else {
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });


    $("#tablaActivo").on("click", ".eliminar", function(){

        let idactivo = $(this).data("idactivo");

        Swal.fire({
        title: "¿Estás seguro de eliminar este activo?",
        text: "Se marcará como inactivo, no se eliminará físicamente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "controllers/Activo.controller.php",
                    type: "GET",
                    data: { op: "eliminarActivo", idactivo: idactivo },
                    dataType: "json",
                    success: function (r) {
                        if (r.status === "success") {
                            mostrarAlerta("success", "Activo eliminado correctamente.");
                            listarActivos();
                        } else {
                            mostrarAlerta("error", r.message || "Error al eliminar");
                        }
                    },
                    error: function () {
                        mostrarAlerta("error", "Error de conexión al eliminar");
                    }
                });
            }
        });
    });

    $("#tablaActivo").on("click", ".modificar, .mover, .devolucion, .eliminar, .ver", function (e) {
        e.preventDefault();   // evita comportamiento por defecto
        e.stopPropagation();  // evita que afecte checkbox / fila
    });

    $("#formMovimientoActivos").on("submit", function (e) {
        e.preventDefault(); // evita recarga
        validarTipoMovActivos();
    });

    function volverActivos(){
        window.location.href = "main.php?view=activo";
    }


    function aplicarFiltros() {

        let filtro_categoria = $("#filtro_categoria").val() || null;
        let filtro_responsable = $("#filtro_responsable").val() || null;

        let filtro_calidad  = $("#filtro_calidad").val() || null;
        let filtro_sede     = $("#filtro_sede").val() || null;

        let filtro_dependencia   = $("#filtro_dependencia").val() || null;
        let filtro_estado  = $("#filtro_estado").val() || null;
        let filtro_garantia  = $("#filtro_garantia").val() || null;

        $.ajax({
            url: 'controllers/Activo.controller.php',
            type: 'GET',
            data: {
                'op': 'filtrarActivo',
                'filtro_categoria': filtro_categoria,
                'filtro_responsable': filtro_responsable,
                'filtro_calidad': filtro_calidad,
                'filtro_sede': filtro_sede,
                'filtro_dependencia': filtro_dependencia,
                'filtro_estado': filtro_estado,
                'filtro_garantia': filtro_garantia
            },
            success: function(result){
                var tabla = $("#tablaActivo").DataTable();
                tabla.destroy();
                $("#datosActivo").html(result);
                $("#tablaActivo").DataTable({
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
                        {
                            extend: 'copy',
                            exportOptions: { columns: [0, 3, 4, 5, 6, 7, 8, 9, 10] }
                        },
                        {
                            extend: 'pdf',
                            exportOptions: { columns: [0, 3, 4, 5, 6, 7, 8, 9, 10] }
                        },
                        {
                            extend: 'excel',
                            exportOptions: { columns: [0, 3, 4, 5, 6, 7, 8, 9, 10] }
                        },
                        {
                            extend: 'print',
                            text: "Imprimir",
                            title: "",
                            footer: true,
                            exportOptions: {
                                columns: [0, 3, 4, 5, 6, 7, 8, 9, 10]
                            }
                        }
                    ]
                });
            }
        });
    }

    // Ejecutar cuando cambia cualquiera
    $("#filtro_categoria, #filtro_responsable, #filtro_calidad, #filtro_sede, #filtro_dependencia, #filtro_estado, #filtro_garantia")
    .change(function () {
        aplicarFiltros();
    });

    
    $("#cancelar").click(function(){
        $("#formularioActivo")[0].reset();
        $("#select_responsable").val(null).trigger('change');
        $("#select_sede").val(null).trigger('change');
        $("#select_dependencia").val(null).trigger('change');
        $("#idcategoria").val(null).trigger('change');
        $("#foto").next(".custom-file-label").text("Eliga imagen");
        $("#foto").val("");
        div_sede_dependencia.classList.add('asignar');
    });

    $("#borrar_datos_modal").click(function(){
        $("#formularioActivo")[0].reset();
        $("#select_responsable").val(null).trigger('change');
        $("#select_sede").val(null).trigger('change');
        $("#select_dependencia").val(null).trigger('change');
        $("#idcategoria").val(null).trigger('change');
        $("#foto").next(".custom-file-label").text("Eliga imagen");
        $("#foto").val("");
        div_sede_dependencia.classList.add('asignar');
    });


    $("#cancelar_mov").click(function(){
        $("#formMovimiento")[0].reset();
        $("#select_responsable").val(null).trigger('change');
        $("#sede_destino").val(null).trigger('change');
        $("#dependencia_destino").val(null).trigger('change');
        div_transferencia.classList.add('asignar');
        div_prestamo.classList.add('asignar');
    });

    $("#cancelar_mov_activos").click(function(){
        $("#formMovimientoActivos")[0].reset();
        $("#mov_responsable").val(null).trigger('change');
        $("#sede_destino_ac").val(null).trigger('change');
        $("#dependencia_destino_ac").val(null).trigger('change');
        div_transferencia_ac.classList.add('asignar');
        div_prestamo_ac.classList.add('asignar');
    });

    $("#cancelar_mov_dev").click(function(){
        $("#formMovimientoDev")[0].reset();
    });

    function modificarActivo() {

        let idactivo = $("#txt_idactivo").attr("data-idactivo");

        let idcategoria = $("#idcategoria_editar").val();
        let marca = $("#txt_marca_editar").val();
        let modelo = $("#txt_modelo_editar").val();
        let serie = $("#txt_serie_editar").val();
        let codPatrimonial = $("#txt_patrimonial_editar").val();
        let idadministrativo = $("#select_responsable_editar").val();
        let idsede = $("#select_sede_editar").val();
        let iddependencia = $("#select_dependencia_editar").val();
        let estado = $("#select_estado_editar").val();
        let fecha_adquisicion = $("#fecCompra_editar").val();
        let orden_compra = $("#ordenCompra_editar")[0].files[0];
        let observacion = $("#txt_observacion_editar").val();
        let foto = $("#foto_editar")[0].files[0]; // archivo imagen
        let foto_actual = $("#foto_actual").val();

        // Validación mínima
        if (
            idcategoria == "" ||
            marca == "" ||
            modelo == "" ||
            serie == "" ||
            codPatrimonial == "" ||
            !idadministrativo ||
            !idsede ||
            !iddependencia ||
            !estado ||
            observacion == ""
        ) {
            mostrarAlerta("warning", "¡Completa los campos obligatorios!");
            return;
        }

        Swal.fire({
            icon: "question",
            title: "¿Está seguro de modificar el activo?",
            showCancelButton: true,
            confirmButtonText: "Sí, modificar",
            cancelButtonText: "Cancelar"
        }).then((result) => {

            if (result.isConfirmed) {

                let datos = new FormData();
                datos.append("op", "modificarActivo");
                datos.append("idactivo", idactivo);
                datos.append("idcategoria", idcategoria);
                datos.append("marca", marca);
                datos.append("modelo", modelo);
                datos.append("serie", serie);
                datos.append("codPatrimonial", codPatrimonial);
                datos.append("idadministrativo", idadministrativo);
                datos.append("idsede", idsede);
                datos.append("iddependencia", iddependencia);
                datos.append("estado", estado);
                datos.append("fecha_adquisicion", fecha_adquisicion);
                if (orden_compra !== undefined) {
                    datos.append("ordenCompra", orden_compra);
                }
                datos.append("observacion", observacion);
                datos.append("foto_actual", foto_actual);

                if (foto !== undefined) {
                    datos.append("foto", foto); // si cambia foto
                }

                $.ajax({
                    url: "controllers/Activo.controller.php",
                    type: "POST",
                    data: datos,
                    contentType: false,
                    processData: false,
                    success: function(e) {
                        let r = JSON.parse(e);

                        if (r.resultado == 1) {
                            mostrarAlerta("success", "Activo modificado correctamente");
                            $("#modal_editar").modal("hide");
                            listarActivos();
                        } else if (r.resultado == 2) {
                            mostrarAlerta("error", "¡El código patrimonial ya está registrado!");
                        } else {
                            mostrarAlerta("error", "¡Error desconocido al modificar!");
                        }
                    }
                });

            }

        });

    }


    function registrarMovimientoDevolucion(){
        let idactivo = $("#mov_idactivo_dev").val();
        let mov_idtipo = $("#mov_idtipo_dev").val();
        // let mov_fecha = $("#mov_fecha").val();
        let dev_responsable = $("#idresponsable_dev").val();
        let dev_motivo = $("#devolucion_motivo").val();
        
        if(idactivo == "" || mov_idtipo == "" || dev_responsable == "" || dev_motivo == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            Swal.fire({
                icon: 'warning',
                title: '¿Confirma que desea registrar la restitución del activo?',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then((result) =>{
                if(result.isConfirmed){
                    var datos ={
                        'op' : 'registrarMovDevolucion',
                        'idactivo' : idactivo,
                        'mov_idtipo' : mov_idtipo,
                        'dev_responsable' : dev_responsable,
                        'dev_motivo' : dev_motivo
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Activo.controller.php',
                        type: 'GET',
                        data: datos,
                        success: function(e){
                            let data = JSON.parse(e);

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
                            $("#modalMovimientoDev").modal("hide");
                            listarActivos();
                        }
                    });
                }
            });
        }
    }


    // $("#categoriaselect").change(function(){
    //     var filtros = $(this).val();
    //     // console.log(filtros);
    //     if(filtros==""){
    //         listarProductosFarmaciaPrueba();
    //     }else{

    //         $.ajax({
    //             url: 'controllers/Producto.controller.php',
    //             type: 'GET',
    //             data: {
    //                 'op': 'filtrarCategorias',
    //                 'idcategoria' : filtros
    //                 },
    //             success: function(result){
    //                 // console.log(result);
    //                 $("#tablaProductolistar").html(result);
    //             }
    //         });
    //     }
    // });

    $("#idcategoriamodal").change(function(){
        var filtros = $(this).val();
        console.log(filtros);
        // console.log(filtros);
        if(filtros==""){
            listarProductosFarmaciaPrueba();
        }else{
            $.ajax({
                url: 'controllers/Producto.controller.php',
                type: 'GET',
                data: {
                    'op': 'cargarProducto',
                    'idcategoria' : filtros
                    },
                success: function(result){
                    console.log(result);
                    $("#idproductomodal").html(result);
                }
            });
        }
    });

    $("#idcategoriasalida").change(function(){
        var filtros = $(this).val();
        console.log(filtros);
        // console.log(filtros);
        if(filtros==""){
            listarProductosFarmaciaPrueba();
        }else{
            $.ajax({
                url: 'controllers/Producto.controller.php',
                type: 'GET',
                data: {
                    'op': 'cargarProducto',
                    'idcategoria' : filtros
                    },
                success: function(result){
                    console.log(result);
                    $("#idproductosalida").html(result);
                }
            });
        }
    });

    // Select de sede y dependencia
    $('#select_responsable').on('change', function(){
        let idAdministrativo = $(this).val();
        if(idAdministrativo){
            div_sede_dependencia.classList.remove('asignar');
            cargarSedePorAdministrativo(idAdministrativo)
            cargarDependenciaPorAdministrativo(idAdministrativo)
            // cargarDependenciasPorSede(idSede);
        }
    });

    $('#select_responsable_editar').on('change', function(){
        let idAdministrativo = $(this).val();
        if(idAdministrativo){
            cargarSedePorAdministrativo(idAdministrativo)
            cargarDependenciaPorAdministrativo(idAdministrativo)
            // cargarDependenciasPorSede(idSede);
        }
    });

    $('#mov_responsable').on('change', function(){
        let idAdministrativo = $(this).val();
        if(idAdministrativo){
            cargarSedePorAdministrativo(idAdministrativo)
            cargarDependenciaPorAdministrativo(idAdministrativo)
            // cargarDependenciasPorSede(idSede);
        }
    });

    $('#mov_responsable_ac').on('change', function(){
        let idAdministrativo = $(this).val();
        if(idAdministrativo){
            cargarSedePorAdministrativo(idAdministrativo)
            cargarDependenciaPorAdministrativo(idAdministrativo)
            // cargarDependenciasPorSede(idSede);
        }
    });

    $('#mov_idtipo').on('change', function() {
        let tipo = $(this).val();
        let div_prestamo = document.querySelector("#div_prestamo");
        let div_transferencia = document.querySelector("#div_transferencia");

        if (tipo == "PRESTAMO") {
            div_prestamo.classList.remove('asignar'); // tiempo + motivo
            div_transferencia.classList.add('asignar');
            $("#mov_responsable").val(null).trigger('change');
            $("#sede_destino").val(null).trigger('change');
            $("#dependencia_destino").val(null).trigger('change');

        } else if (tipo == "TRANSFERENCIA") {
            div_transferencia.classList.remove('asignar');
            div_prestamo.classList.add('asignar');
            $("#foto_editar").val("");
        }
    });

    $('#mov_idtipos').on('change', function() {
        let tipo = $(this).val();
        let div_prestamo = document.querySelector("#div_prestamo_ac");
        let div_transferencia = document.querySelector("#div_transferencia_ac");

        if (tipo == "PRESTAMO") {
            div_prestamo.classList.remove('asignar'); // tiempo + motivo
            div_transferencia.classList.add('asignar');
            $("#mov_responsable_ac").val(null).trigger('change');
            $("#sede_destino_ac").val(null).trigger('change');
            $("#dependencia_destino_ac").val(null).trigger('change');

        } else if (tipo == "TRANSFERENCIA") {
            div_transferencia.classList.remove('asignar');
            div_prestamo.classList.add('asignar');
        }
    });

    
    $(document).on("change", ".chk-activo", function() {
        let id = $(this).val();

        if (this.checked) {
            agregarActivoSeleccionado({
                id: id,
                codigo: $(this).data("codigo"),
                foto: $(this).data("foto"),
                responsable: $(this).data("responsable"),
                dependencia: $(this).data("dependencia"),
                responsable_nombre: $(this).data("nombre")
            });
            btn_mov_registrar.classList.remove('asignar');
        } else {
            quitarActivoSeleccionado(id);
            if (Object.keys(activosSeleccionados).length === 0) {
                btn_mov_registrar.classList.add('asignar');
            }
        }
    })

    // Cada vez que DataTables dibuja la tabla
    $("#tablaActivo").on("draw.dt", function () {
        restaurarSeleccionados();
    });


    function traerJefeDependencia(idsActivos, id_dependencia) {

        $.ajax({
            url: 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: {
                op: 'getJefeOficina',
                iddependencia: id_dependencia
            },
            success: function (response) {

                if ($.trim(response) === "") {
                    Swal.fire("Error", "No se encontró jefe para esta dependencia", "error");
                    return;
                }

                let jefe = JSON.parse(response)[0];

                // Setear datos en el modal
                $("#idresponsable_activos").val(jefe.id_administrativo);
                $("#pres_jefe_activos").val(jefe.npersona);
                $("#transf_responsable_actual_ac").val(jefe.npersona);

                // Guardar IDs de activos
                $("#mov_idactivos").val(idsActivos.join(','));

                // Mostrar modal
                $("#modalMovimientoActivos").modal("show");

                cargarOtrosAdministrativosActivos("#resp_temporal_activos");
                cargarOtrosAdministrativosActivos("#mov_responsable_ac");
            }
        });
    }


    function registrarMovimientoPrestamo(){
        let idactivo = $("#mov_idactivo").attr("data-idactivo");
        let mov_idtipo = $("#mov_idtipo").val();
        // let mov_fecha = $("#mov_fecha").val();
        let prestamo_tiempo = $("#prestamo_tiempo").val();
        let pres_responsable = $("#idresponsable").val();
        let resp_temporal = $("#resp_temporal").val();
        let prestamo_motivo = $("#prestamo_motivo").val();
        let archivo            = $("#doc_sustento")[0].files[0];
        
        if(mov_idtipo == "" || pres_responsable == "" || resp_temporal == undefined || prestamo_tiempo == "" || prestamo_motivo == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }
        
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
                icon: 'question',
                title: '¿Está seguro de registrar?',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then((result) =>{

                if (!result.isConfirmed) return;

                // FORM DATA (CLAVE)
                let formData = new FormData();
                formData.append("op", "registrarMovPrestamo");
                formData.append("idactivo", idactivo);
                formData.append("mov_idtipo", mov_idtipo);
                formData.append("pres_responsable", pres_responsable);
                formData.append("resp_temporal", resp_temporal);
                formData.append("prestamo_tiempo", prestamo_tiempo);
                formData.append("prestamo_motivo", prestamo_motivo);
                formData.append("doc_sustento", archivo);
0
                    $.ajax({
                        url: 'controllers/Activo.controller.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(e){
                            let data = JSON.parse(e);
                            console.log(data)
                            Swal.fire({
                                icon: "success",
                                title: "Préstamo registrado",
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
                            $("#formMovimiento")[0].reset();
                            $("#select_responsable").val("null").trigger('change');
                            $("#sede_destino").val("");
                            $("#dependencia_destino").val("");
                            div_transferencia.classList.add('asignar');
                            div_prestamo.classList.add('asignar');
                            $("#modalMovimiento").modal("hide");
                            listarActivos();
                        },
                        error: function () {
                            Swal.fire("Error", "No se pudo registrar el movimiento", "error");
                        }
                    });
            });
        
    }

    function registrarMovimientoPrestamoActivos() {

        let ids_activos        = $("#mov_idactivos").val(); // "1,3,7"
        let tipo_mov           = $("#mov_idtipos").val();   // PRESTAMO
        let responsable_actual = $("#idresponsable_activos").val(); // jefe o mismo responsable
        let custodio_temporal  = $("#resp_temporal_activos").val();
        let tiempo             = $("#prestamo_tiempo_activos").val();
        let motivo             = $("#prestamo_motivo_activos").val();
        let archivo            = $("#doc_sustento_ac")[0].files[0];

        // VALIDACIÓN BÁSICA
        if (!ids_activos || !tipo_mov || !responsable_actual || !custodio_temporal || !tiempo || !motivo) {
            Swal.fire("Atención", "Complete todos los campos obligatorios", "warning");
            return;
        }

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
            icon: "question",
            title: "¿Confirmar préstamo?",
            text: "Los activos serán marcados como PRESTADOS",
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar"
        }).then((result) => {

            if (!result.isConfirmed) return;

                // FORM DATA (CLAVE)
                let formData = new FormData();
                formData.append("op", "registrarMovPrestamoActivos");
                formData.append("ids_activos", ids_activos);
                formData.append("tipo", tipo_mov);
                formData.append("responsable_actual", responsable_actual);
                formData.append("custodio_temporal", custodio_temporal);
                formData.append("tiempo", tiempo);
                formData.append("motivo", motivo);
                formData.append("doc_sustento_ac", archivo);


            $.ajax({
                url: "controllers/Activo.controller.php",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {

                    console.log("RESPUESTA:", response);

                    let data = JSON.parse(response);

                    Swal.fire({
                        icon: "success",
                        title: "Préstamo registrado",
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

                    // Limpieza normal
                    $("#formMovimientoActivos")[0].reset();
                    $("#resp_temporal_activos").val(null).trigger('change');    

                    limpiarSeleccionActivos();    

                    div_transferencia_ac.classList.add('asignar');    
                    div_prestamo_ac.classList.add('asignar');        
                    
                    $("#modalMovimientoActivos").modal("hide");

                    activosSeleccionados = {};
                    listarActivos();
                },
                error: function () {
                    Swal.fire("Error", "No se pudo registrar el movimiento", "error");
                }
            });
        });
    }



    function validarTipoMov(){
        let mov_idtipo = $("#mov_idtipo").val();

        if(mov_idtipo == 'PRESTAMO'){
            registrarMovimientoPrestamo();
        }else if(mov_idtipo == 'TRANSFERENCIA'){
            registrarMovimientoTransferencia();
        }else{
            mostrarAlerta("error","Seleccione movimiento a realizar!")
        }
    }

    function validarTipoMovActivos(){
        let mov_idtipo = $("#mov_idtipos").val();

        if(mov_idtipo == 'PRESTAMO'){
            registrarMovimientoPrestamoActivos();
        }else if(mov_idtipo == 'TRANSFERENCIA'){
            registrarMovimientoTransferenciaActivos();
        }else{
            mostrarAlerta("error","Seleccione movimiento a realizar!")
        }
    }


    function validarResponsableActivo() {

        let ids = Object.keys(activosSeleccionados);

        $("#mov_fecha_activos").val(new Date().toISOString().slice(0, 10));

        if (ids.length === 0) {
            Swal.fire("Atención", "Debe seleccionar al menos un activo.", "warning");
            return;
        }

        //Obtener dependencias y responsables
        let dependencias = ids.map(id => activosSeleccionados[id].dependencia);
        let responsables = ids.map(id => activosSeleccionados[id].responsable);

        let dependenciasUnicas = [...new Set(dependencias)];
        let responsablesUnicos = [...new Set(responsables)];

        console.log("Dependencias:", dependenciasUnicas);
        console.log("Responsables:", responsablesUnicos);

        //CASO 1: DIFERENTES DEPENDENCIAS → BLOQUEAR
        if (dependenciasUnicas.length > 1) {
            Swal.fire({
                icon: "error",
                title: "Movimiento no permitido",
                text: "Los activos seleccionados pertenecen a distintas dependencias.",
                confirmButtonText: "Entendido"
            });
            return;
        }

        //CASO 2: MISMA DEPENDENCIA + MISMO RESPONSABLE
        if (responsablesUnicos.length === 1) {
            let nombre = activosSeleccionados[ids[0]].responsable_nombre;
            console.log("Misma dependencia y mismo responsable");

            $("#mov_idactivos").val(ids.join(','));
            $("#idresponsable_activos").val(responsablesUnicos[0]);
            // console.log("id: "+responsablesUnicos[0]);
            $("#pres_jefe_activos").val(nombre);
            $("#transf_responsable_actual_ac").val(nombre);
            
            cargarOtrosAdministrativosActivos("#resp_temporal_activos");
            cargarOtrosAdministrativosActivos("#mov_responsable_ac");
        

            $("#modalMovimientoActivos").modal("show");
            return;
        }

        //CASO 3: MISMA DEPENDENCIA + DISTINTOS RESPONSABLES
        console.log("Misma dependencia, diferentes responsables → mostrar jefe");

        Swal.fire({
            title: "Atención",
            text: "Los activos pertenecen a distintos responsables. El movimiento será gestionado por el Jefe de la dependencia.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Continuar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let iddependencia = $("#iddependencia_activos").val(dependenciasUnicas[0]).trigger("change");
                // Abrir modal especial para jefe
                traerJefeDependencia(ids, dependenciasUnicas[0]);
            }
        });
    }



    function registrarMovimientoTransferencia(){
        let idactivo = $("#mov_idactivo").attr("data-idactivo");
        let mov_idtipo = $("#mov_idtipo").val();

        let transf_responsable_actual = $("#idresponsable").val();
        let mov_responsable = $("#mov_responsable").val(); //destino responsable
        let sede_destino = $("#sede_destino").val();
        let dependencia_destino = $("#dependencia_destino").val();
        let transferencia_motivo = $("#transferencia_motivo").val();
        let archivo            = $("#doc")[0].files[0];

        
        if(mov_idtipo == undefined || transf_responsable_actual == "" || mov_responsable == undefined || sede_destino == undefined || dependencia_destino == undefined || transferencia_motivo == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }

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
                icon: 'question',
                title: '¿Está seguro de registrar?',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then((result) =>{
                if (!result.isConfirmed) return;
                
                // FORM DATA (CLAVE)
                let formData = new FormData();
                formData.append("op", "registrarMovTransferencia");
                formData.append("idactivo", idactivo);
                formData.append("mov_idtipo", mov_idtipo);
                formData.append("transf_responsable_actual", transf_responsable_actual);
                formData.append("mov_responsable", mov_responsable);
                formData.append("sede_destino", sede_destino);
                formData.append("dependencia_destino", dependencia_destino);
                formData.append("transferencia_motivo", transferencia_motivo);
                formData.append("doc", archivo);

                    console.log(formData);
                    $.ajax({
                        url: 'controllers/Activo.controller.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(e){
                            let data = JSON.parse(e);
                            console.log(data);
                            Swal.fire({
                                icon: "success",
                                title: "Transferencia registrada",
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
                            $("#formMovimiento")[0].reset();
                            $("#mov_responsable").val(null).trigger('change');
                            $("#sede_destino").val("");
                            $("#dependencia_destino").val("");
                            div_transferencia.classList.add('asignar');
                            div_prestamo.classList.add('asignar');
                            $("#modalMovimiento").modal("hide");
                            listarActivos();
                        },
                        error: function () {
                            Swal.fire("Error", "No se pudo registrar el movimiento", "error");
                        }
                    });
            });
    }

    // Registrar transferencias de varios activos
    function registrarMovimientoTransferenciaActivos(){
        let ids_activos        = $("#mov_idactivos").val(); // "1,3,7"
        let tipo_mov           = $("#mov_idtipos").val();   // TRANSFERENCIA

        let transf_responsable_actual = $("#idresponsable_activos").val();
        let mov_responsable = $("#mov_responsable_ac").val(); //destino responsable
        let sede_destino = $("#sede_destino_ac").val();
        let dependencia_destino = $("#dependencia_destino_ac").val();
        let transferencia_motivo = $("#transferencia_motivo_ac").val();
        let archivo            = $("#doc_ac")[0].files[0];
        
        
        if(tipo_mov == undefined || transf_responsable_actual == "" || mov_responsable == undefined || sede_destino == undefined || dependencia_destino == undefined || transferencia_motivo == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }

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
                icon: 'question',
                title: '¿Está seguro de registrar?',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then((result) =>{

                if (!result.isConfirmed) return;

                // FORM DATA (CLAVE)
                let formData = new FormData();
                formData.append("op", "registrarMovTransferenciaActivos");
                formData.append("ids_activos", ids_activos);
                formData.append("tipo", tipo_mov);
                formData.append("transf_responsable_actual", transf_responsable_actual);
                formData.append("mov_responsable", mov_responsable);
                formData.append("sede_destino", sede_destino);
                formData.append("dependencia_destino", dependencia_destino);
                formData.append("transferencia_motivo", transferencia_motivo);
                formData.append("doc_ac", archivo);

                $.ajax({
                    url: "controllers/Activo.controller.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {

                        console.log("RESPUESTA:", response);

                        let data = JSON.parse(response);

                        Swal.fire({
                            icon: "success",
                            title: "Transferencia registrada",
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

                        $("#formMovimientoActivos")[0].reset();
                        $("#mov_responsable_ac").val(null).trigger('change');
                        $("#sede_destino_ac").val("");
                        $("#dependencia_destino_ac").val("");

                        limpiarSeleccionActivos();

                        div_transferencia_ac.classList.add('asignar');
                        div_prestamo_ac.classList.add('asignar');
                        $("#modalMovimientoActivos").modal("hide");

                        activosSeleccionados = {};
                        listarActivos();
                    },
                    error: function () {
                        Swal.fire("Error", "No se pudo registrar el movimiento", "error");
                    }
                });
            });
    }

    function registrarSalidas(){
        let idproductosalida = $("#idproductosalida").val();
        let cantidadsalida = $("#cantidadsalida").val();
        let detalle = $("#detalle").val();
        
        if(idproductosalida == "" || cantidadsalida == "" || detalle == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            Swal.fire({
                icon: 'question',
                title: '¿Está seguro de registrar?',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar'
            }).then((result) =>{
                if(result.isConfirmed){
                    var datos ={
                        'op' : 'registrarSalidas',
                        'idproducto' : idproductosalida,
                        'cantidadsalida' : cantidadsalida,
                        'detalle' : detalle
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Salidas.controller.php',
                        type: 'GET',
                        data: datos,
                        success: function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioSalidas")[0].reset();
                            listarSalidas();
                        }
                    });
                }
            });
        }
    }

    function limpiarSeleccionActivos() {
        // Vaciar objeto
        activosSeleccionados = {};

        // Desmarcar checkboxes
        $(".chk-activo").prop("checked", false);

        // Limpiar chips
        $("#contenedorSeleccionados").html(
            "<span style='font-size: 12px;'>Ninguno seleccionado</span>"
        );

        // Limpiar input oculto si lo usas
        $("#mov_idactivos").val("");

        btn_mov_registrar.classList.add('asignar');
    }

    


    function listarSalidas(){
        $.ajax({
            url: 'controllers/Salidas.controller.php',
            type: 'GET',
            data: 'op=listarSalidas',
            success: function(e){
                var tabla = $("#tablaSalidas").DataTable();
                tabla.destroy();
                $("#tablaSalidaslistar").html(e);
                $("#tablaSalidas").DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
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
                                columns: [ 0, 1, 2, 3,4],
                                stripHtml: false, /* Aquí indicamos que no se eliminen las imágenes */
                            }
                        }
                    ]
                });
            }
        });
    }

    function agregarActivoSeleccionado(data) {
         console.log("DATA RECIBIDA:", data);
        activosSeleccionados[data.id] = data;
        renderSeleccionados();
        $(".chk-activo[value='" + data.id + "']").prop("checked", true);
    }

    window.quitarActivoSeleccionado = function(id) {
        delete activosSeleccionados[id];
        $(".chk-activo[value='" + id + "']").prop("checked", false);
        renderSeleccionados();
    }

    function renderSeleccionados() {
        let cont = $("#contenedorSeleccionados");
        cont.empty();

        for (let id in activosSeleccionados) {
            let a = activosSeleccionados[id];
            cont.append(`
                <div class="activo-chip" title="Activo: ${a.codigo}">
                    <img src="img/${a.foto}">
                    <span>${a.codigo}</span>
                    <span class="chip-close" onclick="quitarActivoSeleccionado(${id})">×</span>
                </div>
            `);
        }

        if (Object.keys(activosSeleccionados).length === 0) {
            cont.html("<span style='font-size: 12px;'>Ninguno seleccionado</span>");
            btn_mov_registrar.classList.add('asignar');
        }
    }

    function restaurarSeleccionados() {
        for (let id in activosSeleccionados) {
            $(".chk-activo[value='" + id + "']").prop("checked", true);
        }
    }


    function listarRestock(){
        $.ajax({
            url: 'controllers/Restock.controller.php',
            type: 'GET',
            data: 'op=listarRestock',
            success: function(e){
                var tabla = $("#tablaRestock").DataTable();
                tabla.destroy();
                $("#tablaRestocklistar").html(e);
                $("#tablaRestock").DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
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
                                columns: [ 0, 1, 2, 3,4],
                                stripHtml: false, /* Aquí indicamos que no se eliminen las imágenes */
                            }
                        }
                    ]
                });
            }
        });
    }

    // listarRestock();
    // listarSalidas();
    listarActivos();
    $("#registrar").click(activoYaExiste);
    $('#volver_').click(volverActivos);
    $("#actualizar").click(modificarActivo);
    // $("#btnRegistrarRestock").click(registrarRestock);
    // $("#btnRegistrarSalidas").click(registrarSalidas);
    cargarCategorias("#idcategoriasalida");
    cargarCategorias("#idcategoria");
    cargarCategorias("#idcategoriamodal");
    cargarCategoriasFiltro("#filtro_categoria");
    cargarCategorias("#idcategoria_editar");
    cargarAdministrativos("#select_responsable");
    cargarAdministrativos("#select_responsable_editar");
    cargarAdministrativosFiltro("#filtro_responsable");

    cargarSedeFiltro("#filtro_sede");
    cargarDependenciaFiltro("#filtro_dependencia");
    
    $("#btn_mov_registrar").click(validarResponsableActivo);
    $("#registrar_mov_activos").click(validarTipoMovActivos);

    $("#registrar_mov").click(validarTipoMov);
    $("#registrar_mov_dev").click(registrarMovimientoDevolucion);

    // ACTUALIZAR LABEL DE ARCHIVOS
    $("#foto, #ordenCompra, #foto_editar, #ordenCompra_editar").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").html(fileName);
    });

    // IMPORTACIÓN EXCEL
    $("#archivo_carga").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").html(fileName);
        $("#btnProcesarCarga").prop("disabled", !fileName);
    });

    $("#btnProcesarCarga").click(function () {
        var file = $("#archivo_carga")[0].files[0];
        if (!file) {
            mostrarAlerta("warning", "Seleccione un archivo");
            return;
        }

        var formData = new FormData();
        formData.append("op", "cargaMasivaActivos");
        formData.append("archivo_carga", file);

        Swal.fire({
            icon: "info",
            title: "Procesando...",
            text: "Esto puede tomar unos segundos",
            allowOutsideClick: false,
            didOpen: function () {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: "controllers/Activo.controller.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                var r = JSON.parse(response);
                Swal.close();

                if (r.status === "success") {
                    var html = '<div class="alert alert-success"><strong>' + r.insertados + '</strong> activos insertados correctamente.</div>';

                    if (r.errores && r.errores.length > 0) {
                        html += '<div class="alert alert-warning"><strong>' + r.errores.length + '</strong> filas con errores:</div>';
                        html += '<div style="max-height:200px;overflow-y:auto;">';
                        r.errores.forEach(function (e) {
                            html += '<div class="mb-1 p-1 border-bottom"><small><b>Fila ' + e.fila + ' (' + e.codigo + '):</b> ' + e.errores.join(", ") + '</small></div>';
                        });
                        html += '</div>';
                    }

                    $("#resumenCarga").html(html);
                    $("#resultadoCarga").show();
                    listarActivos();
                } else {
                    $("#resumenCarga").html('<div class="alert alert-danger">' + (r.message || "Error al procesar") + '</div>');
                    $("#resultadoCarga").show();
                }
            },
            error: function () {
                Swal.fire("Error", "No se pudo procesar el archivo", "error");
            }
        });
    });

    $("#modalImportarExcel").on("hidden.bs.modal", function () {
        $("#formImportarExcel")[0].reset();
        $("#archivo_carga").next(".custom-file-label").html("Elegir archivo");
        $("#btnProcesarCarga").prop("disabled", true);
        $("#resultadoCarga").hide();
    });
    
});