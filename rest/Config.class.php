<?php
class Config
{
    const SECRET_KEY = 'some-random-jwt-secret';

    public static function DB_HOST()
    {
        return 'eu-cdbr-west-03.cleardb.net';
    }

    public static function DB_USERNAME()
    {
        return 'bb6bdd9e5bf32c';
    }

    public static function DB_PASSWORD()
    {
        return '2b863271';
    }

    public static function DB_SCHEMA()
    {
        return 'heroku_b1aa226bce2c892';
    }
}
?>