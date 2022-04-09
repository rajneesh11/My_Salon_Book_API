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

// filter bookings fetch based on user or owner
// $filter = isset($_POST['user']) ? $_POST['user'] : "";
// echo $filter;
$data = json_decode(file_get_contents("php://input"), true);
// print_r($data);
if(!empty($data['bookings_data'])) {
    if($data['bookings_data'] == "today") {
        $stmt = $booking->read($data['user']);
    } else{
        $stmt = $booking->read_old($data['user']);
    }
}
// query bookings
// $stmt = $booking->read($filter);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num > 0) {
    // bookings array
    $bookings_arr = array();
    $bookings_arr["records"] = array();
    $bookings_arr["status"] = true;

    // fetch table content
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // print_r($row);//."\n";

        // extract row
        // this will make $row['name'] to $name only
        extract($row);
        $booking_item = array(
            "id" => $id,
            "booked_date" => $booked_date,
            "job" => $job,
            "slot" => $slot,
            "booked_on" => $booked_on,
            "user" => $name,
            "cancelled" => $cancelled,
            "job_served" => $job_served
        );

        array_push($bookings_arr["records"], $booking_item);
    }

    // set response code - 200 OK
    // http_response_code(200);

    echo json_encode($bookings_arr);
} else {
    // set response code - 404 Not found
    // http_response_code(404);
  
    // tell the user no products found
    echo json_encode(
        array("status" => false, "message" => "No bookings found.")
    );
}
?>
