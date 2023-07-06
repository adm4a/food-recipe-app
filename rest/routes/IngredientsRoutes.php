<?php

Flight::route("GET /ingredients", function () { // Get all ingredients

    Flight::json(Flight::ingredient_service()->getAll());
});

Flight::route("GET /ingredient/@id", function ($id) { // Get ingredient by id 

    Flight::json(Flight::ingredient_service()->getByID($id));
});

Flight::route("GET /ingredient_by_id", function () { // Get ingredient by id with query

    $id = Flight::request()->query['id'];
    Flight::json(Flight::ingredient_service()->getByID($id));
});

Flight::route("DELETE /ingredient/@id", function ($id) { // Delete ingredient by id

    Flight::ingredient_service()->deleteByID($id);
    Flight::json(["message" => "ingredient with id " . $id . " deleted successfully"]);
});

Flight::route("POST /ingredient", function () { // Insert new ingredient

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "ingredient added successfully", "data: " => Flight::ingredient_service()->insertData($request)]);
});

Flight::route("PUT /ingredient/@id", function ($id) {

    $request = Flight::request()->data->getData();
    // $request['id'] = $id;     Another way to show id         
    Flight::json(["message" => "ingredient updated successfully", "data: " => Flight::ingredient_service()->updateData($request, $id)]);
});

?>