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
                <p class="text-title mt-1 font-weight-bold" style="font-size: 22px" id="Aviso">Registrar Dependencia/Sede</p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" id="formularioDependenciaSede">
                    <div class="form-group">
                        <div class="row">

                            <div class="col-12 mt-3" id="div_sede">
                                <label for="select_sede">Sede:</label>
                                <select name="select_sede" id="select_sede" class="form-control select2" style="width: 100%;">

                                </select>
                            </div>
                            <div class="col-12 mt-3" id="div_dependencia">
                                <label for="select_dependencia">Dependencia:</label>
                                <select name="select_dependencia" id="select_dependencia" class="form-control select2" style="width: 100%;">

                                </select>
                            </div>
                            <div class="col-12 mt-3 asignar" id="div_estado">
                                <label for="">Estado:</label>
                                <select name="" id="select_estado" class="form-control">
                                    <option value="" selected disabled>-- Seleccione estado --</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                            </div>
                            <input type="hidden" id="iddependenciasede">
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-right bg-white">
                <button type="button" class="btn bg-gradient-secondary" id="cancelar">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="registrar">Guardar</button>
                <button type="button" class="btn bg-gradient-info asignar" id="actualizar">Actualizar</button>
            </div>
            <!-- /.card-footer -->
        </div>
    </div>
    <div class="col-md-9">
        <div class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title font-weight-bold" style="font-size: 22px" >Lista de Dependencia/Sede</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table" id="tablaDependenciaSede">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nombre Sede</th>
                            <th class="text-center">Nombre Dependencia</th>
                            <!-- <th class="text-center">Cargo</th> -->
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosDependenciaSede">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script src="js/dependencia_sede.js"></script>
<script>
    $(document).ready(()=>{
        $('.select2').select2();
    })
</script>