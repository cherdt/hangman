<?php
//simulate a remote connection
sleep(2);
 
//include any libraries you want to use here.
include 'db.php';
 
//Set the content-type header to xml
header("Content-type: text/xml");
//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);

$status="failure";

// Verify that we've received the expected data
if ( $_GET['id'] != '' && is_numeric($_GET['id']) && ( $_GET['method'] == 'wins' || $_GET['method'] == 'plays' ) ) {
    $id = $_GET['id'];

    // Set DB info
    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Check DB connection
    if ($mysqli->connect_errno) {
        echo("Failed to connect to database: " . $mysqli->connect_error);
    }
    
    // Set column info
    $column = $_GET['method'];
    
    // SELECT the current data
    $stmt = $mysqli->prepare("SELECT ?
                              FROM puzzle
                              WHERE id = ?";
    $stmt->bind_param('si', $column, $id);

    // Run SELECT query
    $result = $stmt->execute();

    // Set update value
    while ($myrow = $mysqli->fetch_assoc()) {
        $value = $myrow[$column]+1;
    }

    // Close statement
    $stmt->close();
                
    // Set SQL query
    $stmt = $mysqli->prepare("UPDATE puzzle
                              SET ? = ?
                              WHERE id = ?");
    $stmt->bind_param('sii', $column, $value, $id);

    // Run UPDATE query
    $result = $stmt->execute();
    
    $status = "success";
}

// Write XML response to the page
printf("<result>%s</result>",$status);
?>
