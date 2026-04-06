import $ from 'jquery';
import 'datatables.net';
import 'datatables.net-responsive';

$(document).ready(function() {
    $('#usuarios-table').DataTable({
        ajax: '/admin/usuarios/data',
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'name', title: 'Nombre' },
            { data: 'email', title: 'Email' },
            { data: 'created_at', title: 'Creado' }
        ],
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });
});