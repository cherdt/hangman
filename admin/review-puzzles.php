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
//include any libraries you want to use here.
require '../db.php';

// Set up database connection
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($_SERVER['REQUEST_METHOD']=='POST') {
    // process form

    $id = $_POST['id'];
    $method = $_POST['method'];
    if ( is_numeric($id) ) {
        if ($method=='delete') {
            $stmt = $mysqli->prepare("DELETE FROM puzzle WHERE id = ?");
            $msg = "Entry deleted.";
        } else {
            $stmt = $mysqli->prepare("UPDATE puzzle SET approved = 'Y' WHERE id = ?");
            $msg = "Entry approved.";
        }
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        echo '<div id="results">'. $msg .'</div>';
    } else {
        printf("The specified ID, %s, is not valid.", htmlentities($id));
    }
}

 
    // display form
    echo "<h2>Approve Entries</h2>";

    // Create SQL statement
    $sql = "SELECT id, category, words, points
            FROM puzzle
            WHERE approved = 'N'
            ORDER BY id DESC";

    // Run query
    $result = mysql->query($sql);

    echo "<p>There are currently <strong><em>" . $result->num_rows . "</em></strong> unapproved puzzle entries</p>";

    // Display results, if there are any
    if ($result->num_rows > 0) {
        $rowCount = 0;
        while ($myrow = $result->fetch_assoc()) {
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
            </form>', $_SERVER['PHP_SELF'], $myrow['id'], $myrow['id'], htmlentities($myrow['category']), ereg_replace(" ", "+", htmlentities($myrow['words'])), htmlentities($myrow['words']), $myrow['points'], $_SERVER['PHP_SELF'], $myrow['id'], $myrow['id']);
            echo '</div>';
        }
    }
    $mysqli->close();
?>


</body>
</html>
