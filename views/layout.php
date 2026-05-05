<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD Sisma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold">CRUD Sisma</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white">
                <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?>
            </span>
            <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <ul class="nav nav-tabs mb-4" id="tabs">
        <li class="nav-item">
            <a class="nav-link <?= $vista === 'productos'  ? 'active' : '' ?>"
               href="index.php?vista=productos">Productos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $vista === 'clientes'   ? 'active' : '' ?>"
               href="index.php?vista=clientes">Clientes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $vista === 'empleados'  ? 'active' : '' ?>"
               href="index.php?vista=empleados">Empleados</a>
        </li>
    </ul>

    <div id="contenido">
        <?php
        $vistas_permitidas = ['productos', 'clientes', 'empleados'];
        $archivo = in_array($vista, $vistas_permitidas) ? $vista : 'productos';
        require_once __DIR__ . "/{$archivo}.php";
        ?>
    </div>
</div>

<script src="public/js/app.js"></script>
</body>
</html>
