<?php

// Include our database and other necessary global configuration
include("config.php");

// Double vote verification via cookie and Question ID
$id = $_POST['id']; // The Question ID
$cookie = $_COOKIE['canheit-question-'.$id]; // The cookie to check against

// Checking to see if the user up or down voted the question
if ($_POST['vote'] == 'up') {
	$vote = "+";
} elseif ($_POST['vote'] == 'down') {
	$vote = "-";
}

if ($cookie) {
	echo "<script>alert('You have already up/down voted this question.');</script>";
} else {
	$query_updatescore = "UPDATE questions SET score=(score $vote 1) WHERE id=$id";
 	$mysqli->query($query_updatescore);

	setcookie('canheit-question-'.$id,1,time() + (86400 * 7),"/",".uottawa.ca"); // Cookie expires in 7 days
}

$mysqli->close;

?>
