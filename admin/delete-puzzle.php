<?php
// Check to see that we received the expected, non-NULL, inputs
if ($_GET['method'] == 'delete' && is_numeric($_GET['id']) ) {
    $id = $_GET['id'];
    $sql = "DELETE FROM puzzle WHERE id = $id";
    $msg = "Entry deleted.";

    // Set up DB connection
    $db = mysql_connect("localhost", "dbuser", "hunter2");
    mysql_select_db("hangman",$db);
  
    // Run DELETE query
    $result = mysql_query($sql) or die("sql error");

}

//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
echo "<result><id>".$id."</id><message>".$msg."</message></result>";
?>
