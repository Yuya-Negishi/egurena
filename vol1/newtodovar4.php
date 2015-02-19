
<?php 
//セッションスタート
session_start();
require_once('config.php');//設定ファイル
require_once('functions.php');

//ログインされていなかった場合
if(!$_SESSION['user']){
	header('Location:http://localhost/my_create/egurena2/ok/login.php');
}

$user = $_SESSION['user'];
//var_dump($_SESSION['user']);
//$userid = $db -> facebookId($user);
//$_SESSION['userid'] = $userid;

$db = new db;
$facebookInf = $db->facebookInf();
//var_dump($facebookInf); 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>todoアプリ</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	
	<style>

	/* カーソルと色 */
	.deleteTask,.editTask,.edit_group,.delete_group{
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
	}
	#tasks{
		user-select: none;
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
     position:relative;
     right:0px;
     overflow-x:hidden;
	}
	#subtask{
		position:fixed;
		top:0;
		right:-200px;
		width:200px;
		height:100%;
		background:orange;
	}
	.taskLists{
		text-align:center;
	}
	

	
	</style>
</head>

<body>

<div id ="header" class="container" style="background:white;">
	<?php
	//$userid = $_SESSION['username'];
	//$userpass = $_SESSION['password'];
	//echo $userpass.'さん'; 
	//echo $userid;
	?>
	
	<a id="teamphp" href="team.php">チーム画面</a>
	<span><?php echo $facebookInf['username']; ?>さん</span>
	<span><input type="button" id="facebook" href="'. $loginUrl .'" value="ログアウト"></span>

	<!--logout処理-->
	<?php
	//Facebook公式SDK(開発セット)を読み込む
	require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';
	$facebook = new Facebook(array(
	    'appId'  => '796879627040680',
	    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42',
	));

	if(isset($_POST['hidout'])){
	 $params = array( 'next' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'] );
	 $logoutUrl = $facebook->getLogoutUrl($params);
	 $facebook->destroySession();
	}
	 ?>

	<form method="post" action="" id="logout">
	<!--<input type="button" name="logout" id="logout" value="logout">-->
	<input type="hidden" name="hidout">
	</form>
</div>

<?php 

if(isset($_POST['hidout'])){
	//unset($_SESSION['user']);
	header("Location:http://localhost/my_create/egurena2/ok/login.php");
}



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
 	<div class="row">
 		<div class="col-sm-4 col-xs-4" style="background:pink;">
		<h1>プロジェクト</h1>
		<?php
		//データベース接続
		$db = new db;
		$db -> select_task_group();
		$task_group = $db->select_task_group();
		?>

		<!--タスクグループ-->
		<!--グループ追加-->
		<div id="sidebar">
			<!--task_group表示-->
			<form method="post" id="formgroup">
				<ul id ="grouptask">
					<?php foreach ($task_group as $groups) : ?>
						<li id ="group_<?php echo ($groups['id']); ?>" data-id="<?php echo ($groups['id']) ; ?>" data-group="<?php echo ($groups['group_name']); ?>">
						<?php $id = $groups['id']; ?>
						<input type="hidden" name="groupId" value="">
						<a href="#" class ="form_group" style="cursor:pointer"><span><?php echo $groups['group_name']; ?></span></a>
						<span class="edit_group"><i class="fa fa-pencil"></i></span>
						<span class="delete_group"><i class="fa fa-trash"></i></span>
						<span class="addTeam">登録</span>
						<span class="drag_group"><i class="fa fa-bars"></i></span>
						</li>
					<?php endforeach ; ?>	
				</ul>
			</form> 
			<div align="center" class="task_on"><i class="fa fa-plus"></i></div>
			<div class="task_input">
			<input type="text" name="group" id="group">
			<input type="button" value="追加" id="add_group">	
			</div>
			
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
		<div class="col-sm-8 col-xs-4 taskLists" style="background:lightgreen;">
		<ul id ="tasks"></ul>

		<div align="center" class="task_on2"><i class="fa fa-plus"></i></div>
		<div class="task_input2">
		<input type="text" id="title">
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
			<ul id ="tasks">
				<?php foreach ($tasks as $task) : ?>
					<li id = "task_<?php echo h($task['id']); ?>" data-id="<?php echo h($task['id']); ?>">
						<input type="checkbox" name="check" class="checkTask" <?php if($task['type'] =="done"){ echo "checked"; }?>>
						<span class="<?php echo h($task['type']); ?>"><?php echo h($task['title']); ?></span>
						<span <?php if($task['type'] =='notyet'){ echo 'class="editTask"'; 
							 }elseif($task['type']=='done'){echo 'class="edit"'; }?>> 
						<i class="fa fa-pencil"></i></span>
						<span class="deleteTask"><i class="fa fa-trash"></i></span>
						<span class="drag"><i class="fa fa-bars"></i></span>
					</li>
				<?php endforeach; ?>
				<!--<p><img src=""></p>-->
			</ul>
		<p><img src=""></p>	
		</div>

		<!--<div class="col-sm-4 col-xs-4" style="background:orange;" id="subtask" >-->
		<div id="subtask">
			<p class="subHeads"><span id="subhead"></span><span id="subClose"></span></p>
			<!--<div id="subtaskTitle"></div>-->
			<ul id="subtaskTitle"></ul>
				<div id="subtaskPlus">
					<input type="text" name="subtask" id="subText">
					<input type="submit" value="追加" id="addSubtask"><br>
				</div>

				<div id="subtaskList">
					<i class="fa fa-plus"></i>
				</div>

			<div id="MoreTask">
			<textarea name="MoreTask" rows="4" cols="20"></textarea>
			</div>
		</div>
	</div>
</div>

<script>
$(function(){
	
	/*$('#ajax1').click(function(){
		alert('unnko');
	});*/
//logoutボタン　
	$(function(){
		$("#facebook").click(function(){
		$("#logout").submit();
		
		});
	});

	
	/*function form(){
			$('#formgroup').attr("action","");
			$('#formgroup').submit();
			var days = ['0', '1', '2'];

		}*/
		
		//タスクグループのtitleがクリックされた時の処理
		//var ids = 'Global Scope';
		/*function fire(){
			var ids = $(".form_group").parents('li').data('id');
			console.log(ids);
			return ids;
		}*/

		$(document).on('click','.form_group',function (){
			var group = $(this).parent().data('group');
			var ids = $(this).parents('li').data('id');
			var title = $(this).children("span").text();
			console.log(title);
			console.log(ids);
			console.log(group);

			$.post('ajax_select_group.php',{
				id:ids,
				group:group,
				title:title
			},function(rs){
				//表示する場所
				$('#tasks')
				.html(rs);
				$('.task_input2')
				.css("display","none");
				$('.task_on2').show();
							
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
				$('#tasks').sortable({
					axis:'y',
					opacity: 0.2,
					handle: '.drag',
					update: function() {
						$.post('_ajax_sort_task.php' , {
							task: $(this).sortable('serialize')
						});
					}
				});

				//taskのplusが押されたときの処理
				$(document).on('click','.task_on2',function(){
					$('.task_on2').css("display","none")
					$('.task_input2').show();
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
		
		//タスクグループ追加
		$('#add_group').click(function(){
			
			/* 
			if($("input[name = 'group']").val() !== ""){
				return ture;
			} */
			
			//グループ名が空の時エラーを吐く
			if($("input[name = 'group']").val()==""){
				alert('グループ名を入力してください');
				return false;
			};
			
			//追加された時の処理
			var group = $('#group').val();
			$.post('_ajax_add_group.php',{
					group:group,
					//group_id:group_id
			},function(rs){
				var e = $(
				'<li id= "group_'+rs+'" data-id="'+rs+'" data-group="'+group+'">'+
				'<a class ="form_group"><span></span></a>'+
				'<input type="hidden" name="groupId" value="">'+
				'<span class="edit_group"><i class="fa fa-pencil"></i></span>'+
				'<span class="delete_group"><i class="fa fa-trash"></i></span>'+
				'<span class="addTeam">登録</span>'+
				'<span class="drag_group"><i class="fa fa-bars"></i></span>'+
				'</li>'
				);
				
				$('#grouptask').append(e).find('li:last span:eq(0)').text(group);
				$(':hidden[name="groupId"]').val();
				$('#group').val('').focus();
				$('.task_on').show();
				$('.task_input').css("display","none");
			});		
		});
		
		//チーム登録
		$(document).on('click','.addTeam',function(){
			var id = $(this).parents('li').data('id');
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
				var id = $(this).parent().data('id');
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
						'<span class="delete_group"><i class="fa fa-trash"></i></span> ' +
						'<span class="drag_group"><i class="fa fa-bars"></i></span>'
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
		$('#grouptask').sortable({
				axis:'y',
				opacity: 0.2,
				handle: '.drag_group',
				update: function(){
					$.post('_ajax_sort_group.php' , {
						group:$(this).sortable('serialize')
					});
				}
			});
			
			
		//group削除
		$(document).on('click','.delete_group',function(){
			if(confirm('グループを削除しますか')){
				var id=$(this).parents('li').data('id');
				var group=$(this).parents('li').data('group');
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
			'<a class="tasklist"><span></span></a> '+
			'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
			'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
			'<span class="drag"><i class="fa fa-bars"></i></span>'
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
				
		var id = $(this).parents('li').data('id');
		var title = $(this).next();
		$.post('_ajax_check_task.php', { 
			id:id
		}, function(rs) {
			if(title.hasClass('done')){
				title.removeClass('done').next().addClass('editTask').removeClass('edit');
				title.addClass('notyet');
			}else { 
				title.addClass('done').next().removeClass('editTask').addClass('edit');
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
		
			var id = $(this).parents('li').data('id');
			//alert(id);
			$.post('_ajax_delete_task.php',{
				id: id 
			}, function(rs){
				$('#task_'+id).fadeOut(800);
			});
		
	});

	//タスクグループのプラスが押されたとき
	$(function(){
		$('.task_on').click(function(){
			$('.task_on')
			.css("display","none");
			$(".task_input").show();
		});
	});

	//taskの追加ボタンが押されたときの処理
	$('#addTask').click(function(){
		//var ids = fire();
		var title = $('#title').val();
	//console.log(title);
	//console.log(ids);
		$.post('_ajax_add_task.php', {
		title: title,
		//id:ids
		}, function(rs){
		var e = $(
			'<li id ="task_'+rs+'" data-id="'+rs+'"　data-task="'+title+'">' + 
			'<input type="checkbox" class="checkTask"> ' +
			'<a class="tasklist"><span></span></a>'+
			'<span class="editTask"><i class="fa fa-pencil"></i></span> ' +
			'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
			'<span class="drag"><i class="fa fa-bars"></i></span>' +
			'</li>'
			
		);
		$('#tasks').append(e).find('li:last span:eq(0)').text(title);
		$('.task_on2').show()
		$('.task_input2').css("display","none");
		});
	});

	//サブタスクを表示
	//タスクがダブルクリックがダブルクリックされた時
		$(function(){
			var menu = $('#subtask'),
					body = $(document.body),
					menuWidth = menu.outerWidth();
			$(document).on('dblclick','.tasklist', function (){
				var id = $(this).parents('li').data('id')
				var task = $(this).text()
				var head = 'のサブタスク'
				var close = '✖'
				//console.log(task);
				//console.log(id);
				$("#subhead").html(task + head)
				$("#subClose").html(close).attr('style','float:right')
				
				//$("#subhead").html(close).attr('align','right');
				//$("#subtask").animate({width:'toggle'},300);
				$.post('ajax_select_subtask.php',{
					id:id,
					task:task
				},function(rs){
					$("#subtaskTitle").html(rs);
				});

				body.toggleClass('open');
				if(body.hasClass('open')){
					body.animate({'right' : menuWidth }, 300);
					menu.animate({'right' : 0 }, 300);
				} else {
					menu.animate({'right' : -menuWidth }, 300);
					body.animate({'right' : 0 }, 300);
				}

				$("#subtask").show();
				//$("#subtaskTitle").html(task);

			});
		});
		
		//サブタスクを追加する
		$('#addSubtask').click(function(){
			//console.log(id);
			var subTitle = $('#subText').val();
			console.log(subTitle);
			$.post('ajax_add_sub.php',{
				subtaskTitle:subTitle
			},function(rs){
				var e = $(
					'<li id="'+rs+'" data-id="'+rs+'">'+
						'<input type="checkbox" class="CheckSub">'+
						'<span></span>'+
						'<span class="editSub"><i class="fa fa-pencil"></i></span>'+
						'<span class="deleteSub"><i class="fa fa-trash"></i></span>'+
						'<span class="dragSub"><i class="fa fa-bars"></i></span>'+
					'</li>'
					);
				$('#subtasks').append(e).find('li:last span:eq(0)').text(subTitle);
				$('#subText').val('').focus();
			});
		});

		//サブタスクを削除する
		$(document).on('click','.deleteSub',function(){
			var id = $(this).parents('li').data('id');
			//console.log(id);
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
			var id = $(this).parent().data('id');
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
				'<span class="dragSub"><i class="fa fa-bars"></i></span>' +
				'</li>'
				);
				$('#sub_'+id).empty().append(e).find('span:eq(0)').text(sub);
			});
		});
	});


</script>
</body>
</html>
