// Función para seleccionar cliente
function seleccionarcliente(idcliente, nombreCompleto) {
    document.getElementById("busqueda_paciente").value = nombreCompleto;
    document.getElementById("id_cliente").value = idclientee;
    document.getElementById("lista_clientes").style.display = "none";
}

function buscarclientes() {
    const input = document.getElementById("busqueda_cliente").value.trim();
    const lista = document.getElementById("lista_clientes");

    if (input.length > 0) {
        const url = "index.php?c=citas&a=AutocompletarClientes";
        const formData = new FormData();
        formData.append("campo", input);

        fetch(url, {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = "";

                if (data.length > 0) {
                    data.forEach(cliente => {
                        const li = document.createElement("li");

                        // Usamos clases Bootstrap + clase personalizada para efecto
                        li.className = "list-group-item list-group-item-action cliente-item";
                        li.textContent = `${cliente.cedula} - ${cliente.nombre} ${cliente.apellido}`;

                        li.addEventListener("mousedown", () => {
                            seleccionarCliente(cliente.id, li.textContent);
                        });

                        lista.appendChild(li);
                    });

                    // Mostrar lista
                    lista.classList.add("list-group", "shadow-sm", "border");
                    lista.style.display = "block";
                } else {
                    lista.style.display = "none";
                }
            })
            .catch(err => {
                console.error("Error al buscar cliente:", err);
                lista.style.display = "none";
            });
    } else {
        lista.style.display = "none";
    }
}

// Event listener para buscar clientes
document.getElementById("busqueda_cliente").addEventListener("keyup", buscarclientes);

// Agregar evento blur para verificar si el cliente está registrado
document.getElementById("busqueda_cliente").addEventListener("blur", function () {
    let input = this.value;
    let lista = document.getElementById("lista_clientes");
    let clientes = Array.from(lista.getElementsByTagName("li")).map(li => li.textContent.trim());
    // Verificar si el cliente ingresado está en la lista
    if (input.length > 0 && !clientes.some(cliente => input === cliente)) {
        alert("cliente no registrado, verifique los datos");
        this.value = ''; // Opcional: limpiar el campo de búsqueda
    }
});




function seleccionarDoctor(idDoctor, nombreCompleto, idServicioMedico) {
    document.getElementById("busqueda_doctor").value = nombreCompleto;
    document.getElementById("id_servicio_medico").value = idServicioMedico;
    document.getElementById("lista_doctores").style.display = "none";

    // Mostrar el horario del doctor
    mostrarHorarioDoctor(idDoctor);
}
function cargarDoctoresPorEspecialidad() {
    const selectEspecialidad = document.getElementById("especialidad");
    const idEspecialidad = selectEspecialidad.value;
    const inputDoctor = document.getElementById("busqueda_doctor");
    const listaDoctores = document.getElementById("lista_doctores");

    if (idEspecialidad === "") {
        // Si no hay especialidad seleccionada, limpia la lista
        listaDoctores.innerHTML = "";
        listaDoctores.style.display = "none";
        return;
    }

    // Realizar fetch para obtener doctores de esa especialidad
    fetch(`index.php?c=citas&a=obtenerDoctoresPorEspecialidad&id_especialidad=${idEspecialidad}`)
        .then(res => res.json())
        .then(data => {
            // Limpiar la lista actual
            listaDoctores.innerHTML = "";

            if (data.length > 0) {
                data.forEach(doctor => {
                    let li = document.createElement("li");
                    li.textContent = doctor.nombre_completo;
                    li.style.padding = "8px 12px";
                    li.style.cursor = "pointer";
                    li.style.borderBottom = "1px solid #eee";
                    li.style.transition = "background-color 0.2s";
                    // Efecto hover
                    li.addEventListener("mouseenter", () => { li.style.backgroundColor = "#f5f5f5"; });
                    li.addEventListener("mouseleave", () => { li.style.backgroundColor = ""; });

                    li.onclick = () => { seleccionarDoctor(doctor.id, doctor.nombre_completo, doctor.id_servicio_medico); };
                    listaDoctores.appendChild(li);
                });
                listaDoctores.style.display = 'block';
                inputDoctor.value = data[0].nombre_completo;
            } else {
                listaDoctores.style.display = 'none';
            }
        })
        .catch(err => console.log(err));
}
document.getElementById("busqueda_doctor").addEventListener("blur", function () {
    let input = this.value;
    let lista = document.getElementById("lista_doctores");
    let doctores = Array.from(lista.getElementsByTagName("li")).map(li => li.textContent.trim());
    if (input.length > 0 && !doctores.some(doctor => input === doctor)) {
        alert("Doctor no registrado verifique los datos");
        this.value = ''; 
    }
});


function mostrarHorarioDoctor(doctorId) {
    fetch(`index.php?c=horarioPersonal&a=getHorariosPorDoctor&id=${doctorId}`)
        .then(res => res.json())
        .then(data => {
            const horarioDisplay = document.getElementById('horario_display');
            const horarioContent = document.getElementById('horario_content');

            horarioContent.innerHTML = ''; // Limpiar contenido anterior

            if (!Array.isArray(data) || data.length === 0) {
                horarioContent.innerHTML = '<p>No hay horario registrado</p>';
                horarioDisplay.style.display = 'block';
                return;
            }

            // Construir HTML con los horarios
            let html = '';
            data.forEach(horario => {
                const diasLaborables = horario.dias_laborables || 'Días laborables no definidos'; // Manejo de undefined
                const horaEntrada = horario.hora_entrada || 'Hora no definida'; // Manejo de undefined
                const horaSalida = horario.hora_salida || 'Hora no definida'; // Manejo de undefined

                html += `<p>${diasLaborables}: ${horaEntrada} - ${horaSalida}</p>`;
            });


            horarioContent.innerHTML = html;
            horarioDisplay.style.display = 'block'; // Asegúrate de mostrar el contenedor
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('horario_content').innerHTML = '<p>Error al cargar horarios</p>';
            document.getElementById('horario_display').style.display = 'block';
        });
}



// Función para preparar el modal de citas
function prepararModal(accion, citaData = {}) {
    // Rellena los datos comunes
    document.getElementById('citaId').value = citaData.id || '';
    document.getElementById('fecha').value = citaData.fecha || '';
    document.getElementById('motivo').value = citaData.motivo || '';

    if (accion === 'editar') {
        // Oculta los campos de búsqueda y select
        document.getElementById('buscarPaciente').style.display = 'none';
        document.getElementById('seleccionarEspecialidad').style.display = 'none';
        document.getElementById('buscarDoctor').style.display = 'none';

        // Limpia los valores de búsqueda y select
        document.getElementById('busqueda_paciente').value = '';
        document.getElementById('especialidad').value = '';
        document.getElementById('busqueda_doctor').value = '';

        // Deshabilita los inputs para evitar edición
        document.getElementById('especialidad').disabled = true;
        document.getElementById('busqueda_doctor').disabled = true;
        document.getElementById('busqueda_paciente').disabled = true;

        // Cambia el título del modal para editar
        document.getElementById('citaModalLabel').textContent = 'Editar Cita';

    } else {
        // Modo crear: mostrar campos y habilitarlos
        document.getElementById('buscarPaciente').style.display = 'block';
        document.getElementById('seleccionarEspecialidad').style.display = 'block';
        document.getElementById('buscarDoctor').style.display = 'block';

        // Limpia los campos para crear nueva cita
        document.getElementById('citaId').value = '';
        document.getElementById('busqueda_paciente').value = '';
        document.getElementById('especialidad').value = '';
        document.getElementById('busqueda_doctor').value = '';
        document.getElementById('fecha').value = '';
        document.getElementById('motivo').value = '';

        // Habilita los inputs
        document.getElementById('especialidad').disabled = false;
        document.getElementById('busqueda_doctor').disabled = false;
        document.getElementById('busqueda_paciente').disabled = false;

        // Oculta el horario del doctor si está visible
        const horarioDisplay = document.getElementById('horario_display');
        if (horarioDisplay) {
            horarioDisplay.style.display = 'none';
            document.getElementById('horario_content').innerHTML = '';
        }

        // Cambia el título del modal para crear
        document.getElementById('citaModalLabel').textContent = 'Registrar Cita';
    }
}
