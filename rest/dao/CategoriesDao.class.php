<?php
require_once "BaseDao.class.php";

class CategoriesDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("categories");
    }

}
?>