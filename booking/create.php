<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../object/booking.php';

$database = new Database();
$db = $database->getConnection();

$booking = new Booking($db);

// get json posted data
$data = json_decode(file_get_contents("php://input"), true);

if(
    !empty($data['job']) &&
    !empty($data['booked_date']) &&
    !empty($data['slot']) &&
    !empty($data['user'])
// default will be set for id, booked_on, canceled, job_served
    ) {
        $booking->job = $data['job'];
        $booking->booked_date = $data['booked_date'];
        $booking->slot = $data['slot'];
        $booking->user = $data['user'];
        // default values except for `id` will be set by database
        // $booking->booked_on = date('Y-m-d H:i:s');
        // $booking->cancelled = 0; // 0 - NO, 1 - YES
        // $booking->job_served = 0; // 0 - NO, 1 - YES

        if($booking->create()) {
            // set response code - 201 created
            http_response_code(201);

            // tell the user
            echo json_encode(array("status" => true, "message" => "Booking slot created."));
        } else {
            // set response code - 503 service unavailable
            http_response_code(503);

            // tell the user
            echo json_encode(array("status" => false, "message" => "Unable to create booking slot."));
        }
    } else {
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("status" => false, "message" => "Unable to create booking slot."));
    }
?>