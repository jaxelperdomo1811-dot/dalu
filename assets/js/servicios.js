function seleccionarDoctor(idDoctor, nombreCompleto) {
    document.getElementById("busqueda_Doctor").value = nombreCompleto; // Mostrar en input
    document.getElementById("id_doctor").value = idDoctor; // Guardar ID en el campo oculto
    document.getElementById("lista_Doctor").style.display = "none"; // Ocultar lista
}
// Función para buscar doctores (autocompletar)
function buscarDoctor() {
    const input = document.getElementById("busqueda_Doctor").value.trim();
    const lista = document.getElementById("lista_Doctor");

    if (input.length > 0) {
        const url = "index.php?c=servicios&a=autocompletarDoctores";
        const formData = new FormData();
        formData.append("campo", input);

        fetch(url, { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                lista.innerHTML = ""; // Limpiar resultados anteriores

                if (data.length > 0) {
                    data.forEach(doctor => {
                        let li = document.createElement("li");
                        li.textContent = `${doctor.cedula} - ${doctor.nombre} ${doctor.apellido}`;

                        // Estilos en línea
                        li.style.padding = "8px 12px";
                        li.style.cursor = "pointer";
                        li.style.borderBottom = "1px solid #eee";
                        li.style.transition = "background-color 0.2s";

                        // Efecto hover
                        li.addEventListener("mouseenter", () => {
                            li.style.backgroundColor = "#f5f5f5";
                        });
                        li.addEventListener("mouseleave", () => {
                            li.style.backgroundColor = "";
                        });

                        // Al hacer clic, seleccionar el doctor
                        // li.onclick = () => {
                        //     seleccionarDoctor(doctor.id, `${doctor.cedula} - ${doctor.nombre} ${doctor.apellido}`);
                        // };
                        li.addEventListener("mousedown", () => {
                            seleccionarDoctor(doctor.id, `${doctor.cedula} - ${doctor.nombre} ${doctor.apellido}`);
                        });

                        lista.appendChild(li);
                    });
                    lista.style.display = 'block'; // Mostrar la lista
                } else {
                    lista.style.display = 'none'; // Ocultar si no hay resultados
                }
            })
            .catch(err => console.log(err));
    } else {
        lista.style.display = 'none'; // Ocultar si el input está vacío
    }
}

// Event listener para buscar doctores al escribir
document.getElementById("busqueda_Doctor").addEventListener("keyup", buscarDoctor);

// Agregar evento blur para verificar si el paciente está registrado
document.getElementById("busqueda_Doctor").addEventListener("blur", function () {
    let input = this.value;
    let lista = document.getElementById("lista_Doctor");
    let pacientes = Array.from(lista.getElementsByTagName("li")).map(li => li.textContent.trim());
    // Verificar si el paciente ingresado está en la lista
    if (input.length > 0 && !pacientes.some(paciente => input === paciente)) {
        alert("Doctor no registrado, verifique los datos");
        this.value = ''; // Opcional: limpiar el campo de búsqueda
    }
});







const modal = document.getElementById('modal');
const tbodyEspecialidades = document.querySelector('#tabla-especialidades tbody');
const formEspecialidades = document.getElementById('form-especialidades');
let doctorIdActual = null;


// Selecciona todos los botones con clase 'btn-especialidades' y les agrega un evento click
document.querySelectorAll('.btn-especialidades').forEach(btn => {
    btn.addEventListener('click', () => {
        // Obtiene el id del doctor desde el atributo data-id del botón clickeado
        doctorIdActual = btn.getAttribute('data-id');

        // Realiza una petición GET para obtener las especialidades y precios del doctor
        fetch(`index.php?c=servicios&a=getEspecialidadesDoctorSimple&doctor_id=${doctorIdActual}`)
            .then(res => res.json()) // Convierte la respuesta a JSON
            .then(data => {
                // Limpia el contenido actual del tbody de la tabla de especialidades
                tbodyEspecialidades.innerHTML = '';

                // Si no hay especialidades, muestra un mensaje indicando que no hay datos
                if (data.length === 0) {
                    tbodyEspecialidades.innerHTML = '<tr><td colspan="2">No hay especialidades.</td></tr>';
                } else {
                    // Si hay especialidades, recorre cada una y crea una fila con su nombre y precio editable
                    data.forEach(e => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${e.nombre}</td>
                            <td>
                                <input
                                    type="number" 
                                    name="precio" 
                                    value="${e.precio}" 
                                    min="0"                                   
                                    data-servicio-id="${e.servicio_id}" 
                                    required
                                >
                            </td>
                        `;
                        // Agrega la fila creada al tbody
                        tbodyEspecialidades.appendChild(tr);
                    });
                }
                // Muestra el modal con la tabla de especialidades y precios
                modal.style.display = 'block';
            });
    });
});

// Evento para cerrar el modal cuando se clickea el botón 'Cerrar'
document.getElementById('cerrar-modal').addEventListener('click', () => {
    // Oculta el modal
    modal.style.display = 'none';
    // Limpia la tabla para dejarla vacía para la próxima vez
    tbodyEspecialidades.innerHTML = '';
});

// Evento para enviar el formulario que actualiza los precios de las especialidades
formEspecialidades.addEventListener('submit', e => {
    e.preventDefault(); // Previene que la página recargue al enviar el formulario

    // Obtiene todos los inputs donde el usuario puede editar el precio
    const inputs = tbodyEspecialidades.querySelectorAll('input[name="precio"]');
    const precios = [];

    // Recorre cada input para crear un array con los datos que se enviarán al servidor
    inputs.forEach(input => {
        precios.push({
            id: input.getAttribute('data-servicio-id'), // id del servicio especialidad
            precio: parseFloat(input.value) // precio ingresado, convertido a número decimal
        });
    });

    // Envía la información al backend usando fetch con método POST
    fetch('index.php?c=servicios&a=actualizarPreciosEspecialidades', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        // El array precios se convierte a JSON y se codifica para enviarlo correctamente
        body: 'precios=' + encodeURIComponent(JSON.stringify(precios))
    })
    .then(res => res.json()) // Convierte la respuesta a JSON
    .then(response => {
        // Si el servidor confirma que la actualización fue exitosa
        if (response.status === 'success') {
            alert('Precios actualizados correctamente');
            // Cierra el modal
            modal.style.display = 'none';
        } else {
            // Si hubo error, muestra mensaje de error
            alert('Error al actualizar precios');
        }
    });
});






