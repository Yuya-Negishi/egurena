<?php
session_start();
require('functions.php');


$db = new db;
$dbh = $db->connectDb();
$subtaskTitle = $_POST['subtaskTitle'];
$taskId = $_SESSION['taskId'];

$sql = "select max(seq)+1 from subtask";
$result = $dbh->query($sql)->fetchColumn();

//var_dump($result);

$sql1 = "insert into subtask (seq,subtaskTitle,tasksid) values (:result,:subtaskTitle,:taskId)";
$stmt = $dbh->prepare($sql1);
$stmt ->execute(array(
	':result' => $result,
	':subtaskTitle' => $subtaskTitle,
	':taskId' => $taskId
	));

echo $dbh->lastInsertId();


?>