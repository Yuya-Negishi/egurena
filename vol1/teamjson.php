<?php
session_start();
require_once('config.php');

try{
		$dbh = new PDO($dsn,$username,$password);
		
	}catch (PDOException $e){
		print('Connection failed:'.$e->getMessage());
		die();
	}

	$id = $_POST['id'];

	$sql = "select * from tasks where group_id='$id'";

	//$stmt = $dbh->query($sql);
	// $stmt->execute();
	// while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
	// 	$hoge = ($result['title']).'<br />';
	// }

	
	$hoge = array();
	foreach($dbh->query($sql) as $row ){
		array_push($hoge,$row);
	}

	$tasks = array();
	foreach($hoge as $num): ?>
		<p><input type="checkbox" <?php if($num['type']==='done'):?> checked <?php endif; ?>><?php echo $num['title'] ; ?></p>
		<?php 
		
		$tasks['type'] = $num['type'];
		endforeach ;
	//echo($tasks['title']);
	
	// $rs = array(
	// 	"title" => $tasks['title']
	// 	);
	/*foreach($hoge as $tasks){
		$tasks = $hoge['title'];
	}

print_r($tasks);*/


//var_dump($_POST['id']);
//print_r($tasks);
//header('Content-type:application/jsoon; charset=utf-8');
//echo json_encode($tasks);
// echo Json_encode($rs);
// header('Content-type:json');
// echo json_encode($tasks);
?>