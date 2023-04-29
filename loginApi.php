<?php
declare(strict_types=1);

use Firebase\JWT\JWT;

require_once("config.php");
require_once("DbQuery.php");
require "vendor/autoload.php";

header("Access-Control-Allow-Origin: http://localhost/loginapi/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$dbQueryObj = new DbQuery();
$data = json_decode(file_get_contents("php://input"));
$email = $data->email;
$password = $data->password;

if (empty(trim($email))) {
    sendMessage("error", "Error Found", "Please Add Valid EmailId");
}

if (empty(trim($password))) {
    sendMessage("error", "Error Found", "Please Add Valid Password (Minimum 8 character with special characters and number and letters)");
}


$dataIns = [
"email" => $email,
"password" => $password,
];

$result = $dbQueryObj->getData($dataIns);

if (isset($result[0]) && !empty($result[0])) {
    http_response_code(200);
    $secret_key = JWT_KEY;
    $issuer_claim = "THE_ISSUER"; // this can be the servername
    $audience_claim = "THE_AUDIENCE";
    $issuedat_claim = time(); // issued at
    $notbefore_claim = $issuedat_claim + 10; //not before in seconds
    $expire_claim = $issuedat_claim + 360; // expire time in seconds
    $token = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "nbf" => $notbefore_claim,
        "exp" => $expire_claim,
"data" => array(
            "id" => $result[0]['id'],
            "name" => $result[0]['name'],
            "email" => $email
    )        );
    $jwt = JWT::encode($token, $secret_key, JWT_ALG);
    sendMessage("success", "Login Successfully", ['jwt'=>$jwt,"expire_at"=>$expire_claim]);
} else {
    http_response_code(404);
    sendMessage("error", "Login Failed", "");
}
