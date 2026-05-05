<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Empleado.php';

class EmpleadoService
{
    public static function listar(): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Empleados_ObtenerTodos');
        $stmt->execute();
        return ['ok' => true, 'data' => $stmt->fetchAll()];
    }

    public static function obtener(int $id): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Empleados_ObtenerPorId @id = ?');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();

        if (!$row) {
            return ['ok' => false, 'mensaje' => 'Empleado no encontrado.'];
        }

        return ['ok' => true, 'data' => $row];
    }

    public static function insertar(array $datos): array
    {
        $empleado = new Empleado(
            null,
            trim($datos['nombre']       ?? ''),
            trim($datos['apellido']     ?? ''),
            trim($datos['cargo']        ?? ''),
            isset($datos['salario'])    ? (float) $datos['salario'] : null,
            trim($datos['fecha_ingreso'] ?? '')
        );

        $error = self::validar($empleado);
        if ($error) return ['ok' => false, 'mensaje' => $error];

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Empleados_Insertar @nombre = ?, @apellido = ?, @cargo = ?, @salario = ?, @fecha_ingreso = ?');
        $stmt->execute([
            $empleado->getNombre(),
            $empleado->getApellido(),
            $empleado->getCargo(),
            $empleado->getSalario(),
            $empleado->getFechaIngreso(),
        ]);
        $row = $stmt->fetch();

        return ['ok' => true, 'id' => $row['id']];
    }

    public static function actualizar(array $datos): array
    {
        $id = (int) ($datos['id'] ?? 0);
        if (!$id) return ['ok' => false, 'mensaje' => 'ID requerido.'];

        $empleado = new Empleado(
            $id,
            trim($datos['nombre']       ?? ''),
            trim($datos['apellido']     ?? ''),
            trim($datos['cargo']        ?? ''),
            isset($datos['salario'])    ? (float) $datos['salario'] : null,
            trim($datos['fecha_ingreso'] ?? '')
        );

        $error = self::validar($empleado);
        if ($error) return ['ok' => false, 'mensaje' => $error];

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Empleados_Actualizar @id = ?, @nombre = ?, @apellido = ?, @cargo = ?, @salario = ?, @fecha_ingreso = ?');
        $stmt->execute([
            $empleado->getId(),
            $empleado->getNombre(),
            $empleado->getApellido(),
            $empleado->getCargo(),
            $empleado->getSalario(),
            $empleado->getFechaIngreso(),
        ]);
        $row = $stmt->fetch();

        if (!$row || (int) $row['filas_afectadas'] === 0) {
            return ['ok' => false, 'mensaje' => 'No se pudo actualizar el empleado.'];
        }

        return ['ok' => true];
    }

    public static function eliminar(int $id): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Empleados_Eliminar @id = ?');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();

        if (!$row || (int) $row['filas_afectadas'] === 0) {
            return ['ok' => false, 'mensaje' => 'No se pudo eliminar el empleado.'];
        }

        return ['ok' => true];
    }

    private static function validar(Empleado $e): ?string
    {
        if (!$e->getNombre())      return 'El nombre es requerido.';
        if (!$e->getApellido())    return 'El apellido es requerido.';
        if (!$e->getCargo())       return 'El cargo es requerido.';
        if ($e->getSalario() === null || $e->getSalario() < 0) return 'El salario debe ser un número positivo.';
        if (!$e->getFechaIngreso()) return 'La fecha de ingreso es requerida.';
        return null;
    }
}
