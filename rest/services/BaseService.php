<?php

class BaseService {
    private $dao;
    public function __construct($dao) {
        $this->dao = $dao;
    }

    public function getAll() {

        return $this->dao->getAll();
    }

    public function getByID($id) {

        return $this->dao->getByID($id);
    }

    public function deleteByID($id) {

        return $this->dao->deleteByID($id);
    }

    public function insertData($entity) {

        return $this->dao->insertData($entity);
    }

    public function updateData($entity, $id) {

        return $this->dao->updateData($entity, $id);
    }

}

?>