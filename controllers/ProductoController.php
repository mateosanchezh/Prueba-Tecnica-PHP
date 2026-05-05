<?php
require_once __DIR__ . '/../services/ProductoService.php';

class ProductoController
{
    public static function listar(): void
    {
        header('Content-Type: application/json');
        echo json_encode(ProductoService::listar());
        exit;
    }

    public static function obtener(): void
    {
        header('Content-Type: application/json');
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) {
            echo json_encode(['ok' => false, 'mensaje' => 'ID requerido.']);
            exit;
        }
        echo json_encode(ProductoService::obtener($id));
        exit;
    }

    public static function insertar(): void
    {
        header('Content-Type: application/json');
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        echo json_encode(ProductoService::insertar($datos));
        exit;
    }

    public static function actualizar(): void
    {
        header('Content-Type: application/json');
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        echo json_encode(ProductoService::actualizar($datos));
        exit;
    }

    public static function eliminar(): void
    {
        header('Content-Type: application/json');
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        $id    = (int) ($datos['id'] ?? 0);
        if (!$id) {
            echo json_encode(['ok' => false, 'mensaje' => 'ID requerido.']);
            exit;
        }
        echo json_encode(ProductoService::eliminar($id));
        exit;
    }
}
