<?php
session_start();
require_once('config.php');
require_once('functions.php');
//DBに接続　
//var_dump($dsn,$username,$password);
	//var_dump($_SESSION['groupName']);
	$groupId = $_SESSION['groupId'];
	try{
		$dbh = new PDO($dsn,$username,$password);
		//print('接続に成功しました');
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}
	$group_id = $_SESSION['groupId'];

/* 	$check_sql= "SELECT seq FROM tasks";
	$check_sql = $dbh->query($check_sql)->fetchColumn();

	if(!isset($check_sql)){
		//$check_sqlの中身が空だった場合
		$sql = "insert into tasks
				(seq,title,group_id)
				values
				(0,:title,:group_id)";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array(
			":title" => $_POST['title'],
			":group_id" => $groupId
		));
	}else{ */
	
	$sql  = "select max(seq) +1 from tasks where type!= 'deleted'";
	$seq = $dbh->query($sql)->fetchColumn();
	
	$sql = "insert into tasks
			(seq,title,created,modified,group_id)
			values
			(:seq,:title,now(),now(),:groupId)";
	
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(
		":seq" => $seq,
		":title" => $_POST['title'],
		":groupId" => $group_id
	));
	
	echo $dbh->lastInsertId();
	//}
	
	
	