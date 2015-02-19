<?php
session_start();
require("functions.php");
$taskId = $_SESSION['taskId'];
$text = $_POST['text'];
var_dump($taskId);

$db = new db; 
$dbh = $db->connectDb();

$sql = "INSERT INTO memo (
        textId,
        comment
    ) VALUES (
        '$taskId',
        '$text'
    ) ON DUPLICATE KEY UPDATE
        comment = '$text' ,
       textId = '$taskId';";

$stmt = $dbh->prepare($sql);
$stmt -> execute();

?>