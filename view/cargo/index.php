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
                <p class="card-title mt-1 text-info" style="font-size: 22px" id="Aviso"><b>Registrar Cargo</b></p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" id="formularioCargo">
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
                                <label for="nombrecargo">Nombre de Cargo:</label>
                                <input id="nombrecargo" class="form-control form-control-border">
                            </div>
                            <div class="col-12 mt-3 asignar" id="div_estado">
                                <label for="">Estado:</label>
                                <select name="" id="select_estado" class="form-control form-control-border">
                                    <option value="" selected disabled>-- Seleccione estado --</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                            </div>
                            <input type="hidden" id="idcargo">
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
                <p class="card-title text-info" style="font-size: 22px"><b>Lista de Cargos</b></p>
            </div>
            <div class="card-body table-responsive">
                <table class="table" id="tablaCargo">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nombre Cargo</th>
                            <!-- <th class="text-center">Fecha Registro</th> -->
                            <!-- <th class="text-center">Cargo</th> -->
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosCargo">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="js/cargo.js"></script>