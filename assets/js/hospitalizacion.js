
function prepararModal(accion, hospitalizacion = null) {
    const modalTitle = document.getElementById('hospitalizacionModalLabel');
    const btn = document.getElementById('botton');
    const id = document.getElementById('id');
    const id_control = document.getElementById('id_control');
    const fecha_hora_inicio = document.getElementById('fecha_hora_inicio');
    const precio_horas = document.getElementById('precio_horas');
    const total = document.getElementById('total');
    const fecha_hora_final = document.getElementById('fecha_hora_final');

    if (accion === 'crear') {
        modalTitle.innerText = 'Registrar Hospitalización';
        id.value = '';
        id_control.value = '';
        fecha_hora_inicio.value = '';
        precio_horas.value = '';
        total.value = '';
        fecha_hora_final.value = '';
        btn.innerText = 'Guardar';

    } else if (accion === 'editar' && hospitalizacion) {
        modalTitle.innerText = 'Editar Hospitalización';
        id.value = hospitalizacion.id;
        id_control.value = hospitalizacion.id_control;
        fecha_hora_inicio.value = hospitalizacion.fecha_hora_inicio;
        precio_horas.value = hospitalizacion.precio_horas;
        total.value = hospitalizacion.total;
        fecha_hora_final.value = hospitalizacion.fecha_hora_final;
        btn.innerText = 'Guardar';

    }
}



// Función para abrir el modal de insumos y asignar el id de hospitalización
function abrirModalInsumos(id) {
    $('#id_hospitalizacion').val(id);
    cargarInsumos();
    $('#insumosModal').modal('show');
}

$(document).ready(function() {
    // Evento para buscar hospitalizaciones por paciente mediante AJAX
    $('#btnBuscar').on('click', function() {
        var idPaciente = $('#idPaciente').val();

        $.ajax({
            url: 'index.php?c=hospitalizacion&a=getHospitalizacionesPorPaciente&id=' + encodeURIComponent(idPaciente),
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var tbody = $('#tablaHospitalizaciones tbody');
                tbody.empty();

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(function(registro) {
                        var row = `
                            <tr>
                                <td>${registro.nombre}</td>
                                <td>${registro.fecha_hora_inicio}</td>
                                <td>${registro.precio_horas}</td>
                                <td>${registro.total}</td>
                                <td>${registro.fecha_hora_final}</td>
                                <td>${registro.diagnostico}</td>
                                <td>${registro.insumos_usados || 'Sin insumos'}</td>
                            </tr>
                        `;
                        tbody.append(row);
                    });
                } else {
                    tbody.append('<tr><td colspan="6" class="text-center">No se encontraron registros</td></tr>');
                }
            },
            error: function() {
                console.error('Error en la solicitud AJAX');
            }
        });
    });

    // Actualizar el atributo max y limpiar cantidad al cambiar el insumo seleccionado
    $('#id_insumo').on('change', function() {
        var existencia = $(this).find(':selected').data('existencia');
        $('#cantidad').attr('max', existencia);
        $('#cantidad').val('');
    });

    // Manejar el envío del formulario para agregar insumos con validación y AJAX
    $('#formAgregarInsumos').on('submit', function(event) {
        event.preventDefault();

        var cantidad = parseInt($('#cantidad').val());
        var max = parseInt($('#cantidad').attr('max'));

        if (cantidad > max) {
            alert('La cantidad no puede ser mayor a la existencia disponible: ' + max);
            return;
        }

        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert(response.message);
                    $('#insumosModal').modal('hide');
                     $('#formAgregarInsumos')[0].reset(); // Limpiar formulario
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error en la solicitud: ' + error);
            }
        });
    });
});


// Función para actualizar el select de insumos dinámicamente
function actualizarSelectInsumos(insumos) {
    var $select = $('#id_insumo');
    $select.empty(); // Limpiar opciones actuales
    $select.append('<option value="">Seleccione un insumo</option>');
    insumos.forEach(function(insumo) {
        var optionText = `${insumo.nombre} - ${insumo.descripcion} (Disponibles: ${insumo.existencia})`;
        var $option = $('<option></option>')
            .val(insumo.id)
            .attr('data-existencia', insumo.existencia)
            .text(optionText);
        $select.append($option);
    });
    // Limpiar cantidad y max
    $('#cantidad').val('');
    $('#cantidad').removeAttr('max');
}

function cargarInsumos() {
    $.ajax({
        url: 'index.php?c=hospitalizacion&a=getInsumosAjax',
        method: 'GET',
        dataType: 'json',
        success: function(insumos) {
            var $select = $('#id_insumo');
            $select.empty();
            $select.append('<option value="">Seleccione un insumo</option>');

            insumos.forEach(function(insumo) {
                var optionText = `${insumo.nombre} - ${insumo.descripcion} (Disponibles: ${insumo.existencia})`;
                var $option = $('<option></option>')
                    .val(insumo.id)
                    .attr('data-existencia', insumo.existencia)
                    .text(optionText);
                $select.append($option);
            });

            // Limpiar cantidad y max
            $('#cantidad').val('');
            $('#cantidad').removeAttr('max');
        },
        error: function() {
            alert('Error al cargar los insumos');
        }
    });
}

