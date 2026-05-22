

function prepararModal(accion, insumo = null) {
    const modalTitle = document.getElementById('insumoModalLabel');
    const insumoId = document.getElementById('insumoId');
    const nombre = document.getElementById('nombre');
    const descripcion = document.getElementById('descripcion');
    const precio = document.getElementById('precio');
    const estado = document.getElementById('estado'); 
    const btn = document.getElementById('btn');

    if (accion === 'crear') {
        modalTitle.innerText = 'Registrar Insumo';
        insumoId.value = '';
        nombre.value = '';
        descripcion.value = '';
        precio.value = '';
        estado.value = '';
        btn.innerText = 'Guardar';

    } else if (accion === 'editar' && insumo) {
        console.log(insumo); // Verifica el contenido del objeto
        modalTitle.innerText = 'Editar Insumo';
        insumoId.value = insumo.id;
        nombre.value = insumo.nombre;
        descripcion.value = insumo.descripcion;
        precio.value = insumo.precio;
        estado.value = insumo.estado;
        btn.innerText = 'Guardar';
    }
}

