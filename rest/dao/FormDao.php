<?php

require_once __DIR__ . '/BaseDao.php';

class FormDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('Form');
    }

    public function add_formtest($form)
    {
        $query = "INSERT INTO Form (name, email, phone, message)
        VALUES (:name, :email, :phone, :message)";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':name', $form['name']);
        $statement->bindParam(':email', $form['email']);
        $statement->bindParam(':phone', $form['phone']);
        $statement->bindParam(':message', $form['message']);
        $statement->execute();
        return $form;
    }

    public function get_form()
    {
        $query = "SELECT name,email, phone, message FROM Form";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete_form($name)
    {
        $stmt = $this->connection->prepare("DELETE FROM Form WHERE name = :name");
        $stmt->execute(["name" => $name]);
    }
};
