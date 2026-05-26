<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] == 'OPERADOR') {
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
        <div class=" card card-outline card-info">
            <div class="card-header">
                <p class="card-title" style="font-size: 22px">Lista de Personas</p>
                <a type="button" class="btn btn-sm bg-success float-right" data-toggle='modal' data-target="#modal_persona" href="#"><i class="fas fa-plus"></i>&nbsp; Registrar</a>
            </div>

            <div class="card-body table-responsive">
                <table class="table text-center" id="tablaPersona">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">DNI</th>
                            <th class="text-center">Persona</th>
                            <th class="text-center">Celular</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Dirección</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosPersona">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!-- Modal Registrar -->
<div class="modal fade" id="modal_persona" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><b>Registro de Persona</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-4 mb-3">
                        <label for="">Nro Documento</label>
                        <input type="text" class="form-control form-control-border" maxlength="8" id="txt_nro" onkeypress="return soloNumeros(event)">
                    </div>
                    <div class="col-8 mb-3">
                        <label for="">Nombres</label>
                        <input type="text" class="form-control form-control-border" id="txt_nom" onkeypress="return sololetras(event)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Apellido Paterno</label>
                        <input type="text" class="form-control form-control-border" id="txt_apepa" onkeypress="return sololetras(event)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Apellido Materno</label>
                        <input type="text" class="form-control form-control-border" id="txt_apema" onkeypress="return sololetras(event)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Fecha Nacimiento</label>
                        <input type="date" class="form-control form-control-border" id="txt_fnac">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Movil</label>
                        <input type="text" class="form-control form-control-border" id="txt_movil" onkeypress="return soloNumeros(event)">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="">Dirección</label>
                        <input type="text" class="form-control form-control-border" id="txt_dire">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="">Email</label>
                        <input type="text" class="form-control form-control-border" id="txt_email">
                    </div>
                </div>

            </div>
            <div class="modal-footer pl-4 pr-4">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="registrar">Registrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Editing Area -->
<div class="modal fade" id="modal_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Editar datos de la Persona</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4 mb-3">
                        <input type="text" id="txt_idpersona" hidden>
                        <label for="">Nro Documento:</label>
                        <input type="text" class="form-control form-control-border" maxlength="8" id="txt_nro_editar" onkeypress="return soloNumeros(event)">
                        <span id="error_dni" style="color: red; font-size: 14px;"></span>
                    </div>
                    <div class="col-8 mb-3">
                        <label for="">Nombres:</label>
                        <input type="text" class="form-control form-control-border" id="txt_nom_editar" onkeypress="return sololetras(event)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Apellido Paterno:</label>
                        <input type="text" class="form-control form-control-border" id="txt_apepa_editar" onkeypress="return sololetras(event)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Apellido Materno:</label>
                        <input type="text" class="form-control form-control-border" id="txt_apema_editar" onkeypress="return sololetras(event)">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Fecha Nacimiento:</label>
                        <input type="date" class="form-control form-control-border" id="txt_fnac_editar">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="">Movil:</label>
                        <input type="text" class="form-control form-control-border" id="txt_movil_editar" onkeypress="return soloNumeros(event)">
                    </div>
                    <div class="col-12 mb-3">
                        <label for="">Dirección:</label>
                        <input type="text" class="form-control form-control-border" id="txt_dire_editar">
                    </div>
                    <div class="col-8 mb-3">
                        <label for="">Email:</label>
                        <input type="text" class="form-control form-control-border" id="txt_email_editar">
                    </div>
                    <div class="col-4 mb-3">
                        <label for="">Estado:</label>
                        <select name="" id="select_estatus_editar" class="form-control form-control-border">
                            <option value="ACTIVO">ACTIVO</option>
                            <option value="INACTIVO">INACTIVO</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnModificarPersona">MODIFICAR</button>
            </div>
        </div>
    </div>
</div>


<script src="js/Persona.js"></script>

<script>
    $('#modal_persona').on('shown.bs.modal', function() {
        $('#txt_nro').trigger('focus')
    });
    $('#modal_editar').on('shown.bs.modal', function() {
        $('#txt_idpersona').trigger('focus')
    });
</script>