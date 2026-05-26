$(document).ready(function(){
 
    idusuario = "";
    var txtUsuario = document.querySelector("#idusuariomod");
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");
    
    function registrarUsuarios() {
        // let nombres = $("#nombres").val();
        // let apellidos = $("#apellidos").val();
        let nombreusuario = $("#nombreusuario").val();
        let contrasena = $("#contrasena").val();
        let idpersona = $("#idpersona").val();
        let nivelacceso = $("#nivelacceso").val();
        if(nombreusuario == "" || contrasena == "" || idpersona == undefined || nivelacceso == undefined){
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
                        'op' : 'registrarUsuario',
                        // 'nombres'               : nombres, 
                        // 'apellidos'             : apellidos, 
                        'nombreusuario' : nombreusuario, 
                        'contrasena' : contrasena,
                        'idpersona' : idpersona,
                        'nivelacceso' : nivelacceso
                    };

                    $.ajax({
                        url: 'controllers/Usuario.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioUsuario")[0].reset();
                            listarUsuarios();
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
    function listarUsuarios(){
        $.ajax({
            url: 'controllers/Usuario.controller.php',
            type: 'GET',
            data: 'op=listarUsuarios',
            success: function(e){
                var tabla = $("#tablaUsuario").DataTable();
                tabla.destroy();
                $("#datosUsuario").html(e);
                $("#tablaUsuario").DataTable({
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

    function GestionUsuario(tipo, valor, icono, mensaje){
        var datos = {
            'op' : tipo,
            'idusuario' : valor
        };
        console.log(datos);
        $.ajax({
            url: 'controllers/Usuario.controller.php',
            type: 'GET',
            data: datos,
            success: function(e){
                mostrarAlerta(icono, mensaje);
                listarUsuarios(); 
            }
        });
    }

    // $('#datosUsuario').on('change',"#on",function(){
    //     var idusuario2 = $(this).attr('data-idusuario2');
    //     if(this.checked){
    //         GestionUsuario("reactivarUsuario", idusuario2, "success", "¡Restaurado con éxito!");
    //     }
    //     else{
    //         GestionUsuario("eliminarUsuario", idusuario2, "success", "¡Eliminado con éxito!");
    //     }
    // });

    // ACTIVAR
    $('#tablaUsuario').on('click', '.activar', function () {
    const id = $(this).data('id');
    const usuario = $(this).closest('tr').find('td:eq(1)').text().trim(); // toma el nombre mostrado

    Swal.fire({
        title: `¿Desea activar al usuario ${usuario}?`,
        text: "Una vez activado el usuario tendrá acceso al sistema.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "Cancelar"
    }).then((res) => {
        if (!res.isConfirmed) return;
        // Reutiliza tu función existente:
        GestionUsuario("reactivarUsuario", id, "success", "¡Restaurado con éxito!");
        // refresca tabla después de que el backend responda
        setTimeout(listarUsuarios, 300); // pequeño delay para evitar condiciones de timing
    });
    });

    // DESACTIVAR
    $('#tablaUsuario').on('click', '.desactivar', function () {
    const id = $(this).data('id');
    const usuario = $(this).closest('tr').find('td:eq(1)').text().trim();

    Swal.fire({
        title: `¿Desea desactivar al usuario ${usuario}?`,
        text: "Una vez desactivado el usuario no tendrá acceso al sistema.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "Cancelar"
    }).then((res) => {
        if (!res.isConfirmed) return;
        GestionUsuario("eliminarUsuario", id, "success", "¡Eliminado con éxito!");
        setTimeout(listarUsuarios, 300);
    });
    });

    $("#tablaUsuario").on('click', ".modificar", function(){
        var idusuario = $(this).attr('data-idusuariomod');

        $.ajax({
            url: 'controllers/Usuario.controller.php',
            type: 'GET',
            data: {
                'op': 'getUsuario',
                'idusuario' : idusuario
            },
            success: function(result){                        
                if ($.trim(result) != ""){
                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("Actualizar Usuario");
                    $("#label_contrasena").closest('div').hide();
                    $('#contrasena').closest('div').hide(); 
                    txtUsuario.classList.remove('asignar');
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    // $("#idpersona").prop('disabled', true);
                    // $("#apellidos").prop('disabled', true);

                    var resultado = JSON.parse(result);

                    $("#nombreusuario").val(resultado[0].usu_usuario);
                    $("#contrasena").val(resultado[0].usu_contra);
                    $("#idpersona").val(resultado[0].id_persona);
                    $("#nivelacceso").val(resultado[0].usu_nivel_acceso);
                    txtUsuario.setAttribute("data-idusuario", resultado[0].id_usuario);
                    $("#idusuariomod").hide();
                }else{
                    
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });

    //MODAL - EDITAR CONTRASEÑA USUARIO
    $('#tablaUsuario').on('click','.contra',function(){
        $("#modal_contra").modal('show');
    })

    
    function modificarUsuarios(){
        console.log('id a modificar:', $('#idusuariomod').val());
        let idusuario = $("#idusuariomod").attr('data-idusuario');
        let nombreusuario = $("#nombreusuario").val();
        // let contrasena = $("#contrasena").val();
        let idpersona = $("#idpersona").val();
        let nivelacceso = $("#nivelacceso").val();
        if(nombreusuario == "" || idpersona == undefined || nivelacceso == undefined){
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
                        'op'              : 'modificarUsuario',
                        'idusuario'       : idusuario,
                        'nombreusuario'   : nombreusuario,
                        'idpersona'       : idpersona,
                        'nivelacceso'     : nivelacceso
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Usuario.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Modificado con éxito!");

                            $("#formularioUsuario")[0].reset();
                            $("#Aviso").html("Registrar Usuario");
                            txtUsuario.classList.add('asignar');
                            botonActualizar.classList.add('asignar');
                            botonGuardar.classList.remove('asignar');
                            $("#idpersona").prop('disabled', false);
                            // $("#apellidos").prop('disabled', false);
                            $("#label_contrasena").closest('div').show();
                            $('#contrasena').closest('div').show();

                            listarUsuarios();
                        }
                    });
                }
            });
        }
    }

    $("#cancelar").click(function(){
        $("#formularioUsuario")[0].reset();
        $("#Aviso").html("Registrar Usuario");
        txtUsuario.classList.add('asignar');
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        $("#idpersona").prop('disabled', false);
        // $("#apellidos").prop('disabled', false);
        $("#label_contrasena").closest('div').show();
        $('#contrasena').closest('div').show(); 
    });


    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    function actualizarClave(){
        const claveActual = $("#claveactual").val();
        const claveNueva = $("#clavenueva").val();
        const claveNuevaConfirmada = $("#clavenuevaconfirmada").val();

        if(claveActual == "" || claveNueva == "" || claveNuevaConfirmada == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            if(claveNueva != claveNuevaConfirmada){ // faltaria validacion para cuando la clave actual no es correcta
                mostrarAlerta("warning", "¡Las nuevas contraseñas no coinciden!");
            }else{
                Swal.fire({
                    icon: 'question',
                    title: '¿Está seguro de cambiar la contraseña?',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed){
                        $.ajax({
                            url: 'controllers/Usuario.controller.php',
                            type: 'GET',
                            data: {
                                'op' : 'actualizarClave',
                                'claveActual' : claveActual,
                                'claveNueva' : claveNueva 
                            },
                            success: function(result){
                                if($.trim(result) == "OK"){
                                    mostrarAlerta("success", "¡Actualizado correctamente!");
                                    resetearFormulario();
                                }else{
                                    mostrarAlerta("warning", "¡La contraseña actual es incorrecta");
                                    $("#claveactual").focus();
                                }
                            }
                        });
                    }
                });
            }
        }
    }

    function nombreusuarioYaExiste(){
        let nombreusuarioYaExiste = $("#nombreusuario").val();
        
        if(nombreusuarioYaExiste == ""){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else{
            var datos = {
                'op' : 'nombreusuarioYaRegistrado',
                'nombreusuario' : nombreusuarioYaExiste
            };
            $.ajax({
                type: "GET",
                url:  "controllers/Usuario.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya existe este nombre de usuario!");
                    }
                    else if(e == 2){
                        nombrepersonaYaAsignado();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    function nombrepersonaYaAsignado(){
        let persona_asignada = $("#idpersona").val();

        if(persona_asignada == undefined){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'personaYaAsignada',
                'idpersona' : persona_asignada
            };

            $.ajax({
                type: 'GET',
                url: "controllers/Usuario.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya tiene cuenta esta persona!");
                    }
                    else if(e == 2){
                        registrarUsuarios();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    
    cargarPersonas("#idpersona");
    $("#cambiarclave").click(actualizarClave);
    $("#registrar").click(nombreusuarioYaExiste);
    $("#actualizar").click(modificarUsuarios);
    listarUsuarios();
    reporteAsistencia();
});