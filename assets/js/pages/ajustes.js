/**
 * ajustes.js - Funciones para Ajustes
 */

// --- Configuración de Intro.js para Ajustes ---
document.addEventListener('DOMContentLoaded', () => {
    const btnAyuda = document.getElementById('btnAyudaInteractiva');
    if (btnAyuda) {
        btnAyuda.addEventListener('click', (e) => {
            e.preventDefault();
            const tour = introJs();
            tour.setOptions({
                nextLabel: 'Siguiente',
                prevLabel: 'Anterior',
                doneLabel: 'Entendido',
                exitOnOverlayClick: false,
                steps: [
                    {
                        title: "Módulo de Ajustes",
                        intro: "Aquí configuras opciones generales del sistema."
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: "Puedes ver o actualizar configuraciones clave, como la tasa del Dólar."
                    }
                ]
            });
            tour.start();
        });
    }
});
