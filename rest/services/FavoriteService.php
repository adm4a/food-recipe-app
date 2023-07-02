<?php
require_once "BaseService.php";
require_once __DIR__."/../dao/FavoritesDao.class.php";

class FavoriteService extends BaseService{

    public function __construct() {
        parent::__construct(new FavoritesDao);

    }


}

?>