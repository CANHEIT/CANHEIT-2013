<?php

// Include our database and other necessary global configuration
include("config.php");

// Sanitization
$sessionid = $_POST['sessionid'];
$question = urlencode($_POST['question']);

// Our SQL query for inserting questions
$query = "INSERT INTO questions (sessionid, question) VALUES ('$sessionid', '$question')";

// Insert the question into canheit_questions.questions table
$mysqli->query($query);

// Close the connection
$mysqli->close;

?>