<?php

require_once "../vendor/autoload.php";
require_once "./helpers.php";
require_once "./JwtHelper.php";

require_once("services/UserService.php");
require_once("services/RecipeService.php");
require_once("services/CategoryService.php");
require_once("services/FavoriteService.php");
require_once("services/IngredientService.php");

Flight::register("user_service", "UserService");
Flight::register("recipe_service", "RecipeService");
Flight::register("category_service", "CategoryService");
Flight::register("favorite_service", "FavoriteService");
Flight::register("ingredient_service", "IngredientService");

require_once "routes/UsersRoutes.php";
require_once "routes/RecipesRoutes.php";
require_once "routes/CategoryRoutes.php";
require_once "routes/FavoritesRoutes.php";
require_once "routes/IngredientsRoutes.php";

Flight::before('start', function (&$params, &$output) {
    $excludedRoutesStartsWith = ["/login", "/register", "/recipes", "/recipe/", "/users"];
    $request = Flight::request();

    foreach ($excludedRoutesStartsWith as $route) {
        if (strpos($request->url, $route) === 0) {
            return;
        }
    }

    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        Flight::json(["message" => "Unauthorized"], 401);
        Flight::stop();
    }

    $token = substr($headers['Authorization'], 7);
    $userId = getUserIdFromToken($token);
    if (!$userId) {
        Flight::json(["message" => "Authorization token is not valid"], 403);
        Flight::stop();
    }

    Flight::set('user', $userId);
});



Flight::start();

?>