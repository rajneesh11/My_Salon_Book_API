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
// data [id, cancelled, job_served]
// $booking->id = $data['id'];
// $booking->cancelled = $data['cancelled'];
// $booking->job_served = $data['job_served'];
$req_completed = false;

if(!empty($data['id']) && !empty($data['cancelled'])) {
    $booking->id = $data['id'];
    $booking->cancelled = $data['cancelled'];
    
    $req_completed = $booking->update_cencel();
    
} elseif(!empty($data['id']) && !empty($data['job_served'])) {
    $booking->id = $data['id'];
    $booking->job_served = $data['job_served'];
    
    $req_completed = $booking->update_job_served();
}

if($req_completed) {
    // set response code - 200 OK
    // http_response_code(200);

    // tell the user
    echo json_encode(array("status" => true, "message" => "Booking slot updated."));
} else {
    // set response code - 503 service unavailable
    // http_response_code(503);

    // tell the user
    echo json_encode(array("status" => false, "message" => "Unable to update booking slot."));
}
?>