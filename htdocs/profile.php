<?php 
session_start(); // Start the session before headers are sent.
?> 
<!-- This file creates the profile page. It creates the profile table and finds all the rooms in which the user has booked a space. It then calls the javascript function in profile.js to create the rows in the profile table by passing data. -->

<!-- Elaine and Catherine -->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>repl.it</title>
    <link href="profile.css" rel="stylesheet" type="text/css" />
    <!-- Google Open Sans font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
</head>
<body>
  
<script src="profile.js"></script>

<!-- JQuery library -->
<script type="text/javascript">
    document.write("\<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'>\<\/script>");
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>


<!--menubar-->
<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <!--buttons on menubar-->
  <a href="https://booklock.catherinezheng1.repl.co/enterCode.html" class="btnnewroom"><img class= "newroom" src='newroom.png' width="150" height= "100" display: "inline-block"/>New Room</a>
  <a href="https://booklock.catherinezheng1.repl.co/profile.php" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Profiles</a>
   <a href="yourrooms.php" class="btnyourroom"><img class="yourroom" src='yourroom.png' width ="150" height="100" display:"inline-block"/>Your Room</a>
  <a href="https://Homepage.elainehuang3.repl.co" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Sign Out</a>
</div>

<!--button that opens menubar-->
<div id="main">
  <button id = "open" class="openbtn" onclick="openNav();">☰</button> <button type = "button" disabled class = "btn"><b>☰<b></button> 

  <!--image above menu, no function-->
  <div class="bar-container">
    <img class = "prof" src = 'prof.png'  width="600" height="325">
    <!--email-->
    <p class "email" id="email"></p>
    <!--Access user's email-->
    <script type="text/javascript"> var googlemail = <?php echo json_encode($_SESSION['name']);?> ; </script>
    <script src = "https://BookLock.catherinezheng1.repl.co/profile.js"></script>
  </div>

  <!--the light-pink bar uptop-->
  <div class="ibar-container">
    <!--table-->
    <table class= "table" id = "table" style="width:75%">
      <tr>
        <th class="columnames">Room</th>
        <th class="columnames">Locker Number</th> 
        <th class="columnames">Locker Combination</th>
      </tr>
    </table>
    <!--save and edit button-->
    <button class= "edit" onclick="myFunction(this);">save</button>
  </div>
</div>

<?php 

// Open database connection.
require ( '../connect_db.php' );

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Get all the rows in which the availability column matches the user's email address. These are all the rooms in which the user has booked a square.
$Query = "SELECT name, number, combination FROM users WHERE availability= " ."'" . $_SESSION['name'] . "'";

$Subject=mysqli_query($dbc, $Query) or die( mysqli_error($dbc));

// While there are rows that satisfy the conditions, call the javascript function to create a row in the table.
while ($row = mysqli_fetch_array($Subject)) {
  
  $combo = $row['combination'];
  $room = $row['name'];
  $number = $row['number'];

  echo '<script type="text/javascript">',
  'addCell('."'" .$row['combination']. "'".','. "'".$row['name']. "'". ',' . "'".$row['number']."'".');',
  '</script>';
}

?> 

</body>

</html>