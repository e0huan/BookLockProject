<?php
session_start(); // Start session before headers are sent.

# This file creates passes data to the mappgreturn.php file to create the squares in the administrator map. It retrieves all the squares for the room associated with the room code.

switch($_POST['functionname']){
  // Get the squares from the user's own created room code
  case 'returnown':

    // Open database connection.
    require ( '../connect_db.php' ) ;

    if (mysqli_connect_errno()) {
	  // If there is an error with the connection, stop the script and display the error.
	    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Select all the squares and values associated with the room code.
    $Quer = "SELECT id, x, y, size, availability FROM users WHERE code = " ."'" . $_SESSION['code'] . "'";


    $Subj=mysqli_query($dbc, $Quer) or die( mysqli_error($dbc));

    // Call the function to create the squares while there are rows in the database that satisfy the conditions.
    while ($row = mysqli_fetch_array($Subj)) {
      echo '<script type="text/javascript" src="mappage.php">',
      'returnOwn('. "'" .$row['x']."', '". $row['y'] . "', " . "'" . $row['availability'] . "', '" .  $row['size'] . "'".');';
  }
}

?>