<?php
header('Content-Type: applicati on/json');
include "./connect.php";
include_once "./token.php";

$entityBody = file_get_contents('php://input');
$phpData = json_decode($entityBody);

$token = getallheaders();
// בדוק בכלל אם נשלח טוקן
if (!isset($token["auth-token"])) {
    die("{'err':'you must send token'}");
}

// לבדוק אם הטוקן תקין או תקף
$checkToken = Token::validateToken($token["auth-token"]);
echo $checkToken;
if (!$checkToken) {
    die("{'err':'token not valid or expired'}");
} else {
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            $query = "INSERT INTO shop (name, info, category, img_url, price, user_id) VALUES (?,?,?,?,?,?)";
            $stmnt = $conn->prepare($query);
            $stmnt->bind_param("ssssii", $phpData->name, $phpData->info, $phpData->category, $phpData->img_url,$phpData->price, $checkToken);
            $stmnt->execute();
            $res = $stmnt->insert_id;
            // $query = "INSERT INTO shop (name, info, category, img_url, price, user_id) VALUES ('{$phpData->name}','{$phpData->info}','{$phpData->category}','{$phpData->img_url}', '{$phpData->price}', $checkToken)";
            // $conn->query($query);
            // echo "{id:" . $conn->insert_id . "}";
            echo "{\"id\":{$res}}";
            break;
        case "PUT":
            if(!isset($_GET['id'])){
                echo  "{\"msg\":\"You need to send id as query string\"}";
                break;
              }
            $editId = (int)$_GET["id"];
            // $query = "UPDATE shop SET name = '{$phpData->name}', info = '{$phpData->info}' , category = '{$phpData->category}' , img_url = '{$phpData->img_url}' , price = {$phpData->price} WHERE id = {$editId}";
            $query = "UPDATE shop SET name=? , info=? , category=?, img_url=?, price=? WHERE id = ? AND user_id = ?  ";
            $stmnt = $conn->prepare($query);
            $stmnt->bind_param("ssssiii", $phpData->name, $phpData->info, $phpData->category, $phpData->img_url,$phpData->price,$editId, $checkToken);
            $stmnt->execute();
            $res = $stmnt->affected_rows;
            echo "{\"n\":{$res}}";
            // $conn->query($query);
            // echo "{n:" . $conn->affected_rows . "}";
            break;
        case "DELETE":
            if(!isset($_GET['id'])){
                echo  "{\"msg\":\"You need to send id as query string\"}";
                break;
              }
            $delId = (int)$_GET["id"];
            $query = "DELETE FROM shop WHERE id = '{$delId}' AND user_id = '{$checkToken}'";
            $stmnt = $conn->prepare($query);
            $stmnt->execute();
            $res = $stmnt->affected_rows;
            echo "{\"n\":{$res}}";
            // $conn->query($query);
            // echo "{n:" . $conn->affected_rows . "}";
            break;
        default:
        echo "{\"msg\":\"You need to send body! and post,put,delete request\"}";
        break;
    }
}