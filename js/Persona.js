$(document).ready(function(){
 
    idusuario = "";
    var txtpersona = document.querySelector("#txt_idpersona");
    // var botonActualizar = document.querySelector("#actualizar");
    // var botonGuardar = document.querySelector("#registrar");
    
    function registrarPersona() {
        // let nombres = $("#nombres").val();
        // let apellidos = $("#apellidos").val();
        let txt_nro = $("#txt_nro").val();
        let txt_nom = $("#txt_nom").val();
        let txt_apepa = $("#txt_apepa").val();
        let txt_apema = $("#txt_apema").val();
        let txt_fnac = $("#txt_fnac").val();
        let txt_movil = $("#txt_movil").val();
        let txt_dire = $("#txt_dire").val();
        let txt_email = $("#txt_email").val();
        
        if(txt_nro == "" || txt_nom == "" || txt_apepa == "" || txt_apema == "" || txt_fnac == "" || txt_movil == "" || txt_dire == "" || txt_email == ""){
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
                        'op' : 'registrarPersona',
                        // 'nombres'               : nombres, 
                        // 'apellidos'             : apellidos, 
                        'txt_nro' : txt_nro, 
                        'txt_nom' : txt_nom,
                        'txt_apepa' : txt_apepa,
                        'txt_apema' : txt_apema,
                        'txt_fnac' : txt_fnac, 
                        'txt_movil' : txt_movil,
                        'txt_dire' : txt_dire,
                        'txt_email' : txt_email
                    };

                    $.ajax({
                        url: 'controllers/Persona.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            // Limpia los campos del formulario
                            $("#modal_persona input").val("");
                            // Cierra el modal
                            $("#modal_persona").modal('hide');
                            // Refresca la tabla
                            listarPersonas();
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
    
    function listarPersonas(){
        $.ajax({
            url: 'controllers/Persona.controller.php',
            type: 'GET',
            data: 'op=listarPersonas',
            success: function(e){
                var tabla = $("#tablaPersona").DataTable();
                tabla.destroy();
                $("#datosPersona").html(e);
                $("#tablaPersona").DataTable({
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

    $("#tablaPersona").on('click', ".modificar", function(){
        var idpersona = $(this).attr('data-idpersona');

        $("#modal_editar").modal('show');

        $.ajax({
            url: 'controllers/Persona.controller.php',
            type: 'GET',
            data: {
                'op': 'getPersona',
                'idpersona' : idpersona
            },
            success: function(result){                        
                if ($.trim(result) != ""){

                    var resultado = JSON.parse(result);

                    $("#txt_nro_editar").val(resultado[0].nro_documento);
                    $("#txt_nom_editar").val(resultado[0].per_nombre);
                    $("#txt_apepa_editar").val(resultado[0].per_apepat);
                    $("#txt_apema_editar").val(resultado[0].per_apemat);
                    $("#txt_fnac_editar").val(resultado[0].fecha_nacimiento);
                    $("#txt_movil_editar").val(resultado[0].nro_movil);
                    $("#txt_dire_editar").val(resultado[0].per_direccion);
                    $("#txt_email_editar").val(resultado[0].per_email);
                    $("#select_estatus_editar").val(resultado[0].estado);

                    txtpersona.setAttribute("data-idpersona", resultado[0].id_persona);
                    // $("#idusuariomod").hide();
                }else{
                    
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });


    $("#btnModificarPersona").click(function(){
        modificarPersona();
    });

    
    function modificarPersona(){
        console.log('id a modificar:', $('#txt_idpersona').val());
        let idpersona = $("#txt_idpersona").attr('data-idpersona');

        let txt_nro_editar = $("#txt_nro_editar").val();
        let txt_nom_editar = $("#txt_nom_editar").val();
        let txt_apepa_editar = $("#txt_apepa_editar").val();
        let txt_apema_editar = $("#txt_apema_editar").val();
        let txt_fnac_editar = $("#txt_fnac_editar").val();
        let txt_movil_editar = $("#txt_movil_editar").val();
        let txt_dire_editar = $("#txt_dire_editar").val();
        let txt_email_editar = $("#txt_email_editar").val();
        let select_estatus_editar = $("#select_estatus_editar").val();

        if(txt_nro_editar == "" || txt_nom_editar == "" || txt_apepa_editar == "" || txt_apema_editar == "" || txt_fnac_editar == "" || txt_movil_editar == "" || txt_dire_editar == "" || txt_email_editar == "" || select_estatus_editar == ""){
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
                        'op'              : 'modificarPersona',
                        'idpersona'       : idpersona,
                        'txt_nro_editar' : txt_nro_editar,
                        'txt_nom_editar'   : txt_nom_editar,
                        'txt_apepa_editar'       : txt_apepa_editar,
                        'txt_apema_editar'       : txt_apema_editar,
                        'txt_fnac_editar'     : txt_fnac_editar,
                        'txt_movil_editar' : txt_movil_editar,
                        'txt_dire_editar' : txt_dire_editar,
                        'txt_email_editar' : txt_email_editar,
                        'select_estatus_editar' : select_estatus_editar,

                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Persona.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            let respuesta = JSON.parse(e);
                            if(respuesta.resultado == 1){
                                mostrarAlerta("success", "¡Persona modificada con éxito!");
                                $("#modal_editar input").val("");
                                $("#modal_editar").modal('hide');
                                listarPersonas();
                            } else if(respuesta.resultado == 2){
                                mostrarAlerta("error", "¡El DNI ya está registrado en otra persona!");
                            } else {
                                mostrarAlerta("error", "¡Error desconocido al modificar!");
                            }
                        }
                    });
                }
            });
        }
    }


    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    function dniYaExiste(){
        let nro_dni = $("#txt_nro").val();
        
        if(nro_dni == ""){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else{
            var datos = {
                'op' : 'dniYaExiste',
                'nro_dni' : nro_dni
            };
            $.ajax({
                type: "GET",
                url:  "controllers/Persona.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya existe este Nro DNI en la base de datos!");
                    }
                    else if(e == 2){
                        registrarPersona();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
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

    
    cargarPersonas("#idpersona");
    $("#registrar").click(dniYaExiste);
    // $("#actualizar").click(modificarUsuarios);
    listarPersonas();
});