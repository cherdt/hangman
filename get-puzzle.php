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

// Set up DB
$db = mysql_connect("localhost", "dbuser","hunter2");
mysql_select_db("hangman",$db);

// Set SELECT query
$sql = "SELECT id, category, words, plays
	FROM puzzle
	WHERE approved ='Y'
	ORDER BY RAND()
	LIMIT 1";
// Run SELECT query
$result = mysql_query($sql,$db);

//$result = mysql_query("SELECT `puzzle`.category, `puzzle`.words FROM puzzle WHERE approved = 'Y' ORDER BY RAND() LIMIT 1",$db);

// Select a random number between zero and # of results returned
//$row_number = rand(0,mysql_num_rows($result));



while ($myrow = mysql_fetch_assoc($result)) {
	// Write XML output to the page
	printf("<puzzles><puzzle><id>%s</id><category>%s</category><words>%s</words></puzzle></puzzles>",$myrow['id'],$myrow['category'],$myrow['words']);

	// Update times played data
	$plays = $myrow['plays'] + 1;

	// Set update query
	$sql = "UPDATE puzzle
				SET plays = $plays
				WHERE id = $myrow[id];";

	// Run UPDATE query
	$update = mysql_query($sql,$db);	
}



// This is another method of looping through query results
//$resultAAA = mysql_query("SELECT `puzzle`.category, `puzzle`.words FROM puzzle",$db);
//for($x=0; $x < mysql_num_rows($resultAAA); $x++){ 
//	$myrow = mysql_fetch_assoc($resultAAA);
//	$xmloutput = '<puzzle><category>'.$myrow['category'].'</category><words>'.$myrow['words'].'</words></puzzle>';
//}
//echo mysql_num_rows($result);
//echo $xmloutput;
//echo 'test';
?>
