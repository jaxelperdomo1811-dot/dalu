document.addEventListener('DOMContentLoaded', () => {
    const phoneInputs = document.querySelectorAll('input[name="telefono"]');
    const itiInstances = new Map();

    phoneInputs.forEach((input) => {
        if (!window.intlTelInput) {
            return;
        }

        const iti = window.intlTelInput(input, {
            initialCountry: 've',
            separateDialCode: true,
            preferredCountries: ['ve', 'us', 'co'],
            nationalMode: false,
            autoHideDialCode: false,
            placeholderNumberType: window.intlTelInput?.NUMBER_TYPE?.MOBILE,
            loadUtils: async () => {
                const basePath = window.location.origin + window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'));
                const module = await import(basePath + '/assets/js/libs/utils.js');
                return module.default;
            },
        });

        itiInstances.set(input, iti);

        input.addEventListener('blur', () => {
            if (!input.value.trim()) {
                input.setCustomValidity('');
                return;
            }

            if (typeof iti.isValidNumber === 'function' && !iti.isValidNumber()) {
                input.setCustomValidity('Teléfono inválido');
            } else {
                input.setCustomValidity('');
            }
        });
    });

    document.querySelectorAll('form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const phoneField = form.querySelector('input[name="telefono"]');
            if (!phoneField) {
                return;
            }

            const iti = itiInstances.get(phoneField);
            if (!iti) {
                return;
            }

            if (phoneField.value.trim() && typeof iti.isValidNumber === 'function') {
                if (!iti.isValidNumber()) {
                    phoneField.setCustomValidity('Teléfono inválido');
                    event.preventDefault();
                    phoneField.reportValidity();
                    return;
                } else {
                    phoneField.setCustomValidity('');
                }
            }

            // Check standard form validity before mutating values
            if (form.checkValidity && !form.checkValidity()) {
                return;
            }

            if (phoneField.value.trim()) {
                phoneField.value = iti.getNumber();
            }
        });
    });
});
