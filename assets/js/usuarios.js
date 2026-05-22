function prepararModal(accion, usuarioData = null) {
    const modalTitle = document.getElementById('usuarioModalLabel');
    const usuarioId = document.getElementById('usuarioId');
    const nombre = document.getElementById('nombre');
    const usuarioInput = document.getElementById('usuario'); 
    const password = document.getElementById('password');
    const llave = document.getElementById('llave'); 
    const btn = document.getElementById('botton');

    if (accion === 'crear') {
        modalTitle.innerText = 'Registrar Usuario';
        usuarioId.value = '';
        nombre.value = '';
        usuarioInput.value = ''; 
        password.value = '';
        llave.value = '';
        btn.innerText = 'Guardar';
    } else if (accion === 'editar' && usuarioData) {
        modalTitle.innerText = 'Editar Usuario';
        usuarioId.value = usuarioData.id;
        nombre.value = usuarioData.nombre;
        usuarioInput.value = usuarioData.usuario; 
        password.value = '';
        llave.value = usuarioData.llave; 
        btn.innerText = 'Guardar';
    }
}
