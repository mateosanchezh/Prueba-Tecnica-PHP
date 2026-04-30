<?php

class Database
{
    private static ?PDO $connection = null;

    private const HOST     = 'DESKTOP-5SOQEP7';
    private const DB_NAME  = 'PruebaPhpDB';
    private const USER     = 'sa';
    private const PASSWORD = '12345';

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dsn = 'sqlsrv:Server=' . self::HOST . ';Database=' . self::DB_NAME;

            self::$connection = new PDO($dsn, self::USER, self::PASSWORD, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$connection;
    }
}
