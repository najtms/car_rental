<?php

require_once __DIR__ . '/BaseDao.php';

class ReservedDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('reference');
    }
    public function createReservation($userId, $carId, $location, $approvalStatus, $approvalDate)
    {
        $query = "INSERT INTO reservations (reference_id, user_id, car_id, location, approval_status, approval_date) 
                  VALUES (:reference_id, :user_id, :car_id, :location, :approval_status, :approval_date)";

        $statement = $this->connection->prepare($query);

        $statement->bindParam(':reference_id', $referenceId);
        $statement->bindParam(':user_id', $userId);
        $statement->bindParam(':car_id', $carId);
        $statement->bindParam(':location', $location);
        $statement->bindParam(':approval_status', $approvalStatus);
        $statement->bindParam(':approval_date', $approvalDate);

        $statement->execute();
    }
}
