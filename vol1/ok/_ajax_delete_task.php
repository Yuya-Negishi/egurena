<?php 
require_once('config.php');
require_once('functions.php');
//DBに接続　
//var_dump($dsn,$username,$password);
	try{
		$dbh = new PDO($dsn,$username,$password);
		//print('接続に成功しました');
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}

	echo ($_POST['id']);
	$sql = "update tasks set type ='delete' , modified = now() where id = :id";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":id" =>(int)$_POST['id']));
	