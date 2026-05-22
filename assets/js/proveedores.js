function prepararModal(accion, proveedor = null) {
    const modalTitle = document.getElementById('pacienteModalLabel');
    const proveedorId = document.getElementById('proveedorId');
    const nombre = document.getElementById('nombre');
    const rif = document.getElementById('rif');    
    const telefono = document.getElementById('numero_telefono');
    const direccion = document.getElementById('direccion');
    const email = document.getElementById('email');

    if (accion === 'crear') {
        modalTitle.innerText = 'Crear Proveedor';
        proveedorId.value = '';
        nombre.value = '';
        rif.value = '';
        telefono.value = '';
        direccion.value = '';
        email.value = '';

    } else if (accion === 'editar' && proveedor) {
        modalTitle.innerText = 'Editar Proveedor';
        proveedorId.value = proveedor.id;
        nombre.value = proveedor.nombre;
        rif.value = proveedor.rif;
        telefono.value = proveedor.telefono;
        direccion.value = proveedor.direccion;
        email.value = proveedor.email;
    }
}

function setProveedorId(id, nombre) {
    document.getElementById('id_proveedor').value = id; // Asigna el ID del proveedor al campo oculto
    document.getElementById('nombre_proveedor').value = nombre; // Asigna el nombre del proveedor al campo de texto
}


$(document).ready(function() {
    // Mostrar entradas de insumo
    $('.ver-entradas').click(function() {
        var idInsumo = $(this).data('id');
        
        $.ajax({
            url: 'index.php?c=proveedores&a=obtenerEntradas',
            type: 'GET',
            data: {id: idInsumo},
            beforeSend: function() {
                $('#contenidoEntradasInsumo').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
            },
            success: function(response) {
                $('#contenidoEntradasInsumo').html(response);
                $('#entradasInsumoModal').modal('show'); // Asegúrate de que el ID del modal sea correcto
            },
            error: function() {
                $('#contenidoEntradasInsumo').html('<div class="alert alert-danger">Error al cargar las entradas</div>');
            }
        });
    });
});