<?php

require_once __DIR__ . '/services/CarsService.class.php';
require_once __DIR__ . '/dao/CarsDao.class.php';

$payload = $_REQUEST;

// Check if the 'username' field is missing or empty
if (!isset($payload['car']) || empty($payload['car'])) {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "Car field is missing"]));
}

// Create a UserDao instance
$car_dao = new CarsDao();

// Create a UserService instance and pass the UserDao instance as a parameter
$car_service = new CarsService($car_dao);

$cars = $car_service->add_car($payload);

echo json_encode(['message' => "You have successfully added the user", 'data' => $cars]);
