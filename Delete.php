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
$found1=0;

if(isset($_POST['submitdoctor'])){
	$docid = $_POST['DcId'];
	$queryd = "select doctorid from doctors";
    $stmtd = $db->prepare($queryd);
    $stmtd->execute();
	$resd = $stmtd->get_result();
	if($resd->num_rows==0){
		$fail= "doctor not found";
	}
		
	while($rowd=$resd->fetch_assoc()){
		if($docid == $rowd['doctorid']){
			if(!isset($_POST['permanent'])){
				$query = "update `doctors` set `isarchived`=1 where doctorid=$docid";
				$stmt = $db->prepare($query);
				$stmt->execute();
				$found1=1;
				$success = "Doctor archived";
				break;
			}
			else{
				$querydoc = "select doctorid from registration";
				$stmtdoc = $db->prepare($querydoc);
				$stmtdoc->execute();
				$resdoc = $stmtdoc->get_result();
				while($rowdoc=$resdoc->fetch_assoc()){
					if($docid==$rowdoc['doctorid']){
						$query = "delete from registration where doctorid= $docid ";
						$stmt = $db->prepare($query);
						$stmt->execute();
						break;
					}
				}
				$query = "delete from doctors where doctorid= $docid ";
				$stmt = $db->prepare($query);
				$stmt->execute();
				$success = "Doctor deleted";
				$found1=1;
				break;
			}
		}
	}
	if($found1==0)
		$fail="Doctor not found";
}
if(isset($_POST['submitcourse'])){
	$crscode = $_POST['Crscode'];
	$language = $_POST['language'];
	if($language!="English" && $language!="French"){
			echo "Wrong language";
			exit;
	}
	$query = "select code,language from courses";
	$stmt = $db->prepare($query);
	$stmt->execute();
	$res = $stmt->get_result();
	while($row=$res->fetch_assoc()){
		if($row['code']==$crscode && $row['language']==$language){
			$found=1;
			break;
		}
	}
	if($found==1){
		$query1 = "select courseid,code,language from courses where code = ? and language = ?";
		$stmt1 = $db->prepare($query1);
		$stmt1->bind_param('ss',$crscode,$language);
		if(!$stmt1)
			echo $db->errno." ".$db->error;
		$stmt1->execute();
		$res1 = $stmt1->get_result();
		$row1=$res1->fetch_assoc();
		$crsid=$row1['courseid'];
		$queryc = "select courseid from courses";
		$stmtc = $db->prepare($queryc);
		$stmtc->execute();
		$resc = $stmtc->get_result();
		if($resc->num_rows==0){
			$failc = "course not found";
		}

		while($rowc=$resc->fetch_assoc()){
			if($crsid == $rowc['courseid']){
				if(!isset($_POST['permanent'])){
					$query = "update `courses` set `isarchived`=1 where courseid=$crsid";
					$stmt = $db->prepare($query);
					$stmt->execute();
					$found=1;
					$successc = "Course archived";
					break;
				}
				else{
					$querycor = "select courseid from registration";
					$stmtcor = $db->prepare($querycor);
					$stmtcor->execute();
					$rescor = $stmtcor->get_result();
					while($rowcor=$rescor->fetch_assoc()){
						if($crsid==$rowcor['courseid']){
							$query = "delete from registration where courseid= $crsid ";
							$stmt = $db->prepare($query);
							$stmt->execute();
							break;
						}
					}
					$query = "delete from courses where courseid=? ";
					$stmt = $db->prepare($query);
					$stmt->bind_param('s',$crsid);
					$stmt->execute();
					$successc = "Course deleted";
					$found=1;
					break;
				}
			}
		}
	}
	if($found==0)
		$failc="Course not found";
}

$querydoc = "select doctorid,firstname,lastname from doctors";
$stmtdoc = $db->prepare($querydoc);
$stmtdoc->execute();
$resdoc = $stmtdoc->get_result();

$querycor= "Select distinct code,name from courses";
$stmtcor = $db->prepare($querycor);
$stmtcor->execute();
$rescor = $stmtcor->get_result();

?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Deletion</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="style1.css">
	</head>
	<body class="img js-fullheight" style=" font-family: poppins;">
	<header style="background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url(images/12.jpg); font-family: poppins; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  margin-top: -50px;
  height: 107.6vh;">
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
		      	<h3 color="white" >Delete Doctor / Course</h3><br><br>
		      	<form action="Delete.php" class="signin-form" method="Post">
			     <div class="d-flex" >
				<div class="col-md-10" id="move" >				
				<h6> &nbsp &nbsp Enter Doctor Id to be deleted</h6><br>
		      		<div class="form-group">
		      			<input list="doctors" type="text" class="form-control" name="DcId" placeholder="Doctor Id" required>
						<datalist id="doctors">
							<?php 
							while($rowdoc=$resdoc->fetch_assoc()){
							?>
							<option value="<?php echo $rowdoc['doctorid']; ?>"><?php echo $rowdoc['firstname']; ?> <?php echo $rowdoc['lastname']; ?>
							<?php
							}
							?>
						</datalist>
		      		</div>
					<div class="form-group ">
						<div class="w-100">
							<label class="checkbox-wrap checkbox-primary" >Permanent delete?
								<input type="checkbox" name="permanent">
									<span class="checkmark"></span>
							</label>
						</div>			
					</div>
	                <div class="form-group">
						<input type="submit" name="submitdoctor" class="form-control btn btn-primary submit px-3" value="Delete Doctor">
	                </div>
					</form>
					<center>
					<div class="form-group">
						<h3>
							<?php if(isset($fail)) echo $fail;
								  if(isset($success)) echo $success;
							?>
						</h3>
	                </div>
					</center>
				</div>
				<div class = "vertical"></div>
				<div class="col-md-10" id="move2">			  
				<h6> &nbsp &nbsp Enter Course Id to be deleted</h6><br>
				<form action="Delete.php" class="signin-form" method="Post">
					<div class="form-group">
		      			<input list="courses" type="text" class="form-control" name="Crscode" placeholder="Course Code" required>
						<datalist id="courses">
							<?php 
							while($rowcor=$rescor->fetch_assoc()){
							?>
							<option value="<?php echo $rowcor['code']; ?>"><?php echo $rowcor['name']; ?>
							<?php
							}
							?>
						</datalist>
		      		</div>
					<div class="form-group" >
						<input list="languages" type="text" class="form-control" id="language" name="language" placeholder="Language" required>
							<datalist id="languages">
								<option value="English">
								<option value="French">
							</datalist>
					</div>
					<div class="form-group ">
						<div class="w-100">
							<label class="checkbox-wrap checkbox-primary" >Permanent delete?
								<input type="checkbox" name="permanent">
									<span class="checkmark"></span>
							</label>
						</div>			
					</div>
					<div class="form-group">
						<input type="submit" name="submitcourse" class="form-control btn btn-primary submit px-3" value="Delete Course">
	                </div>
				</form>
					<center>
					<div class="form-group">
						<h3>
							<?php if(isset($failc)) echo $failc;
								  if(isset($successc)) echo $successc;
							?>
						</h3>
	                </div>
					</center>
				</div>
			
					
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