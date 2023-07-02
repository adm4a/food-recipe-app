<?php
require_once "BaseService.php";
require_once __DIR__."/../dao/CategoriesDao.class.php";

class CategoryService extends BaseService{

    public function __construct() {
        parent::__construct(new CategoriesDao);

    }


}

?>