<?php
session_start(); // Start the session before headers are sent.

#This file runs when the 'Next' button is clicked on the questions page to create a customized room. This file is associated with the questions.html file on Elaine's replit. This file retrieves the values, assigns necessary values to session variables to create the room in mappage.php, and inserts the number of rows in the database corresponding to the number of squares the user wants.

// Open database connection.
require ( '../connect_db.php' );

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Assign the values to the session variables.
$_SESSION['column'] = $_GET['column'];
$_SESSION['row'] = $_GET['row'];
$_SESSION['class'] = $_GET['class'];

// Since the room is newly created, all the spaces are available.
$availability = "available";

// Insert new rows into the database with the values submitted from the questions page form.
$stmt = $dbc->prepare('INSERT INTO users (email, code, name, number, availability) VALUES (?, ?, ?, ?, ?)');

// Assign values to the variables needed in the for loop.
$row = (int) $_GET['row'];
$column = (int) $_GET['column'];
$number = 0;

// For the number of spaces the user wishes to have in the newly created room, a new row is inserted into the database with the corresponding values. 
for ($i=0; $i<($row*$column); $i++){
  $number++;
  $stmt->bind_param('sssss', $_SESSION['name'], $_SESSION['code'], $_SESSION['class'], $number, $availability);
  $stmt->execute();
}

//Close the connection and the statement.
$stmt->close();
$dbc->close();

// Redirect the user to the administrator version map page.
echo("<script language=\"javascript\">"); echo("top.location.href = \"https://booklock.catherinezheng1.repl.co/mappage.php\";"); echo("</script>");
?>
