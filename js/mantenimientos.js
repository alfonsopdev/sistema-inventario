$(document).ready(function () {

    var idActivoGlobal = null;

    window.iniciarMantenimientos = function (idactivo) {
        idActivoGlobal = idactivo;
        listarMantenimientos(idactivo);
    };

    function listarMantenimientos(idactivo) {
        $.ajax({
            url: 'controllers/Mantenimiento.controller.php',
            type: 'GET',
            data: { op: 'listarPorActivo', idactivo: idactivo },
            dataType: 'json',
            success: function (data) {
                var html = '';
                if (data.length === 0) {
                    html = '<tr><td colspan="8" class="text-center text-muted">No hay mantenimientos registrados</td></tr>';
                } else {
                    data.forEach(function (m) {
                        var tipoBadge = '';
                        switch (m.tipo_mantenimiento) {
                            case 'PREVENTIVO':  tipoBadge = '<span class="badge bg-info">Preventivo</span>'; break;
                            case 'CORRECTIVO':  tipoBadge = '<span class="badge bg-warning text-dark">Correctivo</span>'; break;
                            case 'PREDICTIVO':  tipoBadge = '<span class="badge bg-primary">Predictivo</span>'; break;
                            default:            tipoBadge = '<span class="badge bg-secondary">' + m.tipo_mantenimiento + '</span>';
                        }

                        var docLink = m.documento_pdf
                            ? '<a href="archivos/' + m.documento_pdf + '" target="_blank" class="btn btn-sm btn-outline-info"><i class="fa fa-file-pdf"></i></a>'
                            : '<span class="text-muted">—</span>';

                        var costo = m.costo ? 'S/ ' + parseFloat(m.costo).toFixed(2) : '—';

                        html += '<tr>' +
                            '<td class="text-center">' + tipoBadge + '</td>' +
                            '<td class="text-center">' + m.fecha_mantenimiento + '</td>' +
                            '<td>' + (m.descripcion || '—') + '</td>' +
                            '<td class="text-center">' + (m.responsable || '—') + '</td>' +
                            '<td class="text-center">' + costo + '</td>' +
                            '<td class="text-center">' + (m.proveedor || '—') + '</td>' +
                            '<td class="text-center">' + (m.fecha_proximo_mantenimiento || '—') + '</td>' +
                            '<td class="text-center">' + docLink + '</td>' +
                            '<td class="text-center">' +
                            '  <button class="btn btn-sm btn-warning editar-mantenimiento" data-id="' + m.id_mantenimiento + '"><i class="fa fa-edit"></i></button>' +
                            '  <button class="btn btn-sm btn-danger eliminar-mantenimiento" data-id="' + m.id_mantenimiento + '"><i class="fa fa-trash"></i></button>' +
                            '</td></tr>';
                    });
                }
                $('#tablaMantenimientos tbody').html(html);
            }
        });
    }

    $(document).on('click', '#btnNuevoMantenimiento', function () {
        $('#formMantenimiento')[0].reset();
        $('#id_mantenimiento_edit').val('');
        $('#documento_actual_manto').val('');
        $('#modalMantenimiento .modal-title').text('Nuevo Mantenimiento');
        $('#modalMantenimiento').modal('show');
    });

    $(document).on('click', '.editar-mantenimiento', function () {
        var id = $(this).data('id');
        $.ajax({
            url: 'controllers/Mantenimiento.controller.php',
            type: 'GET',
            data: { op: 'cargar', idmantenimiento: id },
            dataType: 'json',
            success: function (data) {
                if (data.length > 0) {
                    var m = data[0];
                    $('#id_mantenimiento_edit').val(m.id_mantenimiento);
                    $('#man_tipo').val(m.tipo_mantenimiento);
                    $('#man_fecha').val(m.fecha_mantenimiento);
                    $('#man_descripcion').val(m.descripcion || '');
                    $('#man_responsable').val(m.responsable || '');
                    $('#man_costo').val(m.costo || '');
                    $('#man_proveedor').val(m.proveedor || '');
                    $('#man_fecha_proximo').val(m.fecha_proximo_mantenimiento || '');
                    $('#documento_actual_manto').val(m.documento_pdf || '');
                    $('#modalMantenimiento .modal-title').text('Editar Mantenimiento');
                    $('#modalMantenimiento').modal('show');
                }
            }
        });
    });

    $(document).on('click', '.eliminar-mantenimiento', function () {
        var id = $(this).data('id');
        Swal.fire({
            icon: 'question',
            title: '¿Eliminar mantenimiento?',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'controllers/Mantenimiento.controller.php',
                    type: 'GET',
                    data: { op: 'eliminar', idmantenimiento: id },
                    success: function () {
                        Swal.fire('Eliminado', '', 'success');
                        listarMantenimientos(idActivoGlobal);
                    }
                });
            }
        });
    });

    $(document).on('click', '#guardarMantenimiento', function () {
        var idEdit = $('#id_mantenimiento_edit').val();
        var op = idEdit ? 'editar' : 'registrar';

        var formData = new FormData();
        formData.append('op', op);
        formData.append('id_activo', idActivoGlobal);
        formData.append('id_mantenimiento', idEdit);
        formData.append('tipo_mantenimiento', $('#man_tipo').val());
        formData.append('fecha_mantenimiento', $('#man_fecha').val());
        formData.append('descripcion', $('#man_descripcion').val());
        formData.append('responsable', $('#man_responsable').val());
        formData.append('costo', $('#man_costo').val());
        formData.append('proveedor', $('#man_proveedor').val());
        formData.append('fecha_proximo_mantenimiento', $('#man_fecha_proximo').val());
        formData.append('documento_actual', $('#documento_actual_manto').val());

        var file = $('#man_documento_pdf')[0].files[0];
        if (file) formData.append('documento_pdf', file);

        if (!$('#man_tipo').val() || !$('#man_fecha').val()) {
            mostrarAlerta('warning', 'Complete tipo y fecha de mantenimiento');
            return;
        }

        $.ajax({
            url: 'controllers/Mantenimiento.controller.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                $('#modalMantenimiento').modal('hide');
                Swal.fire('Guardado', '', 'success');
                listarMantenimientos(idActivoGlobal);
            }
        });
    });
});
