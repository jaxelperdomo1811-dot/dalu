
// $(document).ready(function () {
      
//     // Autocompletar paciente
//     $('#paciente').on('input', function () {
//         let query = $(this).val();
//         if (query.length < 2) {
//             $('#pacienteList').empty().hide();
//             $('#id_paciente').val('');
//             return;
//         }
//         $.ajax({
//             url: 'index.php?c=citas&a=AutocompletarPacientes',
//             method: 'POST',
//             data: { campo: query },
//             success: function (data) {
//                 let pacientes = JSON.parse(data);
//                 let list = '';
//                 pacientes.forEach(paciente => {
//                     list += `<a href="#" class="list-group-item list-group-item-action" data-id="${paciente.id}" data-nombre="${paciente.nombre} ${paciente.apellido}" data-cedula="${paciente.cedula}">${paciente.cedula} - ${paciente.nombre} ${paciente.apellido}</a>`;
//                 });
//                 $('#pacienteList').html(list).show();

//                 // Agregar evento mousedown a los items generados
//                 $('#pacienteList a').on('mousedown', function (e) {
//                     e.preventDefault(); // prevenir comportamiento por defecto

//                     let id = $(this).data('id');
//                     let nombreCompleto = $(this).data('cedula') + ' - ' + $(this).data('nombre');

//                     $('#paciente').val(nombreCompleto);
//                     $('#id_paciente').val(id);
//                     $('#pacienteList').empty().hide();
//                 });
//             }
//         });
//     });

//     // Ocultar lista si clic fuera
//     $(document).click(function (e) {
//         if (!$(e.target).closest('#paciente, #pacienteList').length) {
//             $('#pacienteList').empty().hide();
//         }
//     });

//     // Enviar formulario vía AJAX
//     $(document).ready(function () {
//         $('#facturaForm').submit(function (e) {
//             e.preventDefault();

//             let id_paciente = $('#id_paciente').val();
//             let fecha = $('#fecha').val();
//             let id_metodo_pago = $('#id_metodo_pago').val();
//             let referencia = $('#referencia').val().trim();
//             let monto_pago = parseFloat($('#monto_pago').val());

//             if (!id_paciente || !fecha || !id_metodo_pago || !referencia || isNaN(monto_pago) || monto_pago <= 0) {
//                 alert('Complete todos los campos correctamente.');
//                 return;
//             }

//             let data = {
//                 id_paciente: id_paciente,
//                 fecha: fecha,
//                 total: monto_pago,
//                 estado: 'activo',
//                 pago: {
//                     id_metodo_pago: id_metodo_pago,
//                     referencia: referencia,
//                     monto: monto_pago
//                 }
//             };

//             $.ajax({
//                 url: 'index.php?c=facturacion&a=GuardarFacturaCompleta',
//                 method: 'POST',
//                 data: JSON.stringify(data),
//                 contentType: 'application/json',
//                 success: function (response) {
//                     let res = JSON.parse(response);
//                     if (res.status === 'success') {
//                         alert(res.message);
//                         window.location.href = 'index.php?c=facturacion&a=Index';
//                     } else {
//                         alert('Error: ' + res.message);
//                     }
//                 },
//                 error: function () {
//                     alert('Error al guardar la factura.');
//                 }
//             });
//         });
//     });

// });










$(document).ready(function () {

        $('#paciente').on('input', function () {
        let query = $(this).val();
        if (query.length < 2) {
            $('#pacienteList').empty().hide();
            $('#id_paciente').val('');
            return;
        }
        $.ajax({
            url: 'index.php?c=citas&a=AutocompletarPacientes',
            method: 'POST',
            data: { campo: query },
            success: function (data) {
                let pacientes = JSON.parse(data);
                let list = '';
                pacientes.forEach(paciente => {
                    list += `<a href="#" class="list-group-item list-group-item-action" data-id="${paciente.id}" data-nombre="${paciente.nombre} ${paciente.apellido}" data-cedula="${paciente.cedula}">${paciente.cedula} - ${paciente.nombre} ${paciente.apellido}</a>`;
                });
                $('#pacienteList').html(list).show();

                // Agregar evento mousedown a los items generados
                $('#pacienteList a').on('mousedown', function (e) {
                    e.preventDefault(); // prevenir comportamiento por defecto

                    let id = $(this).data('id');
                    let nombreCompleto = $(this).data('cedula') + ' - ' + $(this).data('nombre');

                    $('#paciente').val(nombreCompleto);
                    $('#id_paciente').val(id);
                    $('#pacienteList').empty().hide();
                });
            }
        });
    });
      
    // Autocompletar paciente (corregido: URL a facturacion, no citas)
    // $('#paciente').on('input', function () {
    //     let query = $(this).val();
    //     if (query.length < 2) {
    //         $('#pacienteList').empty().hide();
    //         $('#id_paciente').val('');
    //         return;
    //     }
    //     $.ajax({
    //         url: 'index.php?c=facturacion&a=AutocompletarPacientes',  // Corrección: c=facturacion
    //         method: 'POST',
    //         data: { campo: query },
    //         success: function (data) {
    //             let pacientes = JSON.parse(data);
    //             let list = '';
    //             pacientes.forEach(paciente => {
    //                 list += `<a href="#" class="list-group-item list-group-item-action" data-id="${paciente.id}" data-nombre="${paciente.nombre} ${paciente.apellido}" data-cedula="${paciente.cedula}">${paciente.cedula} - ${paciente.nombre} ${paciente.apellido}</a>`;
    //             });
    //             $('#pacienteList').html(list).show();

    //             // Agregar evento mousedown a los items generados
    //             $('#pacienteList a').on('mousedown', function (e) {
    //                 e.preventDefault(); // prevenir comportamiento por defecto

    //                 let id = $(this).data('id');
    //                 let nombreCompleto = $(this).data('cedula') + ' - ' + $(this).data('nombre');

    //                 $('#paciente').val(nombreCompleto);
    //                 $('#id_paciente').val(id);
    //                 $('#pacienteList').empty().hide();
    //             });
    //         }
    //     });
    // });

    // Ocultar lista si clic fuera (sin cambios)
    $(document).click(function (e) {
        if (!$(e.target).closest('#paciente, #pacienteList').length) {
            $('#pacienteList').empty().hide();
        }
    });

    // Adición mínima: Filtro dinámico - Al cambiar doctor, carga servicios vía AJAX
    $('#selectDoctor').on('change', function () {
        let idDoctor = $(this).val();
        let selectServicio = $('#selectServicio');
        let montoPago = $('#monto_pago');

        // Limpiar y deshabilitar
        selectServicio.html('<option value="">Cargando...</option>').prop('disabled', true);
        montoPago.val('');  // Limpia monto

        if (!idDoctor) {
            selectServicio.html('<option value="">-- Primero selecciona un doctor --</option>').prop('disabled', true);
            return;
        }

        // AJAX a ObtenerServiciosPorDoctor (usa controlador)
        $.ajax({
            url: 'index.php?c=facturacion&a=ObtenerServiciosPorDoctor',  // Ruta al método del controlador
            method: 'POST',
            data: { id_doctor: idDoctor },
            dataType: 'json',  // Espera JSON directo
            success: function (data) {
                selectServicio.html('<option value="">-- Selecciona un servicio --</option>');
                
                if (data.error) {
                    selectServicio.html('<option value="">Error al cargar</option>');
                } else {
                    data.forEach(function (servicio) {
                        let texto = servicio.especialidad + ' - ' + servicio.precio;
                        let option = new Option(texto, servicio.id_servicio_medico);
                        selectServicio.append(option);
                    });
                }
                
                selectServicio.prop('disabled', false);
            },
            error: function () {
                selectServicio.html('<option value="">Error en la conexión</option>').prop('disabled', false);
            }
        });
    });

    // Adición mínima: Al seleccionar servicio, setea monto (total) automáticamente
    $('#selectServicio').on('change', function () {
        let selectedOption = $(this).find('option:selected');
        let precioTexto = selectedOption.text();  // Ej.: "CARDIOLOGIA - 10.00"
        let precio = precioTexto.split(' - ')[1];  // Extrae precio del texto
        if (precio) {
            $('#monto_pago').val(precio);
        }
    });

    // Enviar formulario vía AJAX (unido en ready; agregado id_servicio_medico en data)
    $('#facturaForm').submit(function (e) {
        e.preventDefault();

        let id_paciente = $('#id_paciente').val();
        let id_servicio_medico = $('#selectServicio').val();  // Adición: ID del servicio seleccionado
        let fecha = $('#fecha').val();
        let id_metodo_pago = $('#id_metodo_pago').val();
        let referencia = $('#referencia').val().trim();
        let monto_pago = parseFloat($('#monto_pago').val());

        // Validación agregada para servicio
        if (!id_paciente || !id_servicio_medico || !fecha || !id_metodo_pago || !referencia || isNaN(monto_pago) || monto_pago <= 0) {
            alert('Complete todos los campos correctamente (incluyendo doctor y servicio).');
            return;
        }

        let data = {
            id_paciente: id_paciente,
            id_servicio_medico: id_servicio_medico,  // Adición: Envía al controlador para insertar en factura
            fecha: fecha,
            total: monto_pago,
            estado: 'activo',
            pago: {
                id_metodo_pago: id_metodo_pago,
                referencia: referencia,
                monto: monto_pago
            }
        };

        $.ajax({
            url: 'index.php?c=facturacion&a=GuardarFacturaCompleta',
            method: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    alert(res.message);
                    window.location.href = 'index.php?c=facturacion&a=Index';
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function () {
                alert('Error al guardar la factura.');
            }
        });
    });

});
