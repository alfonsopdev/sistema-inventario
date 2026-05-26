$(document).ready(function(){

    var txt_categoria = document.querySelector("#idcategoria");
    var div_estado = document.querySelector("#div_estado");
    var botonActualizar = document.querySelector("#actualizar");
    var botonGuardar = document.querySelector("#registrar");

    function listarCategoria(){
        $.ajax({
            url: 'controllers/Categoria.controller.php',
            type: 'GET',
            data: 'op=ListarCategorias',
            success: function(e){
                var tabla = $("#tablaCategoria").DataTable();
                tabla.destroy();
                $("#datosCategoria").html(e);
                $("#tablaCategoria").DataTable({
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
                                columns: [ 0, 1, 2, 3,4,5 ],
                                stripHtml: false, /* Aquí indicamos que no se eliminen las imágenes */
                            }
                        }
                    ]
                });
            }
        });
    }

    function registrarCategorias(){
        var nombre_categoria = $("#nombrecategoria").val();
        var descripcion_categoria = $("#descripcion_categoria").val();
        // var fotografia = $("#fotografia")[0].files[0];
        
        if(nombre_categoria == "" || descripcion_categoria == ""){
            mostrarAlerta("warning", "¡Completar los campos necesarios!");
        }else{
            Swal.fire({
                icon:'question',
                title:'¿Está seguro de registrar?',
                showCancelButton: true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Aceptar'
            }).then((result) => {
                
                if(result.isConfirmed){
                    var datos = {
                        'op' : 'registrarCategoria',
                        'nombre_categoria' : nombre_categoria, 
                        'descripcion_categoria' : descripcion_categoria
                    };

                    $.ajax({
                        url : 'controllers/Categoria.controller.php',
                        type: 'GET',
                        data: datos,   
                        // contentType: false,
                        // processData: false,
                        // cache: false,                     
                        success: function(e){
                            mostrarAlerta("success", "¡Registrado con éxito!");
                            $("#formularioCategoria")[0].reset();
                            listarCategoria();
                        }
                    });
                }
            });
        }
    }

    $("#tablaCategoria").on('click', ".modificar", function(){
        var idcategoria = $(this).attr('data-idcategoria');

        $.ajax({
            url: 'controllers/Categoria.controller.php',
            type: 'GET',
            data: {
                'op': 'getCategoria',
                'idcategoria' : idcategoria
            },
            success: function(result){                        
                if ($.trim(result) != ""){
                    //Asiganamos y quitamos la clase que muestra la caja de texto
                    $("#Aviso").html("<b>Actualizar Categoría</b>");
                    botonActualizar.classList.remove('asignar');
                    botonGuardar.classList.add('asignar');
                    div_estado.classList.remove('asignar');
                    // $("#idpersona").prop('disabled', true);
                    // $("#apellidos").prop('disabled', true);

                    var resultado = JSON.parse(result);

                    $("#nombrecategoria").val(resultado[0].nombre_categoria);
                    $("#descripcion_categoria").val(resultado[0].descripcion);
                    $("#select_estado").val(resultado[0].estado);

                    txt_categoria.setAttribute("data-idcategoria", resultado[0].id_categoria);
                }else{
                    mostrarAlerta("warning", "¡No encontramos registros!");
                }
            }
        });
    });



    $("#cancelar").click(function(){
        $("#formularioCategoria")[0].reset();
        $("#Aviso").html("<b>Registrar Categoría</b>");
        botonActualizar.classList.add('asignar');
        botonGuardar.classList.remove('asignar');
        $("#idcategoria").prop('disabled', false);
        // $("#apellidos").prop('disabled', false);
        div_estado.classList.add('asignar');
    });

    function nombrecategoriaYaExiste(){
        let nombrecategoria = $("#nombrecategoria").val();

        if(nombrecategoria == ""){
            mostrarAlerta("warning", "¡Complete los datos necesarios!");
        }else {
            var datos = {
                'op' : 'categoriaYaExiste',
                'nombrecategoria' : nombrecategoria
            };

            $.ajax({
                type: 'GET',
                url: "controllers/Categoria.controller.php",
                data: datos,
                success: function(e){
                    if(e == 1){
                        mostrarAlerta("error", "¡Ya está registrado la categoría: "+nombrecategoria);
                    }
                    else if(e == 2){
                        registrarCategorias();
                    }else{
                        mostrarAlerta("error", "¡A ocurrido un error!");
                    }
                }
            });
        }
    }

    function modificarCategoria(){
        console.log('id a modificar:', $('#idcategoria').val());
        let idcategoria = $("#idcategoria").attr('data-idcategoria');
        let nombrecategoria = $("#nombrecategoria").val();
        let descripcion = $("#descripcion_categoria").val();
        let select_estado = $("#select_estado").val();
        if(nombrecategoria == "" || descripcion == "" || select_estado == undefined){
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
                        'op'              : 'modificarCategoria',
                        'idcategoria'       : idcategoria,
                        'nombrecategoria'   : nombrecategoria,
                        'descripcion'       : descripcion,
                        'select_estado'     : select_estado
                    };
                    console.log(datos);
                    $.ajax({
                        url: 'controllers/Categoria.controller.php',
                        type:'GET',
                        data: datos,
                        success:function(e){
                            let respuesta = JSON.parse(e);
                            if(respuesta.resultado == 1){
                                mostrarAlerta("success", "¡Modificado con éxito!");
                                $("#formularioCategoria")[0].reset();
                                $("#Aviso").html("Registrar Categoría");
                                botonActualizar.classList.add('asignar');
                                botonGuardar.classList.remove('asignar');
                                div_estado.classList.add('asignar');
                                
                                listarCategoria();

                            } else if(respuesta.resultado == 2){
                                mostrarAlerta("error","¡Categoría ya registrado!");
                            } else {
                                mostrarAlerta("error","¡Error desconocido al modificar!");
                            }



                        }
                    });
                }
            });
        }
    }


    listarCategoria();
    $("#registrar").click(nombrecategoriaYaExiste);
    $("#actualizar").click(modificarCategoria);
});