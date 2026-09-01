/**
 * clientes.js – Lógica y validaciones para la vista de clientes.
 */
document.addEventListener('DOMContentLoaded', () => {

    // API Cedula integration
    const cedulaInput = document.getElementById('cedula');
    const tipoPersonaSelect = document.getElementById('tipo_persona');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const mensajeCedula = document.getElementById('mensaje-cedula');
    const labelNombre = document.getElementById('label-nombre');
    const inputApellido = document.getElementById('input-apellido');
    const inputApellidoContainer = document.getElementById('input-apellido-container');
    const inputNombreContainer = document.getElementById('input-nombre-container');

    if (cedulaInput && tipoPersonaSelect) {
        cedulaInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });
        cedulaInput.addEventListener('blur', async () => {
            const cedula = cedulaInput.value.trim();
            const tipo = tipoPersonaSelect.value;
            
            if (cedula.length >= 6 && tipo) {
                try {
                    mensajeCedula.style.color = 'blue';
                    mensajeCedula.innerText = 'Consultando cédula...';
                    
                    const response = await fetch(`?c=clientes&accion=consultarCedula&tipo_persona=${tipo}&cedula=${cedula}`);
                    const textData = await response.text();
                    
                    try {
                        const data = JSON.parse(textData);
                        const modal = cedulaInput.closest('.modal') || document;
                        const nInput = modal.querySelector('input[name="nombre"]');
                        const aInput = modal.querySelector('input[name="apellido"]');
                        const btnSubmit = modal.querySelector('button[type="submit"]');
                        
                        if (data.exists) {
                            const persona = data.data;
                            if (nInput) nInput.value = persona.nombre_completo || '';
                            if (aInput) aInput.value = '';

                            mensajeCedula.style.color = 'green';
                            mensajeCedula.innerText = 'Datos encontrados.';
                            if (btnSubmit) btnSubmit.disabled = false;
                        } else {
                            // No existe en BD
                            mensajeCedula.style.color = data.valid_format ? 'orange' : 'red';
                            mensajeCedula.innerText = data.message || 'Cédula no encontrada. Por favor ingrese su nombre y apellido manualmente';
                            if (nInput) nInput.value = '';
                            if (aInput) aInput.value = '';
                            if (btnSubmit) btnSubmit.disabled = false;
                        }
                    } catch (e) {
                        console.error('Invalid JSON response', textData);
                        mensajeCedula.style.color = 'red';
                        mensajeCedula.innerText = 'Error al parsear la respuesta.';
                        const modal = cedulaInput.closest('.modal') || document;
                        const btnSubmit = modal.querySelector('button[type="submit"]');
                        if (btnSubmit) btnSubmit.disabled = false;
                    }
                } catch (error) {
                    console.error('Error al consultar cédula:', error);
                    mensajeCedula.style.color = 'red';
                    mensajeCedula.innerText = 'Error al conectar con la API.';
                    const modal = cedulaInput.closest('.modal') || document;
                    const btnSubmit = modal.querySelector('button[type="submit"]');
                    if (btnSubmit) btnSubmit.disabled = false;
                }
            } else {
                mensajeCedula.innerText = '';
                const modal = cedulaInput.closest('.modal') || document;
                const btnSubmit = modal.querySelector('button[type="submit"]');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        });
    }
    if (tipoPersonaSelect) {
        tipoPersonaSelect.addEventListener('change', () => {
            const tipo = tipoPersonaSelect.value;
            if (tipo === 'J-') {
                inputNombreContainer.classList.remove('col-md-6');
                inputNombreContainer.classList.add('col-md-12');
                labelNombre.innerText = 'Razon Social';
                inputApellido.value = '';
                inputApellido.required = false;
                inputApellido.removeAttribute('required');
                inputApellidoContainer.style.display = 'none';
            } else {
                inputNombreContainer.classList.remove('col-md-12');
                inputNombreContainer.classList.add('col-md-6');
                labelNombre.innerText = 'Nombre';
                inputApellido.value = '';
                inputApellido.setAttribute('required', '');
                inputApellidoContainer.style.display = 'block';
            }
        });
    }
});
