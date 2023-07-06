<?php

require_once "./Config.class.php";
use \Firebase\JWT\JWT;

class JwtHelper
{
    public static function generateJwt($userId)
    {
        $secretKey = Config::SECRET_KEY;
        $issuedAt = new DateTimeImmutable();

        $payload = [
            'iat' => $issuedAt->getTimestamp(),
            'iss' => 'localhost',
            'userId' => $userId
        ];

        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];

        $encodedHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
        $encodedPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

        $signature = hash_hmac('sha256', $encodedHeader . "." . $encodedPayload, $secretKey, true);
        $encodedSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $encodedHeader . "." . $encodedPayload . "." . $encodedSignature;
    }

    public static function decodeJwt($token)
    {
        $secretKey = Config::SECRET_KEY;

        try {
            $decoded = JWT::decode($token, $secretKey, ['HS256']);
            $payload = (array) $decoded;
            return $payload;
        } catch (Exception $e) {
            error_log('Caught exception: ' . $e->getMessage());
            return false;
        }
    }
}