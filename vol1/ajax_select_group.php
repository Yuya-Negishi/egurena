<?php
session_start();
require_once('functions.php');
?>

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

<table class="table table-hover" id="tableTasks">
<tbody id="tbodyTask">
<?php foreach ($tasks as $task) : ?>

	<tr id = "task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>" data-task="<?php echo $task['title']; ?>">
	<td>
		<span style="font-size:150%" class="stopWatch" id="sW_<?php echo h($task['id']); ?>">0:00</span>
	</td>
	<td>
		<input type="checkbox" name="check" class="checkTask" <?php if($task['type'] =="done"){ echo "checked"; }?>>
		<span class="<?php echo h($task['type']); ?>"><a class="tasklist"><?php echo h($task['title']); ?></a></span>
		<span <?php if($task['type'] =='notyet'){ echo 'class="editTask"'; 
			 }elseif($task['type']=='done'){echo 'class="edit"'; }?>> 
		<i class="fa fa-pencil"></i></span>
		<span class="deleteTask"><i class="fa fa-trash"></i></span>
	</td>
	<td>
		<button type="button" class="btn btn-default runButton" id="run_<?php echo h($task['id']); ?>">start</button>
		<!--<span class="drag"><i class="fa fa-bars"></i></span>-->
		<button type="button" class="btn btn-default stopButton" id="stop_<?php echo h($task['id']); ?>">stop</button>
		<button type="button" class="btn btn-default resetButton" id="reset_<?php echo h($task['id']); ?>">reset</button>
	</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>


