<?php

require_once __DIR__ . "/../Config.class.php";
class BaseDao
{

    protected $pdo;
    protected $table_name;

    /**
     * Class constructor used to establish the connection to the db
     */
    public function __construct($table_name)
    {
        try {
            $this->table_name = $table_name;
            $host = Config::DB_HOST(); 
            $dbname = Config::DB_SCHEMA(); 
            $user = Config::DB_USERNAME(); 
            $pass = Config::DB_PASSWORD(); 

            // Create a new PDO connection
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

            // Set the PDO error mode to exception
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Echo a message to confirm that the connection was successful
            echo "Connected successfully to database $dbname on host $host";

        } catch (PDOException $e) {
            // If an error occurs, catch the exception and display the error message
            echo "Connection failed: " . $e->getMessage();
        }

    }

    /**
     * Method used to get all entities from db
     */
    public function getAll()
    {

        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Method used to get entity by id from db
     */
    public function getByID($id)
    {

        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Method used to delete entity by id from db
     */
    public function deleteByID($id)
    {

        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table_name . " WHERE id = :id");
        $stmt->bindParam(':id', $id); // Prevents SQL injection
        $stmt->execute();
    }

    /**
     * Method used to insert entity to db
     */
    public function insertData($entity)
    {

        $query = "INSERT INTO " . $this->table_name . " (";
        foreach ($entity as $column => $value) {
            $query .= $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":" . $column . ", ";
        }
        $query = substr($query, 0, -2);
        $query .= ")";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($entity);
        $entity['id'] = $this->pdo->lastInsertID();
        return $entity;
    }


    /**
     * Method used to update entity in db
     */
    public function updateData($entity, $id, $id_column = "id")
    {
        $query = "UPDATE " . $this->table_name . " SET ";
        $params = [];

        foreach ($entity as $column => $value) {
            $query .= $column . " = :" . $column . ", ";
            $params[$column] = $value;
        }

        $query = rtrim($query, ", ");
        $query .= " WHERE {$id_column} = :id";
        $params['id'] = $id;

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            return $entity;
        } catch (PDOException $e) {
            echo "Failed to update entity: " . $e->getMessage();
            return false;
        }
    }



}
?>