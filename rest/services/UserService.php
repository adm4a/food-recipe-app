<?php
require_once "BaseService.php";
require_once __DIR__."/../dao/UsersDao.class.php";

class UserService extends BaseService {

    public function __construct() {
        parent::__construct(new UsersDao);

    }

    public function insertData($entity) {
        $entity['password'] = md5($entity['password']);   // Converts password to hash code 
        return parent::insertData($entity);
    }

    public function updateData($user, $id){
        $user['password'] = md5($user['password']);
        if(isset($user['id_column'])  && !is_null($user['id_column'])){
            return parent::updateData($user, $id, $user['id_column']);
        }
        return parent::updateData($user, $id);
    }
}

?>