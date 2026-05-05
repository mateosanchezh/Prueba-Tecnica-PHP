<?php

/**
 * ProductoService — lógica de negocio para Productos.
 *
 * Este servicio es el único que habla directamente con la base de datos
 * para la entidad Producto. Valida los datos usando el modelo y ejecuta
 * los procedimientos almacenados definidos en schematics.sql.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoService
{
    /**
     * Devuelve todos los productos activos.
     * Llama al SP: sp_Productos_ObtenerTodos
     */
    public static function listar(): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Productos_ObtenerTodos');
        $stmt->execute();
        return ['ok' => true, 'data' => $stmt->fetchAll()];
    }

    /**
     * Devuelve un producto por su ID.
     * Llama al SP: sp_Productos_ObtenerPorId
     */
    public static function obtener(int $id): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Productos_ObtenerPorId @id = ?');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();

        if (!$row) {
            return ['ok' => false, 'mensaje' => 'Producto no encontrado.'];
        }

        return ['ok' => true, 'data' => $row];
    }

    /**
     * Crea un nuevo producto.
     * Valida los datos usando el modelo antes de insertar.
     * Llama al SP: sp_Productos_Insertar
     */
    public static function insertar(array $datos): array
    {
        // Construimos el objeto Producto con los datos recibidos.
        // Esto nos permite validar usando el modelo de forma centralizada.
        $producto = new Producto(
            null,
            trim($datos['nombre']      ?? ''),
            trim($datos['descripcion'] ?? ''),
            isset($datos['precio']) ? (float) $datos['precio'] : null,
            isset($datos['stock'])  ? (int)   $datos['stock']  : null,
            trim($datos['categoria']   ?? '')
        );

        // Validamos antes de tocar la base de datos
        $error = self::validar($producto);
        if ($error) return ['ok' => false, 'mensaje' => $error];

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Productos_Insertar @nombre = ?, @descripcion = ?, @precio = ?, @stock = ?, @categoria = ?');
        $stmt->execute([
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getStock(),
            $producto->getCategoria(),
        ]);
        $row = $stmt->fetch();

        return ['ok' => true, 'id' => $row['id']];
    }

    /**
     * Actualiza un producto existente.
     * Llama al SP: sp_Productos_Actualizar
     */
    public static function actualizar(array $datos): array
    {
        $id = (int) ($datos['id'] ?? 0);
        if (!$id) return ['ok' => false, 'mensaje' => 'ID requerido.'];

        $producto = new Producto(
            $id,
            trim($datos['nombre']      ?? ''),
            trim($datos['descripcion'] ?? ''),
            isset($datos['precio']) ? (float) $datos['precio'] : null,
            isset($datos['stock'])  ? (int)   $datos['stock']  : null,
            trim($datos['categoria']   ?? '')
        );

        $error = self::validar($producto);
        if ($error) return ['ok' => false, 'mensaje' => $error];

        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Productos_Actualizar @id = ?, @nombre = ?, @descripcion = ?, @precio = ?, @stock = ?, @categoria = ?');
        $stmt->execute([
            $producto->getId(),
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getPrecio(),
            $producto->getStock(),
            $producto->getCategoria(),
        ]);
        $row = $stmt->fetch();

        if (!$row || (int) $row['filas_afectadas'] === 0) {
            return ['ok' => false, 'mensaje' => 'No se pudo actualizar el producto.'];
        }

        return ['ok' => true];
    }

    /**
     * Elimina un producto (soft delete: marca activo = 0, no borra el registro).
     * Llama al SP: sp_Productos_Eliminar
     */
    public static function eliminar(int $id): array
    {
        $pdo  = Database::getConnection();
        $stmt = $pdo->prepare('EXEC sp_Productos_Eliminar @id = ?');
        $stmt->execute([$id]);
        $row  = $stmt->fetch();

        if (!$row || (int) $row['filas_afectadas'] === 0) {
            return ['ok' => false, 'mensaje' => 'No se pudo eliminar el producto.'];
        }

        return ['ok' => true];
    }

    /**
     * Valida las reglas de negocio del modelo Producto.
     * Devuelve el mensaje de error o null si todo está bien.
     */
    private static function validar(Producto $p): ?string
    {
        if (!$p->getNombre())                                return 'El nombre es requerido.';
        if ($p->getPrecio() === null || $p->getPrecio() < 0) return 'El precio debe ser un número positivo.';
        if ($p->getStock()  === null || $p->getStock()  < 0) return 'El stock debe ser un número positivo.';
        if (!$p->getCategoria())                             return 'La categoría es requerida.';
        return null;
    }
}
