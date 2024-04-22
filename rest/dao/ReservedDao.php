<?php

require_once __DIR__ . '/BaseDao.php'; // Assuming BaseDao provides database connection

class ReservedDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('reference'); // Replace 'cars' with your actual table name
    }
    public function createReservation($userId, $carId, $location, $approvalStatus, $approvalDate)
    {
        $query = "INSERT INTO reservations (reference_id, user_id, car_id, location, approval_status, approval_date) 
                  VALUES (:reference_id, :user_id, :car_id, :location, :approval_status, :approval_date)";

        $statement = $this->connection->prepare($query);

        // Bind parameters
        $statement->bindParam(':reference_id', $referenceId);
        $statement->bindParam(':user_id', $userId);
        $statement->bindParam(':car_id', $carId);
        $statement->bindParam(':location', $location);
        $statement->bindParam(':approval_status', $approvalStatus);
        $statement->bindParam(':approval_date', $approvalDate);

        // Execute the query
        $statement->execute();
    }
}
