$(document).ready(function () {
    var table = $('#promociones-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/promociones/data',
            type: 'GET'
        },
        columns: [
            { data: 'name', title: 'Nombre' },
            { data: 'periodo', title: 'Periodo' },
            { data: 'discount', title: 'Descuento' },
            { data: 'single_use', title: 'Uso único', orderable: false, searchable: false },
            { data: 'created_at', title: 'Creado' },
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

    $('#promociones-table').on('click', '.delete-btn', function () {
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
                    url: '/promociones/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Eliminado!',
                            text: 'La promoción ha sido eliminada.',
                            icon: 'success',
                            timer: 1800,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        var errorMessage = 'No se pudo eliminar la promoción.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    });
});
