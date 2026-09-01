/**
 * ventas.js - Funciones para Ventas
 */

// --- Configuración de Intro.js para Ventas ---
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
                        title: "Módulo de Ventas",
                        intro: "Aquí registras y gestionas las ventas directas a los clientes."
                    },
                    {
                        element: document.querySelector('button[data-bs-target="#agregarVentaModal"]'),
                        intro: "Presiona este botón para iniciar el registro de una nueva venta."
                    },
                    {
                        element: document.querySelector('.table-responsive'),
                        intro: "Aquí puedes ver el historial de ventas concretadas."
                    }
                ]
            });
            tour.start();
        });
    }
});
