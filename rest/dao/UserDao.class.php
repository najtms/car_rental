<?php

require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('user');
    }

    public function add_user($user)
    {
        $query = "INSERT INTO user (email, username, password)
                  VALUES (:email, :username, :password)";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':email', $user['email']);
        $statement->bindParam(':username', $user['username']);
        $statement->bindParam(':password', $user['password']);
        $statement->execute();
        $user['id'] = $this->connection->lastInsertId();
        return $user;
    }

    public function get_username($username)
    {
        $statement = $this->connection->prepare("SELECT * FROM user WHERE username = ?");
        $statement->execute([$username]);
        $user = $statement->fetch(); // Assuming you want to fetch as associative array

        return $user; // This will return the user information if found, or false if not found
    }

    public function delete_user($id)
    {
    }
    public function get_email($email)
    {
        $statement = $this->connection->prepare("SELECT * FROM user WHERE email = ?");
        $statement->execute([$email]);
        $user = $statement->fetch(); // Assuming you want to fetch as associative array

        return $user; // This will return the user information if found, or false if not found
    }
}
