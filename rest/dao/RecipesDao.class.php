<?php
require_once "BaseDao.class.php";

class RecipesDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("recipes");
    }

    public function getRecipes($page = 1, $itemsPerPage = 8, $searchText = '')
    {
        $offset = ($page - 1) * $itemsPerPage;

        // If a search term is provided, add a WHERE clause to the query.
        $searchQuery = '';
        if ($searchText !== '') {
            $searchText = '%' . $searchText . '%'; // Using % on both sides for a full match.
            $searchQuery = ' WHERE title LIKE :searchText';
        }

        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . $searchQuery . " ORDER BY id LIMIT :itemsPerPage OFFSET :offset");

        $stmt->bindValue(':itemsPerPage', (int) $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        // If a search term is provided, bind the parameter.
        if ($searchText !== '') {
            $stmt->bindValue(':searchText', $searchText);
        }

        $stmt->execute();
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get the total count of recipes.
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as totalCount FROM " . $this->table_name . $searchQuery);

        // If a search term is provided, bind the parameter.
        if ($searchText !== '') {
            $stmt->bindValue(':searchText', $searchText);
        }

        $stmt->execute();
        $totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['totalCount'];

        return ['recipes' => $recipes, 'totalCount' => (int) $totalCount];
    }

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
            // Log or handle the exception as needed
            echo "Failed to update entity: " . $e->getMessage();
            return null;
        }
    }


    public function getByUserId($userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $recipes;
    }


}
?>