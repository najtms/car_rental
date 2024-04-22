<?php

require_once __DIR__ . '/BaseDao.php'; // Assuming BaseDao provides database connection

class CarsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('cars'); // Replace 'cars' with your actual table name
    }

    public function add_car($cars)
    {
        // Validate car data (optional, enhance based on your requirements)
        if (
            empty($cars['car']) || empty($cars['engine']) ||
            empty($cars['kilometers']) || empty($cars['fueltype']) ||
            empty($cars['transmissions']) || empty($cars['seats']) ||
            empty($cars['cartype'])
        ) {
            throw new Exception("Missing required car data");
        }

        // Check for duplicate car name
        $query = "SELECT COUNT(*) FROM cars WHERE car = :car";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':car', $cars['car']);
        $statement->execute();
        $count = $statement->fetchColumn();

        if ($count > 0) {
            throw new Exception("Car with the name '" . $cars['car'] . "' already exists");
        }

        // Insert data if no duplicates found (existing code)
        $query = "INSERT INTO cars (car, engine, kilometers, fueltype, transmissions, seats, cartype, imgurl)
                  VALUES (:car, :engine, :kilometers, :fueltype, :transmissions , :seats, :cartype, :imgurl)";
        $statement = $this->connection->prepare($query);

        $statement->bindParam(':car', $cars['car']);
        $statement->bindParam(':engine', $cars['engine']);
        $statement->bindParam(':kilometers', $cars['kilometers']);
        $statement->bindParam(':fueltype', $cars['fueltype']);
        $statement->bindParam(':transmissions', $cars['transmissions']);
        $statement->bindParam(':seats', $cars['seats']);
        $statement->bindParam(':cartype', $cars['cartype']);
        $statement->bindParam(':imgurl', $cars['imgurl']);




        if ($statement->execute()) {
            return $cars; // Optional: return the inserted data
        } else {
            throw new Exception("Error adding car to database");
        }
    }
    public function delete_car($cars)
    {
        $query = "DELETE FROM cars WHERE car = :car";
        $statement = $this->connection->prepare($query);
        $statement->execute(['car' => $cars]);
    }
    public function get_car($car_id)
    {
        $query = "SELECT * FROM cars WHERE car = :car"; // Modified query parameter name
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':car', $car_id); // Modified parameter name
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function update_car($car_id, $data)
    {
        $query = "UPDATE cars SET ";
        $params = [];

        // Build dynamic update query based on provided data
        foreach ($data as $key => $value) {
            $query .= "`$key` = :$key, ";
            $params[":$key"] = $value;
        }

        // Remove trailing comma from query
        $query = rtrim($query, ', ');
        $query .= " WHERE car_id = :car_id";
        $params[":car_id"] = $car_id;

        $statement = $this->connection->prepare($query);
        $statement->execute($params);
    }
}
