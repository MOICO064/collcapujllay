$(document).ready(function () {
    var table = $('#usuarios-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/usuarios/data',
            type: 'GET'
        },

        columns: [
            { data: 'name', title: 'Nombre' },
            { data: 'role', title: 'Rol' },
            { data: 'email', title: 'Email' },
            {
                data: 'estado',
                title: 'Estado',
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

    // Recargar tabla con efecto de carga
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

    // Eliminar usuario
    $('#usuarios-table').on('click', '.delete-btn', function () {
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
                    url: '/usuarios/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function () {
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Eliminado!',
                            text: 'El usuario ha sido eliminado.',
                            icon: 'success',
                            timer: 1800,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        var errorMessage = 'No se pudo eliminar el usuario.';
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