<?php # DISPLAY COMPLETE LOGIN PAGE.
session_start(); // Start session before headers are sent.

# This file displays the output once the user has clicked the "LOG IN" button on the login page. It displays a message according to the validity of the email and password inputs. If both are valid, it redirects the user to the instructions page.

// Open database connection.
require ( '../connect_db.php' ) ;

# Set encoding to match PHP script encoding.
mysqli_set_charset( $dbc, 'utf8' ) ;
?>

<html>
<head>
<!--Exclamation mark icon-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!--Google font Open Sans-->
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
 <link href="login.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (empty($_POST['email']) || empty($_POST['password'])) {
	// Could not get the data that should have been sent.
	exit('<center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #FFD96A; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Please fill both the email and password fields!</span></center>');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $dbc->prepare('SELECT password FROM accounts WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the email is a string so we use "s"
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

  if ($stmt->num_rows > 0) {
	  $stmt->bind_result($password);
	  $stmt->fetch();
    $stmt->close();
	  // Account exists, now we verify the password.
	  // Note: remember to use password_hash in your registration file to store the hashed passwords.
	  if (password_verify($_POST['password'], $password)) {
		  // Verification success! User has logged-in! Assign the email to the session variable.
		  $_SESSION['name'] = $_POST['email'];

      // Since the login is successful, redirect the user to the instructions page.
      echo("<script language=\"javascript\">"); echo("top.location.href = \"https://Instructions.elainehuang3.repl.co\";"); echo("</script>");
	  } 
    else {
		  // Incorrect password. However, only display that the email and/or password is incorrect.
		  echo '<center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #FFD96A; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Incorrect email and/or password!</span></center>';
	  }
  } 
  else {
	  // Incorrect email. However, only display that the email and/or password is incorrect.
	  echo '<center><i class="fa fa-exclamation-triangle fa_custom"></i><span style="color: #FFD96A; font-size: 98%; font-family: Open Sans, sans-serif;">&nbsp;&nbsp;Incorrect email and/or password!</span></center>';
  }
}
?>

</body>
</html>
