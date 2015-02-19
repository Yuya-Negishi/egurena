<?php
require('functions.php');
session_start();
//$taskId = $_SESSION['taskId'];
$taskId = $_POST['id'];


$db = new db ; 
$dbh = $db->connectDb();
$sql = "select * from memo where textId = '$taskId'";
$memos = array();
foreach($dbh->query($sql) as $row){
	array_push($memos,$row);
}
foreach($memos as $hoge){
	$memo = $hoge['comment'];
}
if(isset($memo)){
	echo $memo;
}else{
	$memo = '' ;
	echo $memo;
}

?>
