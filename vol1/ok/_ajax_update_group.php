<?php
session_start();
require_once('config.php');
//require_once('functions.php');
//DBに接続　
//var_dump($dsn,$username,$password);
	try{
		$dbh = new PDO($dsn,$username,$password);
		//print('接続に成功しました');
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}
	
	//$groupName=$_SESSION['groupName'];
	//$groupId = $_SESSION['groupId'];
	
	
	// var_dump($_POST['id']);
	 //var_dump($_POST['group']);
	$sql = "update task_group set group_name= :group  where id = :id";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(
		":id" =>(int)$_POST['id'],
		":group" => $_POST['group']
	));
	
	/* $sql2 = "update tasks set groupName = :groupName where group_id = :groupId";
	$stmt1 = $dbh->perpare($sql2);
	$stmt1->execute(array(
		":groupName" => $groupName,
		":groupId" => $groupId
	)); */
	
	
	
	
	
	
	
