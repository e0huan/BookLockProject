<?php
session_start(); // Start session before headers are sent.

# This file runs when the 'save' button is clicked on the profile page. It is associated with the profile.php file and it updates the locker combinations into the database for each room displayed on the profile table. 

switch($_POST['functionname']){
  // Update the locker combinations in the database
  case 'update':

  // Open database connection.
  require ( '../connect_db.php' );

  if (mysqli_connect_errno()) {
	  // If there is an error with the connection, stop the script and display the error.
	  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
  }

  $roomname = $_POST['roomname'];
  $roomnumber = $_POST['roomnumber'];
  $lockercombo = $_POST['lockercombo'];

  // Update the combination column in the row that matches the room name, the space's number, and the user's email address in the availability column.
  $stmt = $dbc->prepare('UPDATE users SET combination = ? WHERE name = ? AND number = ? AND availability = ?');
  
  $stmt->bind_param('ssss', $lockercombo, $roomname, $roomnumber, $_SESSION['name']);
  $stmt->execute();
	$stmt->close();
  $dbc->close();
}
?>