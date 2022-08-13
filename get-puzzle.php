<?php
//simulate a remote connection
sleep(2);
 
//include any libraries you want to use here.
require 'db.php';
 
//Set the content-type header to xml
header("Content-type: text/xml");
//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);

// Set up DB
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

// Set SELECT query
$sql = "SELECT id, category, words, plays
    FROM puzzle
    WHERE approved ='Y'
    ORDER BY RAND()
    LIMIT 1";

// Run SELECT query
$result = $mysqli->query($sql);


while ($myrow = $result->fetch_assoc()) {
    // Write XML output to the page
    printf("<puzzles><puzzle><id>%s</id><category>%s</category><words>%s</words></puzzle></puzzles>",$myrow['id'],$myrow['category'],$myrow['words']);

    // Update times played data
    $plays = $myrow['plays'] + 1;

    // Set update query
    $stmt = $mysqli->prepare("UPDATE puzzle
                              SET plays = ?
                              WHERE id = ?");
    $stmt->bind_param('ii', $plays, $myrow[id]);

    // Run UPDATE query
    $update = $stmt->execute();
    $stmt->close();
}

$mysqli->close();

?>
