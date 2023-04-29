<?php
require_once("config.php");

/**
 * Mysql Query
 */
class DbQuery
{
    public function __construct()
    {
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$this->conn) {
            die('Connection Closed');
        }
        $this->tableName = 'user';
    }

    public function insert($ins)
    {
        $name = $ins['name'];
        $email = $ins['email'];
        $password  = md5($ins['password']);
        $dob  = $ins['dob'];
        $phone  = $ins['phone'];
        
        $execute = mysqli_query($this->conn, "INSERT INTO $this->tableName(name,email,password,dob,phone) VALUES ('".$name."','".$email."','".$password."','".$dob."','".$phone."')");

        return $execute;
    }

    public function updateData($ins)
    {
        $name = $ins['name'];
        $dob  = $ins['dob'];
        $phone  = $ins['phone'];
        $id  = $ins['id'];
        $execute = mysqli_query($this->conn, "UPDATE $this->tableName SET name = '".$name."',dob = '".$dob."',phone = '".$phone."' WHERE id = '".$id."' ");

        return $execute;
    }

    public function getData($ins)
    {
        $email = $ins['email'];
        $password = md5($ins['password']);
        $query = mysqli_query($this->conn, "select * from ".$this->tableName." WHERE email = '".$email."' AND password = '".$password."' ");
        $fetch_array = mysqli_fetch_all($query, MYSQLI_ASSOC);
        return $fetch_array;
    }

    public function checkEmail($ins)
    {
        $email = $ins['email'];
        $query = mysqli_query($this->conn, "select * from ".$this->tableName." WHERE email = '".$email."'");
        $fetch_array = mysqli_fetch_all($query, MYSQLI_ASSOC);
        return $fetch_array;
    }
}
