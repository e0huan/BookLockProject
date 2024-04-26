<?php 
session_start(); // Start session before headers are sent.
?>

<!-- This file creates the joined room map page. It retrieves all the squares associated with the code for that room and allows the user to book a space. It displays the availability of all the squares. -->

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BookLock</title>
  <link href="newmap.css" rel="stylesheet" type="text/css">
  <!--Google font Open Sans Light-->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
  <!--JQuery-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
<!--Body-->
<body style="background-color:#DCE6ED;">

<!--Legend-->
<div class="rectangle" id="rectangle"></div>
<div class="availsquare"></div>
<div class="unavailsquare"></div>
<div class="ownsquare"></div>
<p class="textavail" style="font-family:'Open Sans', sans-serif">Available locker</p>
<p class="textunavail" style="font-family:'Open Sans', sans-serif">Unvailable locker</p>
<p class="textown" style="font-family:'Open Sans', sans-serif">Your locker</p>

<!--Help button with question mark icon-->
<button id="newmyBtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle bi_custom" viewBox="0 0 16 16">
  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
  <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
</svg></button>

<!--Header for room name-->
<p class="par1">
  <?php echo "".$_SESSION['class']; ?>
</p>

<!--Help modal-->
<div id="newmyModal" class="newmodal">
  <!-- Modal content -->
  <div class="newmodal-content">
    <span class="newclose">&times;</span>
    <p class="newinquiry" id="newinquiry">&nbsp;1. Click on an available square of your choice. <br><br><br>&nbsp;2. Click confirm once you have made your choice.<br><br><br>&nbsp;3. To switch, choose a new available square.</p>
  </div>
</div>

<!-- Confirm Booking Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p class="booking">Confirm your Booking</p>
    <p class="inquiry" id="inquiry">Do you want to book space </p>
    <button class="confirm" id="confirm">Confirm</button>
  </div>
</div>

<!--rectangular container inside the rectangle with a border to contain the squares-->
<div id=container></div>

<!--JQquery-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
// Rectangle with border.
var cont = document.getElementById("rectangle");

// Variables needed in the function for the square's text, the booked space's id, and the user's email.
var number = 0;
var ownsquareid = "";
var email = "<?php echo $_SESSION['name']; ?>";

// Create a square with the values in the database.
function makeRows(x, y, availability, size) {
  // Set the square's attributes.
  number++;
  let square = document.createElement("square");
  square.setAttribute("class", "square");
  square.setAttribute("class", "square" + (number));
  square.setAttribute("name", "square"+(number));
  square.setAttribute("id", ""+(number));

  // Determine which color the square should be.
  if (availability == "available"){
    square.style.backgroundColor = "#FFD96A";
  }
  else if (availability == ""+email){
    square.style.backgroundColor = "#98A6E8";
    ownsquareid = square.id;
  }
  else {
    square.style.backgroundColor = "#656C8B";
  }

  // Text with the square's number.
  var h1 = document.createElement("h1");
  h1.textContent = number;

  // The square's size.
  var squareHeight = size;
  var squareWidth = size;

  $(square).height(squareHeight);
  $(square).width(squareWidth);

  // The square's position.
  var xposition = x;
  var yposition = y;

  $(square).css({top: yposition, left: xposition, position:'absolute'});

  // Append the elements.
  square.appendChild(h1);
  $("body").append(square);

  square.onclick = function(){
    // Text on modal.
    document.getElementById('inquiry').innerHTML = 'Do you want to book space '+square.id+'?'; 

    // Get the color of the square clicked by the user.
    var bg_color = window.getComputedStyle(square, null).backgroundColor;
    bg_color = bg_color.match(/\d+/g);

    function componentToHex(c) {
    var hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
    }

    function rgbToHex(rgb) {
      return "#" + componentToHex(+rgb[0]) + componentToHex(+rgb[1]) + componentToHex(+rgb[2]);
    }

    // If the square is yellow (available), open the modal.
    if (rgbToHex(bg_color) == "#ffd96a"){
      var modal = document.getElementById("myModal");
      var span = document.getElementsByClassName("close")[0];

      modal.style.display = "block";

      // Close the modal if the close button is clicked.
      span.onclick = function() {
        modal.style.display = "none";
      }

      if (event.target == modal) {
        modal.style.display = "none";
      }

      // Confirm button to confirm the user's booking.
      var confirm = document.getElementById("confirm");

      confirm.onclick = function() {  
      // Execute if the user has already booked a square in this room.
      if (ownsquareid != ""){
        var ownsquare = document.getElementById(ownsquareid);

        // The previous square becomes available (yellow) and the newly booked square is light blue.
        ownsquare.style.backgroundColor = "#FFD96A";
        square.style.backgroundColor = "#98A6E8";

        var previous = ownsquareid;

        // Update the values in the dataabse.
        $(document).ready(function(){
        $.post("newmapupdate.php",
        {
          functionname: 'updateown', 
          'previous': ''+previous, 
          'new' : ''+square.id
        },
        function(data,status){
        });
        });
        // The newly booked square becomes the new id and the modal is closed.
        ownsquareid = square.id;
        modal.style.display = "none";
      }
      // This is the first square the user has booked in the room.
      else {
        square.style.backgroundColor = "#98A6E8";

        // Update the database.
        $(document).ready(function(){
        $.post("newmapupdate.php",
        {
          functionname: 'addown', 
          'own' : ''+square.id
        },
        function(data,status){
        });
        });
        // The newly booked square becomes the new id and the modal is closed.
        ownsquareid = square.id;
        modal.style.display = "none";
      }
     }
    }  
  }
};

// Get the modal for the help feature.
var newmodal = document.getElementById("newmyModal");

// Get the button that opens the modal
var newbtn = document.getElementById("newmyBtn");

// Get the <span> element that closes the modal
var newspan = document.getElementsByClassName("newclose")[0];

// When the user clicks on the button, open the modal
newbtn.onclick = function() {
  newmodal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
newspan.onclick = function() {
  newmodal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == newmodal) {
    newmodal.style.display = "none";
  }
}
</script>

<!--menu button-->
<div id="main">
<button id = "chicken" class="openbtn" onclick="openNav();">☰</button>  

<!--menu sidebar buttons-->
<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="https://booklock.catherinezheng1.repl.co/enterCode.html" class="btnnewroom"><img class= "newroom" src='newroom.png' width="150" height= "100" display: "inline-block"/>New Room</a>
  <a href="profile.php" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Profiles</a>
   <a href="yourrooms.php" class="btnyourroom"><img class="yourroom" src='yourroom.png' width ="150" height="100" display:"inline-block"/>Your Room</a>
  <a href="https://Homepage.elainehuang3.repl.co" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Sign Out</a>
</div>

<script>
// Open the menu bar, shift page content.
function openNav() {
  document.getElementById("main").style.marginLeft = "25%";
  document.getElementById("mySidebar").style.width = "25%";
  document.getElementById("mySidebar").style.display="block"; document.getElementById("chicken").style.display="none";
}

// Close the menu bar, return page content.
function closeNav() {
  document.getElementById("main").style.marginLeft= "0%";
  document.getElementById("mySidebar").style.display = 'none';
  document.getElementById("chicken").style.display = 'inline-block';
}
</script>

<?php

// Open database connection.
require ( '../connect_db.php' );

if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Retrieve the necessary values in the columns for all the rows matching the corresponding room code.
$Query = "SELECT x, y, size, availability FROM users WHERE code = " ."'" . $_SESSION['code'] . "'";


$Subject=mysqli_query($dbc, $Query) or die( mysqli_error($con));

// While there are rows that satisfy the conditions, call the javascript function makeRows(x, y, availability, size) to create the square with the values in the row.
while ($row = mysqli_fetch_array($Subject)) {

echo '<script type="text/javascript">',
     'makeRows('.$row['x'].','. $row['y']. ',' . "'".$row['availability']."'"."," . "'" . $row['size'] . "'" .');',
     '</script>';
 
}

?>

</body>
</html>
