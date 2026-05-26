$(document).ready(function(){
 
    var txt_idsede = document.querySelector("#idsede");
    var div_estado = document.querySelector("#div_estado")
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");
    
    function registrarSedes() {

        let nombresede = $("#nombresede").val();
        let ubicacion = $("#ubicacion").val();
        if(nombresede == "" || ubicacion == ""){
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
                        'op' : 'registrarSede',

                        'nombresede' : nombresede, 
                        'ubicacion' : ubicacion
                    };

                    $.ajax({
                        url: 'controllers/Sede.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioSede")[0].reset();
                            listarSedes();
                        }
                    });
                }
            });
        }
    }

    // Cargar los nombres de las personas
    // function cargarPersonas(select){ 
    //     var datos ={
    //         'op': 'cargarPersona'
    //     };
    //     $.ajax({
    //         url : 'controllers/Persona.controller.php',
    //         type: 'GET',
    //         data: datos,
    //         success:function(e){
    //             $(select).html(e);
    //         }
    //     });
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
    function listarSedes(){
        $.ajax({
            url: 'controllers/Sede.controller.php',
            type: 'GET',
            data: 'op=listarSede',
            success: function(e){
                var tabla = $("#tablaSede").DataTable();
                tabla.destroy();
                $("#datosSede").html(e);
                $("#tablaSede").DataTable({
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


    $("#tablaSede").on('click', ".modificar", function(){
        var idsede = $(this).attr('data-idsede');

        $.ajax({
            url: 'controllers/Sede.controller.php',
            type: 'GET',
            data: {
                'op': 'getSede',
                'idsede' : idsede
            },
            success: function(result){                        
                if ($.trim(result) != ""){
                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("<b>Actualizar Sede</b>");
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    div_estado.classList.remove('asignar');
                    // $("#idpersona").prop('disabled', true);
                    // $("#apellidos").prop('disabled', true);

                    var resultado = JSON.parse(result);

                    $("#nombresede").val(resultado[0].nombre_sede);
                    $("#ubicacion").val(resultado[0].ubicacion);
                    $("#select_estado").val(resultado[0].estado);

                    txt_idsede.setAttribute("data-idsede", resultado[0].id_sede);
                }else{
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });
    
    function modificarSede(){
        console.log('id a modificar:', $('#idsede').val());
        let idsede = $("#idsede").attr('data-idsede');
        let nombresede = $("#nombresede").val();
        let ubicacion = $("#ubicacion").val();
        let select_estado = $("#select_estado").val();
        if(nombresede == "" || ubicacion == "" || select_estado == undefined){
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
                        'op'              : 'modificarSede',
                        'idsede'       : idsede,
                        'nombresede'   : nombresede,
                        'ubicacion'       : ubicacion,
                        'select_estado'     : select_estado
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Sede.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            let respuesta = JSON.parse(e);
                            if(respuesta.resultado == 1){
                                mostrarAlerta("success", "¡Modificado con éxito!");
                                $("#formularioSede")[0].reset();
                                $("#Aviso").html("Registrar Sede");
                                botonActualizar.classList.add('asignar');
                                botonGuardar.classList.remove('asignar');
                                div_estado.classList.add('asignar');
                                
                                listarSedes();

                            } else if(respuesta.resultado == 2){
                                mostrarAlerta("error","¡Sede ya registrado!");
                            } else {
                                mostrarAlerta("error","¡Error desconocido al modificar!");
                            }



                        }
                    });
                }
            });
        }
    }

    $("#cancelar").click(function(){
        $("#formularioSede")[0].reset();
        $("#Aviso").html("<b>Registrar Sede</b>");
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        $("#idsede").prop('disabled', false);
        // $("#apellidos").prop('disabled', false);
        div_estado.classList.add('asignar');
    });


    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    

    function nombresedeYaExiste(){
        let nombresede = $("#nombresede").val();

        if(nombresede == ""){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'sedeExistente',
                'nombresede' : nombresede
            };

            $.ajax({
                type: 'GET',
                url: "controllers/Sede.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya está registrado la sede: "+nombresede);
                    }
                    else if(e == 2){
                        registrarSedes();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    
    // cargarPersonas("#idpersona");
    $("#registrar").click(nombresedeYaExiste);
    $("#actualizar").click(modificarSede);
    listarSedes();
});