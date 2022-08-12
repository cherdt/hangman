<?php

// Alternative to str_split, which is a PHP5 function
// From http://us2.php.net/str_split
function strsplit($str, $l=1) {
    do {$ret[]=substr($str,0,$l); $str=substr($str,$l); }
    while($str != "");
    return $ret;
}

if ($_GET['c'] != '' && $_GET['w'] != '' ) {

$words = strtoupper($_GET['w']);
$words = ereg_replace("[^A-Z]", "", $words); 
$letters = strsplit($words);
$score=0;


// Scrabble tile points in an associative array
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

foreach ($letters as $key => $value) {
	$score += $scrabble[$value];
}

$score = floor(($score/count($letters))*5-4);

$sql = "INSERT INTO puzzle
        (category, words, approved, skill)
        VALUES
        ('$_GET[c]','$_GET[w]','N',$score);";

  $db = mysql_connect("localhost", "dbuser", "hunter2");
  mysql_select_db("hangman",$db);
  
  $result = mysql_query($sql) or die("sql error");

}

//echo the XML declaration
echo chr(60).chr(63).'xml version="1.0" encoding="utf-8" '.chr(63).chr(62);
echo "<result>success</result>";
?>
