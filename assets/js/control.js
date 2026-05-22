function mostrarDiagnosticos(idPaciente, nombrePaciente) {
    // Establece el nombre del paciente en el encabezado del modal de diagnósticos
    document.getElementById('nombrePacienteDiagnostico').innerText = nombrePaciente;

    // Realiza una petición AJAX a tu backend para obtener los diagnósticos del paciente
    fetch(`index.php?c=control&a=getDiagnosticos&id=${idPaciente}`)
        .then(res => res.json()) // Convierte la respuesta en formato JSON
        .then(data => {
            // Selecciona el cuerpo de la tabla donde se mostrarán los diagnósticos
            const container = document.getElementById('tablaDiagnosticos').querySelector('tbody');
            container.innerHTML = ''; // Limpia el contenido anterior de la tabla

            // Verifica si no hay diagnósticos registrados
            if (data.length === 0) {
                // Si no hay diagnósticos, muestra un mensaje en la tabla
                container.innerHTML = '<tr><td colspan="5">No hay diagnósticos registrados.</td></tr>';
                return; // Sale de la función si no hay diagnósticos
            }

            // Crea filas en la tabla para cada diagnóstico recibido
            data.forEach(d => {
                // Crea una fila con los datos del diagnóstico
                const fila = `<tr>
                <td>${d.nota || 'N/A'}</td>
                <td>${d.medicamentos_recetados || 'N/A'}</td>
                <td>${d.fecha_control}</td>
                <td>${d.fecha_regreso || 'N/A'}</td>
                <td>${d.diagnostico}</td>
                </tr>`;
                container.innerHTML += fila; // Agrega la fila a la tabla
            });
        })
        .catch(error => {
            // Maneja cualquier error que ocurra durante la petición
            console.error('Error:', error);
        });

    // Muestra el modal de diagnósticos
    var myModal = new bootstrap.Modal(document.getElementById('diagnosticosModal'));
    myModal.show(); // Abre el modal
}




function abrirModalNuevoDiagnostico(idPaciente, nombrePaciente) {
    // Establece el ID del paciente en un campo oculto del formulario
    document.getElementById('idPacienteNuevo').value = idPaciente;

    // Limpia los campos del formulario para un nuevo diagnóstico
    document.getElementById('diagnostico').value = '';
    document.getElementById('medicamentos_recetados').value = '';
    document.getElementById('fecha_control').value = '';
    document.getElementById('fecha_regreso').value = '';
    document.getElementById('nota').value = '';

    // Crea una instancia del modal de nuevo diagnóstico
    var modal = new bootstrap.Modal(document.getElementById('nuevoDiagnosticoModal'));
    modal.show(); // Abre el modal
}


// Agrega un evento al formulario para manejar el envío
document.getElementById('formNuevoDiagnostico').addEventListener('submit', function (e) {
    e.preventDefault(); // Previene el comportamiento por defecto del formulario


    // Crea un objeto FormData con los datos del formulario
    const formData = new FormData(this);

    // Realiza una petición POST al servidor para guardar el diagnóstico
    fetch('index.php?c=control&a=GuardarControl', {
        method: 'POST', // Método de la petición
        body: formData // Datos del formulario
    })
        .then(res => res.json()) // Convierte la respuesta en formato JSON
        .then(data => {
            // Verifica si la respuesta indica éxito
            if (data.status === "success") {
                alert('Diagnóstico guardado'); // Muestra un mensaje de éxito
                var modalEl = document.getElementById('nuevoDiagnosticoModal');
                var modalInstance = bootstrap.Modal.getInstance(modalEl); // Obtiene la instancia del modal
                modalInstance.hide(); // Cierra el modal
            } else {
                // Si hay un error, muestra el mensaje de error
                alert('Error al guardar: ' + data.message);
            }
        })
        .catch(error => {
            // Maneja cualquier error que ocurra durante la petición
            console.error('Error:', error);
            alert('Error al guardar'); // Muestra un mensaje de error genérico
        });
});























//JS para registrar hospitalizacion
function abrirModalRegistrarHospitalizacion(idDiagnostico) {
    // Establece el ID del diagnóstico en un campo oculto del formulario
    document.getElementById('idDiagnostico').value = idDiagnostico;

    // Limpia los campos del formulario para una nueva hospitalización
    document.getElementById('fecha_hora_inicio').value = '';
    document.getElementById('precio_horas').value = '';
    document.getElementById('total').value = '';
    document.getElementById('fecha_hora_final').value = '';

    // Crea una instancia del modal de registrar hospitalización
    var modal = new bootstrap.Modal(document.getElementById('registrarHospitalizacionModal'));
    modal.show(); // Abre el modal
}
document.getElementById('formRegistrarHospitalizacion').addEventListener('submit', function (e) {
    e.preventDefault(); // Previene el comportamiento por defecto del formulario

    const formData = new FormData(this);

    fetch('index.php?c=hospitalizacion&a=GuardarHospitalizacion', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                alert('Hospitalización registrada'); // Mensaje de éxito
                var modalEl = document.getElementById('registrarHospitalizacionModal');
                var modalInstance = bootstrap.Modal.getInstance(modalEl);
                modalInstance.hide(); // Cierra el modal
            } else {
                alert('Error al registrar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al registrar');
        });
});
