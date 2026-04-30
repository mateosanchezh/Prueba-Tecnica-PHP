<?php

require_once 'config/database.php';

try {
    $pdo = Database::getConnection();
    echo "✓ Conexión exitosa a SQL Server.<br>";
    echo "Base de datos: PruebaPhpDB<br>";
    echo "Servidor: DESKTOP-5SOQEP7<br>";
} catch (PDOException $e) {
    echo "✗ Error de conexión: " . $e->getMessage();
}


