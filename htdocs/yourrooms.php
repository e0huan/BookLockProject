<?php
session_start(); // Start the session before headers are sent.
?>

<!-- This file creates the 'Your Rooms' page. The page displays all the rooms the user has created or joined. When the user clicks on a joined room, they are redirected to the booking version of the map corresponding to that room name. If the user clicks on their own created room, they are redirected to the administrator version of the map corresponding to that room name. -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Your Rooms</title>
    <link href="yourrooms.css" rel="stylesheet" type="text/css" />
    <!--Google font Open Sans-->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
    <!--Help button question mark icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<!-- The Modal -->
<div id="newmyModal" class="newmodal">
  <!-- Modal content -->
  <div class="newmodal-content">
    <span class="newclose">&times;</span>
    <p class="booking">Confirm your Booking</p>
    <p class="inquiry" id="inquiry">Do you want to book space </p>
  </div>
</div>

<!-- JQuery library -->
<script src="yourrooms.js"></script>
  <script type="text/javascript">
  document.write("\<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'>\<\/script>");
</script>

<!--menubar-->
<div id="mySidebar" class="sidebar">
	<button class="closebtn" onclick="closeNav()">×</a>
  <!--buttons on menubar-->
	<button onclick="window.location.href='https://booklock.catherinezheng1.repl.co/enterCode.html'" class="btnnewroom"><img class= "newroom" src='newroom.png' width="150" height= "100" display= "inline-block" />New Room</a>
  <button onclick="window.location.href='https://booklock.catherinezheng1.repl.co/profile.php'" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Profiles</a>
  <button onlick="window.location.href='https://Your-Rooms.elainehuang3.repl.co'" class="btnyourroom"><img class="yourroom" src='yourroom.png' width ="150" height="100" display:"inline-block"/>Your Room</a>
  <button onclick ="window.location.href='https://Homepage.elainehuang3.repl.co'" class="btnlogout"><img class="logout" src='logout.png' width ="150" height="100" display:"inline-block"/>Sign Out</a>
</div>

<!--button that opens menubar-->
<div id="main">
  <button id = "open" class="openbtn" onclick="openNav();"><b>☰<b></button> <button type = "button" disabled class = "btn"><b>☰<b></button> 

<!--Header-->
<h1> Your Rooms: </h1>
<div class= "line"></div>
<div id="text"></div>

<script>
//variables representing headings and parts in table
var room=[];//room
var num = 0;//locker number
var text = document.getElementById("text");
var blank = '';

// Add the room name link on the page.
function addroom(name, type){
  // Append the name of the room to the array.
  room.push(""+name);

  // If the room has been joined by the user, it will call the toRoom(link) function onclick. It will link to the booking version map page.
  if (type == "joined"){
    blank +='<a href = "newmap.php" onclick = "toRoom(this);" id = '+"'"+num+"'>" + 
    room[num];
  }  
  // If the room has been created by the user, it will call the toOwn(link) function onclick. It will link to the administrator version map page.
  else {
    blank +='<a href = "mappgreturn.php" onclick = "toOwn(this);" id ='+"'"+num+"'>" + 
    room[num];
  }
  // Close once all the rooms have been added.
  num++;
  if (num == finalnumber){
    blank += '</a href>';
    text.innerHTML =blank;
  }   
}

// Once a joined room is clicked, pass information to the php file.
function toRoom(link){
  var classname = link.innerHTML;

  $(document).ready(function(){
  $.post("yourroomscode.php",
  {
    functionname: 'find', 
    'roomname': classname
  },
  function(data,status){
  });
  });
}

// Once a created room is clicked, pass information to the php file.
function toOwn(lnk){
    var owclass = lnk.innerHTML;

    $(document).ready(function(){
    $.post("yourroomsown.php",
    {
      functionname: 'findOwn', 
      'ownclass': owclass
    },
    function(data,status){
    });
    });
}
</script>
<!--this div is for the menubar, when it opens whole page shifts right-->
    </div>

<?php
// Open database connection.
require ( '../connect_db.php' );

// Error.
if (mysqli_connect_errno()) {
  // If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Count how many rooms the user has joined and booked a space or created.
$sql = $dbc->prepare("SELECT COUNT(*) FROM 
(
SELECT DISTINCT name, code FROM users WHERE email =? OR availability = ?
) AS subquery");

// Execute the query and bind the result to the variable.
$sql->bind_param('ss', $_SESSION['name'], $_SESSION['name']);
$sql->execute();
$sql->store_result();
$sql->bind_result($count);
$sql->fetch();

// Create the javascript variable (because it somehow doesn't work when I put it as a session variable).
echo "<script> var finalnumber = " . $count . "; </script>";

// Select all the rooms the user has created or joined and booked a space.
$Query = "SELECT DISTINCT name, code FROM users WHERE email = "."'".  $_SESSION['name'] . "'" ."OR availability = " ."'" . $_SESSION['name'] . "'";

$Subject=mysqli_query($dbc, $Query) or die( mysqli_error($dbc));

// While there are rooms the user has joined or created, execute this code.
while ($row = mysqli_fetch_array($Subject)) {
  // The type of the room.
  $type = "joined";

  // Check if the room was created by the user.
  $stmt = $dbc->prepare('SELECT name FROM users WHERE name = ? AND email = ?');

  $stmt->bind_param('ss', $row['name'], $_SESSION['name']);
  $stmt->execute();
  $stmt->store_result();

  // If the room was created by the user, the type of the room is "own".
  if ($stmt->num_rows > 0){
    $type = "own";
  }

  // Call the function for every room the user has joined or created.
  echo '<script type="text/javascript">',
     'addroom('."'".$row['name']."', '". $type . "'" . ');',
     '</script>';
}

$dbc->close();
?>

</body>
</html>