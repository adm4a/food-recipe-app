<?php


Flight::route('POST /favorites/me', function () {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);
    $recipeId = Flight::request()->data->getData()['recipe_id'];
    if ($userId) {
        Flight::favorite_service()->addToFavorites($userId, $recipeId);
        Flight::json(["message" => "Recipe added to favorites"]);
    } else {
        Flight::json(["message" => "Invalid token"], 401);
    }
});

Flight::route('GET /favorites/me', function () {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);

    if ($userId) {
        $favorites = Flight::favorite_service()->getUserFavorites($userId);
        Flight::json($favorites);
    } else {
        Flight::json(["message" => "Invalid token"], 401);
    }
});


Flight::route('DELETE /favorites/me', function () {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);
    $recipeId = Flight::request()->data->getData()['recipe_id'];
    if ($userId) {
        Flight::favorite_service()->removeFromFavorites($userId, $recipeId);
        Flight::json(["message" => "Recipe removed from favorites"]);
    } else {
        Flight::json(["message" => "Invalid token"], 401);
    }
});



Flight::route("GET /favorites", function () { // Get all favorites

    // favorite_service = new Projectfavorite_service() <- don't need this
    // $results = Flight::favorite_service()->getAll();
    Flight::json(Flight::favorite_service()->getAll());
});

Flight::route("GET /favorite/@id", function ($id) { // Get favorite by id 

    Flight::json(Flight::favorite_service()->getByID($id));
});

Flight::route("DELETE /favorite/@id", function ($id) { // Delete favorite by id

    Flight::favorite_service()->deleteByID($id);
    Flight::json(["message" => "favorite with id " . $id . " deleted successfully"]);
});

Flight::route("POST /favorite", function () { // Insert new favorite

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "favorite added successfully", "data: " => Flight::favorite_service()->insertData($request)]);
});

Flight::route("PUT /favorite/@id", function ($id) {

    $request = Flight::request()->data->getData();
    // $request['id'] = $id;     Another way to show id         
    Flight::json(["message" => "favorite updated successfully", "data: " => Flight::favorite_service()->updateData($request, $id)]);
});

?>