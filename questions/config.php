<?php 

// Create connection
$mysqli = new mysqli("tractus.med.uolocal", "canheit", "canheit13!", "canheit_questions");

// Check connection
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

?>