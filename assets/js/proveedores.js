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

// Ya no es necesario porque el ID se selecciona mediante el select en el nuevo modal
// function setProveedorId(id, nombre) { ... }

$(document).ready(function() {
    
    // Check url params for "tab=entradas"
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'entradas') {
        $('#tab-entradas').tab('show');
    }

    // Agregar nuevo producto al formulario de Entradas
    $('#btn_add_producto').click(function() {
        // Clonar la primera fila
        let nuevaFila = $('.producto-row').first().clone();
        // Limpiar los valores de los inputs en la fila clonada
        nuevaFila.find('input').val('');
        nuevaFila.find('select').prop('selectedIndex', 0);
        // Añadir la fila clonada al contenedor
        $('#productos_container').append(nuevaFila);
    });

    // Remover producto del formulario
    $('#productos_container').on('click', '.remover-producto', function() {
        // Verificar que haya más de 1 fila antes de eliminar
        if ($('.producto-row').length > 1) {
            $(this).closest('.producto-row').remove();
        } else {
            alert('Debe registrar al menos un producto en la entrada.');
        }
    });

    // Ver detalles de la entrada
    $('.ver-detalle-entrada').click(function() {
        var idEntrada = $(this).data('id');
        
        $.ajax({
            url: 'index.php?c=entradas&accion=view_detalles',
            type: 'GET',
            data: {id: idEntrada},
            beforeSend: function() {
                $('#contenidoEntradasInsumo').html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
            },
            success: function(response) {
                $('#contenidoEntradasInsumo').html(response);
                $('#entradasInsumoModal').modal('show');
            },
            error: function() {
                $('#contenidoEntradasInsumo').html('<div class="alert alert-danger">Error al cargar los detalles de la entrada.</div>');
            }
        });
    });

});