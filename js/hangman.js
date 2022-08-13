// JavaScript Document


var punc = ",:.?!'- "; // Puncutation characters not to be included as guessable characters
var linelength = 16; // Max characters to be displayed per line
var wins = 0; // Number of wins the user has racked up
var tripContribute = 5; // Number of wins before asked to contribute


function Word(id,c,w) {
    this.id = id;
    this.category = c;
    w = w.toUpperCase();
    this.word = w;
    this.letters = w.split("");
}

function Word_getBreakpoint(n) {
    var breakpoint;

    if (this.letters.length-n>linelength) {
        for (var i=n; i<this.letters.length && i<n+linelength; i++) {
            if (this.letters[i]==" ") {
                breakpoint=i;
            }
        }
    }

    return breakpoint;
}


function Word_build() {
    var punc = ",.?!' ";
    var breakpoint = 0;
    var j=0;
    // set current category
    set("category").innerHTML=this.category;
    // clear current word
    set("word").innerHTML='';
    
    if (this.word.length > linelength) {
        breakpoint = this.getBreakpoint(0);
    }
    
    for (var i in this.letters) {
        var regex = new RegExp(this.letters[i]);

        //alert(this.letters[i] + " " + this.letters[i].search(/a-zA-Z/i));
        //if ( punc.search(regex) < 0 ) {
        // Create an input box only if this is an alpha character
        if ( this.letters[i].search(/[a-zA-Z]/) == 0 ) {
            set("word").innerHTML += '<input type="text" size="1" maxlength="1" id="w'+i+'">&nbsp;';
        } else {
            set("word").innerHTML += this.letters[i] + '&nbsp;';
        }
        
        if ( i == breakpoint && breakpoint != 0 ) {
            // Add break
            set("word").innerHTML += "<br>";
            // Set new breakpoint
            breakpoint = this.getBreakpoint(breakpoint);
        }
    }
}

function Word_disable() {
    for (var i in this.letters) {
        if (set("w"+i)) {
            set("w"+i).value=this.letters[i];
            set("w"+i).disabled=true;
        }
    }
}


Word.prototype.build = Word_build;
Word.prototype.disable = Word_disable;
Word.prototype.getBreakpoint = Word_getBreakpoint;


function Gallows() {
    this.failures;
}

function Gallows_display() {
    set("gallows").innerHTML = '<img src="images/' + this.failures + '.png" width="150" height="150" alt="' + (6-this.failures) + ' Chances Left!">';
}

function Gallows_get() {
    return this.failures;
}

function Gallows_set(n) {
    this.failures = n;
}

function Gallows_message(ltr) {
    var msg;
    switch (this.failures) {
        case 5:
            msg="DANGER--Guess carefully!";
            break;
        case 4:
            msg="Uh-oh, things aren't looking good....";
            break;
        default:
            msg="Ouch! There are no " + ltr + "'s!";
            break;
    }
    return msg;
}

Gallows.prototype.display = Gallows_display;
Gallows.prototype.get = Gallows_get;
Gallows.prototype.set = Gallows_set;
Gallows.prototype.message = Gallows_message;

function Letter(l) {
    this.value = l;
    this.guessed = false;
}

function Letter_getValue() {
    return this.value;
}

function Letter_setValue(v) {
    this.value = v;
}

function Letter_getGuessed() {
    return this.guessed;
}

function Letter_setGuessed(b) {
    this.guessed = b;
}

Letter.prototype.getValue = Letter_getValue;
Letter.prototype.setValue = Letter_setValue;
Letter.prototype.getGuessed = Letter_getGuessed;
Letter.prototype.setGuessed = Letter_setGuessed;

// Construct the set of characters that the user can guess (the alphabet)
// Each character is an object of type Letter
function Alphabet() {
    this.abc = "abcdefghijklmnopqrstuvwxyz".toUpperCase();
    for (var i=0; i<this.abc.length; i++) {
        this[this.abc.charAt(i)] = new Letter(this.abc.charAt(i));
    }
}

function Guess(g) {
    this.letter = g.toUpperCase();
}


function buildWord() {
for (var i in letters) {
    set("word").innerHTML += '<input type="text" size="1" id="w'+i+'"> ';
}    
}


function Game() {
    // Game status
    this.isOver;
    
    // Initialize alphabet guessing board
    this.alpha;
    
    // Initialize player feedback "gallows"
    this.noose = new Gallows;
    
    // Initialize puzzle (game phrase to be guessed)
    this.puzzle;
}

// Initialize game
function Game_init(pz) {
    this.isOver = false;

    //this.puzzle = this.getPuzzle();
    this.puzzle = pz;
    this.puzzle.build();
    
    // Initialize alphabet guessing board
    this.alpha = new Alphabet;
    
    // Reset feedback board ("gallows")
    this.noose.set(0);
    this.noose.display();

    show("guess");
    show("solve");
    set("solution").value="";

    display("message","");

    set("guessedLetters").innerHTML = "";
    for (var i=0; i<this.alpha.abc.length; i++) {
        set("guessedLetters").innerHTML += '<a href="#" id="' + this.alpha.abc.charAt(i) + '" class="letter" onClick="dgame.checkGuess(\'' + this.alpha.abc.charAt(i) + '\'); return false;">' + this.alpha.abc.charAt(i) + '</a> ';
    }

    // Hide loading message
    hide("hover");
    hide("filter");

    // Set mouse/keyboard focus
    set("tryit").focus();
}

function puzzleMe(){
    showAjaxMessage("loading");
    makeRequest("get-puzzle.php",play);
}

function play(xmlData){
    var id;
    var category;
    var words;
    var puzzle;
    if(xmlData){
        var puzzle = xmlData.getElementsByTagName('puzzle')[0];
        id = setVar(puzzle,"id");
        category = setVar(puzzle,"category");
        words = setVar(puzzle,"words");
        if (category!="No data") {
            dgame.init(new Word(id,category,words));
        } else {
            // No data was returned; try loading again
            puzzleMe();
        }
    }
}

// This could, in the future, go out and grab a puzzle with an AJAX call
function Game_getPuzzle() {
    var pick=Math.floor(Math.random()*puzzles.length);
    return new Word(puzzles[pick].category,puzzles[pick].word);
}


function Game_checkGuess(ltr) {
    if ( !this.isOver ) {
        var exists = false;
        var occurrences = 0;
        display("message","");
        ltr = ltr.toUpperCase();
        //var ltr = set("tryit").value.toUpperCase();
        set("tryit").value = "";
        if ( ltr >= 'A' && ltr <= 'Z' ) {
            var myGuess = new Guess(ltr);
            if ( this.alpha[myGuess.letter] && this.alpha[myGuess.letter].getGuessed(true) ) {
                display("message","You already guessed " + this.alpha[myGuess.letter].getValue());
            } else {
                this.alpha[myGuess.letter].setGuessed(true);
                for (var i in this.puzzle.letters) {
                    if ( this.puzzle.letters[i] == myGuess.letter ) {
                        set("w"+i).value = myGuess.letter;
                        exists = true;
                        occurrences+=1;
                    }
                }
                if ( occurrences > 0 ) {
                    set(myGuess.letter).className = "letterExists";
                    if ( occurrences == 1 ) {
                        display("message","There is " + occurrences + " " + ltr + "!");
                    } else {
                        display("message","There are " + occurrences + " " + ltr + "'s!");
                    }
                    // Check to see if the user has guessed all letters
                    this.checkSolution();
                } else {
                    set(myGuess.letter).className = "letterDoesNotExist";
                    display("message","Ouch! There are no " + ltr + "'s!");
                    this.noose.set(this.noose.get()+1);
                    this.noose.display();
                    display("message",this.noose.message(ltr));
                    if (this.noose.get() >= 6) {
                        this.end("Lose");
                    }
                }
            }
        }
    }
}

// Check the current guesses agains the solution
function Game_checkSolution() {
    // Set up regular expression to remove punction & spaces -- we compare only the letter portion
    //had used "["+punc+"]" - ,':;\.\?!  
    var regex = new RegExp("[^a-zA-Z]","g");
    // String in solution form field
    var s = set("solution").value.toUpperCase();
    // Build string out of all letters on the board--in case the user has guessed all the letters
    var board = "";
    var letters = set("word").getElementsByTagName("input");
    for (var i=0; i<letters.length; i++) {
        board+=letters[i].value;
    }    
    if (s.replace(regex,"")==this.puzzle.word.replace(regex,"") || board.replace(regex,"")==this.puzzle.word.replace(regex,"")) {
        this.win();
    }
}

// Win
function Game_win() {
    // Update the number of wins in the database
    makeRequest("update.php?method=wins&id="+this.puzzle.id,doNothing);
    this.end("Win");
    wins++;
    if ( (wins % tripContribute) == 0 && !readCookie("hangmanNoContributePrompt") ) {
        showAjaxMessage("contribute");
        set("newPuzzleCategory").focus();
    }
}


// End the game
function Game_end(status) {
    // Display message
    display("message",'You '+status+'! <a href="noscript.html" onclick="puzzleMe();return false" id="playAgain">Play again</a>');
    // Hide guess letter / guess solution fieldsets
    hide("guess");
    hide("solve");
    // disable letters
    this.puzzle.disable();
    // set game over indicator
    this.isOver = true;
    // Set focus to Play Again link (does this work?)
    set("playAgain").focus;
}

Game.prototype.init = Game_init;
Game.prototype.getPuzzle = Game_getPuzzle;
Game.prototype.checkGuess = Game_checkGuess;
Game.prototype.checkSolution = Game_checkSolution;
Game.prototype.win = Game_win;
Game.prototype.end = Game_end;

/* GAME OBJ:
    isOver property
    word obj
    alphabet/guessing board obj
    gallows/feedback obj
*/

function contribute(c,w,pref) {
    if(pref) createCookie("hangmanNoContributePrompt",1,7);
    hide("contribute");
    set("newPuzzleCategory").value = "";
    set("newPuzzleWords").value = "";
    show("saving");
    // If the user submitted a puzzle, send it to the server
    // otherwise, load a new puzzle
    if(c!='' && w!=''){
        makeRequest("contribute.php?c="+c+"&w="+w,sayThanks);
    } else {
        puzzleMe();
    }
}

function sayThanks() {
    showAjaxMessage("thanks");
}

function showAjaxMessage(id) {
    var pageSize = getPageSize();
    show("hover");
    document.getElementById("filter").style.width = pageSize[0]+"px";
    document.getElementById("filter").style.height = pageSize[1]+"px";
    show("filter");
    if (id!="loading") hide("loading");
    if (id!="saving") hide("saving");
    if (id!="thanks") hide("thanks");
    if (id!="contribute") hide("contribute");
    show(id);
}

// -----------------------------------------------
// Get page size
// Borrowed from Lightbox:
// http://www.lokeshdhakar.com/projects/lightbox2/
// -----------------------------------------------

function getPageSize() {
    var xScroll, yScroll;

    if (window.innerHeight && window.scrollMaxY) {    
        xScroll = window.innerWidth + window.scrollMaxX;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }

    var windowWidth, windowHeight;

    if (self.innerHeight) {    // all except Explorer
        if(document.documentElement.clientWidth){
            windowWidth = document.documentElement.clientWidth; 
        } else {
            windowWidth = self.innerWidth;
        }
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }    

    // for small pages with total height less then height of the viewport
    if(yScroll < windowHeight){
        pageHeight = windowHeight;
    } else { 
        pageHeight = yScroll;
    }
    
    // for small pages with total width less then width of the viewport
    if(xScroll < windowWidth){    
        pageWidth = xScroll;        
    } else {
        pageWidth = windowWidth;
    }
    
    return [pageWidth,pageHeight];
}




// ------------------
// Shortcut functions
// ------------------

// Sets the value of the element (id) to the specified string (msg)
function display(id,msg) {
    if (set(id)) set(id).innerHTML=msg;
}

// document.getElementById(id) shortcut
function set(id){return document.getElementById(id);}

// display the specified element
function show (id,type){
    //type is an optional parameter. it should be either "block" or "inline"
    if (type === undefined) {
        type = "block";
    }
    document.getElementById(id).style.display = type;
}

// hide the specifed element
function hide (id){
    document.getElementById(id).style.display = "none";
}

// Given an xml object and an element name, return the element value
function setVar(xmlObj,element) {
    if (xmlObj.getElementsByTagName(element).length>0 && xmlObj.getElementsByTagName(element)[0].hasChildNodes()) {
        return xmlObj.getElementsByTagName(element)[0].firstChild.data;
    } else {
        return "No data";
    }
}

// Create a cookie (http://www.quirksmode.org/js/cookies.html)
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

// Read cookie (returns value or NULL)
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// Do nothing
function doNothing() { }

// Debugging function
function dump(descr,value) {
    //This function dumps javascript data to the page, if a place for it exists.
    if (set("dump")) set("dump").innerHTML+=descr+" "+value+"<br>";
}
