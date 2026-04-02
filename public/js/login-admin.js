(() => {
    const form = $('#admin-login-form');
    if (!form.length) {
        return;
    }

    const loginUrl = form.data('login-url') || '/login';
    const validationUrl = form.data('validation-url') || '/login/validate';
    const dashboardUrl = form.data('dashboard-url') || '/dashboard';
    const token = $('meta[name="csrf-token"]').attr('content') || '';
    const submitButton = form.find('.admin-login-button');
    const loader = form.find('.admin-login-loader');
    const buttonText = form.find('.button-text');
    const formErrors = $('#form-errors');

    const emailInput = $('#email');
    const passwordInput = $('#password');
    const emailError = $('#email-error');
    const passwordError = $('#password-error');

    emailInput.val('');
    emailInput.attr('autocomplete', 'username');

    passwordInput.val('');
    passwordInput.attr('autocomplete', 'new-password');

    [emailInput, passwordInput].forEach((input) => {
        input.on('input change', () => {
            scheduleValidation();
        });
    });

    $('#toggle-password').on('click', () => {
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $('#toggle-password').toggleClass('active');
    });

    const setFieldError = (input, element, message) => {
        element.text(message || '');
        if (message) {
            input.addClass('input-has-error');
        } else {
            input.removeClass('input-has-error');
        }
    };

    const debounce = (fn, wait = 360) => {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), wait);
        };
    };

    const scheduleValidation = debounce(() => {
        $.ajax({
            url: validationUrl,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
            },
            data: {
                email: emailInput.val().trim(),
                password: passwordInput.val(),
            },
        })
            .done((response) => {
                setFieldError(emailInput, emailError, response.errors?.email?.[0] ?? '');
                setFieldError(passwordInput, passwordError, response.errors?.password?.[0] ?? '');
            })
            .fail((xhr) => {
                if (xhr.status === 422) {
                    const response = xhr.responseJSON || {};
                    setFieldError(emailInput, emailError, response.errors?.email?.[0] ?? '');
                    setFieldError(passwordInput, passwordError, response.errors?.password?.[0] ?? '');
                }
            });
    });

    const validateInputs = () => {
        let valid = true;
        const email = emailInput.val().trim();
        const password = passwordInput.val();
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            setFieldError(emailInput, emailError, 'El usuario es obligatorio.');
            valid = false;
        } else if (!regex.test(email)) {
            setFieldError(emailInput, emailError, 'Ingresa un usuario con formato de correo válido.');
            valid = false;
        } else {
            setFieldError(emailInput, emailError, '');
        }

        if (!password) {
            setFieldError(passwordInput, passwordError, 'La contraseña es obligatoria.');
            valid = false;
        } else if (password.length < 6) {
            setFieldError(passwordInput, passwordError, 'La contraseña debe tener al menos 6 caracteres.');
            valid = false;
        } else {
            setFieldError(passwordInput, passwordError, '');
        }

        return valid;
    };

    const toggleLoading = (loading) => {
        submitButton.prop('disabled', loading);
        if (loading) {
            loader.show();
            buttonText.hide();
        } else {
            loader.hide();
            buttonText.show();
        }
    };

    const displayFormError = (message) => {
        formErrors.text(message || '');
        formErrors.toggle(!!message);
    };

    form.on('submit', (event) => {
        event.preventDefault();
        displayFormError('');
        if (!validateInputs()) {
            return;
        }

        toggleLoading(true);

        $.ajax({
            url: loginUrl,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
            },
            data: {
                email: emailInput.val().trim(),
                password: passwordInput.val(),
                remember: form.find('input[name="remember"]').is(':checked'),
            },
        })
            .done((data) => {
                console.log('Login exitoso', emailInput.val().trim());
                window.location.href = data.intended || dashboardUrl;
            })
            .fail((xhr) => {
                toggleLoading(false);
                const response = xhr.responseJSON || {};
                displayFormError(response.message || 'No se pudo iniciar sesión.');
                if (response.errors) {
                    setFieldError(emailInput, emailError, response.errors.email?.[0]);
                    setFieldError(passwordInput, passwordError, response.errors.password?.[0]);
                }
                console.log('Login fallido', response.errors || response.message || xhr.status);
            });
    });
})();
