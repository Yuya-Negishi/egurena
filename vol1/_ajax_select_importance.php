<?php
require('functions.php');
$db = new db;
$dbh = $db->connectDb();
 $id = $_POST['id'] ;

$sql = "select * from tasks where id = '$id' ";
$stmt = $dbh->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo $result['importance'];


//print_r($hoge);
//print_r($hoge['importance']);


?>