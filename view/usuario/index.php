<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] != "ADMINISTRADOR") {
    echo "<strong>No tiene el nivel de acceso requerido</strong>";
    exit();
}
?>
<style>
    .asignar {
        display: none !important;
    }
</style>

<div class="row">
    <div class="col-md-3">
        <div class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title mt-1" style="font-size: 22px" id="Aviso">Registro de Usuario</p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" id="formularioUsuario">
                    <div class="form-group">
                        <div class="row">
                            <!-- <div class="col-md-12">
                                <label for="nombres">Nombres:</label>
                                <input id="nombres" class="form-control form-control-border">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="apellidos">Apellidos:</label>
                                <input id="apellidos" class="form-control form-control-border">
                            </div> -->
                            <div class="col-md-12 mt-3">
                                <label for="nombreusuario">Nombre de usuario:</label>
                                <input id="nombreusuario" class="form-control form-control-border">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="contrasena" id="label_contrasena">Contraseña:</label>
                                <input id="contrasena" class="form-control form-control-border">
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="idpersona">Persona:</label>
                                <select class="form-control form-control-border" name="idpersona" id="idpersona">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="nivelacceso">Nivel de acceso:</label>
                                <select id="nivelacceso" class="form-control form-control-border">
                                    <option value="" selected disabled>-- Seleccione --</option>
                                    <option value="ADMINISTRADOR">ADMINISTRADOR</option>
                                    <option value="OPERADOR">OPERADOR</option>
                                </select>
                                <input type="hidden" id="idusuariomod">
                            </div>
                        </div>
                    </div>
                    <p>Nota: La contraseña por defecto será 123456</p>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right bg-white">
                <button type="reset" class="btn bg-gradient-secondary" id="cancelar">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="registrar">Guardar</button>
                <button type="button" class="btn bg-gradient-info asignar" id="actualizar">Actualizar</button>
            </div>
            <!-- /.card-footer -->
        </div>
    </div>
    <div class="col-md-9">
        <div class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title" style="font-size: 22px">Lista de Usuarios</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table" id="tablaUsuario">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Acceso</th>
                            <th class="text-center">Dependencia</th>
                            <!-- <th class="text-center">Cargo</th> -->
                            <th class="text-center">Persona</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Operaciones</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosUsuario">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar la contraseña del usuario -->
<div class="modal fade" id="modal_contra" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title-h4" id="staticBackdropLabel"><b>Editar Contraseña del Usuario</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="formularioActualizarContraseña">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="claveactual">Contraseña Actual</label>
                                <input type="text" class="form-control form-control-border" id="claveactual" placeholder="Escriba su contraseña actual">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clavenueva">Contraseña Nueva</label>
                        <input type="text" class="form-control form-control-border" id="clavenueva" placeholder="Escriba su nueva contraseña">
                    </div>
                    <div class="form-group">
                        <label for="clavenuevaconfirmada">Confirmar contraseña nueva</label>
                        <input type="text" class="form-control form-control-border" id="clavenuevaconfirmada" placeholder="Repita su nueva contraseña">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="reset" class="btn bg-gradient-secondary " id="cancelar" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="cambiarclave">Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/usuarios.js"></script>