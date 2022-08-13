# Hangman Game

This is the classic hangman game with a couple of additions:

* Users can submit their own puzzles after 5 wins
* An admin tool allows authorized users to review and approve user-submitted puzzles

This code is largely unchanged since the app was initially launched in 2007, and this was one of my first projects using PHP. I have made the following changes to the code since then:

* moved database credentials to a separate file: `db.php`
* updated the database calls to use `mysqli` and prepared statements/bind parameters
* used `htmlentities()` to escape output

There is a lot of terrible PHP here and many, many improvements could be made.

## Database Credentials

Copy `db.php.EXAMPLE` to `db.php` and replace the values as needed.

## Database Schema

Currently, there is only one table, `puzzle`, with the following fields:

* `id` - int(10) [primary key]
* `category` - varchar(50)
* `words` - varchar(250)
* `approved` - char(1)
* `points` - int(10)
* `plays` - int(10)
* `wins` - int(10)

Notes:

* `category` should have been its own table for database normalization
* `points` is based on the Scrabble score of the puzzle, a general gauge of how difficult it might be
* `plays` and `wins` are to track statistics about each puzzle, to find out which are more challenging than others

## Admin Tools

Currently I have the admin tools protected via `.htaccess` and `.htpasswd` files. The `.htaccess` file looks similar to this:

    AuthUserFile /absolute/path/to/.htpasswd
    AuthType Basic
    AuthName "Hangman Admin Tools"
    Require valid-user

