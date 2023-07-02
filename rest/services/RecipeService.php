<?php
require_once "BaseService.php";
require_once __DIR__."/../dao/RecipesDao.class.php";

class RecipeService extends BaseService{

    public function __construct() {
        parent::__construct(new RecipesDao);

    }


}

?>