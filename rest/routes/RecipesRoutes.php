<?php
require_once "helpers.php";
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="My first API",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="support@example.com"
 *   )
 * )
 */

/**
 * @OA\PathItem(
 *   path="/",
 *   @OA\Get(
 *     tags={"Default"},
 *     summary="Default route",
 *     description="Default route of the API",
 *     @OA\Response(response="200", description="Successful operation")
 *   )
 * )
 */

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

/**
 * @OA\Get(
 *     path="/recipes",
 *     tags={"Recipes"},
 *     summary="Get all recipes",
 *     description="Retrieves all recipes with pagination support.",
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Page number",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             default=1
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="itemsPerPage",
 *         in="query",
 *         description="Number of items per page",
 *         required=false,
 *         @OA\Schema(
 *             type="integer",
 *             default=8
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="searchText",
 *         in="query",
 *         description="Text to search in recipe titles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Recipe")
 *         )
 *     )
 * )
 */
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