<?php

require_once "../vendor/autoload.php";   

require_once ("services/UserService.php");
require_once ("services/RecipeService.php");
require_once ("services/CategoryService.php");
//require_once ("services/IngredientService.php");


Flight::register("user_service", "UserService");
Flight::register("recipe_service", "RecipeService");
Flight::register("category_service", "CategoryService");
//Flight::register("ingredient_service", "IngredientService");


require_once "routes/UsersRoutes.php";
require_once "routes/RecipesRoutes.php";
require_once "routes/CategoryRoutes.php";
//require_once "routes/IngredientRoutes.php";


Flight::start();    

?>
