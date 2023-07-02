<?php
require_once "BaseDao.class.php";

class IngredientsDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("ingredients");
    }

}
?>