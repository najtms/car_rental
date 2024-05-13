<?php
require_once __DIR__ . '/../dao/ReservedDao.php';

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
}
