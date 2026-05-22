
function prepararModal(accion, doctores = null) {
    const modalTitle = document.getElementById('pacienteModalLabel');
    const doctorId = document.getElementById('doctorId');
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    const cedula = document.getElementById('cedula');
    const telefono = document.getElementById('numero_telefono');
    const correo = document.getElementById('correo');
    const direccion = document.getElementById('direccion');
    const btn = document.getElementById('botton');

    if (accion === 'crear') {
        modalTitle.innerText = 'Registrar Doctor';
        doctorId.value = '';
        nombre.value = '';
        apellido.value = '';
        cedula.value = '';
        telefono.value = '';
        correo.value = '';
        direccion.value = '';
        btn.innerText = 'Guardar';

    } else if (accion === 'editar' && doctores) {
        modalTitle.innerText = 'Editar Doctor';
        doctorId.value = doctores.id;
        nombre.value = doctores.nombre;
        apellido.value = doctores.apellido;
        cedula.value = doctores.cedula;
        telefono.value = doctores.telefono;
        correo.value = doctores.correo;
        direccion.value = doctores.direccion;
        btn.innerText = 'Guardar';
    }
}



document.getElementById('cedula').addEventListener('keyup', function () {
    const cedula = this.value.trim();
    const mensajeDiv = document.getElementById('mensaje-cedula');

    if (cedula.length > 0) {
        fetch(`index.php?c=personal&a=verificarCedula&cedula=${cedula}`)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    mensajeDiv.textContent = "La cédula ya está registrada.";
                } else {
                    mensajeDiv.textContent = " ";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        mensajeDiv.textContent = " ";
    }
});
