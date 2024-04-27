<?php

require_once __DIR__ . '/services/CarsService.class.php';
require_once __DIR__ . '/dao/CarsDao.class.php';

$cars_dao = new CarsDao();
$car_service = new CarsService($cars_dao);


$car_id = isset($_GET['car_id']) ? $_GET['car_id'] : null;

if (is_null($car_id)) {
    // Handle missing car ID error (e.g., send error response)
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing car ID']);
    exit();
}

$car = $car_service->get_car($car_id);

if ($car) {
    header('HTTP/1.1 200 OK');
    echo json_encode($car);
} else {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Car not found']);
}
