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

$found=0;
if(isset($_GET['doctorid'])){
	$doctor=$_GET['doctorid'];
	$query = 'select doctorid from doctors';
	$stmt = $db->prepare($query);
	$stmt->execute();
	$res = $stmt->get_result();
	while($row=$res->fetch_assoc()){
		if($row['doctorid']==$doctor){
			$found=1;
			break;
		}
	}
	if($found==1){
		$query = 'select firstname,fathername,lastname,phone,email,acadegree,contracttype,isarchived from doctors where doctorid='.$doctor;
		$stmt = $db->prepare($query);
		$stmt->execute();
		$res = $stmt->get_result();
	}
	else{
		echo "Doctor not found";
		exit;
	}
}

if(isset($_POST['submit'])){
	if(isset($_GET['doctorid'])){
		$firstn = $_POST['Firstname'];
		$middlen = $_POST['Middlename'];
		$lastn = $_POST['Lastname'];
		$phone = $_POST['Phone'];
		$email = $_POST['Email'];
		$acadegree = $_POST['Academicdegree'];
		$contract = $_POST['Contract'];
		if(isset($_POST['archived'])){
			$archived=1;
			}
		else{
			$archived=0;
		}
		$query1="update `doctors` SET `firstname`= '$firstn',`fathername`= '$middlen',`lastname`= '$lastn',`phone`= '$phone',`email`= '$email',`acadegree`= '$acadegree',`contracttype`= '$contract',`isarchived`='$archived' where doctorid= '$doctor'";
		$stmt1 = $db->prepare($query1);
		if($stmt1->execute())
			$updated = "Doctor Updated";
		else
			$failed = "Update Failed";
			
	}
	else{
		$query = "insert into doctors values(?,?,?,?,?,?,?,?,?)";
		$stmt = $db->prepare($query);
		$stmt->bind_param('isssisssi',$docid,$firstn,$middlen ,$lastn,$phone,$email,$acadegree,$contract,$archived);

		$docid = $_POST['DoctorId'];
		$firstn = $_POST['Firstname'];
		$middlen = $_POST['Middlename'];
		$lastn = $_POST['Lastname'];
		$phone = $_POST['Phone'];
		$email = $_POST['Email'];
		$acadegree = $_POST['Academicdegree'];
		$contract = $_POST['Contract'];
		if(isset($_POST['archived'])){
			$archived=1;
			}
		else{
			$archived=0;
		}
		if($stmt->execute())
			$added = "doctor added";
		else
			$failed2 = "Add failed";
	}
}
?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Add Doctor</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">

	<<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="style1.css">
	</head>
	<body style="background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url(images/12.jpg); font-family: poppins; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  margin-top: -70px;">
  <header>
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

				<li>
					<div class="dropdown">
				<button style="font-family: arial; font-weight: normal;" onclick="myFunctions()" class="dropbtn">Courses</button>
				<div id="myDropdownn" class="dropdown-content">
					<a href="addcourse.php">Add</a>
					<a><button  style="color: white; font-weight: normal; font-family: arial; font-size: 16px;"class="slider">Update</button></a>
					<a href="Delete.php">Delete</a>
				</div>
				</div></li>

				<li><a href="distribution.php">Distribution</a></li>
				<li>
					<a href="signin.php?bb=1">Log Out</a>	
				</li>
			</ul>
		</div>
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<h3 class="mb-4 text-center">Add a Doctor</h3>
		      	<form action="" class="signin-form" method="post">
				<?php 
					if(isset($res) && $res->num_rows>0){
						while($row=$res->fetch_assoc()){
				?>
				<div class="d-flex justify-content-center">
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="DoctorId" placeholder="Doctor Id" value="<?php if(isset($doctor)) echo $doctor; ?>" required>
						</div>
					   <div class="form-group">
								<input type="text" class="form-control" name="Firstname" placeholder="First name" value="<?php if(isset($row['firstname'])) echo $row['firstname'];?>"
								required>
							</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Middlename" placeholder="Middle name" value="<?php if(isset($row['fathername'])) echo $row['fathername'];?>"
							required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Lastname" placeholder="Last name" value="<?php if(isset($row['lastname'])) echo $row['lastname'];?>"
							required>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="Phone" placeholder="phone" value="<?php if(isset($row['phone'])) echo $row['phone'];?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Email" placeholder="Email" value="<?php if(isset($row['email'])) echo $row['email'];?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Academicdegree" placeholder="Academic Degree" value="<?php if(isset($row['acadegree'])) echo $row['acadegree'];?>"
							required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Contract" placeholder="Contract Type" value="<?php if(isset($row['contracttype'])) echo $row['contracttype'];?>"
							required>
						</div>
					</div>
					
				</div>
				<div class="form-group ">
					<div class="w-50">
						<label class="checkbox-wrap checkbox-primary" >Is Archived
							<input type="checkbox" name="archived" <?php if($row['isarchived']==1) echo "checked"; ?>>
								<span class="checkmark"></span>
						</label>
					</div>			
				</div>
				<center>
				<div class="form-group">
					<h3>
						<?php if(isset($updated)) echo $updated;
							if(isset($failed)) echo $failed;
						?>
					</h3>
	            </div>
				</center>
				<?php
						}
					}
					else {
				?>
				<div class="d-flex justify-content-center">
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="DoctorId" placeholder="Doctor Id" required>
						</div>
					   <div class="form-group">
								<input type="text" class="form-control" name="Firstname" placeholder="First name" required>
							</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Middlename" placeholder="Middle name" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Lastname" placeholder="Last name" required>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<input type="text" class="form-control" name="Phone" placeholder="phone" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Email" placeholder="Email" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Academicdegree" placeholder="Academic Degree" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Contract" placeholder="Contract Type" required>
						</div>
						
					</div>
				</div>
				<div class="form-group ">
						<div class="w-50">
						<label class="checkbox-wrap checkbox-primary" >Is Archived
							<input type="checkbox" name="archived">
								<span class="checkmark"></span>
						</label>
						</div>			
					</div>
				<center>
				<div class="form-group">
					<h3>
						<?php if(isset($added)) echo $added;
							if(isset($failed2)) echo $failed2;
						?>
					</h3>
	            </div>
				</center>
				<?php
					}
				?>
	            <div class="form-group">
	            	<input type="submit" name="submit" class="form-control btn btn-primary submit px-3" value="Save">
	            </div>
	          </form>
		      </div>
				</div>
			</div>
		</div>
	
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
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
  <script src="js/myjs.js"></script>
	<script>
		if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
		}
	</script>
	
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
</header>
	</body>
	
</html>

