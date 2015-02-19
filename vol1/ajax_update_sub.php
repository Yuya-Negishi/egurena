<?php
session_start();
require_once('functions.php');
$db = new db;
$dbh = $db->connectDb();

$sql = "update subtask set subtaskTitle = :sub  where id = :id";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(
		":id" =>(int)$_POST['id'],
		":sub" => $_POST['sub']
	));
?>