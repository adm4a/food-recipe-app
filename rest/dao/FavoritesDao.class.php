<?php
require_once "BaseDao.class.php";

class FavoritesDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("favorites");
    }

}
?>