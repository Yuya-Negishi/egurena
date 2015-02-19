<?php
session_start();
require_once('functions.php');
?>
<br>




<?php
$db = new db;
$dbh = $db->connectDb();
$id = $_POST['id'];
$_SESSION['groupId'] = $_POST['id'];
//print_r($_SESSION['groupId']);

//$sql = "select * from tasks where　type != 'delete' and group_id = $id";
@$sql ="select * from tasks where type != 'delete' and group_id='$id' order by seq";
$tasks = array();
foreach($dbh->query($sql) as $row){
	array_push($tasks,$row);
}
?>


<?php foreach ($tasks as $task) : ?>

	<li id = "task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>" data-task="<?php echo $task['title']; ?>">
		<input type="checkbox" name="check" class="checkTask" <?php if($task['type'] =="done"){ echo "checked"; }?>>
		<span class="<?php echo h($task['type']); ?>"><a class="tasklist"><?php echo h($task['title']); ?></a></span>
		<span <?php if($task['type'] =='notyet'){ echo 'class="editTask"'; 
			 }elseif($task['type']=='done'){echo 'class="edit"'; }?>> 
		<i class="fa fa-pencil"></i></span>
		<span class="deleteTask"><i class="fa fa-trash"></i></span>
		<span class="drag"><i class="fa fa-bars"></i></span>
	</li>
<?php endforeach; ?>



