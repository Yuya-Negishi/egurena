<?php
//Facebook公式SDK(開発セット)を読み込む
require 'facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';
$facebook = new Facebook(array(
    'appId'  => '796879627040680',
    'secret' => 'ebc35d28e8be8d209cc5f924bc714d42',
));
//ログイン状態を取得する
$user = $facebook->getUser();
$_SESSION['facebook_user'] = $user;
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
} else {
    echo '<div><a id="facebook" href="'. $loginUrl .'">ログイン</a></div>'."\n";
}
//==========================================================================
print_r($user);
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
