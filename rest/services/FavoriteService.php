<?php
require_once "BaseService.php";
require_once __DIR__ . "/../dao/FavoritesDao.class.php";

class FavoriteService extends BaseService
{

    public function __construct()
    {
        parent::__construct(new FavoritesDao);

    }
    public function addToFavorites($userId, $recipeId)
    {
        $this->dao->addToFavorites($userId, $recipeId);
    }

    public function removeFromFavorites($userId, $recipeId)
    {
        $this->dao->removeFromFavorites($userId, $recipeId);
    }

    public function isFavorite($userId, $recipeId)
    {
        return $this->dao->isFavorite($userId, $recipeId);
    }


    public function getUserFavorites($userId)
    {
        return $this->dao->getUserFavorites($userId);
    }


}

?>