<?php

Flight::route("GET /favorites", function () { // Get all favorites

    // favorite_service = new Projectfavorite_service() <- don't need this
    // $results = Flight::favorite_service()->getAll();
    Flight::json(Flight::favorite_service()->getAll());
});

Flight::route("GET /favorite/@id", function ($id) { // Get favorite by id 

    Flight::json(Flight::favorite_service()->getByID($id));
});

Flight::route("GET /favorite_by_id", function () { // Get favorite by id with query

    $id = Flight::request()->query['id'];
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