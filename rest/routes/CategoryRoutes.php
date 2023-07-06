<?php

Flight::route("GET /categories", function () { // Get all categories

    Flight::json(Flight::category_service()->getAll());
});

Flight::route("GET /category/@id", function ($id) { // Get category by id 

    Flight::json(Flight::category_service()->getByID($id));
});

Flight::route("DELETE /category/@id", function ($id) { // Delete category by id

    Flight::category_service()->deleteByID($id);
    Flight::json(["message" => "Category with id " . $id . " deleted successfully"]);
});

Flight::route("POST /category", function () { // Insert new category

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "Category added successfully", "data" => Flight::category_service()->insertData($request)]);
});

Flight::route("PUT /category/@id", function ($id) {

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "Category updated successfully", "data" => Flight::category_service()->updateData($request, $id)]);
});


?>