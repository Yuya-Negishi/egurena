<?php
session_start();
require_once('config.php');
//require_once('functions.php');
//DBに接続　
//var_dump($dsn,$username,$password);
	try{
		$dbh = new PDO($dsn,$username,$password);
		
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}
	$check_sql = "SELECT seq FROM task_group";
	$check_sql = $dbh->query($check_sql)->fetchColumn();
	
	/* if(empty($check_sql=='null')) {
	    //$check_sqlの中身が空だった場合
		$sql = "insert  into task_group
				(seq,group_name)
				values
				(0,:group)";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array(
				":group" => $_POST['group']
				));
	} else { */
	$sql  = "select max(seq) +1 from task_group";
	$seq = $dbh->query($sql)->fetchColumn();
	//var_dump($seq);
	
	$sql = "insert into task_group
			(seq,group_name,user)
			values
			(:seq,:group,:user)";
	
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(
		":seq" => $seq,
		":group" => $_POST['group'],
		":user" => $_SESSION['userid']
	));
	echo $dbh->lastInsertId();
	
	//}
	
		