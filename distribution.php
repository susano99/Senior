<?php
session_start();
if(isset($_SESSION['usr']) || isset($_COOKIE['usr'])){
}
else
	header("Location: signin.php");

require('functions.php');
$db = connect();
$query = "select code,courseid,language,semester from courses where isarchived=0 order by semester,code";
$stmt = $db->prepare($query);
$stmt->execute();
$res = $stmt->get_result();

$queryd = "select doctorid,firstname,lastname from doctors where isarchived=0";
$stmtd = $db->prepare($queryd);
$stmtd->execute();
$resd = $stmtd->get_result();

$date = date("Y");
$datenext = $date+1;

$queryadd = "insert into registration (doctorid,courseid,crshrs,tphrs,tdhrs,year) values(?,?,?,?,?,?)";
$stmtadd = $db->prepare($queryadd);
$stmtadd->bind_param('iiiiis',$drid,$crsid,$crshrs,$tp,$td,$year);

$queryc= "Select distinct code,name from courses";
$stmtc = $db->prepare($queryc);
$stmtc->execute();
$resc = $stmtc->get_result();

if(isset($_POST['submitdoctor'])){
	$drid = $_POST['doctor'];
	$crsid = $_POST['courseid'];
	$year = $_POST['year'];
	$crshrs = $_POST['crshrs'];
	$tp = $_POST['tphrs'];
	$td = $_POST['tdhrs'];
	$stmtadd->execute();
	echo "Registered";
}

$querydoc = "select doctorid,firstname,lastname,isarchived from doctors";
$stmtdoc = $db->prepare($querydoc);
$stmtdoc->execute();
$resdoc = $stmtdoc->get_result();

$querycor= "Select courseid,code,name,language from courses";
$stmtcor = $db->prepare($querycor);
$stmtcor->execute();
$rescor = $stmtcor->get_result();

if(isset($_POST['submitdelete'])){
	$regid = $_POST['regid'];
	$query = "delete from registration where id=$regid";
	$stmt = $db->prepare($query);
	$stmt->execute();
}

if(isset($_POST['submitupdate'])){
	$regid = $_POST['regid'];
	$doctor = $_POST['doctor'];
	$year = $_POST['year'];
	$crshrs = $_POST['crshrs'];
	$tp = $_POST['tphrs'];
	$td = $_POST['tdhrs'];
	$query = "update `registration` SET `doctorid`=?,`crshrs`=?,`tphrs`=?,`tdhrs`=?,`year`=? where id=?";
	$stmt = $db->prepare($query);
	$stmt->bind_param('iiiisi',$doctor,$crshrs,$tp,$td,$year,$regid);
	$stmt->execute();
}
?>

<!doctype html>
<html lang="en">
  <head>
  	<title>Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
    <link rel="stylesheet" href="style1.css">
	</head>
	<body class="img js-fullheight hm-gradient" style="background-image:linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url(images/12.jpg); font-family: times new roman; -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  margin-top: -50px;">
	<div class="container">
	<div class="row">
	<div class="wrapper">
		<br>
			<ul class="nav-area">
				<li><div class="dropdown">
				<button style="font-weight: normal;" onclick="myFunction()" class="dropbtn">Doctors</button>
				<div id="myDropdown" class="dropdown-content">
					<a href="adddoctor.php">Add</a>
					<a ><button style="color: white; font-family: times new roman; font-size: 16px; font-weight: none;" class="sliders">Update</button></a>
					<a href="Delete.php">Delete</a>
				</div>
				</div></li>

				<li>
					<div class="dropdown">
						<button style="font-weight: normal;" onclick="myFunctions()" class="dropbtn">Courses</button>
						<div id="myDropdownn" class="dropdown-content">
							<a href="addcourse.php">Add</a>
							<a><button style="color: white; font-family: times new roman; font-size: 16px; font-weight: none;" class="slider">Update</button></a>
							<a href="Delete.php">Delete</a>
						</div>
				</div></li>

				<li><a href="distribution.php">Distribution</a></li>
				<li>
					<a href="signin.php?bb=1">Log Out</a>	
				</li>
			</ul>
		</div>
		</div>
		</div>
		<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
						<h3 class="mb-4 text-center" style="color: white;">Registration</h3>
					</div>
				</div>
			</div>
		</div>
		</section>
		<div id="myModal1" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		  <span class="close" onclick="close4()">&times;</span>
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
	<div class="container">
		<div class="row">
			<table>
			  <thead>
				<tr>
				  <th scope="col">Course Code</th>
				  <th scope="col">Language</th>
				  <th scope="col">Semester</th>
				  <th scope="col">Add Section</th>
				</tr>
			  </thead>

			  <?php
			  $k=0;
			  while($row=$res->fetch_assoc()){
				$i=0;
				$courseid=$row["courseid"];
				echo'<tr>';		
				echo '<td style="display:none;" class="nu">'.$row["courseid"].'</td>';				
				echo '<td class="nr">'.$row["code"].'</td>';
				echo '<td>'.$row["language"].'</td>';
				echo '<td>'.$row["semester"].'</td>';
				echo "<td><button style='color: black;' id='myBtn$k' class='use-address slide'>+</button></td></tr>";
				$queryput = "select id,doctorid,courseid,crshrs,tphrs,tdhrs,year from registration";
				$stmtput = $db->prepare($queryput);
				$stmtput->execute();
				$resput = $stmtput->get_result();
				if($resput->num_rows>0){
					while($rowput=$resput->fetch_assoc()){
						$querydoc = 'select firstname,lastname from doctors where doctorid='.$rowput["doctorid"];
						$stmtdoc = $db->prepare($querydoc);
						$stmtdoc->execute();
						$resdoc = $stmtdoc->get_result();
						$rowdoc=$resdoc->fetch_assoc();
						if($rowput['courseid']==$row["courseid"]){
							if($i==0){
								echo '<td colspan=4>';
								echo '<table>';
								echo '<tr>';
									echo '<th>Doctor ID</th>';
									echo '<th>First Name</th>';
									echo '<th>Last Name</th>';
									echo '<th>Course Hours</th>';
									echo '<th>TP Hours</th>';
									echo '<th>TD Hours</th>';
									echo '<th>Year</th>';
									echo '<th>Edit</th>';
									echo '<th>Delete</th>';
								echo '</tr>';
							}
							echo '<tr>';
								echo '<td style="display:none;" class="nu">'.$row["courseid"].'</td>';				
								echo '<td style="display:none;" class="nr">'.$row["code"].'</td>';
								echo '<td style="display:none;" class="regid">'.$rowput["id"].'</td>';
								echo '<td class="dc">'.$rowput["doctorid"].'</td>';
								echo '<td>'.$rowdoc["firstname"].'</td>';
								echo '<td>'.$rowdoc["lastname"].'</td>';
								echo '<td class="crs">'.$rowput['crshrs'].'</td>';
								echo '<td class="tp">'.$rowput["tphrs"].'</td>';
								echo '<td class="td">'.$rowput['tdhrs'].'</td>';
								echo '<td class="yr">'.$rowput['year'].'</td>';
								echo "<td><button  class='use-address edit'>Edit</button></td>";
								echo "<td><button class='use-address delete'>x</button></td></tr>";
							echo '</tr>';
							$i++;
						}
					}
					if($i>0)
						echo '</table></td>';
				}
				$k++;
			  }
			  ?>
			  </table>

			  <!-- Trigger/Open The Modal -->


			<!-- The Modal -->
			<div id="myModal" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		  <span class="close" onclick="close2()">&times;</span>
		  <div class="login-wrap p-0">
		  <form action="distribution.php" class="signin-form" method="post">
		   <div class="d-flex">
				<div class="col-md-6">
					<div class="form-group">
						<input id="getid1" type="hidden" class="form-control" name="courseid" placeholder="Course Id" required>
					</div>
					<div class="form-group">
						<input id="getid" type="text" class="form-control" name="coursecode" placeholder="Course Code" readonly required>
					</div>
					<div class="form-group">
						<input list="doctors" type="text" class="form-control" name="doctor" placeholder="Doctor Id" required>
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
						<input type="text" class="form-control" name="year" placeholder="Year" value="<?php echo "$date-$datenext"; ?>" required>
					</div>	
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<input type="text" class="form-control" name="crshrs" placeholder="Course Hours" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="tphrs" placeholder="TP Hours" required>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" name="tdhrs" placeholder="TD Hours" required>
					</div>
				</div>						
			</div>
			<div class="form-group">
				<input type="submit" name="submitdoctor" class="form-control btn board submit px-3" value="Add">
			</div>
			</div>
			</form>
			</div>
			</div>
			
			<!-- The Modal -->
			<div id="myModalali" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		  <span class="close" onclick="closeali()">&times;</span>
		  <div class="login-wrap p-0">
		  <form action="distribution.php" class="signin-form" method="post">
		   <div class="d-flex">
				<div class="col-md-6">
					<div class="form-group">
						<input id="getid3" type="hidden" class="form-control" name="regid" placeholder="Reg Id" required>
					</div>
					<div class="form-group">
						<input id="getid4" type="text" class="form-control" name="coursecode" placeholder="Course Code" readonly required>
					</div>
					<div class="form-group">
						<input id="getid5" list="doctors" type="text" class="form-control" name="doctor" placeholder="Doctor Id" required>
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
						<input id="getid6" type="text" class="form-control" name="year" placeholder="Year" required>
					</div>	
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<input id="getid7" type="text" class="form-control" name="crshrs" placeholder="Course Hours" required>
					</div>
					<div class="form-group">
						<input id="getid8" type="text" class="form-control" name="tphrs" placeholder="TP Hours" required>
					</div>
					<div class="form-group">
						<input id="getid9" type="text" class="form-control" name="tdhrs" placeholder="TD Hours" required>
					</div>
				</div>						
			</div>
			<div class="form-group">
				<input type="submit" name="submitupdate" class="form-control btn board submit px-3" value="Update">
			</div>
			</div>
			</form>
			</div>
			</div>
			
			<!-- The Modal -->
			<div id="myModalnorma" class="modal">

		  <!-- Modal content -->
		  <div class="modal-content">
		  <span class="close" onclick="closenorma()">&times;</span>
		  <div class="login-wrap p-0">
		  <form action="distribution.php" class="signin-form" method="post">
		   <div class="d-flex">
				<div class="col-md-6">
					<div class="form-group">
						<input id="getid10" type="hidden" class="form-control" name="regid" placeholder="Reg Id" required>
					</div>					
				</div>
			</div>
			<center><h4>Are you sure?</h4></center>
			<div class="form-group">
				<input type="submit" name="submitdelete" class="form-control btn board submit px-3" value="Delete">
			</div>
			</div>
			</form>
			</div>
			</div>
	</div>
	</div>

	<script src="js/jquery.min.js"></script>
	<script src="js/popper.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/myjs.js"></script>
	<script>
	$(".use-address").click(function() {
    var $row = $(this).closest("tr");    // Find the row
    var $text = $row.find(".nr").text(); // Find the text code
	var $id = $row.find(".nu").text(); //crsid
	var $dcid = $row.find(".dc").text();
	var $crshrs = $row.find(".crs").text();
	var $tp = $row.find(".tp").text();
	var $td = $row.find(".td").text();
	var $yr = $row.find(".yr").text();
	var $regid = $row.find(".regid").text();
    
    // Let's test it out
    $('#getid').val($text);
	$('#getid1').val($id);
	$('#getid3').val($regid);
	$('#getid4').val($text);
	$('#getid5').val($dcid);
	$('#getid7').val($crshrs);
	$('#getid8').val($tp);
	$('#getid9').val($td);
	$('#getid6').val($yr);
	$('#getid10').val($regid);
	});
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
	var modal = document.getElementById("myModal");
	window.onclick = function(event) {
	  if (event.target == modal1) {
		modal1.style.display = "none";
	  }
	  else{
		  if(event.target == modal2){
			  modal2.style.display = "none";
		  }
		  if(event.target == modal) {
		modal.style.display = "none";
		  }
	  }
	}
	</script>
	<script>
//var buttons = document.querySelectorAll('button');
	var buttons = document.getElementsByClassName("sliders");
	for (var i=0; i<buttons.length; ++i) {
	  buttons[i].addEventListener('click', clickFunc);
	}

	function clickFunc() {
	  modal1.style.display = "block";
	}

	function close4(){
		modal1.style.display = "none";
	}
			// Get the modal
	var modal1 = document.getElementById("myModal1");

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
<script>
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
	</script>
	<script>
//var buttons = document.querySelectorAll('button');
	var buttons = document.getElementsByClassName("edit");
	for (var i=0; i<buttons.length; ++i) {
	  buttons[i].addEventListener('click', clickFunc);
	}

	function clickFunc() {
	  modalali.style.display = "block";
	}

	function closeali(){
		modalali.style.display = "none";
	}
			// Get the modal
	var modalali = document.getElementById("myModalali");

</script>
<script>
//var buttons = document.querySelectorAll('button');
	var buttons = document.getElementsByClassName("delete");
	for (var i=0; i<buttons.length; ++i) {
	  buttons[i].addEventListener('click', clickFunc);
	}

	function clickFunc() {
	  modalnorma.style.display = "block";
	}

	function closenorma(){
		modalnorma.style.display = "none";
	}
			// Get the modal
	var modalnorma = document.getElementById("myModalnorma");

</script>
</body>
	
</html>

