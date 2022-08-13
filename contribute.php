<?php
// Check to see that we received the expected, non-NULL, inputs
if ($_GET['c'] != '' && $_GET['w'] != '' ) {

    // Include scripts to calculate Scrabble score
    require 'scrabble-score.php';

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
    $db = mysql_connect("localhost", "dbuser", "hunter2");
    mysql_select_db("hangman",$db);
  
    // Run INSERT query
    $result = mysql_query($sql) or die("sql error");
}

//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
echo "<result>success</result>";
?>
