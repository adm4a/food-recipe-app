<?php
require_once "BaseDao.class.php";

class RecipesDao extends BaseDao { 

    public function __construct() {
        parent:: __construct("Recipes");
    }

}
?>
