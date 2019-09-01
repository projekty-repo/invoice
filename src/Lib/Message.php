<?php

class Message
{
    public static function set(string $message): void
    {
        $_SESSION['message'] = $message;
    }

    public static function get(): string
    {
        $message = $_SESSION['message'];
        self::clear();

        return $message;
    }

    public static function isSet(): bool
    {
        return !empty($_SESSION['message']);
    }

    public static function clear(): void
    {
        unset($_SESSION['message']);
    }
}