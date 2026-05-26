$(document).ready(function(){
 
    var txt_idcargo = document.querySelector("#idcargo");
    var div_estado = document.querySelector("#div_estado")
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");
    
    function registrarCargos() {

        let nombrecargo = $("#nombrecargo").val();

        if(nombrecargo == ""){
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
                        'op' : 'registrarCargo',

                        'nombrecargo' : nombrecargo
                    };

                    $.ajax({
                        url: 'controllers/Cargo.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioCargo")[0].reset();
                            listarCargos();
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
    function listarCargos(){
        $.ajax({
            url: 'controllers/Cargo.controller.php',
            type: 'GET',
            data: 'op=listarCargo',
            success: function(e){
                var tabla = $("#tablaCargo").DataTable();
                tabla.destroy();
                $("#datosCargo").html(e);
                $("#tablaCargo").DataTable({
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


    $("#tablaCargo").on('click', ".modificar", function(){
        var idcargo = $(this).attr('data-idcargo');

        $.ajax({
            url: 'controllers/Cargo.controller.php',
            type: 'GET',
            data: {
                'op': 'getCargo',
                'idcargo' : idcargo
            },
            success: function(result){                        
                if ($.trim(result) != ""){
                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("<b>Actualizar Cargo</b>");
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    div_estado.classList.remove('asignar');
                    // $("#idpersona").prop('disabled', true);
                    // $("#apellidos").prop('disabled', true);

                    var resultado = JSON.parse(result);

                    $("#nombrecargo").val(resultado[0].nombre_cargo);
                    $("#select_estado").val(resultado[0].estado);

                    txt_idcargo.setAttribute("data-idcargo", resultado[0].id_cargo);
                }else{
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });
    
    function modificarCargo(){
        console.log('id a modificar:', $('#idcargo').val());
        let idcargo = $("#idcargo").attr('data-idcargo');
        let nombrecargo = $("#nombrecargo").val();
        let select_estado = $("#select_estado").val();
        if(nombrecargo == "" || select_estado == undefined){
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
                        'op'              : 'modificarCargo',
                        'idcargo'       : idcargo,
                        'nombrecargo'   : nombrecargo,
                        'select_estado'     : select_estado
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Cargo.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            let respuesta = JSON.parse(e);
                            if(respuesta.resultado == 1){
                                mostrarAlerta("success", "¡Modificado con éxito!");
                                $("#formularioCargo")[0].reset();
                                $("#Aviso").html("Registrar Cargo");
                                botonActualizar.classList.add('asignar');
                                botonGuardar.classList.remove('asignar');
                                div_estado.classList.add('asignar');
                                
                                listarCargos();

                            } else if(respuesta.resultado == 2){
                                mostrarAlerta("error","¡Cargo ya registrado!");
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
        $("#formularioCargo")[0].reset();
        $("#Aviso").html("<b>Registrar Cargo</b>");
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        $("#idcargo").prop('disabled', false);
        // $("#apellidos").prop('disabled', false);
        div_estado.classList.add('asignar');
    });


    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    

    function nombrecargoYaExiste(){
        let nombrecargo = $("#nombrecargo").val();

        if(nombrecargo == ""){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'cargoExistente',
                'nombrecargo' : nombrecargo
            };

            $.ajax({
                type: 'GET',
                url: "controllers/Cargo.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya está registrado el cargo: "+nombrecargo);
                    }
                    else if(e == 2){
                        registrarCargos();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    
    // cargarPersonas("#idpersona");
    $("#registrar").click(nombrecargoYaExiste);
    $("#actualizar").click(modificarCargo);
    listarCargos();
});