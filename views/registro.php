<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .registro-card {
            max-width: 420px;
            margin: 80px auto;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.10);
        }
        .registro-card .card-header {
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

<div class="card registro-card">
    <div class="card-header">Crear cuenta</div>
    <div class="card-body p-4">
        <div id="alerta" class="alert d-none" role="alert"></div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" id="nombre" class="form-control" placeholder="Juan Pérez">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" id="username" class="form-control" placeholder="juanperez">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" class="form-control" placeholder="Mínimo 6 caracteres">
        </div>
        <div class="mb-4">
            <label for="confirmacion" class="form-label">Confirmar contraseña</label>
            <input type="password" id="confirmacion" class="form-control">
        </div>
        <button id="btn-registro" class="btn btn-primary w-100 mb-3">
            <span id="btn-texto">Registrarse</span>
            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
        <p class="text-center mb-0 text-muted small">
            ¿Ya tienes cuenta? <a href="index.php">Inicia sesión</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script>
$(function () {
    function mostrarAlerta(msg, tipo) {
        $('#alerta')
            .removeClass('alert-danger alert-success')
            .addClass('alert-' + tipo)
            .text(msg)
            .removeClass('d-none');
    }

    function setLoading(loading) {
        $('#btn-registro').prop('disabled', loading);
        $('#btn-texto').text(loading ? 'Registrando...' : 'Registrarse');
        $('#btn-spinner').toggleClass('d-none', !loading);
    }

    $('#btn-registro').on('click', function () {
        $('#alerta').addClass('d-none');

        const datos = {
            nombre:       $('#nombre').val().trim(),
            username:     $('#username').val().trim(),
            password:     $('#password').val(),
            confirmacion: $('#confirmacion').val()
        };

        setLoading(true);

        fetch('index.php?action=registrar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                mostrarAlerta('Cuenta creada. Redirigiendo...', 'success');
                setTimeout(() => window.location.href = 'index.php', 1500);
            } else {
                mostrarAlerta(data.mensaje, 'danger');
                setLoading(false);
            }
        })
        .catch(() => {
            mostrarAlerta('Error de conexión. Intenta de nuevo.', 'danger');
            setLoading(false);
        });
    });
});
</script>
</body>
</html>
