<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteService
{
    public static function listar(): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Clientes_ObtenerTodos');
        $stmt->execute();
        return ['ok' => true, 'data' => $stmt->fetchAll()];
    }

    public static function obtener(int $id): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Clientes_ObtenerPorId @id = ?');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();

        if (!$row) {
            return ['ok' => false, 'mensaje' => 'Cliente no encontrado.'];
        }

        return ['ok' => true, 'data' => $row];
    }

    public static function insertar(array $datos): array
    {
        $cliente = new Cliente(
            null,
            trim($datos['nombre']    ?? ''),
            trim($datos['apellido']  ?? ''),
            trim($datos['email']     ?? ''),
            trim($datos['telefono']  ?? ''),
            trim($datos['direccion'] ?? '')
        );

        $error = self::validar($cliente);
        if ($error) return ['ok' => false, 'mensaje' => $error];

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Clientes_Insertar @nombre = ?, @apellido = ?, @email = ?, @telefono = ?, @direccion = ?');
        $stmt->execute([
            $cliente->getNombre(),
            $cliente->getApellido(),
            $cliente->getEmail(),
            $cliente->getTelefono(),
            $cliente->getDireccion(),
        ]);
        $row = $stmt->fetch();

        if (!$row || (int) $row['id'] === -1) {
            return ['ok' => false, 'mensaje' => 'El email ya está registrado.'];
        }

        return ['ok' => true, 'id' => $row['id']];
    }

    public static function actualizar(array $datos): array
    {
        $id = (int) ($datos['id'] ?? 0);
        if (!$id) return ['ok' => false, 'mensaje' => 'ID requerido.'];

        $cliente = new Cliente(
            $id,
            trim($datos['nombre']    ?? ''),
            trim($datos['apellido']  ?? ''),
            trim($datos['email']     ?? ''),
            trim($datos['telefono']  ?? ''),
            trim($datos['direccion'] ?? '')
        );

        $error = self::validar($cliente);
        if ($error) return ['ok' => false, 'mensaje' => $error];

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Clientes_Actualizar @id = ?, @nombre = ?, @apellido = ?, @email = ?, @telefono = ?, @direccion = ?');
        $stmt->execute([
            $cliente->getId(),
            $cliente->getNombre(),
            $cliente->getApellido(),
            $cliente->getEmail(),
            $cliente->getTelefono(),
            $cliente->getDireccion(),
        ]);
        $row = $stmt->fetch();

        if (!$row || (int) $row['filas_afectadas'] === -1) {
            return ['ok' => false, 'mensaje' => 'El email ya está en uso por otro cliente.'];
        }

        if ((int) $row['filas_afectadas'] === 0) {
            return ['ok' => false, 'mensaje' => 'No se pudo actualizar el cliente.'];
        }

        return ['ok' => true];
    }

    public static function eliminar(int $id): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Clientes_Eliminar @id = ?');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();

        if (!$row || (int) $row['filas_afectadas'] === 0) {
            return ['ok' => false, 'mensaje' => 'No se pudo eliminar el cliente.'];
        }

        return ['ok' => true];
    }

    private static function validar(Cliente $c): ?string
    {
        if (!$c->getNombre())   return 'El nombre es requerido.';
        if (!$c->getApellido()) return 'El apellido es requerido.';
        if (!$c->getEmail())    return 'El email es requerido.';
        if (!filter_var($c->getEmail(), FILTER_VALIDATE_EMAIL)) return 'El email no es válido.';
        return null;
    }
}
