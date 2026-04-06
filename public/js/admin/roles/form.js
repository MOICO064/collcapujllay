$(function () {
    var form = $('#roles-form');
    if (!form.length) return;

    var submitButton = form.find('button[type="submit"]');
    var originalButtonHtml = submitButton.html();
    var formErrors = $('#form-errors');
    var errorList = formErrors.find('ul');

    function normalizeFieldId(field) {
        if (!field) return '';
        if (field.startsWith('permissions')) return 'permissions';
        return field.replace(/[\[\].]/g, '_');
    }

    function clearErrors() {
        formErrors.addClass('hidden');
        errorList.empty();
        $('.field-error').text('');
        form.find('.border-red-500').removeClass('border-red-500');
    }

    function showErrors(errors) {
        console.log('📌 Errores del backend:', errors);
        formErrors.removeClass('hidden');
        $.each(errors, function (field, messages) {
            var message = messages && messages.length ? messages[0] : 'Error en el campo ' + field;
            var normalized = normalizeFieldId(field);
            errorList.append('<li data-field="' + normalized + '">' + message + '</li>');

            if (normalized) $('#' + normalized + '-error').text(message);

            var highlightInputs = field.startsWith('permissions')
                ? form.find('[name="permissions[]"]')
                : form.find('[name="' + field + '"]');

            if (highlightInputs.length) highlightInputs.addClass('border-red-500');
        });
    }

    function clearFieldError(field) {
        var normalized = normalizeFieldId(field);
        if (normalized) $('#' + normalized + '-error').text('');

        var highlightInputs = field && field.startsWith('permissions')
            ? form.find('[name="permissions[]"]')
            : form.find('[name="' + field + '"]');

        highlightInputs.removeClass('border-red-500');
        errorList.find('li[data-field="' + normalized + '"]').remove();

        if (errorList.children().length === 0) formErrors.addClass('hidden');
    }

    form.on('input change', 'input, select, textarea', function () {
        var field = $(this).attr('name') || $(this).attr('id');
        if (field) clearFieldError(field);
    });

    form.on('submit', function (event) {
        event.preventDefault();
        clearErrors();
        var url = form.data('url');

       
        var formData = new FormData();
        form.find('input, select, textarea').each(function () {
            var name = $(this).attr('name');
            if (!name) return;

            if (name === 'permissions[]') {
                // recorrer solo los checkboxes seleccionados
                if ($(this).is(':checked')) {
                    formData.append('permissions[]', parseInt($(this).val()));
                }
            } else {
                formData.append(name, $(this).val());
            }
        });


        submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function (response) {
                console.log('✅ Respuesta exitosa del backend:', response);
                Swal.fire({
                    title: 'Listo',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(function () {
                    if (response.redirect) window.location.href = response.redirect;
                });
            },
            error: function (xhr) {
                console.log('❌ Error del backend completo:', xhr);
                if (xhr.responseJSON?.errors) showErrors(xhr.responseJSON.errors);
                else Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
            },
            complete: function () {
                submitButton.prop('disabled', false).html(originalButtonHtml);
            }
        });
    });
});