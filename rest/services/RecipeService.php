<?php
require_once "BaseService.php";
require_once __DIR__ . "/../dao/RecipesDao.class.php";

class RecipeService extends BaseService
{

    public function __construct()
    {
        parent::__construct(new RecipesDao);

    }
    public function getRecipes($page = 1, $itemsPerPage = 8, $searchText = '')
    {
        return $this->dao->getRecipes($page, $itemsPerPage, $searchText);
    }

    public function updateData($entity, $id)
    {
        // Update the recipe in the database
        $updatedRecipe = $this->dao->updateData($entity, $id);

        if ($updatedRecipe !== false) {
            return $updatedRecipe;
        } else {
            Flight::json(["message" => "Failed to update recipe"], 500);
            return;
        }
    }

    public function getByUserId($userId)
    {
        return $this->dao->getByUserId($userId);
    }


}

?>