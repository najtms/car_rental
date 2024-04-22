<?php

require_once __DIR__ . '/services/CarsService.class.php';
require_once __DIR__ . '/dao/CarsDao.class.php';

// Assuming you can get car_id from POST or GET (modify based on your form method)
$car_id = isset($_POST['car_id']) ? $_POST['car_id'] : (isset($_GET['car_id']) ? $_GET['car_id'] : null);

if (is_null($car_id)) {
    // Handle missing car ID error (e.g., send error response)
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing car ID']);
    exit();
}

try {
    $cars_dao = new CarsDao();
    $car_service = new CarsService($cars_dao);

    $deleted = $car_service->delete_car($car_id);

    if ($deleted) {
        header('HTTP/1.1 200 OK');
        echo json_encode(['message' => 'Car deleted successfully']);
    } else {
        throw new Exception("Error deleting car"); // Re-throw for generic error handling
    }
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}
