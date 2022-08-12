<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
	<title>Hangman: Review Puzzles</title>
	<link rel="stylesheet" type="text/css" href="admin.css">
	<script type="text/javascript" src="../js/ajax.js"></script>
	<script type="text/javascript">
		showStatus = function(xmlData) {
			var id = setVar(xmlData,"id");
			var msg = setVar(xmlData,"message");
			document.getElementById("entry"+id).innerHTML = msg;
		}
	</script>
</head>

<body>

<ul>
<li><a href="review-puzzles.php">Review Puzzles</a></li>
<li><a href="enter.php">Enter Puzzles</a></li>
</ul>

<h1>Hangman: Review User-Submitted Puzzles</h1>

<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {
  // process form

	//Dumps contents of POST data to screen
  //while (list($name, $value) = each($_POST)) {
  //  echo "$name = $value<br>\n";
  //}


  $db = mysql_connect("localhost", "dbuser", "hunter2");
  mysql_select_db("hangman",$db);
  $id = $_POST['id'];
  $method = $_POST['method'];
  if ( is_numeric($id) ) {
	if ($method=='delete') {
		$sql = "DELETE FROM puzzle WHERE id = $id";
		$msg = "Entry deleted.";
	} else {
		$sql = "UPDATE puzzle SET approved = 'Y' WHERE id = $id;";
		$msg = "Entry approved.";
	}
    $result = mysql_query($sql) or die("sql error:" . $sql);
    echo '<div id="results">'. $msg .'</div>';
    // echo "Thank you! Entry approved.\n";
    #echo $sql;
    #echo $result;
  } else {
    printf("The specified ID, %s, is not valid.",$id);
  }
}

	//echo get_magic_quotes_gpc(); // Should be 1 (ON)
 
	// display form
  echo "<h2>Approve Entries</h2>";

	// Set up DB
  $db = mysql_connect("localhost", "dbuser", "hunter2");
  mysql_select_db("hangman",$db);

	// Create SQL statement
  $sql = "SELECT id,category,words,points FROM puzzle WHERE approved = 'N' ORDER BY id DESC";

	// Run query
  $result = mysql_query($sql) or die("SQL error");

  	echo "<p>There are currently <strong><em>" . mysql_num_rows($result) . "</em></strong> unapproved puzzle entries</p>";

	// Display results, if there are any
  if (mysql_num_rows($result) > 0) {
	  $rowCount = 0;
	  while ($myrow = mysql_fetch_assoc($result)) {
		$rowCount++;
		if ($rowCount % 2) {
			$rowClass = "row";
		} else {
			$rowClass = "rowAlt";
		}
		echo '<div class="' . $rowClass . '" id="entry' . $myrow['id'] .'">';
		printf('
			<form method="post" action="%s" onsubmit="makeRequest(\'approve-puzzle.php?method=update&id=%s\',showStatus);return false;">
				<input type="hidden" name="id" value="%s">
				<input type="hidden" name="method" value="approve">
				Category: %s<br>Puzzle: <a href="http://www.google.com/search?q=%s">%s</a> (points: %s)<br>
				<input type="submit" name="approve" value="approve">
			</form>
			<form method="post" action="%s" onsubmit="makeRequest(\'delete-puzzle.php?method=delete&id=%s\',showStatus);return false;">
				<input type="hidden" name="id" value="%s">
				<input type="hidden" name="method" value="delete">
				<input type="submit" name="delete" value="delete">
			</form>',$_SERVER['PHP_SELF'],$myrow['id'],$myrow['id'],$myrow['category'],ereg_replace(" ", "+", $myrow['words']),$myrow['words'],$myrow['points'],$_SERVER['PHP_SELF'],$myrow['id'],$myrow['id']);
		//printf('<form method="post" action="%s"><input type="hidden" name="id" value="%s"><input type="hidden" name="method" value="delete"><input type="submit" name="delete" value="delete">',$_SERVER['PHP_SELF'],$myrow['id']);
		echo '</div>';
	}
  }

?>


</body>
</html>
