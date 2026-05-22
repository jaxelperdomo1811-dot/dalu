// Función para mostrar las especialidades en la tabla
function mostrarEspecialidades() {
  fetch('index.php?c=especialidades&a=ver') // URL para obtener las especialidades
    .then(res => res.json()) // Parsear respuesta como JSON
    .then(data => {
      const tbody = document.querySelector('#tablaEspecialidades tbody');
      tbody.innerHTML = ''; // Limpiar contenido previo

      // Verificar si hay datos
      if (!Array.isArray(data) || data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="2">No hay Especialidades registradas.</td></tr>';
        return;
      }

      // Crear filas por cada especialidad
      data.forEach(especialidad => {
        const fila = `
          <tr data-id="${especialidad.id}">
            <td>${especialidad.nombre}</td>
            <td>
            <form action="index.php?c=especialidades&a=EliminarEspecialidades" method="POST">
                <input type="hidden" name="id" value="${especialidad.id}">
                <button type="submit" class="btn btn-danger">Eliminar</button>
            </form>
            </td>
          </tr>`;
        tbody.innerHTML += fila; // Agregar fila a la tabla
      });
    })
    .catch(error => console.error('Error:', error)); // Manejar errores
}

// Función para cargar especialidades en el select
function cargarEspecialidades() {
  fetch('index.php?c=servicios&a=getEspecialidades')
    .then(res => res.json())
    .then(data => {
      const select = document.querySelector('#id_especialidad');
      select.innerHTML = '';

      if (!Array.isArray(data) || data.length === 0) {
        select.innerHTML = '<option value="">No hay Especialidades registradas.</option>';
        return;
      }

      // Opción por defecto (opcional)
      select.innerHTML += '<option value="">Seleccione una especialidad</option>';

      data.forEach(especialidad => {
        const option = document.createElement('option');
        option.value = especialidad.id; // Asegúrate que 'id' existe en los datos
        option.textContent = especialidad.nombre; // Asegúrate que 'nombre' existe
        select.appendChild(option);
      });
    })
    .catch(error => console.error('Error:', error));
}

// Evento al cargar el DOM para inicializar la lista y gestionar el formulario
document.addEventListener('DOMContentLoaded', () => {
  mostrarEspecialidades();
  cargarEspecialidades(); // Cargar especialidades al inicio

  // Manejar envío del formulario para agregar especialidades
  const form = document.querySelector('#formAgregarEspecialidad');
  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar recarga de página

    const formData = new FormData(form);
    fetch('index.php?c=especialidades&a=GuardarEspecialidades', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        // Actualizar lista después de guardar
        mostrarEspecialidades();
        cargarEspecialidades(); 
        form.reset(); // Limpiar formulario
      } else {
        console.error('Error al guardar:', data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
    });
  });
});
