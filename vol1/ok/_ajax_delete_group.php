<?php 
session_start();
require_once('config.php');
//require_once('functions123.php');
//DBに接続　
//var_dump($dsn,$username,$password);
	$groupId = $_SESSION['groupId'];
	var_dump($groupId);
	try{
		$dbh = new PDO($dsn,$username,$password);
		//print('接続に成功しました');
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}
	
	$sql = "delete from task_group where id = :id and group_name = :group";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(
	":id" =>(int)$_POST['id'],
	":group" => $_POST['group']
	));
	
	$sql1 = "delete from tasks where group_id = :group_id";
	$stmt1 = $dbh->prepare($sql1);
	$stmt1 ->execute(array(
		":group_id" => $groupId
	));