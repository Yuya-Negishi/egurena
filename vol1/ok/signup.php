<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<title>egurena</title>
	<meta charset="UTF-8">
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

</head>

<body>
<div class="container">
	<div class="row">
		<div class="col-sm-6 title" >
			<p id="h1v2">egurena</p>

		</div>
		<div class="col-sm-6">
			<div id="card2">
			<!--<p id="h3">h1</p>-->
				<?php
					//Facebook公式SDK(開発セット)を読み込む
					require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';
					$facebook = new Facebook(array(
					    'appId'  => '796879627040680',
					    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42',
					));

					//Facebook公式SDK(開発セット)を読み込む
					//ログイン状態を取得する
					$user = $facebook->getUser();
					if ($user) {
					    try {

					    	//header('Location:http://localhost/my_create/egurena2/ok/signup_form.php');

					        //ログインしていたら、自分のユーザプロファイルを取得

					        $user_profile = $facebook->api('/me');
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
					echo '<hr />'."\n";
					echo '<span class="h4">Sign up</span>';
					//ログインボタン、ログアウトボタンを表示
					if ($user) {
						echo '<br>';
					    echo '<a href="'. $logoutUrl .'"><p class="h2">ログアウト</h2></a>';
					} else {
						echo '<br>';
					    echo '<div><a id="facebook" href="'. $loginUrl .'"><p class="h2">facebook</p></a></div>';
					}
					//==========================================================================
					echo '<hr />'."\n";
					//ログインしていたら、ログインしている人の情報を取得する
					if ($user) {
					    echo '<h3>ログインしている人の写真</h3>'."\n";
					    echo '<img src="https://graph.facebook.com/'. $user .'/picture">'."\n";
					    echo '<h3>ログインしている人の情報 (/me)</h3>'."\n";
					    $_SESSION['picture'] = '<img src="https://graph.facebook.com/'. $user .'/picture">';
					    echo '<pre>'."\n";
					    echo print_r($user_profile);
					    $_SESSION['profile'] = $user_profile ;
					    echo '</pre>'."\n";

	                    header('Location:http://localhost/my_create/egurena2/ok/signup_form.php');

					} else {
					    echo '<strong><em>あなたはまだログインしていません</em></strong>'."\n";
					}
					?>

			</div>
		</div>	
	</div>
</div>
</body>




