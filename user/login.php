<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../object/user.php';
 

// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);

// set ID property of user to be edited
// echo "OK";//$_POST['email'];
// $user->name = isset($_POST['email']) ? $_POST['email'] : die();
// $user->password = base64_encode(isset($_POST['password']) ? $_POST['password'] : die());  

$data = json_decode(file_get_contents("php://input"), true);
$res_code = 200;

// echo "OK: ".$data['email'];

if(!empty($data['email']) && !empty($data['password'])) {
    $user->email = $data['email'];
    $user->password = base64_encode($data['password']);

    $stmt = $user->login();
    $user_arr = array();
    if($stmt->rowCount() > 0){
        // get retrieved rowname
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // create array
        $user_arr = array(
            "status" => true,
            "message" => "Successfully Login!",
            "id" => $row['id'],
            "username" => $row['name']
        );
    } else{
        $res_code = 404;
        $user_arr = array(
            "status" => false,
            "message" => "User not found!",
        );
    }
} else{
    $res_code = 449;
    $user_arr = array(
        "status" => false,
        "message" => "Invalid Email or Password!",
    );
}

// set response code - 200 OK
// http_response_code($res_code);
// make it json format
echo json_encode($user_arr);
?>