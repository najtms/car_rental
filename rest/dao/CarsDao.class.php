<?php

require_once __DIR__ . '/BaseDao.php';

class CarsDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('cars');
    }

    public function add_car($cars)
    {
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
            return $cars;
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
        $query = "SELECT * FROM cars WHERE car = :car";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':car', $car_id);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public function update_car($car_id, $data)
    {
        $query = "UPDATE cars SET ";
        $params = [];

        foreach ($data as $key => $value) {
            $query .= "`$key` = :$key, ";
            $params[":$key"] = $value;
        }

        $query = rtrim($query, ', ');
        $query .= " WHERE car_id = :car_id";
        $params[":car_id"] = $car_id;

        $statement = $this->connection->prepare($query);
        $statement->execute($params);
    }
}
