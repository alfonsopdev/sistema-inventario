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
    <div class="col-md-12">
        <h3 class="mb-3" style="font-size: 22px" ><b>Gestión De Dependencias / Áreas</b></h3>
        <div class=" card card-outline">
            <!-- <div class="card-header">
                <p class="card-title mt-1" style="font-size: 22px" id="Aviso">Registrar Dependencia</p>
            </div> -->
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-10">
                        <form action="" id="formularioDependencia">
                            <div class="form-group mb-0">
                                <div class="row">
                                    <!-- <div class="col-md-12">
                                        <label for="nombres">Nombres:</label>
                                        <input id="nombres" class="form-control form-control-border">
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label for="apellidos">Apellidos:</label>
                                        <input id="apellidos" class="form-control form-control-border">
                                    </div> -->
                                    <div class="col-md-4">
                                        <label for="nombredependencia">Nombre de dependencia:</label>
                                        <input id="nombredependencia" class="form-control" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="descripcion" id="label_contrasena">Descripción:</label>
                                        <textarea id="descripcion" class="form-control" rows="1" ></textarea>
                                    </div>
                                    <div class="col-md-2 asignar" id="div_estado">
                                        <label for="">Estado:</label>
                                        <select name="" id="select_estado" class="form-control">
                                            <option value="" selected disabled>-- Seleccione estado --</option>
                                            <option value="ACTIVO">ACTIVO</option>
                                            <option value="INACTIVO">INACTIVO</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="iddependencia">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 d-flex align-items-end justify-content-end">
                        <div class="text-right bg-white">
                            <button type="reset" class="btn bg-secondary" id="cancelar">Cancelar</button>
                            <button type="button" class="btn btn-info" id="registrar">Guardar</button>
                            <button type="button" class="btn bg-info asignar" id="actualizar">Actualizar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <!-- <div class="card-footer text-right bg-white">
                <button type="reset" class="btn bg-gradient-secondary" id="cancelar">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="registrar">Guardar</button>
                <button type="button" class="btn bg-gradient-info asignar" id="actualizar">Actualizar</button>
            </div> -->
            <!-- /.card-footer -->
        </div>
    </div>

    <div class="col-md-12">
        <div class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title" style="font-size: 20px"><b>Lista de Dependencias</b></p>
            </div>
            <div class="card-body table-responsive">
                <table class="table" id="tablaDependencia">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nombre Dependencia</th>
                            <th class="text-center">Descripción</th>
                            <!-- <th class="text-center">Cargo</th> -->
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosDependencia">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="js/Dependencia.js"></script>