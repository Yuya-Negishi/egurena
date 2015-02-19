<?php 
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ユーザ認証</title>
</head>
<body>
<form action="" method="post" name="form1">
ユーザ名：
<input name="username" type="text" id="username">
<br>
パスワード：
<input name="password" type="password" id="password">
<input type="hidden" name="hid" id="hid">
<input type="submit" name="Submit" value="認証">
<input type="reset" name="Submit2" value="クリア">
</form>
<?php
if(isset($_POST['hid'])){
  $conn=mysqli_connect('localhost','root','') or exit("MySQLへ接続できません。");
  echo "MySQLへ接続できました。<br>";
  mysqli_select_db($conn,'dotinstall_todo_app') or exit("データベース名が間違っています。");
  $sql="SELECT * FROM users where username='{$_POST['username']}' and password='{$_POST['password']}';";
  $result=mysqli_query($conn,$sql) or exit("データの抽出に失敗しました。");
  
  //mysqli_num_rows($MySQL['result'])は、SELECT文で抽出されたレコードの数を返す関数です。
  //抽出されたレコード数が０の場合は、ユーザ名とパスワードが一致しなかったことになります。
  //抽出されたレコード数が一つ以上あれば、ユーザ名とパスワードが一致したことになります。
  //コード数が２以上存在するということは、同じユーザ名とパスワードを持つユーザが２名以上
  //存在することになります。
  //このような登録ができないように次のユーザ登録のところで行います。
  if(mysqli_num_rows($result)!=0){
	// $sql1 = "SELECT * FROM users where username='{$_POST['username']}' and password='{$_POST['password']}';";
	// $result=mysqli_query($conn,$sql);
	// foreach($result as $user){
		 // $user['id'];
	// }
	
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['password'] = $_POST['password'];
	header("Location:http://localhost/my_create/egurena2/ok/newtodovar4.php");
    //echo "ユーザ認証できました。{$_SESSION['username']}様のページです。<br>";
  }
  else{
    echo "ユーザ認証に失敗しました。もう一度入力しなおしてください。";
  } 
  mysqli_close($conn);
 }
 ?>

</body>
</html>
</body>
</html>