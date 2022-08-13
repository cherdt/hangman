<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <title>Hangman - Data Entry</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>

<body>

<ul>
<li><a href="review-puzzles.php">Review Puzzles</a></li>
<li><a href="enter.php">Enter Puzzles</a></li>
</ul>

<h1>Hangman - Enter a new puzzle</h1>

<?php
// Include database settings
require '../db.php';
// Include scripts to calculate Scrabble score
require '../scrabble-score.php';

if ($_SERVER['REQUEST_METHOD']=='POST') {
    // Form data received; process form

    // Sanitize input (clean up user-submitted data)
    // Category should contain only alpha characters and spaces
    $category = ereg_replace("[^A-Za-z ]"," ",$_POST['category']);
    // Puzzle should contain only alpha characters, spaces, and limited punctuation
    $puzzle = ereg_replace("[^A-Za-z\,\-\'\:\;\"\?\!\& ]"," ",$_POST['words']);

    // Print feedback
    printf('<p id="results">Puzzle: <strong>%s</strong><br>',$puzzle);

    // Call function to calculate the Scrabble score of this puzzle
    $points = get_scrabble_score($puzzle);
    
    // Setup DB connection
    $mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);

    // Define SQL statement
    $stmt = $mysqli->prepare("INSERT INTO puzzle
                              (category, words, approved, points, plays, wins)
                              VALUES
                              (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssiii', $category, $puzzle, 'N', $points, 0, 0);

    // Run SQL INSERT statement
    $result = $stmt->execute();

    // Print thank you note
    echo "Thank you! We'll review your puzzle suggestion in the next day or two.</p>\n";

}

// display form
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>">
    <div style="width: 75px; float: left;">Category:</div>
    <div>
        <select name="category">
            <option value="Books">Books</option>
            <option value="Movies">Movies</option>
            <option value="People">People</option>
            <option value="Places">Places</option>
            <option value="Toast Toppings">Toast Toppings</option>
            <option value="TV Shows">TV Shows</option>
        </select>
    </div>
    
    <div style="width: 75px; float: left;">Puzzle:</div>
    <div><input type="Text" name="words" maxlength="250"></div>
    <div><input type="Submit" name="submit" value="Submit"></div>
</form>


</body>
</html>
