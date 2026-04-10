$(document).ready(function () {
    var table = $('#ventas-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/ventas/data',
            type: 'GET'
        },
        columns: [
            { data: 'sale_date', title: 'Fecha' },
            { data: 'items_list', title: 'Ítems', orderable: false, searchable: false },
            { data: 'payment_method', title: 'Método' },
            { data: 'user', title: 'Usuario' },
            { data: 'glosa', title: 'Glosa', orderable: false, searchable: false },
            {
                data: 'total',
                title: 'Total',
                render: function (data) {
                    return 'Bs ' + numberWithDots(parseFloat(data).toFixed(2));
                }
            },
            {
                data: 'acciones',
                title: 'Acciones',
                orderable: false,
                searchable: false
            }
        ],
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        }
    });

    $('#reload-table').on('click', function () {
        var button = $(this);
        $('#reload-text').addClass('hidden');
        $('#reload-spinner').removeClass('hidden');
        button.prop('disabled', true);
        table.ajax.reload(function () {
            $('#reload-text').removeClass('hidden');
            $('#reload-spinner').addClass('hidden');
            button.prop('disabled', false);
        });
    });

    $('#ventas-table').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/ventas/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Eliminado!',
                            text: 'La venta ha sido eliminada.',
                            icon: 'success',
                            timer: 1800,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        var errorMessage = 'No se pudo eliminar la venta.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    });

    function numberWithDots(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
});
