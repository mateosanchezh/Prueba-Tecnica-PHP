<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    AuthController::login();
}

if ($action === 'registrar') {
    AuthController::registrar();
}

if ($action === 'logout') {
    AuthController::logout();
}

# Si no hay sesión activa, se muestra la vista de login o registro
if (empty($_SESSION['usuario_id'])) {
    $vista = $_GET['vista'] ?? '';
    # Si se solicita la vista de registro, se carga esa vista; de lo contrario, se muestra el login
    if ($vista === 'registro') {
        require_once __DIR__ . '/../views/registro.php';
    } else {
        require_once __DIR__ . '/../views/login.php';
    }
    exit;
}

require_once __DIR__ . '/../controllers/ProductoController.php';
require_once __DIR__ . '/../controllers/ClienteController.php';
require_once __DIR__ . '/../controllers/EmpleadoController.php';

$routes = [
    'productos.listar'     => [ProductoController::class,  'listar'],
    'productos.obtener'    => [ProductoController::class,  'obtener'],
    'productos.insertar'   => [ProductoController::class,  'insertar'],
    'productos.actualizar' => [ProductoController::class,  'actualizar'],
    'productos.eliminar'   => [ProductoController::class,  'eliminar'],

    'clientes.listar'      => [ClienteController::class,   'listar'],
    'clientes.obtener'     => [ClienteController::class,   'obtener'],
    'clientes.insertar'    => [ClienteController::class,   'insertar'],
    'clientes.actualizar'  => [ClienteController::class,   'actualizar'],
    'clientes.eliminar'    => [ClienteController::class,   'eliminar'],

    'empleados.listar'     => [EmpleadoController::class,  'listar'],
    'empleados.obtener'    => [EmpleadoController::class,  'obtener'],
    'empleados.insertar'   => [EmpleadoController::class,  'insertar'],
    'empleados.actualizar' => [EmpleadoController::class,  'actualizar'],
    'empleados.eliminar'   => [EmpleadoController::class,  'eliminar'],
];

#Si la acción corresponde a una ruta definida, se ejecuta el método correspondiente
if (isset($routes[$action])) {
    [$class, $method] = $routes[$action];
    $class::$method();
    exit;
}

#Si no hay acción específica, se carga la vista principal
$vista = $_GET['vista'] ?? 'productos';
require_once __DIR__ . '/../views/layout.php';
