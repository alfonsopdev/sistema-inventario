$(document).ready(function(){
 
    var txt_iddependencia = document.querySelector("#iddependencia");
    var div_estado = document.querySelector("#div_estado")
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");
    
    function registrarDependencias() {

        let nombredependencia = $("#nombredependencia").val();
        let descripcion = $("#descripcion").val();
        if(nombredependencia == "" || descripcion == ""){
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
                        'op' : 'registrarDependencia',

                        'nombredependencia' : nombredependencia, 
                        'descripcion' : descripcion
                    };

                    $.ajax({
                        url: 'controllers/Dependencia.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioDependencia")[0].reset();
                            listarDependencias();
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
    function listarDependencias(){
        $.ajax({
            url: 'controllers/Dependencia.controller.php',
            type: 'GET',
            data: 'op=listarDependencia',
            success: function(e){
                var tabla = $("#tablaDependencia").DataTable();
                tabla.destroy();
                $("#datosDependencia").html(e);
                $("#tablaDependencia").DataTable({
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


    $("#tablaDependencia").on('click', ".modificar", function(){
        var iddependencia = $(this).attr('data-iddependencia');

        $.ajax({
            url: 'controllers/Dependencia.controller.php',
            type: 'GET',
            data: {
                'op': 'getDependencia',
                'iddependencia' : iddependencia
            },
            success: function(result){                        
                if ($.trim(result) != ""){
                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("Actualizar Dependencia");
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    div_estado.classList.remove('asignar');
                    // $("#idpersona").prop('disabled', true);
                    // $("#apellidos").prop('disabled', true);

                    var resultado = JSON.parse(result);

                    $("#nombredependencia").val(resultado[0].nombre_dependencia);
                    $("#descripcion").val(resultado[0].descripcion);
                    $("#select_estado").val(resultado[0].estado);

                    txt_iddependencia.setAttribute("data-iddependencia", resultado[0].id_dependencia);
                }else{
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });
    
    function modificarDependencia(){
        console.log('id a modificar:', $('#iddependencia').val());
        let iddependencia = $("#iddependencia").attr('data-iddependencia');
        let nombredependencia = $("#nombredependencia").val();
        let descripcion = $("#descripcion").val();
        let select_estado = $("#select_estado").val();
        if(nombredependencia == "" || descripcion == "" || select_estado == undefined){
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
                        'op'              : 'modificarDependencia',
                        'iddependencia'       : iddependencia,
                        'nombredependencia'   : nombredependencia,
                        'descripcion'       : descripcion,
                        'select_estado'     : select_estado
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Dependencia.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            mostrarAlerta("success", "¡Modificado con éxito!");

                            $("#formularioDependencia")[0].reset();
                            $("#Aviso").html("Registrar Dependencia");
                            botonActualizar.classList.add('asignar');
                            botonGuardar.classList.remove('asignar');
                            div_estado.classList.add('asignar');
                            
                            listarDependencias();
                        }
                    });
                }
            });
        }
    }

    $("#cancelar").click(function(){
        $("#formularioDependencia")[0].reset();
        $("#Aviso").html("Registrar Dependencia");
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        $("#iddependencia").prop('disabled', false);
        // $("#apellidos").prop('disabled', false);
        div_estado.classList.add('asignar');
    });


    function resetearFormulario(){
        $("#formularioActualizarContraseña")[0].reset();
    }

    

    function nombredependenciaYaExiste(){
        let nombredependencia = $("#nombredependencia").val();

        if(nombredependencia == ""){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'dependenciaExistente',
                'nombredependencia' : nombredependencia
            };

            $.ajax({
                type: 'GET',
                url: "controllers/Dependencia.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya está registrado la dependencia: "+nombredependencia);
                    }
                    else if(e == 2){
                        registrarDependencias();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    
    // cargarPersonas("#idpersona");
    $("#registrar").click(nombredependenciaYaExiste);
    $("#actualizar").click(modificarDependencia);
    listarDependencias();
});