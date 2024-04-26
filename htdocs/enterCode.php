<?php
session_start(); // Start session before headers are sent.

# This file is associated with the enterCode.html file. It runs when the user clicks the 'submit' or 'Create Your Own' button on the 'New Room' page. If the user clicks the submit button to enter a code for an existing code, it checks if the room exists and redirects the user to the booking version of the map if it does. Else, it displays that it is incorrect. If the user creates a room, it redirects the user to the questions page to the create the customized room. It also generate a unique random code for the user's room.

// Open database connection.
require ( '../connect_db.php' ) ;

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>

<html>
<head>
  <!-- Exclamation mark with triangle icon for output -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Google Open Sans Light font -->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
  <link href="enterCode.css" rel="stylesheet" >
</head>
<body>

<?php

// Check if it was the submit button that was clicked to enter a room code.
if (isset($_POST['submitbtn'])) {
  // If the variable is empty, display the message that asks users to enter a code.
  if (empty($_POST['code'])) {
	// Could not get the data that should have been sent.
	exit('<center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #FF914D; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Please enter a code!</span></center>');
  }

  // Otherwise, verify that the code exists in the database.
  $Query = "SELECT code from users WHERE code = " ."'" . $_POST['code'] . "'";
  $Subject=mysqli_query($dbc, $Query) or die( mysqli_error($dbc));
  if (mysqli_num_rows($Subject) > 0){

    // Since the code exists, assign the value to the session variable.
    $_SESSION['code'] = $_POST['code'];

    // If the code does exist, retrieve the email address and room name that match the room code and bind them to variables.
    $stmtt = $dbc->prepare('SELECT email, name FROM users WHERE code = ?');
	  $stmtt->bind_param('s', $_POST['code']);
	  $stmtt->execute();
	  $stmtt->store_result();
    $stmtt->bind_result($em, $nam);
	  $stmtt->fetch();
    $stmtt->close();

    // Assign the room name to the session variable.
    $_SESSION['class'] = $nam;

    // Check if the email obtained from the room code (the user that has created the room) matches the email address with which the user has logged in with.
    if ($em == $_SESSION['name']){
      // Redirect the user to the administrator version of the map page corresponding to the room code.
      echo("<script language=\"javascript\">"); echo("top.location.href = \"https://BookLock.catherinezheng1.repl.co/mappgreturn.php\";"); echo("</script>");
    }
    // Otherwise, the user is not the owner of the room. 
    else {
      // The user is redirected to the booking version of the map page corresponding to the code.
      echo("<script language=\"javascript\">"); echo("top.location.href = \"https://BookLock.catherinezheng1.repl.co/newmap.php\";"); echo("</script>");
    }
  // The room code does not exist in the database.
  } 
  else {
    // The message displays that the room code inputted by the user is incorrect.
		echo '<center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #FF914D; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Incorrect code!</span></center>';
  }
  $dbc->close();
}

// Proceed if the 'Create Your Own' button was clicked. The user wishes to create their own room.
if (isset($_POST['createRoom'])) {
  // Generate a random string with a length of 5 characters.
  function generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  // Call the function to generate a random string with a length of 5 characters and assign the value to the variable.
  $randomcode=generateRandomString();

  // Initialize the uniqueness of the random code as false. 
  $is_unique = false;

  // While the room code is not unique, generate a random string.
  while (!$is_unique) {
    // Check if the random code already exists in the database.
    $sql = $dbc->prepare("SELECT COUNT(*) FROM users WHERE code = ?");

    $sql->bind_param('s', $randomcode);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($count);
    $sql->fetch();

    // if you don't get a result, then it is unique.
    if ($count == 0){
        $is_unique = true;
    }
    // if you DO get a result, keep trying.
    else {                    
        $randomcode = generateRandomString();
    }
  }

  // Assign the random code to the session variable.
  $_SESSION['code'] = $randomcode;

  // Redirect the user to the questions page.
 echo("<script language=\"javascript\">"); echo("top.location.href = \"https://questions-page.elainehuang3.repl.co\";"); echo("</script>");
}
?>

</body>
</html>