<?php

require_once __DIR__ . '/services/UserService.class.php';
require_once __DIR__ . '/dao/UserDao.class.php'; // Make sure to include UserDao


// Check if email is provided in the request
if (isset($_REQUEST['email'])) {
    $email = $_REQUEST['email']; // Corrected variable name

    $user_dao = new UserDao();

    // Instantiate UserService
    $user_service = new UserService($user_dao);

    // Retrieve user information
    $user = $user_service->get_email($email); // Corrected parameter name

    // Check if user exists
    if ($user !== null) {
        // User found, return user information in JSON format
        header('Content-Type: application/json');
        echo json_encode($user);
    } else {
        // User not found, return error message
        header("HTTP/1.1 404 Not Found");
        echo json_encode(array("error" => "User not found"));
    }
} else {
    // Email not provided in the request, return error message
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(array("error" => "Email not provided"));
}
