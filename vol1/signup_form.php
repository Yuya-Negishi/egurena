<?php 
session_start();
//var_dump($_SESSION['profile']);
//var_dump($_SESSION['picture']);
$picture = $_SESSION['picture'];
$profile = $_SESSION['profile'];
$facebookId=$profile['id'];

if($_SESSION['profile'] && $_SESSION['picture'] === ''){
	header('Location:login.php');
}
//var_dump($profile['name']);
require('functions.php');
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
<?php
$db = new db;
$dbh = $db -> connectDb();

function setToken(){
	$token = sha1(uniqid(mt_rand(),true));
	$_SESSION['token'] = $token;
}

function checkToken(){
	if(empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
		echo "不正なpostが行われました";
		exit;
	}
}

function emailExists($email,$dbh){
	$sql ="select * from users where email = :email limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":email" => $email));
	$user = $stmt->fetch();
	return $user ? true : false;
}



function facebookIdExists($facebookId,$dbh){
	$sql ="select * from users where facebookId = :facebookId limit 1";
	$stmt = $dbh->prepare($sql);
	$stmt->execute(array(":facebookId" => $facebookId));
	$user = $stmt->fetch();
	return $user ? true : false;
}


	
$err[] = '';

if($_SERVER['REQUEST_METHOD'] != 'POST') {
	//CSRF対策　
	setToken();
}else{
	checkToken();
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	
	$db = new db;
	$db ->connectDb();
	
	$err = array();
	
	//名前が空
	if($name ==''){
		$err['name'] = '名前を入力してください';
	}

	//メールアドレス空
	if($email ==''){
		$err['email'] = 'メールアドレスを入力してください';
	}
	
	//メールアドレス正しい　
	if (filter_var($email, FILTER_VALIDATE_EMAIL === false)){
		$err['email'] = 'メールアドレスの形式が正しくない';
	}
	
	if(emailExists($email,$dbh)){
		$err['email'] = 'このメールアドレスは既に登録されています';
	}

	if(facebookIdExists($facebookId,$dbh)){
		$err['facebookId'] = 'このfacebookですでに登録されています';
	}


	
	
	if(empty($err)){
		//登録処理
		$sql = "insert into users(username,email,facebookId,picture)
				values
				(:name,:email,:facebookId,:picture)";
				
		$stmt = $dbh -> prepare($sql);
		$params = array(
			":name" => $name,
			":email" => $email,
			":facebookId"=>$profile['id'],
			":picture" =>$picture
		);

		$stmt->execute($params);
		header('Location: http://localhost/my_create/egurena2/ok/netodovar4.php');
	}
}
?>

<div class="container">
	<div class="row">
		<div class="col-sm-6 title" >
			<p id="h1v2">egurena</p>

		</div>
		<div class="col-sm-6">
			<div id="card2">
			<!--<p id="h3">h1</p>-->
				<?php
					echo '<form method="post" action="">';
				    echo'<p class="h2">sign up</p></a>';
				?>
					<input type="hidden" name="token" value="<?php echo h($_SESSION['token']); ?>">
				    <input type="text" class="form-control" name="name" value="<?php echo h($profile['name']) ; ?>">
				<?php
				    echo '<input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email">';
				    echo $picture.'<br>';
				    echo '<button type="submit" class="btn btn-default">Sign Up</button>';
				    echo '</form>';
				?>

				<p class="h5"><?php if(isset($err['email'])){echo $err['email'] ;}  ?> </p>
			    <p class="h5"><?php if(isset($err['facebookId'])){echo $err['facebookId'] ;} ?> </p>
			</div>
		</div>	
	</div>
</div>
</body>




