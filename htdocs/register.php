<!-- This file runs when the 'SIGN UP' button is clicked on the signup page. It is associated with the register.html file. This file checks whether the inputs for the email and password are valid and displays a message. -->
<html>
<head>
<!-- Exclamation mark icon -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!-- Google font Open Sans Light -->
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
<link href="register.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php 
// Try and connect using the info above.
// Open database connection.
require ( '../connect_db.php' );

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Make sure the submitted registration values are not empty.
if (empty($_POST['password']) || empty($_POST['email'])) {
	// One or more values are empty. A message is displayed asking the user to complete the form again.
	exit('<center><center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #656C8B; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Please complete the registration form.</span></center>');
}

// We need to check if the account with that username exists.
if ($stmt = $dbc->prepare('SELECT password FROM accounts WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();

	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();
	if ($stmt->num_rows > 0) {
		// Email already exists. Ask the user to choose a new email address.
		echo '<center><center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #656C8B; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Email exists, please choose another!</span></center>';
	} 
  else {
		// Email does not exist, insert new account (new row in the 'accounts' table).
    if ($stmt = $dbc->prepare('INSERT INTO accounts (email, password) VALUES (?, ?)')) {

	  // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
	  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	  $stmt->bind_param('ss', $_POST['email'], $password);
	  $stmt->execute();

    // Display a successful message.
	  echo '<center><center><i class="fa fa-check-circle fa_custom"></i><span style="color: #656C8B; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Registration successful. You can now login!</span></center>';
    } 
    else {
	    // Something is wrong with the sql statement.
	    echo '<center><center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #656C8B; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Could not prepare statement!</span></center>';
    }
	}
	$stmt->close();
} 
else {
	// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
	echo '<center><center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #656C8B; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Could not prepare statement!</span></center>';
}
$dbc->close();
?> 

</body>
</html>