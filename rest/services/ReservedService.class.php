<?php
require_once __DIR__ . '/../dao/ReservedDao.class.php';

class ReservedService
{
    private $reserved_dao;
    private $user_service;
    private $car_service;

    public function __construct(ReservedDao $reserved_dao, UserService $user_service, CarService $car_service)
    {
        $this->reserved_dao = $reserved_dao;
        $this->user_service = $user_service;
        $this->car_service = $car_service;
    }

    public function createReservation($userId, $carId, $location)
    {
        // Validate user ID, car ID, and location (optional)

        // Check if user and car exist
        $user = $this->user_service->getUserById($userId);
        $car = $this->car_service->getCarById($carId);

        if (!$user || !$car) {
            return ['success' => false, 'message' => 'User or car not found'];
        }

        // Process reservation
        $approvalStatus = 'Pending';
        $approvalDate = null; // This can be set when the reservation is approved

        // Create reservation in the DAO
        $this->reserved_dao->createReservation($referenceId, $userId, $carId, $location, $approvalStatus, $approvalDate);

        return ['success' => true, 'message' => 'Reservation created successfully', 'reference_id' => $referenceId];
    }
}
