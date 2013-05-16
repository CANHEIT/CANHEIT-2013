<?php

// Include our database and other necessary global configuration
include("config.php");

// Our SQL query for inserting questions
$query = "INSERT INTO questions (sessionid, question) VALUES ('$_POST[sessionid]','$_POST[question]')";

// Insert the question into canheit_questions.questions table
$mysqli->query($query);

// Close the connection
$mysqli->close;

?>