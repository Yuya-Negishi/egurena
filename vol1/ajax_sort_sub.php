<?php 
require('functions.php');

$db = new db;
$dbh = $db->connectDb();

parse_str($_POST['sub']);
//var_dump($_POST['sub']);
var_dump($_POST['sub']);

foreach($sub as $key => $val){
	$sql = "update subtask set seq = :seq where id=:id";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(
			":seq" => $key,
			":id" => $val
		));
}

?>