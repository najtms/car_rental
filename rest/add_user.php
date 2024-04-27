<?php

require_once __DIR__ . '/services/UserService.class.php';
require_once __DIR__ . '/dao/UserDao.class.php'; // Make sure to include UserDao

$payload = $_REQUEST;

// Check if the 'username' field is missing or empty
if (!isset($payload['username']) || empty($payload['username'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "Username field is missing"]));
}

// Create a UserDao instance
$user_dao = new UserDao();

// Create a UserService instance and pass the UserDao instance as a parameter
$user_service = new UserService($user_dao);

$user = $user_service->add_user($payload);

echo json_encode(['message' => "You have successfully added the user", 'data' => $user]);
class CarRentalError extends Exception
{
    public function __construct($message, $code = null)
    {
        parent::__construct($message);
        $this->code = $code; // Can be string or integer
    }
}
