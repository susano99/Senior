<?php
session_start();
if(isset($_SESSION['usr']) || isset($_COOKIE['usr'])){
}
else
	header("Location: signin.php");

require('functions.php');
$db = connect();
$queryd = "select doctorid,firstname,lastname from doctors";
$stmtd = $db->prepare($queryd);
$stmtd->execute();
$resd = $stmtd->get_result();

$queryc= "Select distinct code,name from courses";
$stmtc = $db->prepare($queryc);
$stmtc->execute();
$resc = $stmtc->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
   <title>Homepage</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
	
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	    <link rel="stylesheet" href="style1.css">

	<script src="js/myjs.js"></script>
	
</head>
<body style="background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url(images/12.jpg); font-family: poppins; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  margin-top: -50px;">
  <div class="container">
	<div class="row ">
		<div class="wrapper">
		<br>
			<ul class="nav-area">
				<li><div class="dropdown">
				<button style="font-family: arial; font-weight: normal;" onclick="myFunction()" class="dropbtn">Doctors</button>
				<div id="myDropdown" class="dropdown-content">
					<a href="adddoctor.php">Add</a>
					<a ><button style="color: white; font-family: arial; font-size: 16px; font-weight: normal;" class="slide">Update</button></a>
					<a href="Delete.php">Delete</a>
				</div>
				</div></li>

				<li><div class="dropdown">
				<button style="font-family: arial; font-weight: normal;" onclick="myFunctions()" class="dropbtn">Courses</button>
				<div id="myDropdownn" class="dropdown-content">
					<a href="addcourse.php">Add</a>
					<a><button  style="color: white; font-weight: normal; font-family: arial; font-size: 16px;"class="slider">Update</button></a>
					<a href="Delete.php">Delete</a>
				</div>
				</div></li>

				<li><a href="distribution.php">Distribution</a></li>
				<li><a href="signin.php?bb=1">Log Out</a>	
				</li>
			</ul>
		</div>
		</div>
		</div>
		<center>
		<div class="welcome-text">
			<h1 style="font-family: poppins;">Welcome To <span><br>Lebanese University</span></h1>
		</div>
		</center>

<div id="myModal1" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		  <span class="close" onclick="close2()">&times;</span>
		  <div class="login-wrap p-0">
		  <form action="adddoctor.php" class="signin-form" method="get">
			<div class="form-group">
			<input style="color: black; border: 1px solid gray; width: 100%; padding: 5px 5px; border-radius: 25px;" list="doctors" type="text" id="doctorid" name="doctorid" placeholder="Doctor Id" required>
				<datalist id="doctors">
				<?php 
				while($rowd=$resd->fetch_assoc()){
				?>
				<option value="<?php echo $rowd['doctorid']; ?>"><?php echo $rowd['firstname']; ?> <?php echo $rowd['lastname']; ?>
				<?php
				}
				?>
				</datalist>
			</div>
			<div class="form-group">
				<input type="submit" class="form-control btn board submit px-3" value="Update">
			</div>
			</form>
	</div>
</div>
</div>
<div id="myModal2" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		  <span class="close" onclick="close3()">&times;</span>
		  <div class="login-wrap p-0">
		  				<form action="addcourse.php" class="signin-form" method="get">
				<div class="d-flex">
					<div class="form-group col-md-6">
						<input style="color: black; border: 1px solid gray; width: 100%; padding: 5px 5px; border-radius: 25px;" list="courses" type="text" id="coursecode" name="coursecode" placeholder="Course Code" required>
							<datalist id="courses">
							<?php 
							while($rowc=$resc->fetch_assoc()){
							?>
							<option value="<?php echo $rowc['code']; ?>"><?php echo $rowc['name'] ?>
							<?php
							}
							?>
							</datalist>
					</div>
					<div class="form-group col-md-6">
						<input style="color: black; border: 1px solid gray; width: 100%; padding: 5px 5px; border-radius: 25px;" list="languages" type="text" id="language" name="language" placeholder="Language" required>
							<datalist id="languages">
								<option value="English">
								<option value="French">
							</datalist>
					</div>
				</div>
					<div class="form-group">
						<input type="submit" class="form-control btn board submit px-3" value="Update">
					</div>
					</form>
	</div>
</div>
</div>


	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
    
<script>
//var buttons = document.querySelectorAll('button');
	var buttons = document.getElementsByClassName("slide");
	for (var i=0; i<buttons.length; ++i) {
	  buttons[i].addEventListener('click', clickFunc);
	}

	function clickFunc() {
	  modal.style.display = "block";
	}

	function close2(){
		modal.style.display = "none";
	}
			// Get the modal
	var modal = document.getElementById("myModal1");

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	  if (event.target == modal) {
		modal.style.display = "none";
	  }
	  else{
		  if(event.target == modal2){
			  modal2.style.display = "none";
		  }
	  }
	}
</script>
<script>
//var buttons = document.querySelectorAll('button');
	var buttons = document.getElementsByClassName("slider");
	for (var i=0; i<buttons.length; ++i) {
	  buttons[i].addEventListener('click', clickFunc);
	}

	function clickFunc() {
	  modal2.style.display = "block";
	}

	function close3(){
		modal2.style.display = "none";
	}
			// Get the modal
	var modal2 = document.getElementById("myModal2");

</script>
</body>
</html>
