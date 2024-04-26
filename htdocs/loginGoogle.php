<?php
session_start(); // Start session before headers are sent.

# This file is associated with the loginpage.html and register.html. This file runs when the user signs in withh their google account. The email is assigned to a session variable so that the email can be used in other files. It then redirects the user to the instructions page.

// Get the Google user's email address and assign the value the the email session variable.
$_SESSION['name'] = $_GET["uid"];  

// Redirect to the instructions page.
echo("<script language=\"javascript\">"); echo("top.location.href = \"https://Instructions.elainehuang3.repl.co\";"); echo("</script>");
?>
