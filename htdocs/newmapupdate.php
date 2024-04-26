<?php
session_start(); // Start session before headers are sent.

# This file is associated with the the mappage.php, mappgreturn.php, and newmap.php files. When the user books a square in a joined or created room, this file runs to update the database. If the square is the first booked space in the room by the user, it only updates one row. Otherwise, it updates the two rows associated with the previously booked square and newly booked square. 

switch($_POST['functionname']){
  // Execute this block of code if a previous square has been booked before in the room.
  case 'updateown':
    // Open database connection.
    require ( '../connect_db.php' );

    if (mysqli_connect_errno()) {
	    // If there is an error with the connection, stop the script and display the error.
	    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $previous = $_POST['previous'];
    $new = $_POST['new'];

    // Update the availability of the row representing the newly booked square from the correct room code with the user's email.
    $stmt = $dbc->prepare('UPDATE users SET availability = ? WHERE code = ? AND number = ?');

    $stmt->bind_param('sss', $_SESSION['name'], $_SESSION['code'],  $new);
    $stmt->execute();

    // The previously booked square must be updated to being available in the database. In case the user had included their lock combination for that space, the combination in the row must be reset.
    $newavailability = "available";
    $replaceOldCombo = "";

    $stmt = $dbc->prepare('UPDATE users SET availability = ?, combination = ? WHERE code = ? AND number = ?');

    $stmt->bind_param('ssss', $newavailability, $replaceOldCombo, $_SESSION['code'],  $previous);
    $stmt->execute();
    $stmt->close();

  // Execute this block if no space has been previously booked in the room. This is the first time the user is booking a square.
  case 'addown':
    // Open the database connection.
    require ( '../connect_db.php' );

    if (mysqli_connect_errno()) {
	  // If there is an error with the connection, stop the script and display the error.
	    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $own = $_POST['own'];

    // The newly booked square has its availability updated with the user's email.
    $stmt = $dbc->prepare('UPDATE users SET availability = ? WHERE code = ? AND number = ?');

    $stmt->bind_param('sss', $_SESSION['name'], $_SESSION['code'],  $own);
    $stmt->execute();
    $stmt->close();
}
$dbc->close();
?>
