<?php
session_start(); // Start session before headers are sent.

# This file is associated with the the mappgreturn.php and mappage.php files. When the user clicks the 'save' button, the positions of the squares are updated into the database.

switch($_POST['functionname']){
  // Update owner's square in the database.
  case 'add':

    // Open database connection.
    require ( '../connect_db.php' );

    if (mysqli_connect_errno()) {
	    // If there is an error with the connection, stop the script and display the error.
	    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
  
    $x = $_POST['xposition'];
    $y = $_POST['yposition'];
    $size = $_POST['widthheight'];

    $stmt = $dbc->prepare('UPDATE users SET x = ?, y = ?, size = ? WHERE code = ? AND number = ?');
    $stmt->bind_param('sssss', $x, $y, $size, $_SESSION['code'], $_POST['squareid']);

    $stmt->execute();
    $stmt->close();
    $dbc->close();
}
?>
