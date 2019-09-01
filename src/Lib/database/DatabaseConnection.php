<?php

class DatabaseConnection
{
    /**
     * @var DatabaseConnection
     */
    private static $instance;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dbConfig = Config::getDatabaseConfig();
            self::$instance = self::connectToDatabase($dbConfig['host'], $dbConfig['database'], $dbConfig['user'], $dbConfig['password']);
        }

        return self::$instance;
    }

    public static function connectToDatabase(string $host, string $database, string $user, string $password): PDO
    {
        try {
            $databaseConnection = new PDO("mysql:host=$host;dbname=$database", $user, $password);
            $databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $databaseConnection->query('SET NAMES utf8mb4');
        } catch (PDOException $e) {
            print 'Problem z połaczeniem do bazy danych';
            die();
        }

        return $databaseConnection;
    }
}