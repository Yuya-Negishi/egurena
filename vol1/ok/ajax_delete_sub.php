<?php
session_start();
require('functions.php');

$taskId = $_SESSION['taskId'];

$db = new db;
$dbh = $db->connectDb();

$sql = "update subtask SET type = 'delete' where tasksid = :taskId and id = :id ;";
$stmt = $dbh->prepare($sql);
$stmt -> execute(array(
		":taskId" => $taskId,
		":id" => $_POST['id']
	));
?>