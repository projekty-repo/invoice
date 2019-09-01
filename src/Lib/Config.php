<?php

class Config
{
    private const DATABASE_CONFIG_FILE = 'database.ini';

    public static function getDatabaseConfig(): array
    {
        return parse_ini_file(DIRECTORY . '/' . self::DATABASE_CONFIG_FILE);
    }
}