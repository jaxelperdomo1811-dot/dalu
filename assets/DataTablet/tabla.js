let table = new DataTable('#myTable',{
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
        info: 'Pagina _PAGE_ de _PAGES_',
        infoEmpty: 'No hay registros disponibles',
        infoFiltered: '(filtrado de _MAX_ registros en total)',
        lengthMenu: 'Mostrar _MENU_ registros por pagina',
        zeroRecords: 'No se encontró nada, lo siento',
        search: "Buscador:",
    },
    "pageLength": 5,    
});