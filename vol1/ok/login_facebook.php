<?php

//Facebook公式SDK(開発セット)を読み込む

require '../facebook-php-sdk-master/facebook-php-sdk-master/src/facebook.php';



//AppIDとAppSecretをFacebook Developer Centerにて取得して下さい。

//　https://developers.facebook.com/apps/

//AppIDとAppSecretを設定してください。

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



//HTMLヘッダを表示

echo <<<_HEADER_

<html>

    <head>

        <meta content='text/html; charset=utf-8' http-equiv='content-type'>

    </head>

    <h1>Facebook連携サンプルプログラム</h1>

    <p>出来る事</p>

    <ul>

        <li>１）ログイン、ログアウト処理</li>

        <li>２）ログインしている人の基本情報を取得する処理</li>

        <li>３）ログインしている人の友達リストを取得する処理</li>

    </ul>

_HEADER_;



//==========================================================================

echo '<hr />'."\n";

//ログインボタン、ログアウトボタンを表示

if ($user) {

    echo '<a href="'. $logoutUrl .'">ログアウト</a>'."\n";

} else {

    echo '<div><a href="'. $loginUrl .'">ログイン</a></div>'."\n";

}



//==========================================================================

echo '<hr />'."\n";



//ログインしていたら、ログインしている人の情報を取得する

if ($user) {

    echo '<h3>ログインしている人の写真</h3>'."\n";

    echo '<img src="https://graph.facebook.com/'. $user .'/picture">'."\n";



    echo '<h3>ログインしている人の情報 (/me)</h3>'."\n";

    echo '<pre>'."\n";

    echo print_r($user_profile);

    echo '</pre>'."\n";


} else {

    echo '<strong><em>あなたはまだログインしていません</em></strong>'."\n";

}



echo<<<_FOOTER_

</body>

</html>

_FOOTER_;

