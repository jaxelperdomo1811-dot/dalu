/**
 * clientes.js – Inicialización de intlTelInput para formularios de clientes (usando CDN).
 */
document.addEventListener('DOMContentLoaded', () => {
    if (!window.intlTelInput) {
        console.error('[clientes.js] intlTelInput no está cargado.');
        return;
    }

    const errorMap = [
        "Número inválido",
        "Código de país no válido",
        "Demasiado corto",
        "Demasiado largo",
        "Número inválido"
    ];

    // Mapa de instancias: input -> iti
    const itiInstances = new Map();

    /**
     * Inicializa intlTelInput en un input dado.
     */
    function initIti(input) {
        if (itiInstances.has(input)) return itiInstances.get(input);

        const iti = window.intlTelInput(input, {
            initialCountry: "ve",
            nationalMode: false,
            showSelectedDialCode: true,
            preferredCountries: ['ve', 'us', 'co'],
            hiddenInputs: {
                phone: "phone_full",
                country: "country_iso2"
            },
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.5.0/build/js/utils.js"
        });

        itiInstances.set(input, iti);

        const errorMsg = input.parentElement.querySelector('.error-msg');
        const validMsg = input.parentElement.querySelector('.valid-msg');

        const reset = () => {
            input.classList.remove('error');
            input.setCustomValidity('');
            if (errorMsg) { errorMsg.innerHTML = ''; errorMsg.style.display = 'none'; }
            if (validMsg) { validMsg.style.display = 'none'; }
        };

        const validate = () => {
            reset();
            if (!input.value.trim()) return;

            if (iti.isValidNumber()) {
                if (validMsg) validMsg.style.display = 'block';
            } else {
                input.classList.add('error');
                const code = iti.getValidationError();
                const msg = errorMap[code] ?? 'Número inválido';
                input.setCustomValidity(msg);
                if (errorMsg) { errorMsg.innerHTML = msg; errorMsg.style.display = 'block'; }
            }
        };

        input.addEventListener('blur', validate);
        input.addEventListener('change', reset);
        input.addEventListener('keyup', reset);

        return iti;
    }

    // Inicializar de forma inmediata todos los campos telefónicos de la página
    document.querySelectorAll('input.phone-input').forEach(input => {
        initIti(input);
    });

    /**
     * Interceptar el envío de CADA formulario para:
     *  1. Bloquear si el número es inválido.
     *  2. Reemplazar el valor del input con el número E.164 (+CCXXXXXXXXX).
     */
    document.addEventListener('submit', (event) => {
        const form = event.target;
        const phoneField = form.querySelector('input.phone-input');
        if (!phoneField) return;

        const iti = itiInstances.get(phoneField);
        if (!iti) return;

        // Limpiar validez previa
        phoneField.setCustomValidity('');

        if (phoneField.value.trim()) {
            if (!iti.isValidNumber()) {
                const code = iti.getValidationError();
                const msg = errorMap[code] ?? 'Número de teléfono inválido';

                phoneField.classList.add('error');
                phoneField.setCustomValidity(msg);

                const errorMsg = phoneField.parentElement.querySelector('.error-msg');
                if (errorMsg) { errorMsg.innerHTML = msg; errorMsg.style.display = 'block'; }

                const validMsg = phoneField.parentElement.querySelector('.valid-msg');
                if (validMsg) validMsg.style.display = 'none';

                event.preventDefault();
                phoneField.reportValidity();
                return;
            }

            // Reemplazar con formato E.164 (+58XXXXXXXXXX)
            phoneField.value = iti.getNumber();
        }
    }, true);

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
                        if (data.data) {
                            const persona = data.data;
                            const modal = cedulaInput.closest('.modal') || document;
                            const nInput = modal.querySelector('input[name="nombre"]');
                            const aInput = modal.querySelector('input[name="apellido"]');
                            
                            if (nInput) nInput.value = `${persona.primer_nombre || ''} ${persona.segundo_nombre || ''}`.trim();
                            if (aInput) aInput.value = `${persona.primer_apellido || ''} ${persona.segundo_apellido || ''}`.trim();
                            
                            mensajeCedula.style.color = 'green';
                            mensajeCedula.innerText = 'Datos encontrados.';
                        } else {
                            mensajeCedula.style.color = 'red';
                            mensajeCedula.innerText = 'Cédula no encontrada.';
                        }
                    } catch (e) {
                        console.error('Invalid JSON response', textData);
                        mensajeCedula.style.color = 'red';
                        mensajeCedula.innerText = 'Error al parsear la respuesta.';
                    }
                } catch (error) {
                    console.error('Error al consultar cédula:', error);
                    mensajeCedula.style.color = 'red';
                    mensajeCedula.innerText = 'Error al conectar con la API.';
                }
            } else {
                mensajeCedula.innerText = '';
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
