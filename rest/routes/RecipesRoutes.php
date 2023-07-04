<?php
require_once "helpers.php";

Flight::route("/", function () {

    echo "Hello world";
});

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
    $request = Flight::request();
    $page = isset($request->query['page']) ? $request->query['page'] : 1;
    $itemsPerPage = isset($request->query['itemsPerPage']) ? $request->query['itemsPerPage'] : 8;
    $searchText = isset($request->query['searchText']) ? $request->query['searchText'] : '';
    Flight::json(Flight::recipe_service()->getRecipes($page, $itemsPerPage, $searchText));
});

Flight::route("GET /recipe/@id", function ($id) { // Get recipes by id 

    Flight::json(Flight::recipe_service()->getByID($id));
});

Flight::route("GET /recipe_by_id", function () { // Get recipes by id with query

    $id = Flight::request()->query['id'];
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

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "Recipe added successfully", "data: " => Flight::recipe_service()->insertData($request)]);
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