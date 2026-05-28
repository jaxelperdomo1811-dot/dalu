/**
 * proveedores.js – Inicialización de intlTelInput para formularios de proveedores (usando CDN).
 */
document.addEventListener('DOMContentLoaded', () => {
    if (!window.intlTelInput) {
        console.error('[proveedores.js] intlTelInput no está cargado.');
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
});
