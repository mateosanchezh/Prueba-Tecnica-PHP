<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthService
{
    public static function login(string $username, string $password): array
    {
        if ($username === '' || $password === '') {
            return ['ok' => false, 'mensaje' => 'Usuario y contraseña son requeridos.'];
        }

        $usuario = self::buscarPorUsername($username);

        if (!$usuario || !password_verify($password, $usuario->getPasswordHash())) {
            return ['ok' => false, 'mensaje' => 'Usuario o contraseña incorrectos.'];
        }

        $_SESSION['usuario_id']     = $usuario->getId();
        $_SESSION['usuario_nombre'] = $usuario->getNombre();

        return ['ok' => true];
    }

    public static function registrar(string $nombre, string $username, string $password, string $confirmacion): array
    {
        if ($nombre === '' || $username === '' || $password === '') {
            return ['ok' => false, 'mensaje' => 'Todos los campos son requeridos.'];
        }

        if ($password !== $confirmacion) {
            return ['ok' => false, 'mensaje' => 'Las contraseñas no coinciden.'];
        }

        if (strlen($password) < 6) {
            return ['ok' => false, 'mensaje' => 'La contraseña debe tener al menos 6 caracteres.'];
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Usuarios_Registrar @username = ?, @password_hash = ?, @nombre = ?');
        $stmt->execute([$username, $hash, $nombre]);
        $row  = $stmt->fetch();

        if (!$row || (int) $row['id'] === -1) {
            return ['ok' => false, 'mensaje' => 'El nombre de usuario ya está en uso.'];
        }

        return ['ok' => true];
    }

    public static function logout(): void
    {
        session_destroy();
    }

    private static function buscarPorUsername(string $username): ?Usuario
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Usuarios_Login @username = ?');
        $stmt->execute([$username]);
        $row  = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Usuario(
            $row['id'],
            $row['username'],
            $row['password_hash'],
            $row['nombre']
        );
    }
}
