<?php
class Config
{
    const SECRET_KEY = 'some-random-jwt-secret';

    public static function DB_HOST()
    {
        return 'localhost';
    }

    public static function DB_USERNAME()
    {
        return 'root';
    }

    public static function DB_PASSWORD()
    {
        return 'root';
    }

    public static function DB_SCHEMA()
    {
        return 'web_prog';
    }
}
?>