# Hangman Game

This code is largely unchanged since the app was initially launched in 2007, and this was one of my first projects using PHP. I have made the following changes to the code since then:

* moved database credentials to a separate file: `db.php`
* updated the database calls to use `mysqli` and prepared statements/bind parameters
* used `htmlentities()` to escape output

There is a lot of terrible PHP here and many, many improvements that could be made.

## Database Credentials

Copy `db.php.EXAMPLE` to `db.php` and replace the values as needed.
