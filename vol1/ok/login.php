<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<title>sign in</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">	
	<!--google font -->
	<link href='http://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
	 <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="styleshet" href="css/bootstrap-responsive.min.css">
	<link rel="styleshet" href="css/bootstrap.min.css">
	<!--stylee sheet-->
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<style>
		.title{
			padding-top:225px;
		}
		
	</style>
<div class="container">
	<div class="row">
		<div class="col-sm-6 title" >
			<p id="h1v2">egurena</p>
		</div>
		<div class="col-sm-6">
			<div id="card2">
			<?php
				//Facebook公式SDK(開発セット)を読み込む
				require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';
				$facebook = new Facebook(array(
				    'appId'  => '796879627040680',
				    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42',
				));
				//ログイン状態を取得する
				$user = $facebook->getUser();
				if ($user) {
				    try {
				        //ログインしていたら、自分のユーザプロファイルを取得
				        $user_profile = $facebook->api('/me');
				        //header('Location:/my_create/egurena2/ok/signup_facebook.php');
				    } catch (FacebookApiException $e) {
				        //ユーザプロファイル取得失敗 = ログインしていない
				        error_log($e);
				        $user = null;
				    }
				}
				if ($user) {
				    //ログインしていたら、ログアウトURLを取得。
				    $params = array( 'next' => 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'] );
				    $logoutUrl = $facebook->getLogoutUrl($params);
				    //セッションのクリア
				    $facebook->destroySession();
				} else {
				    //ログインして無いなら、ログインURLを取得。
				    $loginUrl = $facebook->getLoginUrl();
				}
				//==========================================================================
				//ログインボタン、ログアウトボタンを表示
				if ($user) {
				    echo '<a href="'. $logoutUrl .'">ログアウト</a>'."\n";
				}else{
					echo '<br>';
				    echo '<div><a id="facebook" href="'. $loginUrl .'"><p class="h2">log in</p></a></div>'."\n";
				}
				//==========================================================================
				
				//ログインしていたら、ログインしている人の情報を取得する
				if ($user) {
					$_SESSION['user'] = $user;
					//var_dump($_SESSION['user']);
				    echo '<h3>ログインしている人の写真</h3>'."\n";
				    echo '<img src="https://graph.facebook.com/'. $user .'/picture">'."\n";
				    echo '<h3>ログインしている人の情報 (/me)</h3>'."\n";
				    echo '<pre>'."\n";
				    echo '</pre>'."\n";
				} else {
				//header('Location:localhost/egurena2/ok/signup_facebook.php');  
				}

				if($user){
					$conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
					echo "MySQLへ接続できました。<br>";
					mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。")
					;
					$sql="SELECT * FROM users where facebookId = '$user';";
					$result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");

					if(mysqli_num_rows($result)===1){
						header("Location:http://localhost/my_create/egurena2/ok/newtodovar4.php");
					}else{
						echo "ユーザー認証に失敗しました。もう一度試してください";
						$facebook->destroySession();

					}
					mysqli_close($conn);
				}				

			?>
		</div>	
	</div>
</div>
</body>