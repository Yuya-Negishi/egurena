<?php 
require_once('config.php');
function h($s){
	return htmlspecialchars($s,ENT_QUOTES,"UTF-8");
}


class db{

	//facebookuserのidを取得する
	function facebookId($user){
		$dbh = $this->connectDb();
		$sql = "select * from users where facebookId = '$user'";
		$stmt = $dbh->query($sql);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		var_dump($result['id']);
		return $result['id'];
	} 


	function connectDb(){	
		try{	
			
			$dsn = "mysql:host=localhost;dbname=dotinstall_todo_app";
			$username = "root";
			$password = "";
			$dbh = new PDO($dsn,$username,$password);
			//print('接続に成功しました');
			return $dbh;
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
	}
	
	//ログインをした人のidを取得
	/*function getUserid(){
		$dbh = $this->connectDb();
		$sql = "SELECT * FROM users where username='{$_SESSION['username']}' and password='{$_SESSION['password']}' limit 1;";
		$stmt = $dbh->query($sql);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		//print_r($result);		
		return ($result['id']);
	}*/

	//ログインした人のグループ表示
	function select_usersid(){
		$user = $_SESSION['user'];
		//print_r($user);

	//PDOオブジェクト以外のやりかた=手続き型
	 $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
	 mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
	 
	$sql = "select * from users where facebookId = '$user'";
	
	$result = mysqli_query($conn,$sql) or exit ("データの抽出に失敗しました");
	
	if(mysqli_num_rows($result)!= 0){
		foreach($result as $user){
			 $userid = $user['id'];
				 //var_dump($userid);
				 $_SESSION['userid'] = $userid;
				 return $userid;
				 return $_SESSION['userid'];
			}
		}
	}

	function facebookInf(){
		$user = $_SESSION['user'];
		//print_r($user);

	//PDOオブジェクト以外のやりかた=手続き型
	 $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
	 mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
	 
	$sql = "select * from users where facebookId = '$user'";
	
	$result = mysqli_query($conn,$sql) or exit ("データの抽出に失敗しました");
	
	if(mysqli_num_rows($result)!= 0){
		foreach($result as $info){
				return $info;
			}
		}
	}

		
	function selectQuery(){
  	  $dbh = $this->connectDb(); 
		$tasks = array();
		$sql ="select * from tasks where type != 'delete' order by seq";
		foreach ($dbh->query($sql) as $row) {
			array_push($tasks,$row);
		}
		//var_dump($tasks);
		return $tasks;
	}
	/*
	function select_task_team(){
  	  $dbh = $this->connectDb(); 
		$tasks = array();
		$sql ="select distinct task_team from tasks WHERE task_team IS NOT NULL order by seq";
		foreach ($dbh->query($sql) as $row) {
			array_push($tasks,$row);
		}
		//var_dump($tasks);
		return $tasks;
	}
	*/
	
	/*public static function Select_task_team($a){
		foreach ($a as $task){
			echo $task['task_team'].'<br />' 
		}
	}*/
	
	
	function select_task_group(){
	$userid = $this->select_usersid();
  	  $dbh = $this->connectDb(); 
		$groups = array();

		$sql ="select * from task_group where user = '$userid' order by seq";
		//$sql ="select * from task_group  order by seq";

		foreach ($dbh->query($sql) as $row) {
			array_push($groups,$row);
		}
		//var_dump($tasks);
		return $groups;
	}
	
	/*function teamSignUp($teamId,$teamPass,$teamTitle){
		$conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
		mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
		//echo "MySQLへ接続できました。<br>";
		
		$sql = "select * from teams where teamname='$teamId' and teampass='$teamPass'";
		$rs = mysqli_query($conn,$sql);
		$rows = mysqli_num_rows($rs);
		if($rows !== 0){
			echo '異なるidまたはpassで登録してください';
		}else{
			$sql = "insert into teams (teamname,teampass,teamTitle) values ('$teamId','$teamPass','$teamTitle')";
			$result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");
			echo '登録完了';
		}
	}*/




	function getId_teams(){
		$dbh = $this->connectDb();
		$sql = "SELECT max(id) FROM teams";
		foreach($dbh->query($sql) as $result){
			//print_r($result);
		}
		return($result);
	}

	/*:function signup_userteams($userId,$nowId){
		$dbh = $this->connectDb();
		$sql = "inset into userteams (userId,teamId) value (:userId,:nowId)";

		$stmt = $dbh->prepare($sql);
		$stmt -> execute(array(
			":userId" => $userId,
			":nowId" => $teamId
			));
	}*/

	//team list
	//get the teamid ｏｆ　usersteam

	/*function getUserTeam(){
		$dbh = $this->connectDb();
		$userId=$_SESSION['userid'];
		$sql = "select * from userteams where userId = '$userId';";
		$hoge = array();
		foreach($dbh->query($sql) as $row){
			array_push($hoge , $row);
			//print_r($hoge);
		}
		$getUserId  = array();
		foreach ($hoge as $row){
			///var_dump($row['teamId']);
			$getUserId[]=$row['teamId'];
		}
		return $getUserId;
		//echo $getUserId;
	}*/
	/*
	//team signup
		$dbh = $this->connectDb();
		$userid=$_SESSION[''];
		$sql = "insert into userteams (userId) values ($userid)";
		$stmt = $dbh->prepare($sql);
		$stmt -> execute();
	}*/

	//View the registered team	
	function showTeamTitle($userId){
		$dbh=$this->connectDb();
		$sql = "SELECT * FROM  userteams WHERE userId = $userId";
		$Teams = array();
		foreach($dbh->query($sql) as $row){
			array_push($Teams,$row);
		}
		return($Teams);
	}

		/*$getTeams = array();
		foreach ($dbh->prepare($sql) as $row){
			array_push($getTeams,$row);
			return($getTeams);
		}
	}*/

	function addTeam(){
		$conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
		mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
		$sql = "select * from teams where teamname= '$teamid' and teampass= '$teampass'";
		$result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");
		foreach($result as $team){
			//echo $team['id'];
			$teamteam = $team['id'];
		}
	}

	//team list
	//addteam
	function addnewTeam($teamid,$teampass,$userId){
		$conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
		mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
		$sql = "select * from teams where teamname= '$teamid' and teampass= '$teampass'";
		$result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");
		$statuses = array();
		foreach($result as $row){
			array_push($statuses,$row);
		}
		foreach($statuses as $status){
			 $teamStatusId = $status['id'];
		}

		$sql = "select * from userteams where userId=$userId and teamId = $teamStatusId";
		$result1 = mysqli_query($conn,$sql) or exit ("データの抽出に失敗しました。");

		if(mysqli_num_rows($result1)==0){
			echo 'チームに追加できました'.'<br />';
			foreach($result as $team){
				//echo $team['id'];
				$teamteam = $team['id'];
			}
		$sql2 = "insert into userteams (userId,teamId) values ($userId,$teamteam)";
		$result2 = mysqli_query($conn,$sql2) or exit ("データの登録に失敗しました");
			//var_dump($teamteam);
			//return($teamteam); 
		}else{
			echo 'すでに登録されています';
		}
	}

	function connDb(){
		//echo 'ok';
		$conn =mysqli_connect('localhost','root','') or exit('MYSQLへ接続できません');
		mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違えています");
		return ($conn);
	}
	
	function selectFollow($teamId){
		$conn = $this->connDb();
		$sql = "select * from  followteam where team = '$teamId'";
		$result = mysqli_query($conn,$sql);
		//$groupsId = array();
		foreach($result as $row){
			$group = array();
			$groupId[] = $row['task_group'];	
		}
		//print_r($groupId);
		if(isset($groupId)){
			$sql = "SELECT * FROM  task_group WHERE id IN (".implode(",",$groupId).");";
			$result = mysqli_query($conn,$sql);
			foreach ($result as $hoge) :?>	
				
				<?php $taskId = $hoge['id']; ?>
				<li class="groupClick" data-id ="<?php echo $taskId; ?>">
				<?php echo $hoge['group_name'].'<br />'; ?>
				</li>
			<?php endforeach ;?>
			<?php 
		} 
	}
	 		

	
	function showFollowTeam($groupId){
		$conn = $this->connDb();
		$sql = "select * from task_group where id = '$groupId'";
		$query= mysqli_query($conn,$sql);
		$groupsName = array();
		foreach($query as $row){
			array_push($groupsName,$row);
		}
		
		// $groups = array();
			// foreach ($query as $groupsId){
				//echo $groupsId['id'];
			// }
		// return $groupsId;
	}
		
		
	function teamShow($teamId){
		$dbh = $this->connectDb();
		$sql = "select * from task_group where teamid = '$teamId'" ;
		$team = array();
		foreach ($dbh->query($sql) as $row){
			array_push($team,$row);
		}
		return $team;
	}
	function groupId($groupid){
		$dbh = $this->connectDb();
		$sql3 = "select * from tasks where group_id = '$groupid'" ;
		$groupTask = array();
		foreach ($dbh->query($sql3) as $row){
			array_push($groupTask,$row);
		}
		return $groupTask;
	}
	
	function selectTeam($teamName,$teamPass){
		//var_dump($teamName,$teamPass);
		 $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
		 mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
		$sql5 = "SELECT * FROM teams WHERE teamname='$teamName' AND teampass='$teamPass'";
		$result = mysqli_query($conn,$sql5) or exit ("データの抽出に失敗しました");
		
		if(mysqli_num_rows($result)=='1'){
			foreach($result as $selectTeams){
				$selectTeam = $selectTeams['id'];
			}
			//echo $selectTeam ;
			return $selectTeam;
			}
		}

	function followTeam($teamId,$groupId){
		$conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
		mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
		$sql = "SELECT * FROM followteam WHERE task_group = $groupId AND team = $teamId";
		$result = mysqli_query($conn,$sql);
		if($result){
			if (mysqli_num_rows($result) == '1'){
				$teamText = 'すでに登録されています' ;
				return $teamText;
			}else{
			$sql1 = "INSERT INTO followteam (team,task_group) values ($teamId,$groupId)";
			$result = mysqli_query($conn,$sql1);
				$teamText= '登録完了しました';
				return $teamText;
			}
		}else{
			$teamText = '失敗です';
				return $teamText;
		}
	}

	function showTeam($teamId_userteams){
		$dbh = $this->connectDb();
		$sql = "select * from teams where id IN ($teamId_userteams)";
		$stmt = $dbh->query($sql);
		//$result = $stmt -> fetchColumn();
		if($stmt =='0'){
		//if($result == 0){
			echo 'まだ登録チームが追加されていません';
		}else{
			$hoge = array();
			foreach($dbh->query($sql) as $row){
				array_push($hoge,$row);
			}
			$teamName = array();
			foreach($hoge as $teamName) : ?>
			<div id="teamTitle">
				<p id ="<?php echo $teamName['id'] ; ?>">
				<?php echo $teamName['teamTitle'] .'<br/ >' ; ?>
				</p>
			</div>
			<?php  endforeach; 
		}
	}
}


