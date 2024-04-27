<?php

require_once __DIR__ . '/services/CarsService.class.php';
require_once __DIR__ . '/dao/CarsDao.class.php';

$cars_dao = new CarsDao();
$car_service = new CarsService($cars_dao);

$car_id = isset($_POST['car_id']) ? $_POST['car_id'] : null;
$data = isset($_POST['data']) ? json_decode($_POST['data'], true) : null; // Assuming data is JSON encoded

if (is_null($car_id) || is_null($data)) {
    // Handle missing car ID or data (e.g., send error response)
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing car ID or data']);
    exit();
}

try {
    $updated_car = $car_service->update_car($car_id, $data);
    if ($updated_car) {
        header('HTTP/1.1 200 OK');
        echo json_encode($updated_car);
    } else {
        throw new Exception("Error updating car"); // Handle update failure
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
