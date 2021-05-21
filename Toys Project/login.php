<?php
header('Content-Type: application/json');
include "./connect.php";
include_once "./token.php";

//איסוףפ המידע שנשלח כבאדי
$bodyData = file_get_contents('php://input');

// קידוד המידע שמגיע כגייסון
$phpData = json_decode($bodyData);

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $q = "SELECT * FROM users WHERE email = '{$phpData->email}';";
    $res = $conn->query($q);
    $user = (object)mysqli_fetch_assoc($res);
    if(!isset($user->id)){
        echo "{'err':'User not found'}";
    }else{
        //בדיקת תקינות הסיסמה
        $validPass = password_verify($phpData->pass,$user->pass);
        if(!$validPass){
            echo "{'err':'Password not match'}";
        }else{
            $newToken = Token::genToken($user->id);
            echo "{'token':'{$newToken}'}";
        }
    }
}