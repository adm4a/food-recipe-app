<?php
require_once "BaseDao.class.php";

class UsersDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("users");
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . $this->table_name . " WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteByID($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM " . $this->table_name . " WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }




}
?>