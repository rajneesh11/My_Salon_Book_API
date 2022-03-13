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

// set booking id to be deleted
$booking->id = $data['id'];

if($booking->delete()) {
    // set response code - 200 OK
    http_response_code(200);

    // tell the user
    echo json_encode(array("status" => true, "message" => "Booking slot deleted."));
} else {
    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("status" => false, "message" => "Unable to delete booking slot."));
}
?>