<?php
require_once "BaseDao.class.php";

class RecipesDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("recipes");
    }

    public function getRecipes($page = 1, $itemsPerPage = 8, $searchText = '', $userId = null)
    {
        $offset = ($page - 1) * $itemsPerPage;
        $searchQuery = '';
        if ($searchText !== '') {
            $searchText = '%' . $searchText . '%';
            $searchQuery = ' WHERE title LIKE :searchText';
        }

        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . $searchQuery . " ORDER BY id LIMIT :itemsPerPage OFFSET :offset");

        $stmt->bindValue(':itemsPerPage', (int) $itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        if ($searchText !== '') {
            $stmt->bindValue(':searchText', $searchText);
        }

        $stmt->execute();
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        error_log("Got userid:" . $userId);
        if ($userId) {
            foreach ($recipes as &$recipe) {
                $recipe['isFavorite'] = Flight::favorite_service()->isFavorite($userId, $recipe['id']);
            }
        }



        $stmt = $this->pdo->prepare("SELECT COUNT(*) as totalCount FROM " . $this->table_name . $searchQuery);

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
           
            echo "Failed to update entity: " . $e->getMessage();
            return null;
        }
    }

    public function getByID($id)
    {
        try {
            $query = "SELECT r.*, u.username, c.name as category_name
                      FROM recipes r 
                      JOIN users u ON r.user_id = u.id 
                      JOIN categories c ON r.category_id = c.id 
                      WHERE r.id = :id";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['id' => $id]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
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