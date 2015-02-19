<?php 
session_start();
require('functions.php');

$taskId = $_POST['id'];
$_SESSION['taskId'] = $taskId;

$db = new db ; 
$dbh = $db->connectDb();

$sql = "select * from subtask where tasksid = '$taskId' and type != 'delete' order by seq ;";
$hoge = [];
foreach($dbh->query($sql) as $row){
	array_push($hoge,$row);
}
?>
<!--<ul id="subtasks">-->
<?php foreach($hoge as $subtask): ?>
	
		<li id ="sub_<?php echo $subtask['id']; ?>" data-id="<?php echo $subtask['id']; ?>">
			<input type="checkbox" <?php if($subtask['type'] == 'done'){ echo 'checked';  } ?> class="checkedSub">
			<span class="<?php echo $subtask['type']; ?>"><?php echo $subtask['subtaskTitle']; ?> </span>
			<span class="editSub"><i class="fa fa-pencil"></i></span>
			<span class="deleteSub"><i class="fa fa-trash"></i></span>
			<span class="dragSub"><i class="fa fa-bars"></i></span>
		</li>
<?php endforeach; ?>
<!--</ul>-->