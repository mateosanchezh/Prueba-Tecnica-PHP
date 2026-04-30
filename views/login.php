<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .login-card {
            max-width: 400px;
            margin: 100px auto;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.10);
        }
        .login-card .card-header {
            background: #0d6efd;
            color: #fff;
            border-radius: 12px 12px 0 0;
            text-align: center;
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="card login-card">
    <div class="card-header">CRUD Sisma</div>
    <div class="card-body p-4">
        <div id="alerta" class="alert alert-danger d-none" role="alert"></div>

        <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" id="username" class="form-control" placeholder="admin" autocomplete="username">
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" class="form-control" placeholder="••••••••" autocomplete="current-password">
        </div>
        <button id="btn-login" class="btn btn-primary w-100 mb-3">
            <span id="btn-texto">Ingresar</span>
            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
        <p class="text-center mb-0 text-muted small">
            ¿No tienes cuenta? <a href="index.php?vista=registro">Regístrate</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
$(function () {
    function mostrarError(msg) {
        $('#alerta').text(msg).removeClass('d-none');
    }

    function setLoading(loading) {
        $('#btn-login').prop('disabled', loading);
        $('#btn-texto').text(loading ? 'Ingresando...' : 'Ingresar');
        $('#btn-spinner').toggleClass('d-none', !loading);
    }

    function intentarLogin() {
        const username = $('#username').val().trim();
        const password = $('#password').val();

        $('#alerta').addClass('d-none');

        if (!username || !password) {
            mostrarError('Completa todos los campos.');
            return;
        }

        setLoading(true);

        fetch('index.php?action=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                window.location.href = 'index.php';
            } else {
                mostrarError(data.mensaje);
                setLoading(false);
            }
        })
        .catch(() => {
            mostrarError('Error de conexión. Intenta de nuevo.');
            setLoading(false);
        });
    }

    $('#btn-login').on('click', intentarLogin);

    $('#username, #password').on('keydown', function (e) {
        if (e.key === 'Enter') intentarLogin();
    });
});
</script>
</body>
</html>
