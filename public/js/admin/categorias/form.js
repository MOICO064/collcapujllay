$(function () {
    var form = $('#categoria-form');
    if (!form.length) {
        return;
    }

    var submitButton = form.find('button[type="submit"]');
    var originalButtonHtml = submitButton.html();
    var formErrors = $('#form-errors');
    var errorList = formErrors.find('ul');

    function clearErrors() {
        formErrors.addClass('hidden');
        errorList.empty();
        form.find('.field-error').text('');
        form.find('.border-red-500').removeClass('border-red-500');
    }

    function showErrors(errors) {
        formErrors.removeClass('hidden');
        $.each(errors, function (field, messages) {
            var message = messages && messages.length ? messages[0] : 'Error en el campo ' + field;
            errorList.append('<li data-field="' + field + '">' + message + '</li>');
            $('#' + field + '-error').text(message);
            $('#' + field).addClass('border-red-500');
        });
    }

    function clearFieldError(field) {
        var fieldError = $('#' + field + '-error');
        var fieldInput = $('#' + field);

        fieldError.text('');
        fieldInput.removeClass('border-red-500');
        errorList.find('li[data-field="' + field + '"]').remove();

        if (errorList.children().length === 0) {
            formErrors.addClass('hidden');
        }
    }

    form.on('input change', 'input, select, textarea', function () {
        var field = $(this).attr('name') || $(this).attr('id');
        if (field) {
            clearFieldError(field);
        }
    });

    form.on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        var url = form.data('url');
        var formData = new FormData(this);

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
                Swal.fire({
                    title: 'Listo',
                    text: response.message,
                    icon: 'success',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                });
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    showErrors(xhr.responseJSON.errors);
                } else {
                    Swal.fire('Error', 'Ocurrió un error inesperado.', 'error');
                }
            },
            complete: function () {
                submitButton.prop('disabled', false).html(originalButtonHtml);
            }
        });
    });
});
