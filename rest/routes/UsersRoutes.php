<?php

Flight::route("/", function() {

    echo "Hello world";
});

Flight::route("GET /users", function() {     // Get all users

    // user_service = new Projectuser_service() <- don't need this
    // $results = Flight::user_service()->getAll();
    Flight::json(Flight::user_service()->getAll());
 });

Flight::route("GET /user/@id", function($id) {     // Get user by id 

   Flight::json(Flight::user_service()->getByID($id));
});

Flight::route("GET /user_by_id", function() {     // Get user by id with query

    $id = Flight::request() -> query['id'];
    Flight::json(Flight::user_service()->getByID($id));
 });

Flight::route("DELETE /user/@id", function($id) {   // Delete user by id

    Flight::user_service() -> deleteByID($id);
    Flight::json(["message" => "User with id " . $id . " deleted successfully"]);
});

Flight::route("POST /user", function() {   // Insert new user

    $request = Flight::request()->data->getData();
    Flight::json(["message" => "User added successfully", "data: " => Flight::user_service() -> insertData($request)]);
});

Flight::route("PUT /user/@id", function($id) {   

    $request = Flight::request()->data->getData();
    // $request['id'] = $id;     Another way to show id         
    Flight::json(["message" => "User updated successfully", "data: " => Flight::user_service() -> updateData($request, $id)]);
});

?>