<?php

require_once "helpers.php";


Flight::route('POST /login', function () {
    $request = Flight::request()->data->getData();
    $user = Flight::user_service()->validateUser($request['username'], $request['password']);

    if ($user) {
        $token = JwtHelper::generateJwt($user['id']);
        Flight::json(['token' => $token]);
    } else {
        Flight::json(['message' => 'Invalid username or password'], 401);
    }
});

Flight::route("GET /me", function () {
    $headers = apache_request_headers();
    $token = isset($headers["Authorization"]) ? str_replace("Bearer ", "", $headers["Authorization"]) : null;
    if ($token) {
        $decodedToken = JwtHelper::decodeJwt($token);
        if ($decodedToken) {
            $userId = $decodedToken["userId"];
            Flight::json(Flight::user_service()->getByID($userId));
        } else {
            Flight::json(["message" => "Invalid token: " . $token], 401);
        }
    } else {
        Flight::json(["message" => "Unauthorized"], 401);
    }
});

Flight::route("DELETE /me", function () {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);
    if ($userId) {
        if (Flight::user_service()->deleteByID($userId)) {
            Flight::json(["message" => "Account deleted successfully"]);
        } else {
            Flight::json(["message" => "Failed to delete account"], 500);
        }
    } else {
        Flight::json(["message" => "Invalid token"], 401);
    }
});


Flight::route("POST /me", function () {
    $headers = apache_request_headers();
    $token = isset($headers["Authorization"]) ? str_replace("Bearer ", "", $headers["Authorization"]) : null;
    if ($token) {
        $decodedToken = JwtHelper::decodeJwt($token);
        if ($decodedToken) {
            $userId = $decodedToken["userId"];
            $user = Flight::request()->data->getData();
            if (Flight::user_service()->updateData($user, $userId)) {
                Flight::json(["message" => "Profile updated successfully"]);
            } else {
                Flight::json(["message" => "Failed to update profile"], 500);
            }
        } else {
            Flight::json(["message" => "Invalid token: " . $token], 401);
        }
    } else {
        Flight::json(["message" => "Unauthorized"], 401);
    }
});

Flight::route("POST /register", function () { // Register new user

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "User registered successfully", "data: " => Flight::user_service()->insertData($request)]);
});

Flight::route("GET /users", function () { // Get all users


    Flight::json(Flight::user_service()->getAll());
});

Flight::route("GET /user/@id", function ($id) { // Get user by id 

    Flight::json(Flight::user_service()->getByID($id));
});

Flight::route("GET /user_by_id", function () { // Get user by id with query

    $id = Flight::request()->query['id'];
    Flight::json(Flight::user_service()->getByID($id));
});

Flight::route("DELETE /user/@id", function ($id) { // Delete user by id

    Flight::user_service()->deleteByID($id);
    Flight::json(["message" => "User with id " . $id . " deleted successfully"]);
});


Flight::route("PUT /user/@id", function ($id) {

    $request = Flight::request()->data->getData();
    // $request['id'] = $id;     Another way to show id         
    Flight::json(["message" => "User updated successfully", "data: " => Flight::user_service()->updateData($request, $id)]);
});

?>