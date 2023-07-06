<?php
require_once "BaseDao.class.php";

class FavoritesDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("favorites");
    }

    public function addToFavorites($user_id, $recipe_id)
    {
        $stmt = $this->pdo->prepare("INSERT INTO favorites (user_id, recipe_id) VALUES (:user_id, :recipe_id)");
        $stmt->execute([':user_id' => $user_id, ':recipe_id' => $recipe_id]);
    }

    public function removeFromFavorites($user_id, $recipe_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND recipe_id = :recipe_id");
        $stmt->execute([':user_id' => $user_id, ':recipe_id' => $recipe_id]);
    }

    public function isFavorite($user_id, $recipe_id)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND recipe_id = :recipe_id");
        $stmt->execute([':user_id' => $user_id, ':recipe_id' => $recipe_id]);
        $isFavorite = $stmt->fetchColumn() > 0;
        error_log('Is favorite: ' . ($isFavorite ? 'true' : 'false'));
        return $isFavorite;
    }

    public function getUserFavorites($userId)
    {
        $stmt = $this->pdo->prepare("SELECT r.* FROM favorites f JOIN recipes r ON f.recipe_id = r.id WHERE f.user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $favorites;
    }



}
?>