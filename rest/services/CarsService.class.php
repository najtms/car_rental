<?php

require_once __DIR__ . '/../dao/CarsDao.class.php';

class CarRentalError extends Exception
{
    public function __construct($message, $code = null)
    {
        parent::__construct($message);
        $this->code = $code; // Can be string or integer
    }
}

class CarsService
{
    private $cars_dao;

    public function __construct(CarsDao $cars_dao)
    {
        $this->cars_dao = $cars_dao;
    }

    public function add_car($cars)
    {
        try {
            return $this->cars_dao->add_car($cars);
        } catch (Exception $e) {
            // Log the error or handle it appropriately
            throw new CarRentalError("Error adding car: " . $e->getMessage(), 'CAR_ADD_ERROR'); // Use string cod        }
        }
    }
    public function delete_car($cars)
    {
        $this->cars_dao->delete_car($cars);
    }
    public function get_car($car_id)
    {
        return $this->cars_dao->get_car($car_id);
    }
    public function update_car($car_id, $data)
    {
        $this->cars_dao->update_car($car_id, $data);
        return $this->cars_dao->get_car($car_id); // Return updated car data
    }
}
