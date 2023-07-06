<?php

function getTokenFromHeader()
{
    $headers = apache_request_headers();
    if (isset($headers["Authorization"])) {
        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        return $token;
    }
    return null;
}

function getUserIdFromToken($token)
{
    $decodedToken = JwtHelper::decodeJwt($token); // Implement your logic to decode the JWT token
    if ($decodedToken) {
        return $decodedToken["userId"];
    }
    return null;
}

?>