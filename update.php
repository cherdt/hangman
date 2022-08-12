<?php
//simulate a remote connection
sleep(2);
 
//include any libraries you want to use here.
 
//get the $mode var
//$mode = stripslashes($_GET['mode']);
//Set the content-type header to xml
header("Content-type: text/xml");
//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);

$status="failure";

// Verify that we've received the expected data
if ( $_GET['id'] != '' && is_numeric($_GET['id']) && ( $_GET['method'] == 'wins' || $_GET['method'] == 'plays' ) ) {
	$id = $_GET['id'];

	// Set DB info
	$db = mysql_connect("localhost", "dbuser","hunter2");
	mysql_select_db("hangman",$db);
	
	// Set column info
	$column = $_GET['method'];
	
	// SELECT the current data
	$sql = "SELECT $column
				FROM puzzle
				WHERE id = $id";
	
	// Run SELECT query
	$result = mysql_query($sql,$db);
	
	// Set update value
	while ($myrow = mysql_fetch_assoc($result)) {
		$value = $myrow[$column]+1;
	}
				
	// Set SQL query
	$sql = "UPDATE puzzle
				SET $column = $value
				WHERE id = $id";
	
	// Run UPDATE query
	$result = mysql_query($sql,$db);
	
	$status = "success";
}

// Write XML response to the page
printf("<result>%s</result>",$status);
//echo "<result>success</result>";
?>
