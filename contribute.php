<?php
//include any libraries you want to use here.
require  'db.php';
// Include scripts to calculate Scrabble score
require 'scrabble-score.php';

// Check to see that we received the expected, non-NULL, inputs
if ($_GET['c'] != '' && $_GET['w'] != '' ) {

    // ---------------------------------------------
    // Sanitize input (clean up user-submitted data)
    // ---------------------------------------------
    // Category should contain only alpha characters and spaces
    $category = ereg_replace("[^A-Za-z ]"," ",$_GET['c']);
    // Puzzle should contain only alpha characters, spaces, and limited punctuation
    $puzzle = ereg_replace("[^A-Za-z\,\-\'\:\;\"\?\!\& ]"," ",$_GET['w']);

    // Call function to calculate the Scrabble score of this puzzle
    $points = get_scrabble_score($puzzle);

    // Create a score based on (points/letter count)
    // On second thought, let's just store the score as a base number
    // $score = floor(($score/count($letters))*5-4);

    // Create SQL query
    $sql = "INSERT INTO puzzle
            (category, words, approved, points, plays, wins)
            VALUES
            ('$category','$puzzle','N',$points,0,0);";

    // Set up DB connection
    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
  
    // Check DB connection
    if ($mysqli->connect_errno) {
        echo("Failed to connect to database: " . $mysqli->connect_error);
    }

    // Create SQL query
    $stmt = $mysqli->prepare("INSERT INTO puzzle
                              (category, words, approved, points, plays, wins)
                              VALUES
                              (?, ?, ?, ?, ?, ?)");

    // Bind the variables
    $stmt->bind_param('sssiii', $category, $puzzle, 'N', $points, 0, 0);
    // Execute the SQL statement
    $stmt->execute();
    // Close the statement
    $stmt->close();
    // Close the database connection
    $mysqli->close();
}

//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
echo "<result>success</result>";
?>
