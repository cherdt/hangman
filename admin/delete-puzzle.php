<?php
//include any libraries you want to use here.
require '../db.php';

// Check to see that we received the expected, non-NULL, inputs
if ($_GET['method'] == 'delete' && is_numeric($_GET['id']) ) {
    $id = $_GET['id'];
    $msg = "Entry deleted.";

    // Set up DB connection
    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Prepare statement
    $stmt = $mysqli->prepare(DELETE FROM puzzle WHERE id = ?");
    $stmt->bind_param('i', $id);

    // Run DELETE query
    $result = $stmt->execute();

    $stmt->close();
    $mysqli->close();
}

//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
echo "<result><id>".$id."</id><message>".$msg."</message></result>";
?>
