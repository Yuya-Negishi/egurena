<?php
session_start();
require('functions.php');

$db = new db;
$dbh = $db->connectDb();

$sql = "update subtask set type = if(type='done','notyet','done') where id = :id";
$stmt = $dbh->prepare($sql);
$stmt -> execute(array(
			':id' => $_POST['id']
		));	

?>