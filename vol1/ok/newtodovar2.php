
<?php 
//セッションスタート
session_start();
require_once('config.php');//設定ファイル
require_once('functions.php');

//ログインされていないとき、login.phpに戻す
if(empty($_SESSION['username'])){
	header("Location:http://localhost/my_create/egurena2/ok/login.php");
}
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
	<script src="bootstrap/js/bootstrap.min.js"></script>
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

	a.form_group{
		color:black;
		cursor:pointer;
		text-decoration: none;
	}
	a.form_group:hover{
		color:red;
	}
	li{list-style:none;}
	
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
	
	</style>
</head>

<body>

<div id ="header" class="container" style="background:white;">
	<?php
	$userid = $_SESSION['username'];
	$userpass = $_SESSION['password'];
	//echo $userpass.'さん'; 
	//echo $userid;
	?>
	<form method="post" action="" id="logout">
	<a id="teamphp" href="team.php">チーム画面</a>
	<p><?php echo $userid; ?>さん
	<!--logout処理-->
	<input type="button" name="logout" id="logout" value="logout">
	</p>
	<input type="hidden" name="hidout">
	</form>
	
	<?php
	//ログインした人のグループ表示
	$db = new db;
	$db->select_usersid();
	?>
</div>

<?php 

if(isset($_POST['hidout'])){
	unset($_SESSION['username']);
	header("Location:http://localhost/my_create/egurena2/ok/login.php");
}

//groupNameをセッションに代入
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
?>

<div class="container">
 	<div class="row">
 		<div class="col-sm-4 col-xs-4" style="background:pink;">
		<h1>タスクグループ</h1>
		<?php
		//データベース接続
		$db = new db;
		$db -> select_task_group();
		$task_group = $db->select_task_group();
		?>

		<!--タスクグループ-->
		<!--グループ追加-->
		<div id="sidebar">
			<input type="text" name="group" id="group">
			<input type="button" value="登録する" id="add_group">	
			<!--task_group表示-->
			<form method="post" id="formgroup">
				<ul id ="grouptask">
					<?php foreach ($task_group as $groups) : ?>
						<li id ="group_<?php echo ($groups['id']); ?>" data-id="<?php echo ($groups['id']) ; ?>" data-group="<?php echo ($groups['group_name']); ?>">
						<?php $id = $groups['id']; ?>
						<input type="hidden" name="groupId" value="">
						<a href="#" class ="form_group" style="cursor:pointer"><?php echo $groups['group_name']; ?></a>
						<span class="edit_group"><i class="fa fa-pencil"></i></span>
						<span class="delete_group"><i class="fa fa-trash"></i></span>
						<span class="addTeam">登録</span>
						<span class="drag_group"><i class="fa fa-bars"></i></span>
						</li>
					<?php endforeach ; ?>	
				</ul>
			</form> 
			
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
		<div class="col-sm-4 col-xs-4" style="background:lightgreen;">
		<?php 
			//グループが指定されていなかったら、inputタグを表示しない
			$error = '';
			if(empty($groupId)){
			$error = '1';
			}
			if($error != '1') :?>	
			<h1>todoアプリ</h1>
			<input type ="text" id="title">
			<input type="button" id="addTask" value="追加">
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
		</div>

		<div class="col-sm-4 col-xs-4" style="background:orange;">
			<?php 
			//$gorupId（グループのid)と同じタスクを表示
			//グループの中身を表示baba
			//@$sql1 ="select * from tasks where group_id='$groupId' order by seq";

			//進捗状況表示
			foreach($tasks as $task){
				if($task['type'] == 'notyet'){
					$hoge = $task['title'];
					if(isset($hoge)){
						break;
					}
				}
			} 
			?>
			<?php 
			if(isset($hoge)):?>
				<p>進捗状況</p>
			<?php endif; ?>
			<span class ="progress">
			<?php echo @$hoge; ?>
			</span>

			<?php 
			//チーム登録
			if(isset($_POST['addGroupInTeamHid'])){
				//var_dump($_POST['addGroupInTeamName'],$_POST['addGroupInTeamPass']);
				$db = new db;
				$selectTeamNames = $db->selectTeam($_POST['addGroupInTeamName'],$_POST['addGroupInTeamPass']);
				//echo $selectTeamNames;
				//echo $_POST['addGroupInTeamHid'];
				$addGroupInTeamHid=$_POST['addGroupInTeamHid'];
				$db->followTeam($selectTeamNames,$addGroupInTeamHid);
			}
			?>
		</div>
	</div>
	<div class="row">
	    <div class="col-sm-4 col-xs-4" style="background:white;"></div>
	    <div class="col-sm-4 col-xs-4" style="background:lightgreen;">
	    <p><img src=""></p>
	    </div>
	    <div class="col-sm-4 col-xs-4" style="background:white;"></div>
	
	</div>
</div>

<script>
	//logoutボタン　
	$(function(){
		$("#logout").click(function(){
		$("#logout").submit();
		
		});
	});

	
	/*function form(){
			$('#formgroup').attr("action","");
			$('#formgroup').submit();
			var days = ['0', '1', '2'];

		}*/
		
		//タスクグループのtitleがクリックされた時の処理
		$(document).on('click','.form_group',function(){
			var group = $(this).parent().data('group');
			var id = $(this).parents('li').data('id');
			console.log(id);
			console.log(group);

			 // alert(group);
					 // $(function(){
					 // alert(group);
					 // });
			if (confirm(group)){
				$(function(){
					$(':hidden[name="groupId"]').val(id);
					$('#formgroup').attr("action","");
					$('#formgroup').submit();
				});
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
				'<span class="delete_group"><i class="fa fa-trash"></i></sapn>'+
				'<span class="addTeam">チーム</span>'+
				'<span class="drag_group"><i class="fa fa-bars"></i></span>'+
				'</li>'
				);
				
				$('#grouptask').append(e).find('li:last span:eq(0)').text(group);
				$(':hidden[name="groupId"]').val();
				$('#group').val('').focus();
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
				$('#group_'+id)
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
			});
		});
			
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
			
			
		$(document).on('click','.delete_group',function(){
			if(confirm('グループを削除しますか')){
				var id=$(this).parents('li').data('id');
				var group=$(this).parents('li').data('group');
		        $.post('_ajax_delete_group.php',{
					id:id,
					group:group
				},function(rs){
					$('#formgroup').submit();
					//$('#group_'+id).fadeOut(800);
				});
			}
		});

		
	$(function(){
	
	//タスク追加
	$('#addTask').click(function(){
		var title = $('#title').val();
		$.post('_ajax_add_task.php', {
			title: title
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
		});
	});
	
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
			'<span></span> '+
			'<span class="editTask"><i class="fa fa-bars"></i></span> ' +
			'<span class="deleteTask"><i class="fa fa-trash"></i></span> ' +
			'<span class="drag"><i class="fa fa-bars"></i></span>'
		);
		$('#task_'+id).empty().append(e).find('span:eq(0)').text(title);
	});
	});
	
	//タスクのdrag
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
		if(confirm('本当に削除しますか?')){
			var id = $(this).parents('li').data('id');
			//alert(id);
			$.post('_ajax_delete_task.php',{
				id: id 
			}, function(rs){
				$('#task_'+id).fadeOut(800);
			});
		}
	});
	$(function(){
		$('#add').click(function(){
			$('#add1')
			.empty()
			.append('<div id="add2">')
			.append('<input type="text" name="group" id="group">')
			.append('<input type="button" value="登録する"　id="add_group">')
			.append('</div>');
		});
	});
});
	
</script>
</body>
</html>
