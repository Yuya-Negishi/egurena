<?php 
//セッションスタート
session_start();
require_once('config.php');//設定ファイル
require_once('functions.php');
if(empty($_SESSION['username'])){
	header("Location:http://localhost/my_create/egurena2/ok/login.php");
}
//userのidを取得
var_dump($_SESSION['userid']);
//$_SESSION['userid']  = $userid;
//print_r($resutl);ss
?>

<!DOCTYPE html>
<html lang="ja">
<head>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>todoアプリ</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<style>
	p#newTeam{
		cursor:pointer;
	}
	p#newTeam:hover{
		color:blue;
	}
	p#loginTeamButton{
		cursor:pointer;
	}
	div#teamTitle{
		cursor:pointer;
	}
	div#teamTitle:hover{
		color:blue;
	}
	p.groupName{
		font-size:25px;
		font-weight:bolder;
	}

	</style>
<head>

<body>
	<div id ="header" class="container"　style="background:green";>
		<table>
			<tr>
				<th><a href="newtodovar3.php">自分の画面へ</a></th>
				<th><p align="right"><?php echo ($_SESSION['username'].'さん'); ?></p></th>
			</tr>
		</table>
		<h1>チーム</h1>
		<!--signup team tag -->
		<p id ="newTeam" >新規登録はこちら</p>
			<form method="post" action="" id="teamPost" name="a">
				<p id ="addTeam"></p>
			</form>
	</div>

	<!--チーム追加-->
	<div class="container">
		<div class="row">
		 	<div class="col-sm-4 col-xs-4" style="background:orange;">
				
				<p id ="loginTeamButton">チームを追加</p>
				<form method="post" action="" id="teamForm" name="b">
					<p id ="loginTeam"></p>
				</form>
				
				
				<?php 
				//get teamid
				$userId=$_SESSION['userid'];
				$db= new db;
				$teamTitles=$db->showTeamTitle($userId);
				//var_dump($teamTitles);
				$teamTitleStatus = array();
		        foreach($teamTitles as $teamTitle ){
            		//echo($teamTitle['teamId']);
                	$teamTitleStatus[] =  "'".$teamTitle['teamId']."'";
                }
                //var_dump($teamTitleStatus);

              	$teamTitleString = implode(",", $teamTitleStatus);
              	//echo ($teamTitleString);				

				//View the registered team

				$getTeams=$db->showTeam($teamTitleString);
				//echo $getTeams;

				
				//新規登録ボタンが押されたとき
				if(isset($_POST['hid1'])){
					//echo $_POST['teamId'];
					//echo $_POST['teamPass'];
					//echo ($_POST['teamTitle']);
				$db = new db;
				$db->teamSignUp($_POST['teamId'],$_POST['teamPass'],$_POST['teamTitle']);
				//今登録したidを取得
				/*$db2 = new db;
				$i=$db2->getId_teams();
				$nowId = $i[0];
				echo $nowId;

				
				$db3 = new db;
				$db3->signup_userteams($userId,$nowId);*/
				}

				$userId=$_SESSION['userid'];
				$db= new db;
				$teamTitles=$db->showTeamTitle($userId);
				//var_dump($teamTitles);
				$teamTitleStatus = array();
		        foreach($teamTitles as $teamTitle ){
            		//echo($teamTitle['teamId']);
                	$teamTitleStatus[] =  $teamTitle['teamId'];
                }
                //var_dump($teamTitleStatus);
               $teamTitleString = implode(",", $teamTitleStatus);
               // echo ($teamTitleString);

				
				//チームを追加

				if(isset($_POST['hid2'])){
					
				  	$userId=$_SESSION['userid'];
				  	$db = new db;
                    $teamId = $db -> addnewTeam($_POST['loginTeamId'],$_POST['loginTeamPass'],$userId);
                    
                   //echo $teamId;  
                    } ?>     
            </div>      
        <div class="col-sm-8 col-xs-8" style="background:pink;">      
        <!-- followteamのtask_groupを取得 -->               
      		<div class="groupInTask">
				<?php 
				//$db = new db;
				//$groupsId = $db->selectFollow($teamId);           
				?>      
			</div>
			<div id ="teamContents"></div>
		</div>
	</div> 
</div>

	<script>
		$(function(){
			// //グループ名がクリックされた時、タスク一覧を表示
			$(document).on('click','.groupClick',function(){
				var id = $(this).data("id");
			$.post('teamjson.php',{
				id:id,
				async: false
			},function(data){
				$("#a").html(data)
			})
			$(function(){
				alert(id);
			});
		});
			
		


			// $(document).on('click','.groupClick',function(){
			// 	var id = $(this).data("id");
			// $(function(){
			// 	if(!confirm('ぞっこう？')){
			// 		return false;
			// 	}

			// $.post('teamjson.php',{
			// 	id:id
			// });
			// });
			// });

			//signupteam
			 $("#newTeam").click(function(){
				$('#newTeam')
				.remove()
				$('#addTeam')
				.append("<span>")
				.append("id")
				.append($('<input type="text" name="teamId">."\n"'))
				.append('<br>')
				.append("パスワード")
				.append($('<input type="text" name="teamPass">.'))
				.append($('<input type="hidden" name="hid1" value="hid1">'))
				.append($('<br>'))
				.append("チーム名")
				.append($('<input type="text" name="teamTitle">'))
				.append($('<input type="button" id="newTeamButton" value="登録">'))
				.append("</span>");	
				$("#newTeamButton").click(function(){
					//alert('ok');
					if($("input[name = 'teamId']").val()=="" || ($("input[name = 'teamPass']").val()=="")){
							alert('空欄があります');
							return false;
						};			
					$('#teamPost').submit();
				});		
			});	
		
			//loginteam
 		 $("#loginTeamButton").click(function(){
				$('#loginTeamButton')
				.remove()
				$('#loginTeam')
				.append("<span>")
				.append("id")
				.append($('<input type="text" name="loginTeamId">."\n"'))
				.append('<br>')
				.append("パスワード")
				.append($('<input type="text" name="loginTeamPass">.'))
				.append($('<input type="hidden" name="hid2" value="hid2">'))
				.append($('<br>'))
				.append($('<input type="button" id="loginTeamButton2" value="登録">'))
				.append("</span>");	
				$("#loginTeamButton2").click(function(){
					//alert('ok');
					if($("input[name = 'loginTeamId']").val()=="" || ($("input[name = 'loginTeamPass']").val()=="")){
							alert('空欄があります');
							return false;
						};			
					$('#teamForm').submit();
				});		
			});	 
			
 		$(document).on('click','#teamTitle',function(){
 			var id = $("p",this).attr("id");
 			//alert(id);
 			$.post('ajax_get_group.php',{
 				id:id
 			},function(hoge){
 				//console.log(hoge);
 				$('#teamContents')
 				.empty()
 				.append(hoge);
 			});
 		 });
		});
		
		
		
	</script>
</body>
</html>
