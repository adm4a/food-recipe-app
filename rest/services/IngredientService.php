<?php
require_once "BaseService.php";
require_once __DIR__."/../dao/IngredientsDao.class.php";

class IngredientService extends BaseService{

    public function __construct() {
        parent::__construct(new IngredientsDao);

    }


}

?>