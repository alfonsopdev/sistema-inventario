<?php
require_once '../datatable.php';
require_once '../acceso-seguro.php';
if ($_SESSION['nivelacceso'] == 'Médico') {
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
        <h3 class="mb-4 mt-2 text-center" style="font-size: 25px"><b>Movimiento de los Activos</b></h3>

        <!-- FILTROS -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title text-navy"><b>Filtros</b> <i class="fa fa-filter text-navy"></i></h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: block;">
                <!-- Filtros -->
                <form class="row m-2 justify-content-center" id="formFiltros" style="gap:10px;">
                    <!-- <div class="col-md-2 p-0">
                            <label>Fecha Inicio <i class="fas fa-filter"></i></label>
                            <input type="date" id="reporte_fecha_inicio" class="form-control">
                        </div>
                        <div class="col-md-2 p-0">
                            <label>Fecha Fin <i class="fas fa-filter"></i></label>
                            <input type="date" id="reporte_fecha_fin" class="form-control">
                        </div> -->
                    <!-- <label class="mr-5"><i class="fas fa-filter"></i>Filtros: </label> -->
                    <div class="col-md-2 p-0">
                        <label>Fecha Inicio</label>
                        <input type="date" id="reporte_fecha_inicio" class="form-control">
                    </div>
                    <div class="col-md-2 p-0">
                        <label>Fecha Fin</label>
                        <input type="date" id="reporte_fecha_fin" class="form-control">
                    </div>

                    <div class="col-md-2 p-0">
                        <label>Responsable:</label>
                        <select name="filtro_mov_responsable" id="filtro_mov_responsable" class="form-control select2">
                        </select>
                    </div>

                    <div class="col-md-2 p-0">
                        <label>Tipo Movimiento:</label>
                        <select name="filtro_calidad" id="filtro_calidad" class="form-control">
                            <option value="">Todos</option>
                            <option value="PRESTAMO">Préstamo</option>
                            <option value="DEVOLUCION">Devolución</option>
                            <option value="TRANSFERENCIA">Transferencia</option>
                        </select>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>

        <!-- LISTA DE MOVIMIENTOS -->
        <div class=" card">
            <div class="card-header">
                <p class="card-title text-navy" style="font-size: 22px"><b>Lista de Movimientos</b></p>
                <!-- <a type="button" class="btn btn-sm bg-success float-right" data-toggle='modal' data-target="#modal_registrar" href="#"><i class="fas fa-plus"></i>&nbsp; Registrar</a> -->
            </div>

            <!-- <div class="d-flex m-3 align-items-center" style="gap:10px;">
                <span class="text-info">Activos a mover: </span>

                <div id="contenedorSeleccionados" class="d-flex flex-wrap" style="gap: 10px;">
                     <span style="font-size: 12px;">Ninguno seleccionado</span>
                </div>

                <a type="button" id="btn_mov_registrar" class="btn btn-sm bg-info float-right asignar" href="#"><i class="fas fa-check"></i></a>
            </div>
            <div class="d-flex justify-content-end mr-4 mt-4" style="gap: 8px; align-items:center;">
                <strong>Activos en Calidad de:&nbsp;</strong>

                <span class="bg-suave-azul">
                    Préstamo
                </span>

                <span><b>|</b></span>

                <span class="bg-suave-ambar">
                    Transferencia
                </span>

                <span><b>|</b></span>

                <span><b>Disponible</b></span>
            </div> -->

            <div class="card-body table-responsive">

                <table class="table text-center table-hover" id="tablaMovimiento">
                    <thead style="background-color: #E3ECF5;">
                        <tr>
                            <th rowspan="2" class="text-center">N°</th>
                            <th rowspan="2" class="text-center">Cod</th>
                            <th rowspan="2" class="text-center">Tipo Movimiento</th>
                            <th colspan="2" class="text-center">Ubicación</th>
                            <th colspan="2" class="text-center">Responsables</th>
                            <th rowspan="2" class="text-center">Fecha</th>
                            <th rowspan="2" class="text-center">Doc.</th>
                            <th rowspan="2" class="text-center">Tiempo</th>
                            <th rowspan="2" class="text-center">Acción</th>
                        </tr>
                        <tr>
                            <th class="text-center">Antes</th>
                            <th class="text-center">Actual</th>
                            <th class="text-center">Resp. Movimiento</th>
                            <th class="text-center">Resp. Actual</th>
                        </tr>
                    </thead>
                    <tbody class="table" id="datosMovimiento">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Movimiento Devolucion -->
<div class="modal fade" id="modalMovimientoDevActivos" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formMovimientoDev" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><b>Devolucion del Activo: <span class="text-danger" id="cod_patrimonial_dev_activos"></span></b></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="mov_idactivos_dev">

                    <div class="form-group">
                        <label>Tipo de movimiento:</label>
                        <div class="form-group">
                            <select name="tipo" id="mov_idtipo_dev" class="form-control" readonly>
                                <option value="DEVOLUCION" readonly selected>Devolución</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Fecha movimiento:</label>
                        <input type="date" class="form-control" name="fecha" id="mov_fecha_dev_ac" readonly>
                    </div>

                    <input id="idresponsable_dev_ac" hidden>

                    <div class="form-group">
                        <label>Responsable:</label>
                        <input type="text" class="form-control" id="pres_responsable_dev_ac" readonly>
                    </div>
                    <div class="form-group">
                        <label>Activos prestados:</label>
                        <div id="contenedorSeleccionadosDev" class="d-flex flex-wrap gap-2"></div>
                    </div>
                    <div class="form-group">
                            <label>Documento de sustento (PDF)</label>
                            <input type="file"
                                class="form-control"
                                id="doc_dev_ac"
                                name="doc_dev_ac"
                                accept="application/pdf"
                                required>
                            <small class="text-muted">Máximo 5 MB</small>
                    </div>
                    <div class="form-group">
                        <label>Motivo / Sustento:</label>
                        <textarea class="form-control" id="devolucion_motivo_ac" rows="3" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar_mov_dev_ac">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="registrar_mov_dev_ac">Registrar Devolución</button>
                </div>

            </div>
        </form>
    </div>
</div>


<script src="js/movimiento.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
    $("#tablaMovimiento").on("click", ".ver", function() {
        let idmovimiento = $(this).data("idmovimiento");

        // Redirigir a la página detalle
        window.location.href = "main.php?view=movimiento/view_detalle_movimiento.php&id=" + idmovimiento;
    });

</script>