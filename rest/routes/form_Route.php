<?php

require_once __DIR__ . '/../services/FormService.class.php';
require_once __DIR__ . '/../dao/FormDao.php';

Flight::group('/form', function () {



    Flight::set('form_dao', new FormDao());
    Flight::set('form_service', new FormService(Flight::get('form_dao')));

    /**
     * @OA\Post(
     *     path="/addtest",
     *     summary="Add a new form test",
     *     description="Adds a new form to the form service.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Sample Form")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Form added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You have successfully added the form"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Sample Form")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Name field is missing or empty")
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
    Flight::route('POST /addtest', function () {
        $payload = Flight::request()->data;

        if (!isset($payload['name']) || empty($payload['name'])) {
            Flight::halt(400, json_encode(['error' => "Name field is missing or empty"]));
        }

        try {
            $form = Flight::get('form_service')->add_formtest($payload);
            Flight::json(['message' => "You have successfully added the form", 'data' => $form]);
        } catch (Exception $e) {
            Flight::halt(500, json_encode(['error' => $e->getMessage()]));
        }
    });


    Flight::route('PUT /update ', function () {
    });

    /**
     * @OA\Get(
     *      path="/form/get",
     *      tags={"form"},
     *      summary="Get patient",
     *      @OA\Response(
     *           response=200,
     *           description="Get patient by ID"
     * )
     * )
     */

    Flight::route('GET /get', function () {
        try {
            $formData = Flight::get('form_service')->get_form();
            Flight::json($formData);
        } catch (Exception $e) {
            Flight::halt(500, json_encode(['error' => $e->getMessage()]));
        }
    });
    Flight::route('DELETE /delete/@name', function ($name) {
        if ($name == NULL || $name == '') {
            Flight::halt(500, "Required parameters are missing!");
        }

        $patient_service = Flight::get('form_service');
        $patient_service->delete_form($name);

        Flight::json(['data' => NULL, 'message' => "You have successfully deleted the patient"]);
    });
    /**
     * @OA\Delete(
     *     path="/delete/{name}",
     *     summary="Delete a form",
     *     description="Deletes a form from the form service by name.",
     *     @OA\Parameter(
     *         name="name",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Name of the form to be deleted"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Form deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="null"),
     *             @OA\Property(property="message", type="string", example="You have successfully deleted the patient")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Required parameters are missing!")
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
});
