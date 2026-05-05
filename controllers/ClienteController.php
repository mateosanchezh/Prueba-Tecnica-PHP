<?php
require_once __DIR__ . '/../services/ClienteService.php';

class ClienteController
{
    public static function listar(): void
    {
        header('Content-Type: application/json');
        echo json_encode(ClienteService::listar());
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
        echo json_encode(ClienteService::obtener($id));
        exit;
    }

    public static function insertar(): void
    {
        header('Content-Type: application/json');
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        echo json_encode(ClienteService::insertar($datos));
        exit;
    }

    public static function actualizar(): void
    {
        header('Content-Type: application/json');
        $datos = json_decode(file_get_contents('php://input'), true) ?? [];
        echo json_encode(ClienteService::actualizar($datos));
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
        echo json_encode(ClienteService::eliminar($id));
        exit;
    }
}
