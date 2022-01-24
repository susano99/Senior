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


if(isset($_GET['coursecode']) && isset($_GET['language'])){
	$coursecode=$_GET['coursecode'];
	$language=$_GET['language'];
	$query = "select code,language from courses";
	$stmt = $db->prepare($query);
	$stmt->execute();
	$res = $stmt->get_result();
	while($row=$res->fetch_assoc()){
		if($row['code']==$coursecode && $row['language']==$language){
			$found=1;
			break;
		}
	}
	if($found==1){
		$query1 = "select courseid,code,language from courses where code = ? and language = ?";
		$stmt1 = $db->prepare($query1);
		$stmt1->bind_param('ss',$coursecode,$language);
		if(!$stmt1)
			echo $db->errno." ".$db->error;
		$stmt1->execute();
		$res1 = $stmt1->get_result();
		$row1=$res1->fetch_assoc();
		$course=$row1['courseid'];
		
		$query = 'select code,language,name,iselective,semester,fullhours,coursehours,tphours,tdhours,nbofcredits,department,description,isarchived from courses where courseid='.$course;
		$stmt = $db->prepare($query);
		$stmt->execute();
		$res = $stmt->get_result();
	}
	else{
		echo "Course not found";
		exit;
	}
}

if(isset($_POST['submit'])){
	if(isset($_GET['coursecode']) && isset($_GET['language'])){
		$code = $_POST['code'];
		$lang = $_POST['Language'];
		$name = $_POST['name'];
		$sem = $_POST['semester'];
		$fullhrs = $_POST['fullhours'];
		$crshrs = $_POST['coursehours'];
		$tphrs = $_POST['tphours'];
		$tdhrs = $_POST['tdhours'];
		$credits = $_POST['nbofcredits'];
		$department = $_POST['Department'];
		$desc = $_POST['Description'];
		if(isset($_POST['checkbox'])){
			$elective=1;
			}
		else{
			$elective=0;
		}
		if(isset($_POST['archived'])){
			$archived=1;
			}
		else{
			$archived=0;
		}
		if($lang!="English" && $lang!="French"){
			echo "Wrong language";
			exit;
		}
		$query1="update `courses` SET `code`= '$code',`language`= '$lang',`name`= '$name',`iselective`= '$elective',`semester`= '$sem',`fullhours`= '$fullhrs',`coursehours`= '$crshrs',`tphours`= '$tphrs',`tdhours`= '$tdhrs',`nbofcredits`= '$credits',`department`= '$department',`description`= '$desc', `isarchived`= '$archived' where courseid= '$course'";
		$stmt1 = $db->prepare($query1);
		if($stmt1->execute())
			$updated = "Course Updated";
		else
			$failed = "Update Failed";
			
	}
	else{
		$query = "insert into courses (code,language,name,iselective,semester,fullhours,coursehours,tphours,tdhours,nbofcredits,department,description,isarchived) values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt = $db->prepare($query);
		$stmt->bind_param('sssisiiiiissi',$code,$lang,$name,$elective,$sem,$fullhrs,$crshrs,$tphrs,$tdhrs,$credits,$department,$desc,$archived);
		
		$query1="select code,language from courses";
		$stmt1 = $db->prepare($query1);
		$stmt1->execute();
		$res1 = $stmt1->get_result();

		$code = $_POST['code'];
		$lang = $_POST['Language'];
		$name = $_POST['name'];
		$sem = $_POST['semester'];
		$fullhrs = $_POST['fullhours'];
		$crshrs = $_POST['coursehours'];
		$tphrs = $_POST['tphours'];
		$tdhrs = $_POST['tdhours'];
		$credits = $_POST['nbofcredits'];
		$department = $_POST['Department'];
		$desc = $_POST['Description'];
		if(isset($_POST['checkbox'])){
			$elective=1;
			}
		else{
			$elective=0;
		}
		if(isset($_POST['archived'])){
			$archived=1;
			}
		else{
			$archived=0;
		}
		while($row1=$res1->fetch_assoc()){
			if($row1['code']==$code && $row1['language']==$lang){
				echo "Course exists";
				exit;
			}
		}
		if($lang!="English" && $lang!="French"){
			echo "Wrong language";
			exit;
		}
		if($stmt->execute())
			$added = "Course added";
		else
			$failed2 = "Add failed";
	}
}
$querydoc = "select doctorid,firstname,lastname,isarchived from doctors";
$stmtdoc = $db->prepare($querydoc);
$stmtdoc->execute();
$resdoc = $stmtdoc->get_result();
?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Add Course</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200&display=swap');
	</style>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="style1.css">

	</head>
	<body class="img js-fullheight" style="overflow:hidden; background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url(images/12.jpg); font-family: 'Poppins', sans-serif; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  margin-top: -50px;">
  <header>
	<div class="wrapper">
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
		      	<h3 class="mb-4 text-center">Add a Course</h3>
		      	<form action="" class="signin-form" method="post">
				<?php 
					if(isset($res) && $res->num_rows>0){
						while($row=$res->fetch_assoc()){
				?>
				<div class="d-flex justify-content-center">
				<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="code" placeholder="Course Code" value="<?php echo $row['code']; ?>" required>
						</div>
						<div class="form-group">
							<input list="languages" type="text" class="form-control" name="Language" placeholder="Langauge" value="<?php echo $row['language']; ?>" required readonly>
							<datalist id="languages">
								<option value="English">
								<option value="French">
							</datalist>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="Name" value="<?php echo $row['name']; ?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Description" placeholder="Description" value="<?php echo $row['description']; ?>" required>
						</div>
						<div class="w-50">
						<label class="checkbox-wrap checkbox-primary" >Is Elective
							<input type="checkbox" name="checkbox" <?php if($row['iselective']==1) echo "checked"; ?>>
								<span class="checkmark"></span>
						</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="semester" placeholder="Semester" value="<?php echo $row['semester']; ?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="fullhours" placeholder="Full Hours" value="<?php echo $row['fullhours']; ?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="coursehours" placeholder="Course Hours" value="<?php echo $row['coursehours']; ?>" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="tphours" placeholder="TP Hours" value="<?php echo $row['tphours']; ?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="tdhours" placeholder="TD Hours" value="<?php echo $row['tdhours']; ?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="nbofcredits" placeholder="Nb Of Credits" value="<?php echo $row['nbofcredits']; ?>" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Department" placeholder="Department" value="<?php echo $row['department']; ?>" required>
						</div>
						
						<div class="form-group ">
					<div class="w-50">
						<label class="checkbox-wrap checkbox-primary" >Is Archived
							<input type="checkbox" name="archived" <?php if($row['isarchived']==1) echo "checked"; ?>>
								<span class="checkmark"></span>
						</label>
					</div>			
					</div>
					</div>
				</div>
				<?php
						}
					}
					else{
				?>
				<div class="d-flex justify-content-center">
				<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="code" placeholder="Course Code" required>
						</div>
						<div class="form-group">
							<input list="languages" type="text" class="form-control" name="Language" placeholder="Langauge" required>
							<datalist id="languages">
								<option value="English">
								<option value="French">
							</datalist>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="name" placeholder="Name" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="Department" placeholder="Department" required>
						</div>
						<div class="w-50">
							<label class="checkbox-wrap checkbox-primary" >Is Elective
								<input type="checkbox" name="checkbox">
									<span class="checkmark"></span>
							</label>
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="semester" placeholder="Semester" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="fullhours" placeholder="Full Hours" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="coursehours" placeholder="Course Hours" required>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<input type="text" class="form-control" name="tphours" placeholder="TP Hours" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="tdhours" placeholder="TD Hours" required>
						</div>
						<div class="form-group">
							<input type="text" class="form-control" name="nbofcredits" placeholder="Nb Of Credits" required>
						</div>
						
						<div class="form-group">
							<input type="text" class="form-control" name="Description" placeholder="Description" required>
						</div>
						<div class="form-group ">
						<div class="w-50">
						<label class="checkbox-wrap checkbox-primary" >Is Archived
							<input type="checkbox" name="archived">
								<span class="checkmark"></span>
						</label>
						</div>			
						</div>
					</div>
				</div>
				<?php
				}
				?>
				<div class="form-group">
					<h3>
						<?php if(isset($updated)) echo $updated;
							if(isset($failed)) echo $failed;
						?>
					</h3>
	            </div>
				<div class="form-group">
					<h3>
						<?php if(isset($added)) echo $added;
							if(isset($failed2)) echo $failed2;
						?>
					</h3>
	            </div>
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
				while($rowdoc=$resdoc->fetch_assoc()){
				?>
				<option value="<?php echo $rowdoc['doctorid']; ?>"><?php echo $rowdoc['firstname']; ?> <?php echo $rowdoc['lastname']; ?>
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
	</header>

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
	</body>
	
</html>

