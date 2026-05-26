<div class="d-flex justify-content-between mb-3">
    <h5><b>Mantenimientos del Activo</b></h5>
    <button type="button" class="btn btn-sm bg-info" id="btnNuevoMantenimiento">
        <i class="fas fa-plus"></i> Nuevo Mantenimiento
    </button>
</div>

<table class="table table-bordered table-hover table-sm" id="tablaMantenimientos">
    <thead class="bg-navy">
        <tr>
            <th class="text-center">Tipo</th>
            <th class="text-center">Fecha</th>
            <th>Descripción</th>
            <th class="text-center">Responsable</th>
            <th class="text-center">Costo</th>
            <th class="text-center">Proveedor</th>
            <th class="text-center">Próximo Mant.</th>
            <th class="text-center">Documento</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal Mantenimiento -->
<div class="modal fade" id="modalMantenimiento" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Mantenimiento</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formMantenimiento">
                    <input type="hidden" id="id_mantenimiento_edit">
                    <input type="hidden" id="documento_actual_manto">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo *</label>
                                <select class="form-control" id="man_tipo" required>
                                    <option value="">Seleccione...</option>
                                    <option value="PREVENTIVO">Preventivo</option>
                                    <option value="CORRECTIVO">Correctivo</option>
                                    <option value="PREDICTIVO">Predictivo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha *</label>
                                <input type="date" class="form-control" id="man_fecha" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea class="form-control" id="man_descripcion" rows="2"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Responsable</label>
                                <input type="text" class="form-control" id="man_responsable">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Costo (S/)</label>
                                <input type="number" step="0.01" class="form-control" id="man_costo">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Proveedor</label>
                                <input type="text" class="form-control" id="man_proveedor">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Próximo Mantenimiento</label>
                                <input type="date" class="form-control" id="man_fecha_proximo">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Documento (PDF)</label>
                        <input type="file" class="form-control" id="man_documento_pdf" accept="application/pdf">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" id="guardarMantenimiento">Guardar</button>
            </div>
        </div>
    </div>
</div>


