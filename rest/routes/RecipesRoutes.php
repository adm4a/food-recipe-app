<?php
require_once "helpers.php";

Flight::route('GET /recipes/me', function () {
    $token = getTokenFromHeader();

    $userId = getUserIdFromToken($token);
    if ($userId) {
        $recipes = Flight::recipe_service()->getByUserId($userId);
        Flight::json($recipes);
    } else {
        Flight::json(["message" => "Invalid token"], 401);
    }
});


Flight::route("GET /recipes", function () {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);
    $request = Flight::request();
    $page = isset($request->query['page']) ? $request->query['page'] : 1;
    $itemsPerPage = isset($request->query['itemsPerPage']) ? $request->query['itemsPerPage'] : 8;
    $searchText = isset($request->query['searchText']) ? $request->query['searchText'] : '';
    error_log("Passing userid:" . $userId);
    $recipes = Flight::recipe_service()->getRecipes($page, $itemsPerPage, $searchText, $userId);
    Flight::json($recipes);
});


Flight::route("GET /recipe/@id", function ($id) { // Get recipes by id 

    Flight::json(Flight::recipe_service()->getByID($id));
});


Flight::route("DELETE /recipe/@id", function ($id) {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);

    // Check if the user is authorized to delete the recipe
    $recipe = Flight::recipe_service()->getByID($id);
    if (!$recipe || $recipe['user_id'] !== $userId) {
        Flight::json(["message" => "Unauthorized"], 401);
        return;
    }

    // Delete the recipe
    Flight::recipe_service()->deleteByID($id);

    Flight::json(["message" => "Recipe with id " . $id . " deleted successfully"]);
});


Flight::route("POST /recipe", function () {

    // Fetching the token and the user id from it
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);

    // Check if the user is logged in
    if (!$userId) {
        Flight::json(["message" => "Unauthorized: Not logged in"], 401);
        return;
    }

    $request = Flight::request()->data->getData();
    // Add the user_id to the request data before inserting
    $request['user_id'] = $userId;
    Flight::json(["message" => "Recipe added successfully", "data" => Flight::recipe_service()->insertData($request)]);
});


Flight::route("PUT /recipe/@id", function ($id) {
    $token = getTokenFromHeader();
    $userId = getUserIdFromToken($token);

    // Check if the user is authorized to edit the recipe
    $recipe = Flight::recipe_service()->getByID($id);
    if (!$recipe || $recipe['user_id'] !== $userId) {
        Flight::json(["message" => "Unauthorized"], 401);
        return;
    }

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "Recipe updated successfully", "data" => Flight::recipe_service()->updateData($request, $id)]);
});



?>