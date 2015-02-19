<?php 
session_start();
//Facebook公式SDK(開発セット)を読み込む
require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';

$facebook = new Facebook(array(
    'appId'  => '796879627040680',
    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42'
));

echo $_SESION['facebook_user'];


//ログイン状態を取得する

$user = $facebook->getUser();



if ($user) {


    try {

        //ログインしていたら、自分のユーザプロファイルを取得

        $user_profile = $facebook->api('/me');
       // header('Location:/my_create/egurena2/ok/signup_facebook.php');


    } catch (FacebookApiException $e) {

        //ユーザプロファイル取得失敗 = ログインしていない

        error_log($e);

        $user = null;

    }
}

//ログインしていたら、ログインしている人の情報を取得する

if ($user) {
	//header('Location: signup_facebook.php');

    echo '<h3>ログインしている人の写真</h3>'."\n";

    echo '<img src="https://graph.facebook.com/'. $user .'/picture">'."\n";



    echo '<h3>ログインしている人の情報 (/me)</h3>'."\n";

    echo '<pre>'."\n";

    echo print_r($user_profile);

    echo '</pre>'."\n";
	

} else {
//header('Location:localhost/egurena2/ok/signup_facebook.php');
  
}
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
	#facebook{
		color:black;
	}
	</style>
</head>

<body>

<form method="post" action="">
<div class="contents">
	<p id="h1v2">egurena</p>
	<div id="card2">
		<p id="h2v2">sign up</p>
		<div class="control-group success">
		<div class="controls">
			<input type="text" placeholder="idを入力してください"></br>
			<input type="password" placeholder="passwordを入力してください"></br>
			<input type="email" placeholder="emailを入力してください">
			<div id="height">
			<button class="btn btn-success" type="submit"><font color="white">sign up</font></button><br/>

		</div>
		</div>
	</div>
</div>
</form>

</body>

