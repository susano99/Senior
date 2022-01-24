<?php
session_start();
if(isset($_SESSION['usr']) || isset($_COOKIE['usr'])){
	header("Location: homepage.php");
}

require('functions.php');
$db = connect();
$failed = "";
$query = "select username,password from users";
$stmt = $db->prepare($query);
$stmt->execute();
$res = $stmt->get_result();
if($res->num_rows==0)
	exit;
if(isset($_GET['bb']) && $_GET['bb']==1){
	session_destroy();
	unset($_COOKIE['usr']); 
    setcookie('usr', null, -1);
}
if(isset($_POST['submit'])){
	$usrname = $_POST['usrname'];
	$pass = $_POST['pass'];
	while($row=$res->fetch_assoc()){
		if($usrname == $row['username'] && $pass == $row['password']){
			if(isset($_POST['remember']))
				setcookie("usr",$usrname,time() + (10 * 365 * 24 * 60 * 60));
			else
				$_SESSION['usr'] = $usrname;
			header("Location: homepage.php");
			exit;
		}
		else{
			$failed = "user not found";
			echo $failed;
		}
	}
}
?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Lebanese University</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="ourstyle.css">
	</head>
	<body class="img js-fullheight" style="overflow:hidden; background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url(images/12.jpg); font-family: poppins">
	<header >
	
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				
					<h1 class="heading-section" >Lebanese University - Faculty Of Sciences</h1>
				
			</div>
			<br><br>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
						<h3 class="mb-4 text-center">Login</h3>
						<form action="signin.php" class="signin-form" method="post">
							<div class="form-group">
								<input type="text" class="form-control" name="usrname" placeholder="Username" required>
							</div>
							<div class="form-group">
								<input id="password-field" type="password" class="form-control" name="pass" placeholder="Password" required>
								<span toggle="#password-field" onclick="myFunction()" class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>
							<div class="form-group">
								<input type="submit" name="submit" class="form-control btn btn-primary submit px-3" value="Sign In">
							</div>
							<div class="form-group d-md-flex">
								<div class="w-50">
								<label class="checkbox-wrap checkbox-primary" >Remember Me
									<input type="checkbox" name="remember" >
									<span class="checkmark"></span>
								</label>
								</div>		
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</header>
	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
	<script>
		function myFunction() {
			var x = document.getElementById("password-field");
			if (x.type === "password") {
			x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>
	</body>
</html>

