function prepararModal(accion, paciente = null) {
    const modalTitle = document.getElementById('pacienteModalLabel');
    const pacienteId = document.getElementById('pacienteId');
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    const segNombre = document.getElementById('seg_nombre');
    const segApellido = document.getElementById('seg_apellido');
    const tipoCedula = document.getElementById('tipo_cedula');
    const cedula = document.getElementById('cedula');
    const telefono = document.getElementById('numero_telefono');
    const correo = document.getElementById('correo');
    const direccion = document.getElementById('direccion');
    const fechaNacimiento = document.getElementById('fecha_nacimiento');
    const sexo = document.getElementById('sexo');
    const tipoSangre = document.getElementById('tipo_sangre');
    const observacion = document.getElementById('observacion');
    const btn = document.getElementById('botton');

    if (accion === 'crear') {
        modalTitle.innerText = 'Registrar paciente';
        pacienteId.value = '';
        nombre.value = '';
        apellido.value = '';
        segNombre.value = '';
        segApellido.value = '';
        tipoCedula.value = '';
        cedula.value = '';
        telefono.value = '';
        correo.value = '';
        direccion.value = '';
        fechaNacimiento.value = ''; // Limpiar el campo de fecha
        sexo.value = '';
        tipoSangre.value = '';
        observacion.value = '';
        btn.innerText = 'Guardar';

    } else if (accion === 'editar' && paciente) {
        modalTitle.innerText = 'Editar Paciente';
        pacienteId.value = paciente.id;
        nombre.value = paciente.nombre;
        apellido.value = paciente.apellido;
        segNombre.value = paciente.segNombre || ''; // Manejo de segundo nombre
        segApellido.value = paciente.segApellido || ''; // Manejo de segundo apellido
        tipoCedula.value = paciente.tipo_cedula;
        cedula.value = paciente.cedula;
        telefono.value = paciente.telefono;
        correo.value = paciente.correo;
        direccion.value = paciente.direccion;
        fechaNacimiento.value = paciente.f_n || ''; // Asegúrate de que el formato sea correcto (YYYY-MM-DD)
        sexo.value = paciente.sexo || ''; // Manejo de sexo
        tipoSangre.value = paciente.tipo_sangre || ''; // Manejo de tipo de sangre
        observacion.value = paciente.observacion || ''; // Manejo de observaciones
        btn.innerText = 'Guardar';

    } else if (accion === 'ver' && paciente) {
        // Llenar los campos del modal con los datos del paciente para visualizar
        document.getElementById("modalNombre").textContent = `${paciente.nombre} ${paciente.apellido}`;
        document.getElementById("modalCedula").textContent = `Cédula: ${paciente.tipo_cedula}-${paciente.cedula}`;
        document.getElementById("modalTelefono").textContent = `Teléfono: ${paciente.telefono}`;
        document.getElementById("modalDireccion").textContent = `Dirección: ${paciente.direccion}`;
        document.getElementById("modalFechaNacimiento").textContent = `Fecha de Nacimiento: ${paciente.fecha_nacimiento || 'No disponible'}`;
    }
}



document.getElementById('cedula').addEventListener('keyup', function () {
    const cedula = this.value.trim();
    const mensajeDiv = document.getElementById('mensaje-cedula');

    if (cedula.length > 0) {
        fetch(`index.php?c=pacientes&a=verificarCedula&cedula=${cedula}`)
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
function mostrarDatosPaciente(button) {
    const pacienteId = button.getAttribute('data-id');

    fetch(`index.php?c=pacientes&a=obtenerPaciente&id=${pacienteId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            // Crear el modal si no existe
            let modalEl = document.getElementById('dynamicPacienteModal');
            if (!modalEl) {
                modalEl = document.createElement('div');
                modalEl.id = 'dynamicPacienteModal';
                modalEl.className = 'modal fade';
                modalEl.setAttribute('tabindex', '-1');
                modalEl.setAttribute('aria-hidden', 'true');

                // Contenido del modal con estilos mejorados
                modalEl.innerHTML = `

<div class="modal-dialog modal-md">
  <div class="modal-content shadow rounded text-center">
    <div class="modal-header">
      <h5 class="modal-title"><i class="bi bi-person-fill me-2"></i>Datos del Paciente</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
    </div>
    <div class="modal-body p-4">
      
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <h6><i class="bi bi-person-fill"></i> Nombre</h6>
          <p id="modalNombre" class="mb-0"></p>
        </div>
        <div class="col-md-6">
          <h6><i class="bi bi-person-lines-fill"></i> Segundo Nombre</h6>
          <p id="modalSegNombre" class="mb-0"></p>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <h6><i class="bi bi-person-fill"></i> Apellido</h6>
          <p id="modalApellido" class="mb-0"></p>
        </div>
        <div class="col-md-6">
          <h6><i class="bi bi-person-lines-fill"></i> Segundo Apellido</h6>
          <p id="modalSegApellido" class="mb-0"></p>
        </div>
      </div>

      <hr>

      <ul class="list-group text-start mb-4">
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><i class="bi bi-credit-card-2-front"></i> Cédula</span>
          <strong id="modalCedula"></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><i class="bi bi-file-earmark-text"></i> Tipo de Cédula</span>
          <strong id="modalTipoCedula"></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><i class="bi bi-telephone-fill"></i> Teléfono</span>
          <strong id="modalTelefono"></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><i class="bi bi-envelope-fill"></i> Correo</span>
          <strong id="modalCorreo"></strong>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span><i class="bi bi-geo-alt-fill"></i> Dirección</span>
          <strong id="modalDireccion"></strong>
        </li>
      </ul>

      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <h6><i class="bi bi-calendar-event-fill"></i> Fecha de Nacimiento</h6>
          <p id="modalFechaNacimiento" class="mb-0"></p>
        </div>
        <div class="col-md-4">
          <h6><i class="bi bi-gender-ambiguous"></i> Sexo</h6>
          <p id="modalSexo" class="mb-0"></p>
        </div>
        <div class="col-md-4">
          <h6><i class="bi bi-droplet-fill"></i> Tipo de Sangre</h6>
          <p id="modalTipoSangre" class="mb-0"></p>
        </div>
      </div>

      <hr>

      <h6><i class="bi bi-journal-text"></i> Observaciones</h6>
      <p id="modalObservacion"></p>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    </div>
  </div>
</div>

`;
                document.body.appendChild(modalEl);
            }

            // Llenar los datos en el modal
            document.getElementById("modalNombre").textContent = `${data.nombre}`;
            document.getElementById("modalSegNombre").textContent = `${data.segNombre || 'No disponible'}`;
            document.getElementById("modalApellido").textContent = `${data.apellido}`;
            document.getElementById("modalSegApellido").textContent = `${data.segApellido || 'No disponible'}`;
            document.getElementById("modalCedula").textContent = `${data.tipo_cedula}-${data.cedula}`;
            document.getElementById("modalTipoCedula").textContent = `${data.tipo_cedula}`;
            document.getElementById("modalTelefono").textContent = `${data.telefono}`;
            document.getElementById("modalCorreo").textContent = `${data.correo || 'No disponible'}`;
            document.getElementById("modalDireccion").textContent = `${data.direccion || 'No disponible'}`;
            document.getElementById("modalFechaNacimiento").textContent = `${data.f_n || 'No disponible'}`;
            document.getElementById("modalSexo").textContent = `${data.sexo || 'No disponible'}`;
            document.getElementById("modalTipoSangre").textContent = `${data.tipo_sangre || 'No disponible'}`;
            document.getElementById("modalObservacion").textContent = `${data.observacion || 'Ninguna'}`;

            // Mostrar el modal usando Bootstrap
            const bootstrapModal = new bootstrap.Modal(document.getElementById('dynamicPacienteModal'));
            bootstrapModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al obtener los datos del paciente.');
        });
}