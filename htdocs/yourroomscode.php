<?php
session_start(); // Start the session before headers are sent.

# This is associated with the 'Your Rooms' page. This file runs when the user clicks on a room link representing a joined room. This file gets the room code for that room name and assigns the values to session variables so that the mappgreturn.php file can use the information to create the room.

switch($_POST['functionname']){
  // Retrieve values for the user's joined room.
  case 'find':

  // Open database connection.
  require ( '../connect_db.php' );

  if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
	  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
  }

  // Get the code that matches the room name.
  $stmt = $dbc->prepare('SELECT code FROM users WHERE name = ? AND availability = ?');

	$stmt->bind_param('ss', $_POST['roomname'], $_SESSION['name']);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($code);
	$stmt->fetch();
  $stmt->close();

  // Assign the values to the session variables.
  $_SESSION['class'] = $_POST['roomname'];
  $_SESSION['code'] = $code;

  $dbc->close();
};
?>