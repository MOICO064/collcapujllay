$(document).ready(function () {
    var table = $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/roles/data',
            type: 'GET'
        },

        columns: [
            { data: 'name', title: 'Nombre' },
            { data: 'guard_name', title: 'Guard' },
            {
                data: 'permisos',
                title: 'Permisos',
                orderable: false,
                searchable: false
            },
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

    $('#reload-roles-table').on('click', function () {
        var button = $(this);
        $('#reload-roles-text').addClass('hidden');
        $('#reload-roles-spinner').removeClass('hidden');
        button.prop('disabled', true);
        table.ajax.reload(function () {
            $('#reload-roles-text').removeClass('hidden');
            $('#reload-roles-spinner').addClass('hidden');
            button.prop('disabled', false);
        });
    });

    $('#roles-table').on('click', '.delete-role-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/roles/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Eliminado!',
                            text: 'El rol ha sido eliminado.',
                            icon: 'success',
                            timer: 1800,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        var errorMessage = 'No se pudo eliminar el rol.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                var response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {

                            }
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            }
        });
    });
});
