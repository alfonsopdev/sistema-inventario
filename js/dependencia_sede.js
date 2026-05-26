$(document).ready(function(){
 
    var txt_iddependenciasede = document.querySelector("#iddependenciasede");
    var div_estado = document.querySelector("#div_estado")
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");
    
    function registrarDependenciaSede() {

        let select_sede = $("#select_sede").val();
        let select_dependencia = $("#select_dependencia").val();
        if(select_sede == undefined || select_dependencia == undefined){
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
                        'op' : 'registrarDependenciaSede',

                        'select_sede' : select_sede, 
                        'select_dependencia' : select_dependencia
                    };

                    $.ajax({
                        url: 'controllers/DependenciaSede.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioDependenciaSede")[0].reset();
                            // Limpiar los select2
                            $("#select_sede").val(null).trigger('change');
                            $("#select_dependencia").val(null).trigger('change');
                            listarDependenciaSedes();
                        }
                    });
                }
            });
        }
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
    function listarDependenciaSedes(){
        $.ajax({
            url: 'controllers/DependenciaSede.controller.php',
            type: 'GET',
            data: 'op=listarDependenciaSede',
            success: function(e){
                var tabla = $("#tablaDependenciaSede").DataTable();
                tabla.destroy();
                $("#datosDependenciaSede").html(e);
                $("#tablaDependenciaSede").DataTable({
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


    $("#tablaDependenciaSede").on('click', ".modificar", function(){
        var iddependenciasede = $(this).attr('data-iddependenciasede');

        $.ajax({
            url: 'controllers/DependenciaSede.controller.php',
            type: 'GET',
            data: {
                'op': 'getDependenciaSede',
                'iddependenciasede' : iddependenciasede
            },
            success: function(result){                        
                if ($.trim(result) != ""){
                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("Actualizar Dependencia/Sede");
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    div_estado.classList.remove('asignar');
                    // $("#idpersona").prop('disabled', true);
                    // $("#apellidos").prop('disabled', true);

                    var resultado = JSON.parse(result);

                    $("#select_sede").val(resultado[0].id_sede).trigger('change');
                    $("#select_dependencia").val(resultado[0].id_dependencia).trigger('change');
                    $("#select_estado").val(resultado[0].estado).trigger('change');

                    txt_iddependenciasede.setAttribute("data-iddependenciasede", resultado[0].id_dependencia_sede);
                }else{
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });
    
    function modificarDependenciaSede(){
        console.log('id a modificar:', $('#iddependenciasede').val());
        let iddependenciasede = $("#iddependenciasede").attr('data-iddependenciasede');
        let select_sede = $("#select_sede").val();
        let select_dependencia = $("#select_dependencia").val();
        let select_estado = $("#select_estado").val();
        if(select_sede == "" || select_dependencia == "" || select_estado == undefined){
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
                        'op'              : 'modificarDependenciaSede',
                        'iddependenciasede'       : iddependenciasede,
                        'select_sede'   : select_sede,
                        'select_dependencia'       : select_dependencia,
                        'select_estado'     : select_estado
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/DependenciaSede.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            let respuesta = JSON.parse(e);

                            if(respuesta.resultado == 1){
                                
                                mostrarAlerta("success", "¡Modificado con éxito!");
    
                                $("#formularioDependenciaSede")[0].reset();
                                // Limpiar los select2
                                $("#select_sede").val(null).trigger('change');
                                $("#select_dependencia").val(null).trigger('change');
                                $("#Aviso").html("Registrar Dependencia/Sede");
                                botonActualizar.classList.add('asignar');
                                botonGuardar.classList.remove('asignar');
                                div_estado.classList.add('asignar');
                                
                                listarDependenciaSedes();
                            }else if(respuesta.resultado == 2){
                                mostrarAlerta("error","¡Relación Sede-Dependencia ya registrado!");
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
        $("#formularioDependenciaSede")[0].reset();

        // Limpiar los select2
        $("#select_sede").val(null).trigger('change');
        $("#select_dependencia").val(null).trigger('change');

        $("#Aviso").html("Registrar Dependencia/Sede");
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        $("#iddependenciasede").prop('disabled', false);
        // $("#apellidos").prop('disabled', false);
        div_estado.classList.add('asignar');
    });


    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    

    function relacionDependenciaSedeExistente(){
        let idsede = $("#select_sede").val();
        let iddependencia = $("#select_dependencia").val();

        if(idsede == undefined || iddependencia == undefined){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'relacionDependenciaSedeExistente',
                'idsede' : idsede,
                'iddependencia' : iddependencia
            };

            $.ajax({
                type: 'GET',
                url: "controllers/DependenciaSede.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya está registrado la relacion de dependencia y sede");
                    }
                    else if(e == 2){
                        registrarDependenciaSede();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    
    cargarSede("#select_sede");
    cargarDependencias("#select_dependencia");
    $("#registrar").click(relacionDependenciaSedeExistente);
    $("#actualizar").click(modificarDependenciaSede);
    listarDependenciaSedes();
});