<html>
<head>
<meta charset="UTF-8">
<title>ログイン</title>
</head>

<body>
<?php 
session_start();
?>
<form method="post" action="">
ユーザー名<input type="text" name="username"><br />
パスワード<input type="password" name="password"><br />
<input type="hidden" name="ok" >
<input type="submit" value="login" >
</form>
<?php
	try{	
		$dsn = "mysql:host=localhost;dbname=dotinstall_todo_app";
		$username = "root";
		$password = "";
		$dbh = new PDO($dsn,$username,$password);
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
 if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    // mysqlへの接続
	
 
	$_SESSION['password'] = $_POST['password'];
	$_SESSION['username'] = $_POST['username'];
	$password = $_POST['password'];
	$username = $_POST['username'];
	var_dump($password);
	var_dump($username);
  
 if($_POST['ok']){ 
$sql = "select * from users where username='$username' AND password='$password' limit 1 ";
$stmt = $dbh->prepare($sql);
$user = $stmt->fetch();
return $user ? user : false;
 
if($user != 'false'){
	header('Location: http://localhost/my_create/egurena2/ok/newtodovar2.php');
} else{
echo "エラー";
} 

}
}
?>
<body>
</html>