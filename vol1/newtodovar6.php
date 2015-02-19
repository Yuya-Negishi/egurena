<?php 
//テスト
//セッションスタート
session_start();
require_once('config.php');//設定ファイル
require_once('functions.php');
//Facebook公式SDK(開発セット)を読み込む
require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';
$facebook = new Facebook(array(
    'appId'  => '796879627040680',
    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42',
));
/*$user = $facebook->getUser();
//ログインされていなかった場合
if(!$user){
	//$facebook->destroySession();
//if(!$_SESSION['user']){
	//header('Location:http://localhost/my_create/egurena2/ok/login.php');
} else {
	 //$logoutUrl = $facebook->taskHead();
	 $params = array( 'next' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'] );
   	 $logoutUrl = $facebook->getLogoutUrl($params);
   	 //ログアウトは一端なし 
}}*/
 $user=$_SESSION['userid'];

 if($user){
 	$params = array( 'next' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'] );
   	 $logoutUrl = $facebook->getLogoutUrl($params);
   	 //var_dump($logoutUrl);
   	 //var_dump($logoutUrl);
 }/*else{
 	header('Location:$logoutUrl');
 	session_destroy();
 	header('Location:http://localhost/my_create/egurena2/ok/login.php');
 }*/
//echo ($_POST['hidlogout']);
 if(isset($_POST['hidlogout'])){
 	session_destroy();
 	header('Location:'.$logoutUrl);
 }
 if(!$user){
 	header('Location:http://localhost/my_create/egurena2/ok/login.php');
 }

//$user = $_SESSION['user'];
//var_dump($_SESSION['user']);
//$userid = $db -> facebookId($user);
//$_SESSION['userid'] = $userid;

$db = new db;
$facebookInf = $db->facebookInf($user);
//var_dump($facebookInf); 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>todoアプリ</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	
	<style>

	/* カーソルと色 */
	.deleteTask,.editTask,.edit_group,.delete_group,#handshake{
		cursor:pointer;
		color:blue;
	}
	.drag,.drag_group{
		cursor: n-resize;
		color:gray;
	}
		
	.done {
		text-decoration: line-through;
		color:gray;
	}
	.done + .edit{
		display:none;
	}
	.done + .editSub{
		display:none;
	}

	a.form_group,a.tasklist{
		color:black;
		cursor:pointer;
		text-decoration: none;

	}
	a.form_group:hover,a.tasklist:hover{
		color:red;
	}
	li{list-style:none;}
	a.tasklist{
		cursor:pointer;
	}
	#subtask{
		display:none;
		z-index: 1;
	}
	#tasks{
		user-select: none;
	}
	#taskHead{
		height:50px;
		font-size:30px;
		color:black;
		border-bottom:solid lightgray thin;
		/*background-color:#2ecc71;*/
	}
	 /*  #overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 99999;
    background-color: #000;
	filter:alpha(opacity=70);
    -moz-opacity: 0.7;
    opacity: 0.7;
	}
	#text{
		 font-size: 40px;
        color: #eee;
        padding-top: 400px;
        vertical-align: middle;
        font-weight: bold;
	} */
	
	a#teamphp{
		color:black;
		cursor:pointer;
	}
	.fa-pencil, .fa-trash{
		color:black;
	}
	.fa-pencil:hover{
		color:blue;
	}
	.fa-trash:hover{
		color:blue;
	}
	.task_input,.task_on2,.task_input2,#subtaskPlus{
		display:none;
	}
	body{
     user-select: none;
     -webkit-user-select: none;
     -moz-user-select: none;
     -ms-user-select: none;
     -o-user-select: none;
     position:;
     right:0px;
     overflow-x:hidden;
     width:100%;
	}
	tr,h2,h3,.fontSerif{
		font-family:"ＭＳ Ｐ明朝","ＭＳ 明朝",serif;
	}
	#subtask{
		/*position:absolute;*/
		top:0;
		right:-200px;
		/*width:300px;*/
		height:100%;
		/*background-color:orange;		*/
	}
	.taskLists{
		text-align:center;
	}
	.container{
		margin:0 auto;
		text-align:center;
	}
	.container,body,html{
		height:100%;
	}
	.project{
		height:100%;
	}
	.taskLists{
		height:100%;
	}
	.row{
		height:100%;
	}
	th:hover{
		background-color:white;
	}
	.task_input{
		width:400px;
		height:100px;
		margin:0;
		padding:10px 20px;
		border:2px solid #aaa;
		background:#fff;
		position:fixed;
		display:none;
		z-index:2;
	}
	.button-link{
		color:#00f;
		text-decoration:underline;
	}
	.button-link:hover{
		cursor:pointer;
		color:#f00;
	}
	#modal-overlay{
		z-index:1;
	   display:none;
	   position:fixed;
	   top:0;
	   left:0;
	   width:100%;
	   height:120%;
	   background-color:rgba(0,0,0,0.75);
	}
	.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12{
		padding:0;
		text-align: center;
	}
	#tasks li{
		border-bottom:solid lightgray thin;
		padding-top:10px;
		padding-bottom:10px;
	}
	ul, ol{
		padding:0;
		margin:0;
	}
	
	.col-sm-3.col-xs-3.project{
		border-right:thin solid lightgray;
	}
	.memo{
    	font-size: 15pt;
    	line-height: 31px;
    	width:250px;
    	height:200px;

    /* 罫線描画（各ブラウザ対応）*/
    background-image: -webkit-linear-gradient(left, white 10px, transparent 10px), -webkit-linear-gradient(right, white 10px, transparent 10px), -webkit-linear-gradient(white 30px, rgba(204, 204, 204, 0) 30px, #ccc 31px, white 31px);
    background-image: -moz-linear-gradient(left, white 10px, transparent 10px), -moz-linear-gradient(right, white 10px, transparent 10px), -moz-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
    background-image: -ms-linear-gradient(left, white 10px, transparent 10px), -ms-linear-gradient(right, white 10px, transparent 10px), -ms-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
    background-image: -o-linear-gradient(left, white 10px, transparent 10px), -o-linear-gradient(right, white 10px, transparent 10px), -o-linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
    background-image: linear-gradient(left, white 10px, transparent 10px), linear-gradient(right, white 10px, transparent 10px), linear-gradient(white 30px, #ccc 30px, #ccc 31px, white 31px);
    background-size: 100% 100%, 100% 100%, 100% 31px;
    /* 淡いグレーで枠線を囲む */
    border: 1px solid #ccc;
	}
	#logoutLink{
		cursor:pointer;
	}
	#logoutLink:hover{
		color:;
	}
	.taskLists{
		border-right:thin solid lightgray;
	}
	div#header{
		height:50px;
	}
	#logout{
		height:50px;
		/*text-align:right;*/
		float:right;
		margin-right:15px;
	}
	#header{
		position:relative;
	}
	#config{
		margin-right:10px;
	}
	#handshake{
		color:black;
	}


	</style>
</head>

<body>

<div id ="header" class="header" style="background:white;">
	<?php
	//$userid = $_SESSION['username'];
	//$userpass = $_SESSION['password'];
	//echo $userpass.'さん'; 
	//echo $userid;
	//var_dump($user);

	?>
	
	<!--<span><input type="button" id="facebook" href="'. $loginUrl .'" value="ログアウト"></span>-->

	<!--logout処理-->
	<?php
	//Facebook公式SDK(開発セット)を読み込む
	/*require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';
	$facebook = new Facebook(array(
	    'appId'  => '796879627040680',
	    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42',
	));*/

	//if(isset($_POST['hidout'])){
	 //$params = array( 'next' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'] );
	 //$logoutUrl = $facebook->getLogoutUrl($params);
	 //$facebook->destroySession();
	//}
	 ?>
	 <form method="post" action="" id="logout">
	 <font size="5">
	 	<span 
	 		id="config" 
			data-toggle="popover" 
			data-html="true"
			data-placement="bottom"
			title="ユーザー設定" 
			data-content="<a><div data-toggle='modal' 
						  data-target='#myModal'
						  id ='handshake'>handshake+</div></a><br/>
						  <div id='logoutLink'>ログアウト</div>"
		>
		<!-- Modal -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 	<div class="modal-dialog">
		   		 <div class="modal-content">
		     		 <div class="modal-header">
		       		 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		       		 <h4 class="modal-title" id="myModalLabel" align="center">Handshake+</h4>
			     	</div>
			      	<div class="modal-body">
			        
			      	</div>
			     	 <div class="modal-footer">
			       		<button type="button" class="btn btn-default" data-dismiss="modal">cancel</button>
			        	<button type="button" class="btn btn-primary">doing</button>
			     	</div>
		   		</div>
		 	</div>
		</div>
		<i class="fa fa-cog"></i></span>
		<span class="fontSerif"><?php echo $facebookInf['username']; ?>さん</span>
	 </font>
	 <!--<span><input type="button" value="logout" id="fbLogout"></span>-->
	<!--<input type="button" name="logout" id="logot" value="logout">-->
	<input type="hidden" name="hidlogout" value="logout">
	</form>
</div>

<?php 

/*if(isset($_POST['hidout'])){
	//unset($_SESSION['user']);
	header("Location:http://localhost/my_create/egurena2/ok/login.php");
}*/



	//ログインした人のグループ表示
	$db = new db;
	$db->select_usersid();
	
/*//groupNameをセッションに代入
if(isset($_POST['groupName'])){
$groupName = $_POST['groupName'];
$_SESSION['groupName'] = $groupName;
//var_dump($_SESSION['groupName']);
}

//groupIdをセッションに代入
if(isset($_POST['groupId'])){
$groupId = $_POST['groupId'];
$_SESSION['groupId'] = $groupId;
//var_dump($_SESSION['groupId']);
}
*/
?>

<div class="container">
 		<div class="col-sm-3 col-xs-3 project">
		<th/><h2 align="center">プロジェクト</h2>
		<?php
		//データベース接続
		$db = new db;
		$db -> select_task_group();
		$task_group = $db->select_task_group();
		?>

		<!--タスクグループ-->
		<!--グループ追加-->
		
			<!--task_group表示-->
			<form method="post" id="formgroup">
				<table class="table table-hover" id="grouptask">
				<tbody id="tbodyGroup">
					<?php foreach ($task_group as $groups) : ?>
						<tr id ="group_<?php echo ($groups['id']); ?>" data-id="<?php echo ($groups['id']) ; ?>" data-group="<?php echo ($groups['group_name']); ?>">
						<td>
							<?php $id = $groups['id']; ?>
							<input type="hidden" name="groupId" value="">
							<a href="#" class ="form_group" style="cursor:pointer"><span><?php echo $groups['group_name']; ?></span></a>
							<span class="edit_group"><i class="fa fa-pencil"></i></span>
							<span class="delete_group"><i class="fa fa-trash"></i></span>
							<span class="addTeam">登録</span>
							<!--<span class="drag_group"><i class="fa fa-bars"></i></span>-->
							
						</td>
						</tr>
					<?php endforeach ; ?>	
				</tbody>
				</table>
			</form>
			<div align="center" class="task_on"><i class="fa fa-plus"></i></div>
			<div class="task_input">
				<br>
				<input type="text" name="group" id="group">
				<input type="button" value="追加" id="add_group">	
				<p><a id="modal-close1" class="button-link">キャンセル</a></p>
			</div>
					
		<?php
		//データベース接続
		try{
				$dbh = new PDO($dsn,$username,$password);
				//print('接続に成功しました');
			}catch (PDOException $e){
				print('Connection failed:'.$e->getMessage());
				die();
			}
		?>
			
	</div>
	
		<!--<div class="col-sm-9 col-xs-9 taskLists" style="background:#27ae60;">-->
		<div class="col-sm-9 col-xs-9 taskLists">
		<div id="taskHead"></div>
		<div id="tasks"></div>
		<div align="center" class="task_on2"><i class="fa fa-plus"></i></div>
		<div class="task_input2">
		<input type="text" id="title" name="title">		
		<select name="importance" class="importance">
			<option value="0">優先度</option>
			<option value="1">小</option>
			<option value="2">中</option>
			<option value="3">高</option>
		</select>
		<input type="button" value="追加" id="addTask" name="button">	
		</div>
		
		<?php 
			//グループが指定されていなかったら、inputタグを表示しない
			$error = '';
			if(empty($groupId)){
			$error = '1';
			}
			if($error != '1') :?>	
			<h1>todoアプリ</h1>
			
			<?php endif; ?>

		<?php	
			//グループの中のタスクを表示
			$tasks = array();

			@$sql1 ="select * from tasks where type != 'delete' and group_id='$groupId' order by seq";
			foreach ($dbh->query($sql1) as $row) {
				array_push($tasks,$row);
			}
			
			//idの最大値を取得
			// $sql2 = "select max(id) from task_group" ;
			// $check = $dbh->query($sql2)->fetchColumn();
			//var_dump($check);
		?>
		<!--tasks表示処理-->
			<table id ="tasks">
				<?php foreach ($tasks as $task) : ?>
					<tr id = "task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>">
					<td>
						<input type="checkbox" name="check" class="checkTask" <?php if($task['type'] =="done"){ echo "checked"; }?>>
						<span class="<?php echo h($task['type']); ?>"><?php echo h($task['title']); ?></span>
						<span <?php if($task['type'] =='notyet'){ echo 'class="editTask"'; 
							 }elseif($task['type']=='done'){echo 'class="edit"'; }?>> 
						<i class="fa fa-pencil"></i></span>
						<span class="deleteTask"><i class="fa fa-trash"></i></span>
						<!--<span class="drag"><i class="fa fa-bars"></i></span>-->
					</td>
					</tr>
				<?php endforeach; ?>
				<!--<p><img src=""></p>-->
			</table>
		<p><img src=""></p>	
		</div>

		<!--<div class="col-sm-4 col-xs-4" style="background:orange;" id="subtask" >-->
		<div id="subtask" class="col-sm-3 col-xs-3">
			<p class="subHeads"><span id="subhead"></span><span id="subClose"></span></p>
			<!--<div id="subtaskTitle"></div>-->
			<ul id="subtaskTitle"></ul>
				<div id="subtaskPlus">
					<input type="text" name="subtask" id="subText">
					<input type="button" value="追加" id="addSubtask"><br>
				</div>

				<div id="subtaskList">
					<i class="fa fa-plus"></i>
				</div>
				<div id="MoreTask">
					<br/>
					<textarea class="memo" rows="4" cols="20"></textarea>
				</div>

			
				
		</div>
	
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script>
	$(function(){
		//$('#logoutLink').click(function(){
		$(document).on('click','#logoutLink',function(){
			$('#logout').submit();
			console.log('ok');
		});
		//タスクグループのtitleがクリックされた時の処理
		//var ids = 'Global Scope';
		/*function fire(){
			var ids = $(".form_group").parents('li').data('id');
			console.log(ids);
			return ids;
		}*/

		$(document).on('click','.form_group',function (){
			var group = $(this).parents('tr').data('group');
			var ids = $(this).parents('tr').data('id');
			var title = $(this).children("span").text();
			//console.log(title);
			//console.log(ids);
			//console.log(group);
			var hoge = $(
					'<p id="taskLists">'+title+'</p>'
				);
			$('#taskHead')
			.html(hoge);

			$.post('ajax_select_group.php',{
				id:ids,
				group:group,
				title:title
			},function(rs){
				//表示する場所

				/*$('#tasks')
				.html(rs).attr();*/

				$('#tasks')
				.html(rs);

				$('.task_input2')
				.css("display","none");
				$('.task_on2').show();

				$('.stopWatch').css("visibility","hidden");
				$('.stopButton').css("display","none");
				$('.resetButton').css("display","none");

				//$('.stopWatch').css("display","none");
							
				/*ajax読み込み後、taskの追加ボタンが押されたときの処理
				$('#addTask').click(function(){
				var title = $('#title').val();
				//console.log(title);
				//console.log(ids);
					$.post('_ajax_add_task.php', {
					title: title,
					id:ids
					}, function(rs){
					var e = $(
						'<li id ="task_'+rs+'" data-id="'+rs+'">' + 
						'<input type="checkbox" class="checkTask"> ' +
						'<span></span> '+
						'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
						'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
						'<span class="drag"><i class="fa fa-bars"></i></span>' +
						'</li>'
					);
					$('#tasks').append(e).find('li:last span:eq(0)').text(title);
					$('.task_on2').show()
					$('.task_input2').css("display","none");
			

					});
				});*/
				//ajax読み込み後、taskの変更ボタンが押された時の処理
				/*
				$(document).on('click','.editTask',function(){
					var id = $(this).parents('li').data('id');
					var title = $(this).prev().text();
					title = jQuery.trim(title);
					$('#task_'+id)
					.empty()  
					.append($('<input type="text">').attr('value',title))
					.append('<input type="button" value="更新" class="updateTask">');
					$('#task_'+id+'input:eq(0)').focus();
					});

					$(document).on('click','.updateTask',function(){
						var id= $(this).parent().data('id');
						var title = $(this).prev().val();
						$.post('_ajax_update_task.php' , {
							id:id ,
							title: title 
						}, function(rs){
						var e = $(
							'<input type="checkbox" class="checkTask"> ' +
							'<span></span> '+
							'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
							'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
							'<span class="drag"><i class="fa fa-bars"></i></span>'
						);
						$('#task_'+id).empty().append(e).find('span:eq(0)').text(title);
					});
				});
					
				//ajax読み込み後、taskの削除ボタンが押された時の処理
				$(document).on('click', '.deleteTask', function(){
					if(confirm('本当に削除しますか?')){
						var id = $(this).parents('li').data('id');
						alert(id);
						$.post('_ajax_delete_task.php',{
							id: id 
						}, function(rs){


							$('#task_'+id).fadeOut(800);
						});
					}
				});*/
				//ajax読み込み後、taskのdragが押された時の処理
				$('#tableTasks #tbodyTask').sortable({
					axis:'y',
					opacity: 0.2,
					//handle: '.drag',
					update: function() {
						$.post('_ajax_sort_task.php' , {
							task: $(this).sortable('serialize')
						});
					}
				});
				
			});

			

			 // alert(group);
					 // $(function(){
					 // alert(group);
					 // });
			/*if (confirm(group)){
				$(function(){
					$(':hidden[name="groupId"]').val(id);
					$('#formgroup').attr("action","");
					$('#formgroup').submit();
				});
			}	*/
			
		});
		
		//taskのplusが押されたときの処理
		$(document).on('click','.task_on2',function(){
			$('.task_on2').hide();
			$('.task_input2').show();
			$('#title').focus().val("");
			$('.task_input2').addClass('do');
			console.log('押されてる');
		});

		//キャンセル
		var task_input2 = $('.task_input2');

		$(document).click(function(evt){
 			if(task_input2.hasClass('do')){
 				if(!$(evt.target).closest('.task_on2').length ){
				    if(!$(evt.target).closest('.task_input2').length ){
				      console.log('ok');
				        $('.task_on2').show();
						$('.task_input2').hide();
						task_input2.removeClass('do');
						$('.importance').prop("selectedIndex",0);
						$(".importance").off("click",false);
						task_input2.removeClass('do');
				    }
				}
			}
		});
   		

		//タスクグループ追加
		$('#add_group').click(function(){
			
			/* 
			if($("input[name = 'group']").val() !== ""){
				return ture;
			} */
			
			//グループ名が空の時エラーを吐く
			if($("input[name = 'group']").val()==""){
				alert('グループ名を入力してください');
				$('.task_input2').css('display','none');
				$('.task_on2').show();
				return false;
			};
			
			//追加された時の処理
			var group = $('#group').val();
			$.post('_ajax_add_group.php',{
					group:group,
					//group_id:group_id
			},function(rs){
				var e = $(
				'<tr id="group_'+rs+'" data-id="'+rs+'" data-group="'+group+'">' +
				'<td>'+
				'<a class ="form_group"><span></span></a>'+
				'<input type="hidden" name="groupId" value="">'+
				'<span class="edit_group"><i class="fa fa-pencil"></i></span>'+
				'<span class="delete_group"><i class="fa fa-trash"></i></span>'+
				'<span class="addTeam">登録</span>'+
				//'<span class="drag_group"><i class="fa fa-bars"></i></span>'+
				'</td></tr>'
				);
				
				$('#grouptask').append(e).find('tr:last span:eq(0)').text(group);
				//$(':hidden[name="groupId"]').val();
				$('#group').val('').focus();
				$('.task_on').show();
				$('.task_input').css("display","none");
			});		

		});
		
		//チーム登録
		$(document).on('click','.addTeam',function(){
			var id = $(this).parents('tr').data('id');
			console.log(id);			
			//var overlay = $('<div id = "overlay"></div>').appendTo("body");
				$(function(){
					$('#group_'+id)
						.empty()
						.append('<div id="teamImage">')
						//.append('<form method="post" id="followTeamForm">')
						.append('チーム名')
						.append('<input type="text" name="addGroupInTeamName" id="addGroupInTeamNameId"><br />')
						.append('<p id="text">パスワード</p>')
						.append('<input type="text" name="addGroupInTeamPass" id="addTeamToGrouptimPassId"><br />')
						.append('<input type="hidden" name="addGroupInTeamHid" value="'+id+'">')
						.append('<input type="button" name="addTeamToGroupButton" id="addTeamToGroupButton" value="登録">')
						//.append('</form>')
						.append('</div>');
					$('#addGroupInTeamNameId')
					.focus

					//inputタグ以外のものがクリックされた時の処理
					
					// $('#addTeamToGroupButton').click(function(){
						// $("followTeamForm").attr("action","");
						// $('#followTeamForm').submit();
					// });
					 
					 $('#addTeamToGroupButton').click(function(){
						$("#formgroup").attr("action","")
						$("#formgroup").submit();
					 });
					$('#addTeamToGroupButton').click(function(){
						if($('input[name="addGroupInTeamName"]').val()==''|| ($('input[name="addGroupInTeamPass"]').val()=='')){
							alert('空欄があります');
							return false;
						}
						});
				});
		});
		
		//グループ変更
		$(document).on('click','.edit_group',function(){
				var id = $(this).parents('tr').data('id');
				var group = $(this).prev().text();
				console.log(id);
				console.log(group);
				$('#group_'+id)
					.empty()
					.append($('<input type="text">').attr('value',group))
					.append('<input type="button" value="更新" class="updateGroup">');
				});

					$(document).on('click','.updateGroup',function(){
						var id = $(this).parent().data('id');
						var group = $(this).prev().val();
						$.post('_ajax_update_group.php',{
							id:id,
							group:group
						},function(rs){
						var e =$(
						'<a class ="form_group"><span></span></a>'+
						'<span class="edit_group"><i class="fa fa-pencil"></i></span> ' +
						'<span class="delete_group"><i class="fa fa-trash"></i></span> ' 
						//'<span class="drag_group"><i class="fa fa-bars"></i></span>'
					);
					$('#group_'+id).empty().append(e).find('span:eq(0)').text(group);
					});
				});

						

				/*$('#group_'+id)
					.empty()
					//.append($('<input type="text">').attr('value',id))
					.append($('<input type="text">').attr('value',group))
					.append('<input type="button" value="更新" class="update_group">');
					$('#group_'+id+'input:eq(0)').focus();							
					});
				
		$(document).on('click','.update_group',function(){
			var id = $(this).parent().data('id');
			var group = $(this).prev().val();
			 $.post('_ajax_update_group.php' ,{
				 id:id,
				 group:group
			 },
			function(){
				$(':hidden[name="groupId"]').val(id);
				$('#formgroup').attr("action","");
				$('#formgroup').submit();
			});*/
		
			
			// },function(rs){
				// var e = $(	
					// '<span></span>'+
					// '<span class="edit_group">編集</span>'+
					// '<span class="delete_group"><i class="fa fa-trash"></i></span>'+
					// '<span class="drag_group"><i class="fa fa-bars"></i></span>'
				// );
			// $('#group_'+id).empty().append(e).find('span:eq(0)').text(group);
			// });
		// });
		// $(document).on('click','.update_group',function(){
					// load('');
				// });

		//グループのdrag処理
		$('#grouptask #tbodyGroup').sortable({
				axis:'y',
				opacity: 0.2,
				//handle: '.drag_group',
				update: function(){
					$.post('_ajax_sort_group.php' , {
						group:$(this).sortable('serialize')
					});
				}
			});
			
			
		//group削除
		$(document).on('click','.delete_group',function(){
			if(confirm('グループを削除しますか')){
				var id=$(this).parents('tr').data('id');
				var group=$(this).parents('tr').data('group');
				console.log(id);
				console.log(group);
		        $.post('_ajax_delete_group.php',{
					id:id,
					group:group
				},function(rs){
					$('#group_'+id).fadeOut(800);
					//$('#group_'+id).fadeOut();
				});
			}
		});

		
	
	
	//タスク追加
	/*$('#addTask').click(function(){
		var title = $('#title').val();
		//console.log(fire());
		var ids = fire();
		alert(title);
		console.log(ids);
		$.post('_ajax_add_task.php', {
			title: title,
			id:ids,
		}, function(rs){
			var e = $(
			'<li id ="task_'+rs+'" data-id="'+rs+'">' + 
			'<input type="checkbox" class="checkTask"> ' +
			'<span></span> '+
			'<span class="editTask">[編集]</span> ' +
			'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
			'<span class="drag"><i class="fa fa-bars"></i></span>' +
			'</li>'
			);
			$('#tasks').append(e).find('li:last span:eq(0)').text(title);
			$('#title').val('').focus();
			$('#task_on2').show();
			$('#task_input2').css("display","none");
		});
	});*/
	
	//タスク変更
	var  editTaskId;
	var  editTitle;
	$(document).on('click','.editTask',function(){
		var id = $(this).parents('tr').data('id');
		editTaskId = $(this).parents('tr').data('id');
		var title = $(this).prev().text();
		editTitle = $(this).prev().text();
		console.log(editTitle);
		title = jQuery.trim(title);
		//console.log(title);
		var e = $(
			'<select name="importance" class="importance">'+
			'<option value="0">優先度</option>'+
			'<option value="1">小</option>' +
			'<option value="2">中</option>' +
			'<option value="3">高</option>' +
			'</select>'
			);

		$('#task_'+id)
			.empty()  
			//.append('<div class="showEdit">')
			.append($('<input type="text">').attr('value',title))
			.append(e)
			.append('<input type="button" value="更新" class="updateTask">')
			//.append('<div class="showEdit">')
			.wrapInner("<div class='showEdit'></div>");

		$.post('_ajax_select_importance.php',{
			id:id
		},function(rs){
			$('.importance').val(rs);
		});
		$('#task_'+id).focus();
			//$(".importance").attr("selected",false);
	});

		//キャンセル
		$(document).on('click', function(evt){
			//if(!$(evt.target).closest('.editTask').length ){
			if(!$(evt.target).closest('.editTask').length ){
			    if(!$(evt.target).closest('#task_'+editTaskId).length ){
			      //console.log('.task_input2以外のとこがclickされたよ');
		        var e = $(
					'<td>' + 
					'<input type="checkbox" class="checkTask"> ' +
					'<a class="tasklist"><span></span></a> '+
					'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
					'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
					'<span class="drag"><i class="fa fa-bars"></i></span>' +
					'</td>'
					);
		       	//console.log(editTaskId);
		        //console.log(editTitle);
		        $('#task_'+editTaskId).empty().append(e).find('span:eq(0)').text(editTitle);
			    }
			}
		});


	$(document).on('click','.updateTask',function(){
		var id= $(this).parents('tr').data('id');
		var title = $(this).prev().prev().val();
		var importance = $(this).prev().val();
		//console.log(id);
		//console.log(title);
			$.post('_ajax_update_task.php' , {
			id:id,
			title: title,
			importance:importance
		}, function(rs){
		var e = $(
			'<td>' + 
			'<input type="checkbox" class="checkTask"> ' +
			'<a class="tasklist"><span></span></a> '+
			'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
			'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
			'<span class="drag"><i class="fa fa-bars"></i></span>' +
			'</td>'
		);
		$('#task_'+id).empty().append(e).find('span:eq(0)').text(title);
	});
	});
	
	/*//タスクのdrag
	$('#tasklist').sortable({
		axis:'y',
		opacity: 0.2,
		handle: '.drag',
		update: function() {
			$.post('_ajax_sort_task.php' , {
				task: $(this).sortable('serialize')
			});
		}
	});
	
	*/
	//checkboxがチェックされている、いないの処理
	$(document).on('click', '.checkTask', function(){
				
		var id = $(this).parents('tr').data('id');
		var title = $(this).next();
		$.post('_ajax_check_task.php', { 
			id:id
		}, function(rs) {
			if(title.hasClass('do')){
				title.removeClass('do').next().addClass('editTask').removeClass('edit');
				title.addClass('notyet');
			}else { 
				title.addClass('do').next().removeClass('editTask').addClass('edit');
				title.removeClass('notyet');
			}
		});
		//console.log(id);
	});
	
	//進捗状況表示(phpでもおなじことやってるよ）
		var area = $('.checkTask:not(:checked)').map(function(){
			return $(this).parent('label').text();
		});		 
		//alert(area);
		//console.log(area[0]);
			$('.progress').empty().text(area[0]);
	
	
	//進捗状況表示
	$(document).on('click','.checkTask',function(){
		var area = $('.checkTask:not(:checked)').map(function(){
			return $(this).parent('label').text();
		});		 
		//alert(area);
		//console.log(area[0]);
			$('.progress').empty().text(area[0]);
	});
				
		
	//達成率 
		var CheckBox = $('#content :checked').length;
		var text = $('.checkTask').length;
		//console.log(CheckBox);		
		//console.log(text);
		
		var compleate = ((CheckBox)/(text))*100;
		//console.log(compleate);
		$(function(){
			if((compleate) ==0){
				var comp = 'しっかり';
				console.log(comp);
				$("img").attr("src","gocci1.png");
				
			}else if((compleate) >=25 && (compleate)<50){
				 var comp = 'まだまだだよ';
				 console.log(comp);
				 $("img").attr("src","gocci2.png");
			
			 }else if((compleate) >=50 && (compleate)<75){
				 var comp = 'ちょうど半分';
				 console.log(comp);
				 $("img").attr("src","gocci6.png");
			
			}else if((compleate) >=70 && (compleate)<100){
				var comp = 'もうちょい';
				console.log(comp);
				$("img").attr("src","gocci4.png");
				 
			}else if((compleate) ==100){
				var comp = '完璧マン';
				console.log(comp);
				$("img").attr("src","gocci5.png");
			}
		});

	//checkboxをチェックした場合の達成率
	$(document).on('click','.checkTask',function(){

		var CheckBox = $('#content :checked').length;
		var text = $('.checkTask').length;
		
		//console.log(CheckBox);		
		//console.log(text);
		
		var compleate = ((CheckBox)/(text))*100;
		console.log(compleate);
		$(function(){
			if((compleate) ==0){
				var comp = 'しっかり';
				console.log(comp);
				$("img").attr("src","gocci1.png");
				
				
			}else if((compleate) >=25 && (compleate)<50){
				 var comp = 'まだまだだよ';
				 console.log(comp);
				 $("img").attr("src","gocci2.png");
			
			 }else if((compleate) >=50 && (compleate)<75){
				 var comp = 'ちょうど半分';
				 console.log(comp);
				 	$("img").attr("src","gocci6.png");
			
			}else if((compleate) >=70 && (compleate)<100){
				var comp = 'もうちょい';
				console.log(comp);
					$("img").attr("src","gocci4.png");
				 
			}else if((compleate) ==100){
				var comp = '完璧マン';
				console.log(comp);
					$("img").attr("src","gocci5.png");
			}
		});
	});
	
	
	//タスク削除
	$(document).on('click', '.deleteTask', function(){
		
			var id = $(this).parents('tr').data('id');
			//alert(id);
			$.post('_ajax_delete_task.php',{
				id: id 
			}, function(rs){
				$('#task_'+id).fadeOut(800);
			});
		
	});

	//タスクグループのプラスが押されたとき
	/*$(function(){
		$('.task_on').click(function(){
			$('.task_on')
			.css("display","none");
			$(".task_input").show();
		});
	});
	*/

	//taskの追加ボタンが押されたときの処理
	$('#addTask').click(function(){
		//var ids = fire()
		if($("input[name = 'title']").val()==""){
			alert('タスクを入力してください');
			return false;
		};
		var title = $('#title').val();
		var importance = $('.importance').val();
		console.log(importance);
	//console.log(title);
	//console.log(ids);
		$.post('_ajax_add_task.php', {
		title: title,
		importance:importance
		//id:ids
		}, function(rs){
		var e = $(
			'<tr id ="task_'+rs+'" data-id="'+rs+'"　data-task="'+title+'"><td>' + 
			'<input type="checkbox" class="checkTask"> ' +
			'<a class="tasklist"><span></span></a>'+
			'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
			'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
			'<span class="drag"><i class="fa fa-bars"></i></span>' +
			'</tr></td>'
			
		);
		$('#tableTasks').append(e).find('tr:last span:eq(0)').text(title);
		//$('#title').val("").focus();
		$('.task_on2').show()
		$('.task_input2').css("display","none");
		//$(".importance").attr("selected",'true');
		$('.importance').prop('selectedIndex', 0);
		});
		//});
	});

	//サブタスクを表示
	//タスクがダブルクリックがダブルクリックされた時
		/*function taskId(){
			var id = $('.tasklist').parents('tr').data('id');
			return id;
		};*/

		

		$(function(){
			var menu = $('#subtask'),
				//body = $(document.body),
				taskLists = $('.taskLists'),
				project = $('.project')
				menuWidth = menu.outerWidth();
		$(document).on('dblclick','.tasklist', function (){
			var id = $(this).parents('tr').data('id')
			//taskId = $(this).parents('tr').data('id')
			var task = $(this).text()
			var head = 'のサブタスク'
			//console.log(id);
			//var close = '✖'

			//console.log(task);
			//console.log(id);
			$("#subhead").html(task + head + '<hr/>');
			//$("#subClose").html(close).attr('style','float:right')
			
			//$("#subhead").html(close).attr('align','right');
			//$("#subtask").animate({width:'toggle'},300);
			$.post('ajax_select_subtask.php',{
				id:id,
				task:task
			},function(rs){
				$("#subtaskTitle").html(rs);
			});

			$.post('ajax_select_memo.php',{
				id:id
			},function(fi){
				$(".memo").val(fi);
			});
			taskLists.removeClass('open');
			taskLists.addClass('open');
			if(taskLists.hasClass('open')){
				//taskLists.animate({'right' : menuWidth }, 300);
				//menu.animate({'right' : 0 }, 300);
				taskLists.removeClass("col-sm-9");
				taskLists.removeClass("col-xs-9");
				taskLists.addClass("col-sm-6");
				taskLists.addClass("col-xs-6");
				//project.removeClass("col-sm-3");
				//project.addClass("col-sm-2");
				menu.animate({'right' : 0 }, 310);  
			}
			

			$("#subtask").show();
			//$("#subtaskTitle").html(task);
			//$('#memo').text('');

			//memo表示
			/*$.post('ajax_select_memo.php',{
				id:id
			},function(rs){
				$('.memo').removeClass()
				.addClass('memo memo_'+id);
				$('.memo_'+id).empty();
				$('.memo_'+id).html(rs);
			});*/
		});

		$(document).dblclick(function(event){
			var taskLists = $('.taskLists')
				menu = $('#subtask')
				subtask = $("#subtask").children();
			if(taskLists.hasClass('open')){
				if(!$.contains($("#subtask")[0], event.target)){
					if(!$(event.target).closest('.tasklist').length ){
						//console.log('ok');
						menu.animate({right:-200}, 310, function(){
						taskLists.removeClass("col-sm-6");
						taskLists.removeClass("col-xs-6");
						taskLists.addClass("col-sm-9");
						taskLists.addClass("col-xs-9");
						taskLists.removeClass("open");
						$('#subtask').hide();
						});
					}
				}
			}
		});

		//ストップウォッチ
		var taskId;
		//console.log(taskId);
			$(document).on('click','.runButton',function(){
			//$('#run_'+taskId).click(function(){
				 taskId = $(this).parents('tr').data('id');
				$('#sW_'+taskId).css("visibility","visible");
				//console.log(taskId);
				$('#run_'+taskId).css('display','none');
				$('#stop_'+taskId).show();
				$('#reset_'+taskId).show();
				//console.log(taskId);
			});

			$(document).on('click','.runButton',function(){
				run();
			});

			$(document).on('click','.stopButton',function(){
				$('#run_'+taskId).show();
				$('#stop_'+taskId).css("display","none");
			});

			$(document).on('click','.resetButton',function(){
				$('#run_'+taskId).show();
				$('#stop_'+taskId).css("display","none");
				$('#reset_'+taskId).css("display","none");
			});


			var startTime,
					timerId,
					running = false,
					stopTime;

				/*document.getElementById('run_'+id).onclick = function() {
					run();
					console.log(ok);
				}*/
				//$(document).on('click','.runButton', function(){
			
				/*document.getElementById('stop_'+taskId).onclick = function() {
					stop();
				}*/
				//$(document).on('click','.stopButton',function(){
				$(document).on('click','.stopButton', function(){
					stop();
				});
				
				/*document.getElementById('reset_'+taskId).onclick = function() {
					reset();
				}*/
				//$(document).on('click','.resetButton',function(){
				$(document).on('click','.resetButton',function(){
					reset();
				});

				function run(){
					//console.log('run');
					if(running) return;

					running = true;

					if (stopTime){
						startTime = startTime + (new Date()).getTime() - stopTime;
					}

					if(!startTime){
						startTime = (new Date()).getTime();
					}
					timer();
				}

				function timer(){
					document.getElementById('sW_'+taskId).innerHTML = (((new Date()).getTime() - startTime) / 1000).toFixed(2);
					timerId = setTimeout(function(){
						timer();
					},100);
				}

				function stop(){
					if(!running) return false;
					running = false;
					clearTimeout(timerId);
					stopTime = (new Date()).getTime();
				}

				function reset(){
					if(running) return;
					startTime = undefined;
					document.getElementById('sW_'+taskId).innerHTML = '0.00';
				}
		//});


		$('.memo').change(function(){
			var text = $(this).val();
			//console.log(text);
			//alert(text)
			//console.log(id);
			$.post('ajax_add_memo.php',{
				text:text
			},function(){
				$('.memo').val(text);
			});
		});
		
		
		//$('.memo').click(function(){
		/*$('.memo').(function(){
			$(":not(.sample)").click(function(){
				console.log('はずれた');
			});

			$(document).click(function(evet){
				if(!$.contains($(".memo")[0],event.target)){
					console.log('はずれた');
				}
			});
		});
			*/
		//});
	/*
	$('.memo').click(function(event){
		var memo = $(':focus').attr('class');
		//console.log(memo);
		if(memo = 'memo'){
			//console.log('ok');
			
		}
	});*/
		$('.memo').click(function(){
			$(document).click(function(event){
				var memo = $(':focus').attr('class');
				if(memo === 'memo'){
					if(!$.contains($("#MoreTask")[0],event.target)){
						$('.memo').blur();
					}
				}
			});
		});
		$('.memo').click(function(){
			$(document).dblclick(function(event){
				var memo = $(':focus').attr('class');
				if(memo === 'memo'){
					if(!$.contains($("#MoreTask")[0],event.target)){
						$('.memo').blur();
					}
				}
			});
		});
	


	/*function where(){
	var focus = $(':focus').attr('class');
	console.log(focus);
	return focus;
	}

	$('.memo').click(function(){
		where();
		alert(focus);
		if(focus === 'memo'){
			$(document).click(function(event){
				if(!$.contains($("#MoreTask")[0],event.target)){
				$('.memo').blur();
				}
			});
		}
	});*/


		//サブタスクを追加する
		$('#subtaskList').click(function(){
			$('#subtaskList').css("display","none");
			$("#subtaskPlus").show();
		});

		$('#addSubtask').click(function(){
			//console.log(id);
			var subTitle = $('#subText').val();
			console.log(subTitle);
			$.post('ajax_add_sub.php',{
				subtaskTitle:subTitle
			},function(rs){
				var e = $(
					'<li id="sub_'+rs+'" data-id="'+rs+'">'+
						'<input type="checkbox" class="CheckSub">'+
						'<span></span>'+
						'<span class="editSub"><i class="fa fa-pencil"></i></span>'+
						'<span class="deleteSub"><i class="fa fa-trash"></i></span>'+
					'</li>'
					);
				$('#subtaskTitle').append(e).find('li:last span:eq(0)').text(subTitle);
				$('#subText').val('').focus();

			});
		});

		//サブタスクを削除する
		$(document).on('click','.deleteSub',function(){
			var id = $(this).parents('li').data('id');
			console.log(id);
			$.post('ajax_delete_sub.php',{
				id:id
			},function(rs){
				$('#sub_'+id).fadeOut(800);
							});
		});

		//サブタスクのチェックボックスの変化
		$(document).on('click','.checkedSub',function(){
			var id = $(this).parent('li').data('id');
			var title = $(this).next();
			//console.log(id);
			console.log(title);
			$.post('ajax_check_sub.php',{
				id:id,
			},function(){
				if(title.hasClass('done')){
					title.removeClass('done');
					title.addClass('notyet');
				}else{
					title.removeClass('notyet');
					title.addClass('done');
				}
			});
		});

		//サブタスクのドラッグ処理
		$('#subtaskTitle').sortable({
			axis:'y',
			opacity: 0.2,
			handle: '.dragSub',
			update: function() {
				$.post('ajax_sort_sub.php' , {
					sub: $(this).sortable('serialize')
				});
			}
		});

		//サブタスクの変更処理
		//変更ボタンがクリックされる
		$(document).on('click','.editSub',function(){
			var id = $(this).parent('li').data('id');
			var sub = $(this).prev().text();
			//console.log(id);
			//console.log(sub);
			$('#sub_'+id)
			.empty()
			//.append($('<input type="text">').attr('value',sub));
			.append('<input type="text" value="'+sub+'">')
			.append('<input type="button" value="更新" class="updateSub">');
		});
		//更新ボタンがクリックされる
		$(document).on('click','.updateSub',function(){
			var id = $(this).parent().data('id');
			var sub = $(this).prev().val();
			//console.log(id);
			//console.log(sub);
			$.post('ajax_update_sub.php',{
				id:id,
				sub:sub
			},function(rs){
			var e =$(
				'<li id ="sub_'+rs+'" data-id="'+rs+'">'+
				'<input type="checkbox" class="checkTask">'+
				'<span></span>'+
				'<span class="editSub"><i class="fa fa-pencil"></i></span> ' +
				'<span class="deleteSub"><i class="fa fa-trash"></i></span> ' +
				'</li>'
				);
				$('#sub_'+id).empty().append(e).find('span:eq(0)').text(sub);
			});
		});


	

		$(".task_on").click(function(){

		   //オーバーレイを出現させる
		   $("body").append('<div id="modal-overlay"></div>');
		   $("#modal-overlay").fadeIn("fast");

		   //コンテンツをセンタリングする
		   centeringModalSyncer();

		   //コンテンツをフェードインする
		   $(".task_input").fadeIn("fast");
		   $("#group").val("").focus();

		   //[#modal-overlay]、または[#modal-close]をクリックしたら…
		   $("#modal-overlay,#modal-close1,#add_group").click(function(){

		      //[#modal-content]と[#modal-overlay]をフェードアウトした後に…
		      $(".task_input,#modal-overlay").fadeOut("fast",function(){

		         //[#modal-overlay]を削除する
		         $('#modal-overlay').remove();
				 $('.task_on').show();

		      });

		   });

		});

		//リサイズされたら、センタリングをする関数[centeringModalSyncer()]を実行する
		$(window).resize(centeringModalSyncer);

		//センタリングを実行する関数
		function centeringModalSyncer(){

		   //画面(ウィンドウ)の幅、高さを取得
		   var w = $(window).width();
		   var h = $(window).height();
		   //console.log(w);
		   //console.log(h);
		   //コンテンツ(#modal-content)の幅、高さを取得
		   var cw = $(".task_input").outerWidth();
		   var ch = $(".task_input").outerHeight();
		   //console.log(cw);
		   //console.log(ch);

		   //センタリングを実行する
		   $(".task_input").css({"left": ((w - cw)/2) + "px","top": ((h - ch)/2) + "px"});
		}
			
		$('#config').popover({},function(){
				$('#logoutLink').click(function(){
				$('#logout').submit();
				console.log('ok');
			});
		});

		$(document).click(function(evt){
			if(!$(evt.target).closest('#config').length){
				$('#config').popover('hide')
			}
		});
	});
	});
</script>
</body>
</html>
