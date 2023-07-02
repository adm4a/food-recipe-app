<?php

Flight::route("/", function() {

    echo "Hello world"; 
});

Flight::route("GET /recipes", function() {     // Get all recipes

    // recipe_service = new Projectrecipe_service() <- don't need this
    // $results = Flight::recipe_service()->getAll();
    Flight::json(Flight::recipe_service()->getAll());
 });

Flight::route("GET /recipe/@id", function($id) {     // Get recipes by id 

   Flight::json(Flight::recipe_service()->getByID($id));
});

Flight::route("GET /recipe_by_id", function() {     // Get recipes by id with query

    $id = Flight::request()->query['id'];
    Flight::json(Flight::recipe_service()->getByID($id));
 });

Flight::route("DELETE /recipe/@id", function($id) {   // Delete recipe by id

    Flight::recipe_service() -> deleteByID($id);
    Flight::json(["message" => "Recipe with id " . $id . " deleted successfully"]);
});

Flight::route("POST /recipe", function() {   

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "Recipe added successfully", "data: " => Flight::recipe_service() -> insertData($request)]);
});

Flight::route("PUT /recipe/@id", function($id) {   

    $request = Flight::request()->data->getData();
    // $request['id'] = $id;     Another way to show id         
    Flight::json(["message" => "Recipe updated successfully", "data: " => Flight::recipe_service() -> updateData($request, $id)]);
});


?>