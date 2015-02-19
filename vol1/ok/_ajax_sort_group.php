<?php 
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
	//var_dump($dbh);
	
	parse_str($_POST['group']);//$task
	//var_dump($_POST['group']);
	//var_dump($group);
	
	
	
	foreach($group as $key => $val) {
		$sql = "update task_group set seq = :seq where id= :id";
		$stmt = $dbh->prepare($sql);
		$stmt->execute(array(
			":seq" =>$key,
			":id" => $val,
		));
}