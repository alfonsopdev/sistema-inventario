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
                <p class="card-title mt-1" style="font-size: 22px" id="Aviso">Registrar Administrativo</p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" id="formularioAdministrativo">
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
                                <label for="select_persona">Persona:</label>
                                <select id="select_persona" name="select_persona" class="form-control form-control-border">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="select_sede">Sede:</label>
                                <select id="select_sede" name="select_sede" class="form-control form-control-border">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3 asignar" id="div_dependencia">
                                <label for="select_dependencia">Dependencia:</label>
                                <select id="select_dependencia" name="select_dependencia" class="form-control form-control-border">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="select_cargo">Cargo:</label>
                                <select id="select_cargo" name="select_cargo" class="form-control form-control-border">
                                </select>
                            </div>
                            <div class="col-md-12 mt-3 asignar" id="select_estado">
                                <label for="estado">Estado:</label>
                                <select id="estado" name="" class="form-control form-control-border">
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                            </div>
                            <input type="hidden" id="idadministrativo">
                        </div>
                    </div>
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
                <p class="card-title" style="font-size: 22px">Lista de Administrativos</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table" id="tablaAdministrativo">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Persona</th>
                            <th class="text-center">Sede</th>
                            <th class="text-center">Dependencia</th>
                            <th class="text-center">Cargo</th>
                            <!-- <th class="text-center">Cargo</th> -->
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosAdministrativo">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script src="js/administrativo.js"></script>