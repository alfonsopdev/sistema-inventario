<div class="d-flex justify-content-between mb-3">
    <h5><b>Garantías del Activo</b></h5>
    <button type="button" class="btn btn-sm bg-info" id="btnNuevaGarantia">
        <i class="fas fa-plus"></i> Nueva Garantía
    </button>
</div>

<table class="table table-bordered table-hover table-sm" id="tablaGarantias">
    <thead class="bg-navy">
        <tr>
            <th class="text-center">Proveedor</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Fecha Inicio</th>
            <th class="text-center">Fecha Fin</th>
            <th class="text-center">Estado</th>
            <th class="text-center">Documento</th>
            <th class="text-center">Acción</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal Garantía -->
<div class="modal fade" id="modalGarantia" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Garantía</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="formGarantia">
                    <input type="hidden" id="id_garantia_edit">
                    <input type="hidden" id="documento_actual_garantia">

                    <div class="form-group">
                        <label>Proveedor</label>
                        <input type="text" class="form-control" id="gar_proveedor">
                    </div>
                    <div class="form-group">
                        <label>Tipo de Garantía</label>
                        <select class="form-control" id="gar_tipo_garantia">
                            <option value="">Seleccione...</option>
                            <option value="FABRICANTE">Fabricante</option>
                            <option value="PROVEEDOR">Proveedor</option>
                            <option value="LEGAL">Legal</option>
                            <option value="EXTENDIDA">Extendida</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Inicio</label>
                                <input type="date" class="form-control" id="gar_fecha_inicio">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha Fin *</label>
                                <input type="date" class="form-control" id="gar_fecha_fin" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Documento (PDF)</label>
                        <input type="file" class="form-control" id="gar_documento_pdf" accept="application/pdf">
                    </div>
                    <div class="form-group">
                        <label>Observación</label>
                        <textarea class="form-control" id="gar_observacion" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" id="guardarGarantia">Guardar</button>
            </div>
        </div>
    </div>
</div>


