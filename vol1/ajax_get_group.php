<?php 
require_once('functions.php');

//followteamのtask_groupの取得
$db = new db;
$dbh=$db->connectDb();
$id=$_POST['id'];
$sql = "select * from followteam where team = $id";
$hoges = array();
foreach($dbh->query($sql) as $row){
	array_push($hoges,$row);
}
//var_dump($hoges);
//$taskGroupId = array();
foreach($hoges as $hoge): ?>
	<?php //$taskGroupId[] = "'".$hoge['task_group']."'";
	$taskGroupId = $hoge['task_group'];
	//var_dump($taskGroupId);

	//gorupのnameを取得
	$sql = "select * from task_group where id = $taskGroupId";
	$stmt = $dbh->query($sql);
	$result = $stmt->fetch(PDO::FETCH_ASSOC); ?>
	<p class="groupName"><?php echo $result['group_name'].'<br />'; ?></p>
	<?php
	//taskを取得
	$taskes=array();
	$sql1 = "select * from tasks where group_id = $taskGroupId ";
	foreach($dbh->query($sql1) as $row){
		array_push($taskes , $row);
	}
	foreach($taskes as $task){
		echo $task['title'].'<br />';
	}
	endforeach; ?>



