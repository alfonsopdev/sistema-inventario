$(document).ready(function () {

    var idActivoGlobal = null;

    window.iniciarGarantias = function (idactivo) {
        idActivoGlobal = idactivo;
        listarGarantias(idactivo);
    };

    function listarGarantias(idactivo) {
        $.ajax({
            url: 'controllers/Garantia.controller.php',
            type: 'GET',
            data: { op: 'listarPorActivo', idactivo: idactivo },
            dataType: 'json',
            success: function (data) {
                var html = '';
                if (data.length === 0) {
                    html = '<tr><td colspan="7" class="text-center text-muted">No hay garantías registradas</td></tr>';
                } else {
                    data.forEach(function (g) {
                        var badge = '';
                        switch (g.estado) {
                            case 'VIGENTE':          badge = '<span class="badge bg-success">Vigente</span>'; break;
                            case 'PROXIMO_A_VENCER': badge = '<span class="badge bg-warning text-dark">Próximo a vencer</span>'; break;
                            case 'VENCIDA':          badge = '<span class="badge bg-danger">Vencida</span>'; break;
                            default:                 badge = '<span class="badge bg-secondary">' + g.estado + '</span>';
                        }

                        var docLink = g.documento_pdf
                            ? '<a href="archivos/' + g.documento_pdf + '" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-file-pdf"></i></a>'
                            : '<span class="text-muted">—</span>';

                        html += '<tr>' +
                            '<td class="text-center">' + (g.proveedor || '—') + '</td>' +
                            '<td class="text-center">' + (g.tipo_garantia || '—') + '</td>' +
                            '<td class="text-center">' + (g.fecha_inicio || '—') + '</td>' +
                            '<td class="text-center">' + (g.fecha_fin || '—') + '</td>' +
                            '<td class="text-center">' + badge + '</td>' +
                            '<td class="text-center">' + docLink + '</td>' +
                            '<td class="text-center">' +
                            '  <button class="btn btn-sm btn-warning editar-garantia" data-id="' + g.id_garantia + '"><i class="fa fa-edit"></i></button>' +
                            '  <button class="btn btn-sm btn-danger eliminar-garantia" data-id="' + g.id_garantia + '"><i class="fa fa-trash"></i></button>' +
                            '</td></tr>';
                    });
                }
                $('#tablaGarantias tbody').html(html);
            }
        });
    }

    $(document).on('click', '#btnNuevaGarantia', function () {
        $('#formGarantia')[0].reset();
        $('#id_garantia_edit').val('');
        $('#documento_actual_garantia').val('');
        $('#modalGarantia .modal-title').text('Nueva Garantía');
        $('#modalGarantia').modal('show');
    });

    $(document).on('click', '.editar-garantia', function () {
        var id = $(this).data('id');
        $.ajax({
            url: 'controllers/Garantia.controller.php',
            type: 'GET',
            data: { op: 'cargar', idgarantia: id },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    var g = data[0];
                    $('#id_garantia_edit').val(g.id_garantia);
                    $('#gar_proveedor').val(g.proveedor || '');
                    $('#gar_tipo_garantia').val(g.tipo_garantia || '');
                    $('#gar_fecha_inicio').val(g.fecha_inicio || '');
                    $('#gar_fecha_fin').val(g.fecha_fin || '');
                    $('#gar_observacion').val(g.observacion || '');
                    $('#documento_actual_garantia').val(g.documento_pdf || '');
                    $('#modalGarantia .modal-title').text('Editar Garantía');
                    $('#modalGarantia').modal('show');
                }
            }
        });
    });

    $(document).on('click', '.eliminar-garantia', function () {
        var id = $(this).data('id');
        Swal.fire({
            icon: 'question',
            title: '¿Eliminar garantía?',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'controllers/Garantia.controller.php',
                    type: 'GET',
                    data: { op: 'eliminar', idgarantia: id },
                    success: function () {
                        Swal.fire('Eliminado', '', 'success');
                        listarGarantias(idActivoGlobal);
                    }
                });
            }
        });
    });

    $(document).on('click', '#guardarGarantia', function () {
        var idEdit = $('#id_garantia_edit').val();
        var op = idEdit ? 'editar' : 'registrar';

        var formData = new FormData();
        formData.append('op', op);
        formData.append('id_activo', idActivoGlobal);
        formData.append('id_garantia', idEdit);
        formData.append('proveedor', $('#gar_proveedor').val());
        formData.append('tipo_garantia', $('#gar_tipo_garantia').val());
        formData.append('fecha_inicio', $('#gar_fecha_inicio').val());
        formData.append('fecha_fin', $('#gar_fecha_fin').val());
        formData.append('observacion', $('#gar_observacion').val());
        formData.append('documento_actual', $('#documento_actual_garantia').val());

        var file = $('#gar_documento_pdf')[0].files[0];
        if (file) formData.append('documento_pdf', file);

        if (!$('#gar_fecha_fin').val()) {
            mostrarAlerta('warning', 'La fecha fin es obligatoria');
            return;
        }

        $.ajax({
            url: 'controllers/Garantia.controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#modalGarantia').modal('hide');
                Swal.fire('Guardado', '', 'success');
                listarGarantias(idActivoGlobal);
            }
        });
    });
});
