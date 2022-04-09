<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json, charset=UTF-8");

// inlude database and booking object files
include_once '../config/database.php';
include_once '../object/booking.php';

// instantiate database and booking object
$database = new Database();
$db = $database->getConnection();

// intialize booking object
$booking = new Booking($db);
$today_bookings = $booking->count();

// set response code - 200 OK
//http_response_code(200);
  
// tell the user no products found
echo json_encode(
    array("message" => $today_bookings)
);
?>