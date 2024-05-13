<?php

require_once __DIR__ . '/../services/UserService.class.php';
require_once __DIR__ . '/../dao/UserDao.class.php';

Flight::set('user_dao', new UserDao);
Flight::set('user_service', new UserService(Flight::get('user_dao')));

Flight::route('POST /users/add', function () {
    $payload = Flight::request()->data; // Get request payload

    // Check if the 'username' field is missing or empty
    if (!isset($payload['username']) || empty($payload['username'])) {
        Flight::halt(500, json_encode(['error' => "Username field is missing"]));
    }


    try {
        $user = Flight::get('user_service')->add_user($payload);
        Flight::json(['message' => "You have successfully added the user", 'data' => $user]);
    } catch (CarRentalError $e) {
        // Handle the error appropriately based on $e->getCode() if needed
        Flight::halt($e->getCode() ?: 500, json_encode(['error' => $e->getMessage()]));
    }
});
/**
 * @OA\Post(
 *     path="/users/add",
 *     summary="Add a new user",
 *     description="Adds a new user to the system.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="username", type="string", example="john_doe")
 *             // Add more properties here if needed
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="You have successfully added the user"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 // Define properties of the user object here
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="An unexpected error occurred")
 *         )
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/users/{username}",
 *     summary="Get user by username",
 *     description="Returns a single user by their username.",
 *     @OA\Parameter(
 *         name="username",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Username of the user to retrieve"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User found",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             // Add more properties if needed
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="User not found")
 *         )
 *     )
 * )
 */
Flight::route('GET /users/{username}', function ($username) {
    $user = Flight::get('user_service')->get_username($username);

    if ($user !== null) {
        Flight::json($user); // Success - Send JSON response
    } else {
        Flight::halt(404, json_encode(array("error" => "User not found"))); // Error - 404
    }
});

/**
 * @OA\Get(
 *     path="/users/email",
 *     summary="Get user by email",
 *     description="Returns a single user by their email address.",
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="string"),
 *         description="Email address of the user to retrieve"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User found",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             // Add more properties if needed
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="User not found")
 *         )
 *     )
 * )
 */
Flight::route('GET /users/email', function () {
    $email = Flight::request()->query['email'] ?? null;

    if ($email === null) {
        Flight::halt(400, json_encode(['error' => "Email parameter is missing"]));
    }

    $user = Flight::get('user_service')->get_email($email);

    if ($user !== null) {
        Flight::json($user); // Success - Send JSON response
    } else {
        Flight::halt(404, json_encode(array("error" => "User not found"))); // Error - 404
    }
});
