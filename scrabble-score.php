<?php
// Alternative to str_split, which is a PHP5 function
// From http://us2.php.net/str_split
function strsplit($str, $l=1) {
     do {$ret[]=substr($str,0,$l); $str=substr($str,$l); }
     while($str != "");
     return $ret;
}

// Get Scrabble Score
function get_scrabble_score($word) {
    // Initialize scrabble points variable (return value)
    $points=0;
    // Uppercase all the letters in the word
    $word = strtoupper($word);
    // Get rid of all non-alpha characters
    $word = ereg_replace("[^A-Z]", "", $word);
    // Split string into array
    $letters = strsplit($word);

    // Define Scrabble tile points in an associative array
    $scrabble["A"]=1;
    $scrabble["B"]=3;
    $scrabble["C"]=3;
    $scrabble["D"]=2;
    $scrabble["E"]=1;
    $scrabble["F"]=4;
    $scrabble["G"]=2;
    $scrabble["H"]=4;
    $scrabble["I"]=1;
    $scrabble["J"]=8;
    $scrabble["K"]=5;
    $scrabble["L"]=1;
    $scrabble["M"]=3;
    $scrabble["N"]=1;
    $scrabble["O"]=1;
    $scrabble["P"]=3;
    $scrabble["Q"]=10;
    $scrabble["R"]=1;
    $scrabble["S"]=1;
    $scrabble["T"]=1;
    $scrabble["U"]=1;
    $scrabble["V"]=4;
    $scrabble["W"]=4;
    $scrabble["X"]=8;
    $scrabble["Y"]=4;
    $scrabble["Z"]=10;

    // Loop through the array of alpha chracters and add the scrabble points
    foreach ($letters as $key => $value) {
        $points += $scrabble[$value];
    }

    // Return calculated Scrabble score
    return $points;    
}
?>
