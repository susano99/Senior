<?php
function connect() {
	$db = new mysqli('localhost','root','10101999','distribution');
	if($db->connect_error){
		echo "error connection";
		exit;
	}
	return $db;
}
?>