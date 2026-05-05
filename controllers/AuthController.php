<?php
require_once __DIR__ . '/../services/AuthService.php';

class AuthController
{
    public static function login(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $resultado = AuthService::login(
            trim($data['username'] ?? ''),
            $data['password'] ?? ''
        );

        echo json_encode($resultado);
        exit;
    }

    public static function registrar(): void
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $resultado = AuthService::registrar(
            trim($data['nombre']      ?? ''),
            trim($data['username']    ?? ''),
            $data['password']         ?? '',
            $data['confirmacion']     ?? ''
        );

        echo json_encode($resultado);
        exit;
    }

    public static function logout(): void
    {
        AuthService::logout();
        header('Location: index.php');
        exit;
    }
}
