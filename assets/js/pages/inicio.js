/**
 * inicio.js - Funciones para el Dashboard / Inicio
 */

document.addEventListener('DOMContentLoaded', () => {
    // --- Configuración de Intro.js para Inicio ---
    const btnAyuda = document.getElementById('btnAyudaInteractiva');
    if (btnAyuda) {
        btnAyuda.addEventListener('click', (e) => {
            e.preventDefault();
            const tour = introJs();
            tour.setOptions({
                nextLabel: 'Siguiente',
                prevLabel: 'Atrás',
                doneLabel: 'Entendido',
                steps: [
                    {
                        title: "Bienvenido a Dalu",
                        intro: "Este es tu panel principal donde puedes ver un resumen de tu negocio."
                    },
                    {
                        element: document.querySelector('.toggle-sidebar'),
                        intro: "Con este botón puedes ocultar o mostrar el menú lateral."
                    },
                    {
                        element: document.querySelector('.card-clientes'),
                        intro: "Aquí ves la cantidad total de clientes registrados."
                    },
                    {
                        element: document.querySelector('.card-productos'),
                        intro: "Aquí ves el total de productos en tu inventario."
                    },
                    {
                        element: document.getElementById('bcvDiv'),
                        intro: "Haz clic aquí para actualizar manualmente la tasa del Dólar BCV."
                    }
                ]
            });
            tour.start();
        });
    }
});
