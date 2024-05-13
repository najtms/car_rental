<?php

require_once __DIR__ . '/../services/CarsService.class.php';
require_once __DIR__ . '/../dao/CarsDao.class.php';

Flight::group('/car', function () {

    Flight::set('cars_dao', new CarsDao);
    Flight::set('cars_service', new CarsService(Flight::get('cars_dao')));
    /**
     * @OA\Get(
     *      path="/car/get/{car_id}",
     *      tags={"car"}, 
     *      summary="Retrieve a specific car",
     *      description="Returns the details of a car based on its ID",
     *      @OA\Parameter(
     *          name="car_id",
     *          in="path",
     *          description="The ID of the car to retrieve",
     *          required=true,
     *          @OA\Schema(type="string") 
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Car found successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="car", type="string"),
     *              @OA\Property(property="engine", type="string"),
     *              @OA\Property(property="kilometers", type="string"),
     *              @OA\Property(property="fueltype", type="string"),
     * @OA\Property(property="transmissions", type="string"),
     * @OA\Property(property="seats", type="integer"),
     *       @OA\Property(property="cartype", type="string"),
     *  @OA\Property(property="imgurl", type="string")
     *      *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Car not found",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string", example="Car not found")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="error", type="string") 
     *          )
     *      )
     * )
     */


    Flight::route('GET /get', function ($car_id) {
        try {
            $car = Flight::get('cars_service')->get_car($car_id);

            if ($car) {
                Flight::json($car, 200); // Success with 200 OK
            } else {
                Flight::halt(404, json_encode(['error' => 'Car not found']));
            }
        } catch (CarRentalError $e) {
            Flight::halt($e->getCode() ?: 500, json_encode(['error' => $e->getMessage()]));
        }
    });
    /**
     * @OA\Post(
     *     path="/add",
     *     summary="Add a new car",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="car",
     *                 type="object",
     *                 @OA\Property(property="make", type="string", example="Toyota"),
     *                 @OA\Property(property="model", type="string", example="Corolla"),
     *                 @OA\Property(property="year", type="integer", example=2022)
     *             ),
     *             required={"car"}
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have successfully added the car"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="make", type="string", example="Toyota"),
     *                 @OA\Property(property="model", type="string", example="Corolla"),
     *                 @OA\Property(property="year", type="integer", example=2022)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="Car field is missing"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(@OA\Property(property="error", type="string", example="An unexpected error occurred"))
     *     )
     * )
     */
    Flight::route('POST /add', function () {
        $payload = Flight::request()->data;

        if (!isset($payload['car']) || empty($payload['car'])) {
            Flight::halt(500, json_encode(['error' => "Car field is missing"]));
        }


        try {
            $car = Flight::get('cars_service')->get_car($payload);
            Flight::json(['message' => "You have successfully added the car", 'data' => $car]);
        } catch (CarRentalErrorX $e) {
            // Handle the error appropriately based on $e->getCode() if needed
            Flight::halt($e->getCode() ?: 500, json_encode(['error' => $e->getMessage()]));
        }
    });
    /**
     * @OA\Put(
     *     path="/delete/{car_id}",
     *     summary="Delete a car",
     *     @OA\Parameter(
     *         name="car_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the car to be deleted"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Car deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Missing car ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error deleting car")
     *         )
     *     )
     * )
     */
    Flight::route('PUT /delete/@car_id', function ($car_id) {
        if (!$car_id) {
            Flight::halt(400, json_encode(['error' => 'Missing car ID']));
        }

        try {
            $deleted = Flight::get('cars_service')->delete_car($car_id);

            if ($deleted) {
                Flight::json(['message' => 'Car deleted successfully'], 200);
            } else {
                throw new CarRentalError("Error deleting car");
            }
        } catch (CarRentalError $e) {
            Flight::halt($e->getCode() ?: 500, json_encode(['error' => $e->getMessage()]));
        }
    });
    /**
     * @OA\Put(
     *     path="/update/{car_id}",
     *     summary="Update a car",
     *     @OA\Parameter(
     *         name="car_id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the car to be updated"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="make", type="string", example="Toyota"),
     *             @OA\Property(property="model", type="string", example="Corolla"),
     *             @OA\Property(property="year", type="integer", example=2022)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Car updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="make", type="string", example="Toyota"),
     *             @OA\Property(property="model", type="string", example="Corolla"),
     *             @OA\Property(property="year", type="integer", example=2022)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error updating car")
     *         )
     *     )
     * )
     */
    Flight::route('PUT /update/@car_id', function ($car_id) {
        $data = Flight::request()->data->getData();
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        try {
            $updated_car = Flight::get('cars_service')->update_car($car_id, $data);

            if ($updated_car) {
                Flight::json($updated_car, 200);
            } else {
                Flight::halt(500, json_encode(['error' => 'Error updating car']));
            }
        } catch (CarRentalError $e) {
            Flight::halt($e->getCode() ?: 500, json_encode(['error' => $e->getMessage()]));
        }
    });
    class CarRentalErrorX extends Exception
    {
        public function __construct($message, $code = null)
        {
            parent::__construct($message);
            $this->code = $code; // Can be string or integer
        }
    }
});
