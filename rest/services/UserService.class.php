<?php

require_once __DIR__ . '/../dao/UserDao.class.php';

class UserService
{
    private $user_dao;

    public function __construct(UserDao $user_dao)
    {
        $this->user_dao = $user_dao;
    }

    public function add_user($user)
    {
        try {
            // Validate user data (if needed)

            // Add user using UserDao
            return $this->user_dao->add_user($user);
        } catch (Exception $e) {
            // Log the error or handle it appropriately
            throw new Exception("Error adding user: " . $e->getMessage(), $e->getCode());
        }
    }

    public function get_username($username)
    {
        return $this->user_dao->get_username($username);
    }

    public function get_email($email)
    {
        return $this->user_dao->get_email($email);
    }
}
