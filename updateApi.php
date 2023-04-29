<?php
declare(strict_types=1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once("config.php");
require_once("DbQuery.php");
require "vendor/autoload.php";

header("Access-Control-Allow-Origin: http://localhost/loginapi/");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$dbQueryObj = new DbQuery();

$authHeader = apache_request_headers();
$data = json_decode(file_get_contents("php://input"));
$name = $data->name;
$dob = $data->dob;
$phone = $data->phone;
$jwt = isset($authHeader['Authorization'])?$authHeader['Authorization']:'';
$secret_Key = JWT_KEY;

try {
    if (empty(trim($jwt))) {
        sendMessage("error", "Error Found", "Invalid Token");
    }

    $decoded = JWT::decode($jwt, new Key($secret_Key, JWT_ALG));

    if (isset($name) && empty(trim($name))) {
        sendMessage("error", "Error Found", "Please Add Valid Name");
    }

    if (!empty(trim($name)) && !ctype_alpha($name)) {
        sendMessage("error", "Error Found", "Name Should contain only characters");
    }

    if (isset($phone) && empty(trim($phone))) {
        sendMessage("error", "Error Found", "Please Add Valid Phone)");
    }

    if (!empty(trim($phone)) && !is_numeric($phone)) {
        sendMessage("error", "Error Found", "Phone Should contain only numbers");
    }

    if (isset($phone) && empty($phone)) {
        sendMessage("error", "Error Found", "Phone Should not empty");
    }

    if (!empty($phone)) {
        if (strlen($phone) < 10 || strlen($phone) > 11) {
            sendMessage("error", "Error Found", "Phone Should contain minimum 10 digits and maximum 11 digits");
        }
    }

    if (!empty($dob) && !dobCheck($dob)) {
        sendMessage("error", "Error Found", "User must be minimum 18 years old");
    }

    $dataIns = [
    "name" => $name,
    "phone" => $phone,
    "dob" => $dob,
    "id" => $decoded->data->id,
    ];

    $result = $dbQueryObj->updateData($dataIns);
    if ($result) {
        http_response_code(200);
        sendMessage("success", "Updated Successfully", []);
    }
} catch (\InvalidArgumentException $e) {
    echo $e;
} catch (\Exception $e) {
    echo $e;
}
