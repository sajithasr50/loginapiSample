<?php
function sendMessage($type, $message, $data)
{
    if ($type == 'error') {
        http_response_code(404);
    }
    $arrayMsg = ['type'=>$type,'message'=>$message,'data'=>$data];
    $response =  json_encode($arrayMsg);
    echo $response;
    die();
}
$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';

define("BASEURL", $base_url);
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "user_db");
define("DB_TABLE_NAME", "user");
define("JWT_KEY", "68V0zWFrS72GbpPreidkQFLfj4v9m3Ti+DXc8OB0gcM=");
define("JWT_ALG", "HS256");


function passwordCheck($pass)
{
    $errors = array();
    if (strlen($pass) < 8 || strlen($pass) > 16) {
        $errors[] = "Password should be min 8 characters and max 16 characters";
    }
    if (!preg_match("/\d/", $pass)) {
        $errors[] = "Password should contain at least one digit";
    }
    if (!preg_match("/[A-Za-z]/", $pass)) {
        $errors[] = "Password should contain at least one  Letter";
    }
    if (!preg_match("/\W/", $pass)) {
        $errors[] = "Password should contain at least one special character";
    }
    if (preg_match("/\s/", $pass)) {
        $errors[] = "Password should not contain any white space";
    }

    if (empty($errors)) {
        return true;
    }
    return false;
}

function dobCheck($dob)
{
    $explodedob = explode('-', $dob);

    // get the users Date of Birth
    $BirthDay   = isset($explodedob[0])?$explodedob[0]:0;
    $BirthMonth = isset($explodedob[1])?$explodedob[1]:0;
    $BirthYear  = isset($explodedob[2])?$explodedob[2]:0;
    if (!checkdate($BirthMonth, $BirthDay, $BirthYear)) {
        return false;
    }
    //convert the users DoB into UNIX timestamp
    $stampBirth = mktime(0, 0, 0, $BirthMonth, $BirthDay, $BirthYear);

    // fetch the current date (minus 18 years)
    $today['day']   = date('d');
    $today['month'] = date('m');
    $today['year']  = date('Y') - 18;

    // generate todays timestamp
    $stampToday = mktime(0, 0, 0, $today['month'], $today['day'], $today['year']);

    if ($stampBirth < $stampToday) {
        return true;
    } else {
        return false;
    }
}
