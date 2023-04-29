<?php

require_once("config.php");
require_once("DbQuery.php");

header("Access-Control-Allow-Origin: http://localhost/loginapi/");

$dbQueryObj = new DbQuery();
$data = json_decode(file_get_contents("php://input"));
$name = $data->name;
$email = $data->email;
$password = $data->password;
$dob = $data->dob;
$phone = $data->phone;
if (empty(trim($name))) {
    sendMessage("error", "Error Found", "Please Add Valid Name");
}

if (!ctype_alpha($name)) {
    sendMessage("error", "Error Found", "Name Should contain only characters");
}

if (empty(trim($email))) {
    sendMessage("error", "Error Found", "Please Add Valid EmailId");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendMessage("error", "Error Found", "Please Add Valid EmailId");
}
$dataIns = [
"email" => $email
];

$result = $dbQueryObj->checkEmail($dataIns);

if (isset($result[0]) && count($result[0]) > 0) {
    sendMessage("error", "Error Found", "Email Id already Exist");
}

if (empty(trim($password))) {
    sendMessage("error", "Error Found", "Please Add Valid Password (Minimum 8 character with special characters and number and letters)");
}

if (!passwordCheck($password)) {
    sendMessage("error", "Error Found", "Please Add Valid Password (Minimum 8 character with special characters and number and letters)");
}

if (empty(trim($phone))) {
    sendMessage("error", "Error Found", "Please Add Valid Phone)");
}

if (!is_numeric($phone)) {
    sendMessage("error", "Error Found", "Phone Should contain only numbers");
}

if (strlen($phone) < 10 || strlen($phone) > 11) {
    sendMessage("error", "Error Found", "Phone Should contain minimum 10 digits and maximum 11 digits");
}

if (!empty($dob) && !dobCheck($dob)) {
    sendMessage("error", "Error Found", "User must be minimum 18 years old");
}

$dataIns = [
"name" => $name,
"email" => $email,
"password" => $password,
"phone" => $phone,
"dob" => $dob,
];
$result = $dbQueryObj->insert($dataIns);
if ($result) {
    http_response_code(200);
    sendMessage("success", "Registration Successfull", "");
} else {
    http_response_code(404);
    sendMessage("error", "Something Went Wrong", "");
}
