
function asignarDoctorId(id) {
    document.getElementById('id_personal').value = id; // Asigna el ID del doctor al campo oculto
    cargarHorario(); // Carga los días laborables
    cargarHorariosPorDoctor(id); // Asegúrate de que 'id' se pasa aquí
}

function cargarHorariosPorDoctor(id) {
    fetch(`index.php?c=horarioPersonal&a=getHorariosPorDoctor&id=${id}`)
        .then(res => res.json())
        .then(data => {
            const horarioContent = document.getElementById('horario_content');
            horarioContent.innerHTML = ''; // Limpiar contenido previo

            if (!Array.isArray(data) || data.length === 0) {
                horarioContent.innerHTML = '<p>No hay horarios registrados para este doctor.</p>';
                return;
            }

            // Construir HTML con los horarios
            let html = '';
            data.forEach(horario => {
                const id = horario.id || 'ID no definido'; // Manejo de undefined
                const diasLaborables = horario.dias_laborables || 'Días laborables no definidos'; // Manejo de undefined
                const horaEntrada = horario.hora_entrada || 'Hora no definida'; // Manejo de undefined
                const horaSalida = horario.hora_salida || 'Hora no definida'; // Manejo de undefined

                html += `
                    <div>
                      <p>${diasLaborables}: ${horaEntrada} - ${horaSalida}
                      <button class="btn btn-black fw-bold" onclick="eliminarHorario(${id})">X</button>
                      </p>
                  </div>
                `;
            });


            horarioContent.innerHTML = html; // Mostrar los horarios en el contenedor
        })
        .catch(error => {
            console.error('Error:', error);
            horarioContent.innerHTML = '<p>Error al cargar horarios</p>';
        });
}

function eliminarHorario(id) {
    if (!confirm("¿Estás seguro de que deseas eliminar este horario?")) {
        return; // Si el usuario cancela, no hacer nada
    }

    fetch(`index.php?c=horarioPersonal&a=EliminarHorarioPersonal`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}` // Enviar el ID del horario a eliminar
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Horario eliminado correctamente');
                bootstrap.Modal.getInstance(document.getElementById('registroModal')).hide();

            } else {
                alert('Error al eliminar el horario: ' + (data.message || 'Error desconocido'));
            }
        })

}




// Función para cargar días laborables en el select
function cargarHorario() {
    fetch('index.php?c=horarioPersonal&a=getHorar')
        .then(res => res.json())
        .then(data => {
            const select = document.querySelector('#id_horario');
            select.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                select.innerHTML = '<option value="">No hay Horarios registrados.</option>';
                return;
            }

            // Opción por defecto (opcional)
            select.innerHTML += '<option value="">Seleccione un Día</option>';

            data.forEach(horario => {
                const option = document.createElement('option');
                option.value = horario.id; // Asegúrate que 'id' existe en los datos
                option.textContent = horario.dias_laborables; // Asegúrate que 'dias_laborables' existe
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Función para enviar el formulario con AJAX
function enviarFormulario(event) {
    event.preventDefault();

    const form = document.getElementById('registroForm');
    const formData = new FormData(form);

    fetch('index.php?c=horarioPersonal&a=GuardarHorarioPersonal', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                alert('Horario guardado correctamente');
                // Cerrar el modal
                bootstrap.Modal.getInstance(document.getElementById('registroModal')).hide();
                // Opcional: recargar o actualizar la tabla
            } else {
                alert('Error: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al enviar el formulario');
        });
}

// Asignar el evento de submit al formulario
document.getElementById('registroForm').addEventListener('submit', enviarFormulario);
