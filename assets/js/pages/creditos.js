document.addEventListener('DOMContentLoaded', () => {

    // Logica del Wizard de Pagos en V_Creditos
    const modalWizardEl = document.getElementById('modalWizardPago');
    if (!modalWizardEl) return; // Salir si no estamos en la vista correcta

    const modalWizard = new bootstrap.Modal(modalWizardEl);
    const step1 = document.getElementById('wizardStep1');
    const step2 = document.getElementById('wizardStep2');
    
    const btnBack = document.getElementById('btnBackStep1');
    const options = document.querySelectorAll('.wizard-option');
    const divOtro = document.getElementById('divInputOtroMonto');
    const inputOtro = document.getElementById('inputOtroMonto');
    const btnConfirmarLibre = document.getElementById('btnConfirmarMontoLibre');

    let montoSeleccionadoUsd = 0;
    let maxMontoUsd = 0;
    let tasaDia = 1;

    // Al abrir el wizard desde cualquier boton "Abonar"
    document.querySelectorAll('.btn-abrir-wizard').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const idNota = btn.getAttribute('data-id-nota');
            const cProx = parseFloat(btn.getAttribute('data-cuota-prox')) || 0;
            const cTodo = parseFloat(btn.getAttribute('data-total-pend')) || 0;
            tasaDia = parseFloat(btn.getAttribute('data-tasa')) || 1;
            const cliente = btn.getAttribute('data-cliente');

            document.getElementById('wizard_id_nota').value = idNota;
            document.getElementById('wizard_cliente_nombre').innerText = cliente;
            document.getElementById('lbl_cuota_proxima').innerText = cProx.toFixed(2);
            document.getElementById('lbl_cuota_todo').innerText = cTodo.toFixed(2);
            document.getElementById('lbl_max_monto').innerText = cTodo.toFixed(2);
            
            maxMontoUsd = cTodo;
            
            // Reset UI
            step1.classList.remove('d-none');
            step2.classList.add('d-none');
            divOtro.classList.add('d-none');
            inputOtro.value = '';

            modalWizard.show();
        });
    });

    // Seleccion de opciones en Paso 1
    options.forEach(opt => {
        opt.addEventListener('click', () => {
            const type = opt.getAttribute('data-type');
            
            if (type === 'proxima') {
                montoSeleccionadoUsd = parseFloat(document.getElementById('lbl_cuota_proxima').innerText);
                avanzarPaso2();
            } else if (type === 'todo') {
                montoSeleccionadoUsd = maxMontoUsd;
                avanzarPaso2();
            } else if (type === 'otro') {
                divOtro.classList.remove('d-none');
                inputOtro.focus();
            }
        });
    });

    btnConfirmarLibre.addEventListener('click', () => {
        const val = parseFloat(inputOtro.value) || 0;
        if (val <= 0) {
            alert("Ingrese un monto válido.");
            return;
        }
        if (val > maxMontoUsd) {
            alert("El monto no puede superar la deuda total de $" + maxMontoUsd.toFixed(2));
            return;
        }
        montoSeleccionadoUsd = val;
        avanzarPaso2();
    });

    function avanzarPaso2() {
        step1.classList.add('d-none');
        step2.classList.remove('d-none');

        document.getElementById('wizard_monto_final_usd').innerText = montoSeleccionadoUsd.toFixed(2);
        document.getElementById('wizard_monto_final_bs').innerText = (montoSeleccionadoUsd * tasaDia).toFixed(2);
        
        // Set hidden value
        document.getElementById('input_monto_enviar').value = montoSeleccionadoUsd;
    }

    btnBack.addEventListener('click', () => {
        step2.classList.add('d-none');
        step1.classList.remove('d-none');
    });

    // Detectar moneda según el método seleccionado
    const wizardMetodo = document.getElementById('wizard_metodo');
    if (wizardMetodo) {
        wizardMetodo.addEventListener('change', function() {
            const nombreMetodo = this.options[this.selectedIndex].text.toLowerCase();
            const inputMoneda = document.getElementById('input_moneda_enviar');
            const inputMonto = document.getElementById('input_monto_enviar');
            const divReferencia = document.getElementById('div_referencia_wizard');
            const inputReferencia = document.getElementById('input_referencia_wizard');
            
            if (nombreMetodo.includes('bs') || nombreMetodo.includes('pago móvil')) {
                inputMoneda.value = 'VES';
                inputMonto.value = (montoSeleccionadoUsd * tasaDia).toFixed(2);
            } else {
                inputMoneda.value = 'USD';
                inputMonto.value = montoSeleccionadoUsd.toFixed(2);
            }

            if (nombreMetodo.includes('efectivo')) {
                divReferencia.classList.add('d-none');
                inputReferencia.removeAttribute('required');
                inputReferencia.value = 'N/A';
            } else {
                divReferencia.classList.remove('d-none');
                inputReferencia.setAttribute('required', 'required');
                if (inputReferencia.value === 'N/A') inputReferencia.value = '';
            }
        });
    }
});
