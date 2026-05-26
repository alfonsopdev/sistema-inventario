$(document).ready(function(){
 
    // idadministrativo = "";
    var txt_administrativo = document.querySelector("#idadministrativo");
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");
    var select_estado = document.querySelector("#select_estado");
    var div_dependencia = document.querySelector("#div_dependencia");
    
    function registrarAdministrativo() {
        // let nombres = $("#nombres").val();
        // let apellidos = $("#apellidos").val();
        let select_persona = $("#select_persona").val();
        let select_dependencia = $("#select_dependencia").val();
        let select_cargo = $("#select_cargo").val();
        
        if(select_persona == undefined || select_dependencia == undefined || select_cargo == undefined){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            Swal.fire({
                icon:'question',
                title:'¿Está seguro de registrar?',
                showCancelButton: true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Aceptar'
            }).then((result) =>{
                if(result.isConfirmed){
                    var datos = {
                        'op' : 'registrarAdministrativo',
                        // 'nombres'               : nombres, 
                        // 'apellidos'             : apellidos, 
                        'idpersona' : select_persona, 
                        'iddependencia' : select_dependencia,
                        'idcargo' : select_cargo
                    };

                    $.ajax({
                        url: 'controllers/Administrativo.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            div_dependencia.classList.add('asignar');
                            $("#formularioAdministrativo")[0].reset();
                            listarAdministrativos();
                        }
                    });
                }
            });
        }
    }

    // Cargar los nombres de las personas
    function cargarPersonas(select){ 
        var datos ={
            'op': 'cargarPersona'
        };
        $.ajax({
            url : 'controllers/Persona.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }

    // Cargar los nombres de las dependencias
    function cargarDependencias(select){ 
        var datos ={
            'op': 'cargarDependencias'
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
    
    // Cargar los nombres de las dependencias
    function cargarSede(select){ 
        var datos ={
            'op': 'cargarSede'
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
    
    // Cargar id_dependencia_sede
    function cargarDependenciasPorSede(idSede, idDependenciaSeleccionada = null){ 
        $.ajax({
            url : 'controllers/Dependencia.controller.php',
            type: 'GET',
            data: {
                'op': 'listarDependenciasPorSede',
                'idSede': idSede
            },
            success:function(e){
                $('#select_dependencia').html(e);
                if(idDependenciaSeleccionada){
                    $('#select_dependencia').val(idDependenciaSeleccionada);
                }
            }
        });
    }

    // Cargar los nombres de los cargos
    function cargarCargos(select){ 
        var datos ={
            'op': 'cargarCargos'
        };
        $.ajax({
            url : 'controllers/Cargo.controller.php',
            type: 'GET',
            data: datos,
            success:function(e){
                $(select).html(e);
            }
        });
    }


    // function nombrepersonaYaAsignado(){
    //     let persona_asignada = $("#idpersona").val();

    //     if(persona_asignada == ""){
    //         mostrarAlerta("warning", "¡Complete los datos necesarios!");
    //     }else {
    //         var datos = {
    //             'op' : 'personaYaAsignada',
    //             'idpersona' : persona_asignada
    //         };

    //         $.ajax({
    //             type: 'GET',
    //             url: "controllers/Usuario.controller.php",
    //             data: datos,
    //             success: function(e){
    //                 if(e == 1){
    //                     mostrarAlerta("error", "¡Ya tiene cuenta esta persona!");
    //                 }
    //                 else if(e == 2){
    //                     registrarUsuarios();
    //                 }else{
    //                     mostrarAlerta("error", "¡A ocurrido un error!");
    //                 }
    //             }
    //         });
    //     }
    // }

    function reporteAsistencia(){
        $.ajax({
            url: 'controllers/Usuario.controller.php',
            type: 'GET',
            data: 'op=reporteAsistencia',
            success: function(e){
                var tabla = $("#tablareporte").DataTable();
                tabla.destroy();
                $("#datosreporte").html(e);
                $("#tablareporte").DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
                    columnDefs: [
                    {
                        visible: true,
                        searchable: true
                    }
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy', 'print', 'pdf', 'excel']
                });
            }
        });
    }
    
    function listarAdministrativos(){
        $.ajax({
            url: 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: 'op=listarAdministrativos',
            success: function(e){
                var tabla = $("#tablaAdministrativo").DataTable();
                tabla.destroy();
                $("#datosAdministrativo").html(e);
                $("#tablaAdministrativo").DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
                    columnDefs: [
                    {
                        visible: true,
                        searchable: true
                    }
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy', 'print', 'pdf', 'excel']
                });
            }
        });
    }

    $("#tablaAdministrativo").on('click', ".modificar", function(){
        var idadministrativo = $(this).attr('data-idadministrativo');

        $.ajax({
            url: 'controllers/Administrativo.controller.php',
            type: 'GET',
            data: {
                'op': 'getAdministrativo',
                'idadministrativo' : idadministrativo
            },
            success: function(result){                        
                if ($.trim(result) != ""){

                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("Actualizar Administrativo");
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    select_estado.classList.remove('asignar');
                    div_dependencia.classList.remove('asignar');

                    var resultado = JSON.parse(result);

                    $("#select_persona").val(resultado[0].id_persona);
                    $("#select_sede").val(resultado[0].id_sede);
                    cargarDependenciasPorSede(resultado[0].id_sede, resultado[0].id_dependencia_sede);
                    // $("#select_dependencia").val(resultado[0].id_dependencia);
                    $("#select_cargo").val(resultado[0].id_cargo);
                    $("#estado").val(resultado[0].estado);

                    txt_administrativo.setAttribute("data-idadministrativo", resultado[0].id_administrativo);
                    // $("#idusuariomod").hide();
                }else{
                    
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });

    
    function modificarAdministrativo(){
        console.log('id a modificar:', $('#idadministrativo').val());
        let idadministrativo = $("#idadministrativo").attr('data-idadministrativo');

        let select_persona = $("#select_persona").val();
        let select_dependencia = $("#select_dependencia").val();
        let select_cargo = $("#select_cargo").val();
        let estado = $("#estado").val();


        if(select_persona == undefined || select_dependencia == undefined || select_cargo == undefined || estado == undefined ){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            Swal.fire({
                icon:'question',
                title:'¿Está seguro de modificar?',
                showCancelButton: true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Aceptar'
            }).then((result) =>{
                if(result.isConfirmed){
                    var datos = {
                        'op'              : 'modificarAdministrativo',
                        'idadministrativo'       : idadministrativo,
                        'select_persona' : select_persona,
                        'select_dependencia'   : select_dependencia,
                        'select_cargo'       : select_cargo,
                        'estado'       : estado

                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Administrativo.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Administrativo(a) modificado(a) con éxito!");

                            $("#formularioAdministrativo")[0].reset();
                            $("#Aviso").html("Registrar Administrativo");
                            botonActualizar.classList.add('asignar');
                            botonGuardar.classList.remove('asignar');
                            select_estado.classList.add('asignar');
                            div_dependencia.classList.add('asignar');

                            listarAdministrativos();
                        }
                    });
                }
            });
        }
    }

    $("#cancelar").click(function(){
        $("#formularioAdministrativo")[0].reset();
        $("#Aviso").html("Registrar Administrativo");
        // txtUsuario.classList.add('asignar');
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        select_estado.classList.add('asignar');
        div_dependencia.classList.add('asignar');

    });


    $('#select_sede').on('change', function(){
        let idSede = $(this).val();
        if(idSede){
            div_dependencia.classList.remove('asignar');
            cargarDependenciasPorSede(idSede);
        }
    });

    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    // function dniYaExiste(){
    //     let nro_dni = $("#txt_nro").val();
        
    //     if(nro_dni == ""){
    //         mostrarAlerta("warning", "¡Complete los datos necesarios!");
    //     }else{
    //         var datos = {
    //             'op' : 'dniYaExiste',
    //             'nro_dni' : nro_dni
    //         };
    //         $.ajax({
    //             type: "GET",
    //             url:  "controllers/Persona.controller.php",
    //             data: datos,
    //             success: function(e){
    //                 if(e == 1){
    //                     mostrarAlerta("error", "¡Ya existe este Nro DNI en la base de datos!");
    //                 }
    //                 else if(e == 2){
    //                     registrarPersona();
    //                 }else{
    //                     mostrarAlerta("error", "¡A ocurrido un error!");
    //                 }
    //             }
    //         });
    //     }
    // }

    function nombrepersonaYaAsignado(){
        let persona_asignada = $("#select_persona").val();

        if(persona_asignada == undefined){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'personaYaAsignada',
                'idpersona' : persona_asignada
            };

            $.ajax({
                type: 'GET',
                url: "controllers/Administrativo.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Esta persona ya figura como Administrativo!");
                    }
                    else if(e == 2){
                        registrarAdministrativo();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    
    cargarPersonas("#select_persona");
    // listarDependenciasPorSede("#select_dependencia");
    cargarDependencias("#select_dependencia");

    cargarCargos("#select_cargo");
    cargarSede("#select_sede");


    $("#registrar").click(nombrepersonaYaAsignado);
    $("#actualizar").click(modificarAdministrativo);
    listarAdministrativos();
});