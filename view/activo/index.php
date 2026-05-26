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
        <div class="row mb-2">
            <div class="col-md-11">
                <h3 class="mt-3" style="font-size: 25px"><b>Gestión De Activos</b></h3>
            </div><!-- /.col -->
            <div class="col-md-1">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item text-center"><i class="nav-icon fas fa-box"></i>&nbsp; Activos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->

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
                        <label>Categoría:</label>
                        <select name="filtro_categoria" id="filtro_categoria" class="form-control select2">
                        </select>
                    </div>

                    <div class="col-md-2 p-0">
                        <label>Responsable:</label>
                        <select name="filtro_responsable" id="filtro_responsable" class="form-control select2">
                        </select>
                    </div>

                    <div class="col-md-2 p-0">
                        <label>En calidad de:</label>
                        <select name="filtro_calidad" id="filtro_calidad" class="form-control">
                            <option value="">Todos</option>
                            <option value="PRESTADO">Préstamo</option>
                            <option value="DISPONIBLE">Disponible</option>
                            <option value="TRANSFERIDO">Transferencia</option>
                        </select>
                    </div>

                    <div class="col-md-2 p-0">
                        <label>Sede:</label>
                        <select name="filtro_sede" id="filtro_sede" class="form-control select2">

                        </select>
                    </div>

                    <div class="col-md-2 p-0">
                        <label>Dependencia:</label>
                        <select name="filtro_dependencia" id="filtro_dependencia" class="form-control select2">

                        </select>
                    </div>

                    <div class="col-md-1 p-0">
                        <label>Estado:</label>
                        <select name="filtro_estado" id="filtro_estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="BUENO">Bueno</option>
                            <option value="REGULAR">Regular</option>
                            <option value="MALO">Malo</option>
                        </select>
                    </div>

                    <div class="col-md-2 p-0">
                        <label>Garantía:</label>
                        <select name="filtro_garantia" id="filtro_garantia" class="form-control">
                            <option value="">Todas</option>
                            <option value="VIGENTE">Vigente</option>
                            <option value="PROXIMO_A_VENCER">Próximo a vencer</option>
                            <option value="VENCIDA">Vencida</option>
                            <option value="SIN_GARANTIA">Sin garantía</option>
                        </select>
                    </div>

                    <!-- <button type="button" id="bfiltro" class="btn btn-secondary">
                                            <i class="fas fa-search"></i>
                                        </button> -->
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <div class=" card">
            <div class="card-header">
                <p class="card-title text-navy" style="font-size: 22px"><b>Lista de Activos</b></p>
                <div class="float-right d-flex" style="gap: 6px;">
                    <a type="button" class="btn btn-sm bg-info" id="btnImportarExcel" data-toggle="modal" data-target="#modalImportarExcel">
                        <i class="fas fa-file-excel"></i>&nbsp; Importar Excel
                    </a>
                    <a type="button" class="btn btn-sm bg-success" data-toggle='modal' data-target="#modal_registrar" href="#">
                        <i class="fas fa-plus"></i>&nbsp; Registrar
                    </a>
                </div>
            </div>

            <div class="d-flex m-3 align-items-center" style="gap:10px;">
                <span class="text-info"><b>Activos a mover: </b></span>

                <div id="contenedorSeleccionados" class="d-flex flex-wrap" style="gap: 10px;">
                    <!-- Aquí se agregan los activos seleccionados dinámicamente -->
                    <small style="font-size: 12px;">Ninguno seleccionado</small>
                </div>

                <a type="button" id="btn_mov_registrar" class="btn btn-sm bg-info float-right asignar" href="#"><i class="fas fa-check"></i></a>
            </div>
            <div class="d-flex justify-content-end mr-4 mt-4" style="gap: 8px; align-items:center;">
                <strong>Activos en Calidad de:&nbsp;</strong>

                <!-- Préstamo: fondo azul claro + texto azul oscuro -->
                <span class="bg-suave-azul">
                    Préstamo
                </span>

                <span><b>|</b></span>

                <!-- Transferencia: fondo crema claro + texto naranja oscuro -->
                <span class="bg-suave-ambar">
                    Transferencia
                </span>

                <span><b>|</b></span>

                <span><b>Disponible</b></span>
            </div>

            <div class="card-body table-responsive">

                <table class="table text-center table-hover" id="tablaActivo">
                    <thead class="bg-navy">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Mover</th>
                            <th class="text-center">Foto</th>
                            <th class="text-center">Código Patrimonial</th>
                            <th class="text-center">Categoría</th>
                            <th class="text-center">Marca / Modelo</th>
                            <th class="text-center">Sede</th>
                            <th class="text-center">Dependencia</th>
                            <th class="text-center">Responsable</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Garantía</th>
                            <th class="text-center">Orden de Compra</th>
                            <th class="text-center">Acción</th>

                        </tr>
                    </thead>
                    <tbody class="table" id="datosActivo">
                        <!-- Se carga de manera dinamica -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalrestock" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar un restock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="border p-2">
                                <legend class="w-auto" style="font-size:12px">Complete los datos correctamente</legend>
                                <form action="" id="formularioRestock" class="p-4">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="idcategoriamodal">Categoría</label>
                                                <select class="form-control" name="idcategoria" id="idcategoriamodal">
                                                </select>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="idproductomodal">Producto</label>
                                                <select class="form-control" name="idproductomodal" id="idproductomodal">
                                                </select>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="number" min="1" class="form-control" id="cantidad">
                                            </div>
                                            <div class="col-md-12">
                                                <label for="detallereestock">Detalle</label>
                                                <textarea class="form-control" id="detallereestock"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="card-footer text-right bg-white">
                                    <button type="button" class="btn bg-gradient-secondary" id="cancelarRestock">Cancelar</button>
                                    <button type="button" class="btn bg-gradient-info" id="btnRegistrarRestock">Registrar</button>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal Registrar -->
<div class="modal fade" id="modal_registrar" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="staticBackdropLabel" style="font-weight: bold;">Registro de Activo</h5>
                <div class="m-0 p-0">
                    <a type="button" class="text-danger" id="borrar_datos_modal">
                        <i class="fa fa-eraser"></i>
                    </a>
                    <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-4">
                <form class="m-0 p-0" id="formularioActivo">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label for="idcategoria">Categoría:</label>
                            <select class="form-control select2" name="idcategoria" id="idcategoria" style="width: 100%;">
                            </select>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="txt_marca">Marca:</label>
                            <input type="text" class="form-control" id="txt_marca" onkeypress="return sololetras(event)">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="txt_modelo">Modelo:</label>
                            <input type="text" class="form-control" id="txt_modelo" onkeypress="return sololetras(event)">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label for="txt_serie">Nro. Serie:</label>
                            <input type="text" class="form-control" id="txt_serie" onkeypress="return sololetras(event)">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="txt_patrimonial">Codigo Patrimonial:</label>
                            <input type="text" class="form-control" id="txt_patrimonial">
                        </div>
                        <div class="col-sm-5 mb-3">
                            <label for="select_responsable">Responsable Administrativo:</label>
                            <select id="select_responsable" name="select_responsable" class="form-control select2" style="width: 100%;">
                            </select>
                        </div>
                        <div class="col-sm-12 asignar m-0" id="div_sede_dependencia">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="select_sede">Sede: </label>
                                    <select id="select_sede" name="select_sede" class="form-control" disabled>
                                    </select>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="select_dependencia">Dependencia:</label>
                                    <select id="select_dependencia" name="select_dependencia" class="form-control" disabled>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="foto">Fotografia:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" accept="image/*">
                                    <label class="custom-file-label" for="foto">Eliga Imagen</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Cargar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="select_estado">Estado</label>
                            <select class="form-control" name="select_estado" id="select_estado">
                                <option value="" disabled selected>--Seleccione estado--</option>
                                <option value="BUENO">Bueno</option>
                                <option value="REGULAR">Regular</option>
                                <option value="MALO">Malo</option>
                            </select>
                        </div>
                         
                        <div class="col-sm-6 mb-3">
                            <label for="fecCompra">Fecha de adquisión:</label>
                            <input type="date" class="form-control" id="fecCompra" name="fecCompra">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="ordenCompra">Orden de compra:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="ordenCompra" accept="application/pdf">
                                    <label class="custom-file-label" for="ordenCompra">Eliga Documento</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Cargar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <label for="txt_observacion">Observacion:</label>
                            <textarea type="text" id="txt_observacion" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer pl-4 pr-4">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal" id="cancelar">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="registrar">Registrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Editar -->
<div class="modal fade" id="modal_editar" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="staticBackdropLabel" style="font-weight: bold;">Actualizar Activo</h5>
                <div class="m-0 p-0">
                    <a type="button" class="text-danger" id="borrar_datos_modal">
                        <i class="fa fa-eraser"></i>
                    </a>
                    <button type="button" class="close ml-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body p-4">
                <form class="m-0 p-0" id="formularioActivo">
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <label for="idcategoria_editar">Categoría:</label>
                            <select class="form-control select2" name="idcategoria_editar" id="idcategoria_editar" style="width: 100%;">
                            </select>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="txt_marca_editar">Marca:</label>
                            <input type="text" class="form-control" id="txt_marca_editar" onkeypress="return sololetras(event)">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="txt_modelo_editar">Modelo:</label>
                            <input type="text" class="form-control" id="txt_modelo_editar" onkeypress="return sololetras(event)">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label for="txt_serie_editar">Nro. Serie:</label>
                            <input type="text" class="form-control" id="txt_serie_editar" onkeypress="return sololetras(event)">
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label for="txt_patrimonial_editar">Codigo Patrimonial:</label>
                            <input type="text" class="form-control" id="txt_patrimonial_editar">
                        </div>
                        <div class="col-sm-5 mb-3">
                            <label for="select_responsable_editar">Responsable Administrativo:</label>
                            <select id="select_responsable_editar" name="select_responsable_editar" class="form-control select2" style="width: 100%;">
                            </select>
                        </div>
                        <div class="col-sm-12 asignar m-0" id="div_sede_dependencia_editar">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <label for="select_sede_editar">Sede: </label>
                                    <select id="select_sede_editar" name="select_sede_editar" class="form-control" disabled>
                                    </select>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label for="select_dependencia_editar">Dependencia:</label>
                                    <select id="select_dependencia_editar" name="select_dependencia_editar" class="form-control" disabled>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-6 mb-3">
                        <label for="fotografia">Fotografia:</label>
                        <input type="file" id="fotografia" class="form-control">
                    </div> -->
                        <div class="col-sm-6 mb-3">
                            <label for="foto">Fotografia:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto_editar" accept="image/*">
                                    <label class="custom-file-label" for="foto_editar">Eliga Imagen</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Cargar</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="select_estado_editar">Estado</label>
                            <select class="form-control" name="select_estado_editar" id="select_estado_editar">
                                <option value="" disabled selected>--Seleccione estado--</option>
                                <option value="BUENO">Bueno</option>
                                <option value="REGULAR">Regular</option>
                                <option value="MALO">Malo</option>
                            </select>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="fecCompra_editar">Fecha de adquisición:</label>
                            <input type="date" class="form-control" id="fecCompra_editar">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="ordenCompra_editar">Orden de compra (PDF):</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="ordenCompra_editar" accept="application/pdf">
                                    <label class="custom-file-label" for="ordenCompra_editar">Elegir archivo</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 mt-3">
                            <label for="txt_observacion_editar">Observacion:</label>
                            <textarea type="text" id="txt_observacion_editar" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="text" id="txt_idactivo" hidden>
                        <input id="foto_actual" name="foto_actual" hidden>
                    </div>
                </form>

            </div>
            <div class="modal-footer pl-4 pr-4">
                <button type="button" class="btn bg-gradient-secondary" data-dismiss="modal" id="cancelar_editar">Cancelar</button>
                <button type="button" class="btn bg-gradient-info" id="actualizar">Actualizar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Movimiento de Activos -->
<div class="modal fade" id="modalMovimientoActivos" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formMovimientoActivos" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">Registrar Movimiento del Activo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="mov_idactivos" name="idactivos">

                    <div class="form-group">
                        <label>Tipo de movimiento:</label>
                        <select name="tipos" id="mov_idtipos" class="form-control" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="PRESTAMO">En calidad de Préstamo</option>
                            <option value="TRANSFERENCIA">En calidad de Transferencia</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Fecha movimiento:</label>
                        <input type="date" class="form-control" name="fecha" id="mov_fecha_activos" required readonly>
                    </div>
                    <input id="idresponsable_activos" class="asignar">
                    <input id="iddependencia_activos" class="asignar">

                    <!-- Prestamo -->
                    <div id="div_prestamo_ac" class="asignar">
                        <div class="form-group">
                            <label>Responsable:</label>
                            <input type="text" class="form-control" id="pres_jefe_activos" readonly>
                        </div>
                        <div class="form-group">
                            <label>Custodio Temporal (Administrativo):</label>
                            <select id="resp_temporal_activos" class="form-control select2" style="width: 100%;"></select>
                        </div>
                        <div class="form-group">
                            <label>Tiempo de préstamo (días):</label>
                            <input type="number" class="form-control" id="prestamo_tiempo_activos" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Documento de sustento (PDF)</label>
                            <input type="file"
                                class="form-control"
                                id="doc_sustento_ac"
                                name="doc_sustento_ac"
                                accept="application/pdf"
                                required>
                            <small class="text-muted">Máximo 5 MB</small>
                        </div>
                        <div class="form-group">
                            <label>Motivo / Sustento:</label>
                            <textarea class="form-control" id="prestamo_motivo_activos" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Transferencia -->
                    <div id="div_transferencia_ac" class="asignar">
                        <div class="form-group">
                            <label>Responsable Actual:</label>
                            <input type="text" class="form-control" id="transf_responsable_actual_ac" readonly>
                        </div>
                        <div class="form-group">
                            <label>Responsable destino:</label>
                            <select id="mov_responsable_ac" class="form-control select2" style="width: 100%;"></select>
                        </div>

                        <div class="form-group">
                            <label>Sede destino:</label>
                            <select id="sede_destino_ac" class="form-control" readonly></select>
                        </div>

                        <div class="form-group">
                            <label>Dependencia destino:</label>
                            <select id="dependencia_destino_ac" class="form-control" readonly></select>
                        </div>
                        <div class="form-group">
                            <label>Documento de sustento (PDF)</label>
                            <input type="file"
                                class="form-control"
                                id="doc_ac"
                                name="doc_ac"
                                accept="application/pdf"
                                required>
                            <small class="text-muted">Máximo 5 MB</small>
                        </div>
                        <div class="form-group">
                            <label>Motivo / Sustento:</label>
                            <textarea class="form-control" id="transferencia_motivo_ac" rows="3" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar_mov_activos">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="registrar_mov_activos">Registrar Movimiento</button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Modal Movimiento -->
<div class="modal fade" id="modalMovimiento" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formMovimiento" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">Registrar Movimiento del Activo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">


                    <input type="hidden" id="mov_idactivo" name="idactivo">

                    <div class="form-group">
                        <label>Tipo de movimiento:</label>
                        <select name="tipo" id="mov_idtipo" class="form-control" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="PRESTAMO">En calidad de Préstamo</option>
                            <option value="TRANSFERENCIA">En calidad de Transferencia</option>
                        </select>
                    </div>



                    <div class="form-group">
                        <label>Fecha movimiento:</label>
                        <input type="date" class="form-control" name="fecha" id="mov_fecha" required readonly>
                    </div>
                    <input id="idresponsable" class="asignar">

                    <!-- Prestamo -->
                    <div id="div_prestamo" class="asignar">
                        <div class="form-group">
                            <label>Responsable:</label>
                            <input type="text" class="form-control" id="pres_responsable" readonly>
                        </div>
                        <div class="form-group">
                            <label>Custodio Temporal (Administrativo):</label>
                            <select id="resp_temporal" class="form-control select2" style="width: 100%;"></select>
                        </div>
                        <div class="form-group">
                            <label>Tiempo de préstamo (días):</label>
                            <input type="number" class="form-control" id="prestamo_tiempo" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Documento de sustento (PDF)</label>
                            <input type="file"
                                class="form-control"
                                id="doc_sustento"
                                name="doc_sustento"
                                accept="application/pdf"
                                required>
                            <small class="text-muted">Máximo 5 MB</small>
                        </div>
                        <div class="form-group">
                            <label>Motivo / Sustento:</label>
                            <textarea class="form-control" id="prestamo_motivo" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Transferencia -->
                    <div id="div_transferencia" class="asignar">
                        <div class="form-group">
                            <label>Responsable Actual:</label>
                            <input type="text" class="form-control" id="transf_responsable_actual" readonly>
                        </div>
                        <div class="form-group">
                            <label>Responsable destino:</label>
                            <select id="mov_responsable" class="form-control select2" style="width: 100%;"></select>
                        </div>

                        <div class="form-group">
                            <label>Sede destino:</label>
                            <select id="sede_destino" class="form-control" readonly></select>
                        </div>

                        <div class="form-group">
                            <label>Dependencia destino:</label>
                            <select id="dependencia_destino" class="form-control" readonly></select>
                        </div>
                        <div class="form-group">
                            <label>Documento de sustento (PDF)</label>
                            <input type="file"
                                class="form-control"
                                id="doc"
                                name="doc"
                                accept="application/pdf"
                                required>
                            <small class="text-muted">Máximo 5 MB</small>
                        </div>
                        <div class="form-group">
                            <label>Motivo / Sustento:</label>
                            <textarea class="form-control" id="transferencia_motivo" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label>Motivo / Sustento:</label>
                        <textarea class="form-control" name="motivo" id="mov_motivo" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Responsable destino (si aplica):</label>
                        <select class="form-control" name="responsable" id="mov_responsable">
                        </select>
                        <small class="form-text text-muted">
                            Solo si es Transferencia o si deseas asignar a otro responsable.
                        </small>
                    </div> -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar_mov">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="registrar_mov">Registrar Movimiento</button>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- Modal Movimiento Devolucion -->
<div class="modal fade" id="modalMovimientoDev" data-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formMovimientoDev" enctype="multipart/form-data">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"><b>Devolucion del Activo: <span class="text-danger" id="cod_patrimonial_dev"></span></b></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="mov_idactivo_dev">

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
                        <input type="date" class="form-control" name="fecha" id="mov_fecha_dev" readonly>
                    </div>

                    <input id="idresponsable_dev" hidden>

                    <div class="form-group">
                        <label>Responsable:</label>
                        <input type="text" class="form-control" id="pres_responsable_dev" readonly>
                    </div>
                    <div class="form-group">
                        <label>Documento de sustento (PDF)</label>
                        <input type="file"
                            class="form-control"
                            id="doc_dev"
                            name="doc_dev"
                            accept="application/pdf"
                            required>
                        <small class="text-muted">Máximo 5 MB</small>
                    </div>
                    <div class="form-group">
                        <label>Motivo / Sustento:</label>
                        <textarea class="form-control" id="devolucion_motivo" rows="3" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelar_mov_dev">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="registrar_mov_dev">Registrar Devolución</button>
                </div>

            </div>
        </form>
    </div>
</div>


<!-- Modal Importar Excel -->
<div class="modal fade" id="modalImportarExcel" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info"><b><i class="fas fa-file-excel"></i> Carga Masiva de Activos</b></h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>&nbsp;
                    Descargue la plantilla, complete los datos y súbala. 
                    Los campos con <span class="text-danger">*</span> son obligatorios.
                </div>
                <a href="controllers/Activo.controller.php?op=descargarPlantilla" class="btn btn-sm bg-navy mb-3">
                    <i class="fas fa-download"></i>&nbsp; Descargar Plantilla
                </a>
                <hr>
                <form id="formImportarExcel">
                    <div class="form-group">
                        <label>Seleccione archivo (Excel o CSV):</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="archivo_carga" accept=".xlsx,.xls,.csv">
                                <label class="custom-file-label" for="archivo_carga">Elegir archivo</label>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="resultadoCarga" style="display:none;">
                    <hr>
                    <div id="resumenCarga"></div>
                    <div id="erroresCarga" class="mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info" id="btnProcesarCarga" disabled>
                    <i class="fas fa-upload"></i>&nbsp; Procesar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="js/activos.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(document).on("change", "#foto", function() {
        let fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").html(fileName);
    });

    $(document).on("change", "#foto_editar", function() {
        let fileName = $(this).val().split("\\").pop();
        $(this).next(".custom-file-label").html(fileName);
    });

    $("#tablaActivo").on("click", ".ver", function() {
        let idactivo = $(this).data("idactivo");

        // Redirigir a la página detalle
        window.location.href = "main.php?view=activo/view_detalle.php&id=" + idactivo;
    });

    $("#tablaActivo").on("click", ".info", function() {
        let idactivo = $(this).data("idactivo");
        let cod_patrimonial = $(this).data("cod");

        // Redirigir a la página detalle
        window.location.href = "main.php?view=activo/view_mas_info.php&codigo=" + cod_patrimonial;
    });

    $('#formMovimiento').on('submit', function(e) {
        e.preventDefault(); // <--- evita el refresh
        registrarMovimiento(); // tu función AJAX
    });

    $(document).on('click', '.row-activo', function(e) {

        if ($(e.target).is("input[type=checkbox]")) return;

        let checkbox = $(this).find(".chk-activo");

        if (!checkbox.is(":disabled")) {
            checkbox.prop("checked", !checkbox.prop("checked")).trigger("change");
        }
    });
</script>