<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Administrativo · Parque Collcapujllay</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login-admin.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script defer src="{{ asset('js/login-admin.js') }}"></script>
</head>

<body>
    <div class="admin-login-shell">
        <div class="admin-login-card">
            <div class="admin-login-logo-badge">
                <img src="{{ asset('img/logo.png') }}" alt="Logo Collcapujllay">
            </div>
            <header class="admin-login-header">
                <h1>Panel administrativo</h1>
                <p class="admin-login-description">
                    Ingresa tus credenciales para acceder.
                </p>
            </header>
            @php
                $authDisabledMessage = session('auth_disabled_message');
            @endphp
            <div id="form-errors"
                class="admin-login-feedback"
                role="alert"
                style="{{ $authDisabledMessage ? 'display:block;' : 'display:none;' }}">
                {{ $authDisabledMessage ?? '' }}
            </div>
            <template x-if="errors.form">
                <div class="admin-login-feedback" x-text="errors.form" role="alert" aria-live="polite"></div>
            </template>

            <form id="admin-login-form"
                data-login-url="{{ route('login') }}"
                data-dashboard-url="{{ route('dashboard') }}"
                data-validation-url="{{ route('login.validate') }}"
                class="admin-login-form"
                novalidate
                autocomplete="off">
                @csrf

                <div class="field-group">
                    <label for="email">Usuario</label>
                    <input id="email"
                        name="email"
                        type="email"
                        autocomplete="username"
                        placeholder="administracion@collcapujllay.net"
                        autofocus>
                    <p class="field-error" id="email-error"></p>
                </div>

                <div class="field-group password-field">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Mínimo 6 caracteres">
                        <button type="button" id="toggle-password" aria-label="Mostrar u ocultar contraseña">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M12 5c-5 0-9.27 3.11-11 7 1.73 3.89 6 7 11 7s9.27-3.11 11-7c-1.73-3.89-6-7-11-7Z" stroke="#fff" stroke-width="1.8"/>
                                <circle cx="12" cy="12" r="3" stroke="#fff" stroke-width="1.8"/>
                                <line x1="4" y1="4" x2="20" y2="20" stroke="rgba(255,255,255,0.8)" stroke-width="1.6" stroke-linecap="round" class="eye-line" />
                            </svg>
                        </button>
                    </div>
                    <p class="field-error" id="password-error"></p>
                </div>

                <div class="admin-login-options">
                    <label>
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                </div>


                <button type="submit" class="admin-login-button">
                    <span class="button-text">Enviar</span>
                    <span class="admin-login-loader" aria-live="polite" style="display:none;">
                        <span class="admin-login-spinner" aria-hidden="true"></span>
                        Validando…
                    </span>
                </button>
            </form>

        </div>
    </div>
</body>

</html>
