<?php
 header("Access-Control-Allow-Origin: *");
 header("Content-Type: application/json, charset=UTF-8");
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../object/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
/* $user->name = $_POST['name'];
$user->address = $_POST['address'];
$user->email = $_POST['email'];
$user->phone = $_POST['phone'];
$user->password = base64_encode($_POST['password']); */

$data = json_decode(file_get_contents("php://input"), true);
$res_code = 200;

if(
    !empty($data['name']) &&
    !empty($data['address']) &&
    !empty($data['email']) &&
    !empty($data['password'])
 ){
    $user->name = $data['name'];
    $user->address = $data['address'];
    $user->email = $data['email'];
    $user->password = base64_encode($data['password']);

    // create the user
    $ret = $user->signup();
    if($ret == 3){
        $user_arr = array(
            "status" => true,
            "message" => "Successfully Signup!",
            "id" => $user->id,
            "username" => $user->name
        );
    } elseif($ret==1) {
        $res_code = 409;
        $user_arr = array(
            "status" => false,
            "message" => "User already exists!"
        );
    } elseif($ret == 2){
        $res_code = 503;
        $user_arr = array(
            "status" => false,
            "message" => "Invalid Inputs!"
        );
    }
 }
 

// http_response_code($res_code);

echo json_encode($user_arr);

?>
