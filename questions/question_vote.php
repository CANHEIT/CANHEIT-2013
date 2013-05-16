<?php

// Include our database and other necessary global configuration
include("config.php");

// Get the IP address of the voting user.
$ip = $_SERVER['REMOTE_ADDR']; 
$id = $_POST['id'];

if ($id) {
	// Verify if the IP address of the voter is in the voting table for that particular question
	$query_ip = "SELECT ip_add FROM voting_ip WHERE q_id_fk = '$id' and ip_add = '$ip'";

	if ($result = $mysqli->query($query_ip)) {
		if ($result->num_rows > 0) {
			$count = 1;
		}
	}
}	

if ($_POST['vote'] == 'up') {
	$vote = "+";
} elseif ($_POST['vote'] == 'down') {
	$vote = "-";
}

if ($count == 0) {
	$query_updatescore = "UPDATE questions SET score=(score $vote 1) WHERE id=$id";
	$query_insertip = "INSERT INTO voting_ip (q_id_fk,ip_add) VALUES ('$id','$ip')";
	
	$mysqli->query($query_updatescore);
	$mysqli->query($query_insertip);
} else {
	echo "<script>alert('You have already up/down voted this question.');</script>";
}

// Close the connection
$mysqli->close;


?>
