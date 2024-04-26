<?php 
session_start();

# This file creates the administrator version of the map page when the user returns to the room from the 'Your Rooms' page. It is very similar to the mappage.php file, except it retrives the positions of the squares to create them instead of creating new rows into the database. It also shows all the squares' availability, including unavailable squares. -->

// Open database connection.
require ( '../connect_db.php' );

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Check how many squares are in the room.
$sql = $dbc->prepare("SELECT COUNT(*) FROM users WHERE name = ? and email = ?");

$sql->bind_param('ss', $_SESSION['class'], $_SESSION['name']);
$sql->execute();
$sql->store_result();
$sql->bind_result($finalnum);
$sql->fetch();
$sql->close();

// Assign value to session variable to use the count in javascript.
$_SESSION['finalno'] = $finalnum;
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BookLock</title>
  <link href="mappgreturn.css" rel="stylesheet" type="text/css">
  <!--Google font Open Sans Light-->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
  <!--JQuery-->
  <link rel="stylesheet" 
  href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
</head>
<body>
<!-- Help button with question mark icon -->
<button id="newmyBtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle bi_custom" viewBox="0 0 16 16">
<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
</svg></button>

<!-- Room code -->
<p class="par1">
  <?php echo "Code: " . $_SESSION['code']; ?>
</p>

<!-- Room name -->
<p class="par2">
  <?php echo "" . $_SESSION['class'];  ?>
</p>

<!-- The Help Modal -->
<div id="newmyModal" class="newmodal">
  <!-- Modal content -->
  <div class="newmodal-content">
    <span class="newclose">&times;</span>
    <!--<p class="newbooking">Confirm your Booking</p>-->
    <p class="newinquiry" id="newinquiry">&nbsp;1. Click and drag the squares to their positions. <br><br>&nbsp;2. Click the save button when you are done.<br><br>&nbsp;3. Click the edit button to edit your map again.<br><br>&nbsp;4. Book a space by clicking on an available square. <br><br>&nbsp;5. Confirm your choice. <br><br>&nbsp;6. To switch, click a new square. </p>
  </div>
</div>

<!-- The Confirm Booking Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p class="booking">Confirm your Booking</p>
    <p class="inquiry" id="inquiry">Do you want to book space </p>
    <button class="confirm" id="confirm">Confirm</button>
  </div>
</div>

<!--Squares availability legend-->
<body style="background-color:#DCE6ED;">
<div class="rectangle" id="rectangle"></div>
<div class="availsquare"></div>
<div class="unavailsquare"></div>
<div class="ownsquare"></div>
<p class="textavail" style="font-family:'Open Sans', sans-serif">Available locker</p>
<p class="textunavail" style="font-family:'Open Sans', sans-serif">Unvailable locker</p>
<p class="textown" style="font-family:'Open Sans', sans-serif">Your locker</p>

<!-- container that holds draggable squares -->
<div id=container></div>

<!-- edit/save button -->
<button class= "edit" onclick="myFunction(this);">Save</button>

<!-- JQuery -->
<script type="text/javascript">
  document.write("\<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'>\<\/script>");
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
<script>
// Variables for determining when to allow booking and how many squares there are.
var saved = "no";
var finalno = "<?php echo $_SESSION['finalno']; ?>";

// When the user clicks the edit/save button.
function myFunction(button) {
  if (button.innerHTML == "Save"){
    // The squares have been saved.
    saved = "yes";

    //Save button becomes edit button.
    button.innerHTML = "Edit"

    // Disable draggable squares.
    $( "square" ).draggable({ disabled: true });

    // Update the positions of the squares to the database.
    for (c = 0; c < (finalno); c++) {
      // Current square.
      const chosenSquare = document.getElementById(""+(c+1));
      var chosSquare = chosenSquare.getBoundingClientRect();

      // Positions/size of the square.
      x = chosSquare.left;
      y = chosSquare.top;
      w = chosSquare.width;
      h = chosSquare.height;

      // Pass information to database.
      $(document).ready(function(){
      $.post("mapupdate.php",
      {
        functionname: 'add', 
        'xposition': ''+x, 
        'yposition' : ''+y, 
        'widthheight': ''+w,
        'squareid': ''+(c+1)
      },
      function(data,status){
      });
      }); 
    }  
  }
  // The edit button was clicked.
  else {
    // Positions are not saved.
    saved = "no";
    
    // Edit button becomes a save button.
    button.innerHTML = "Save"

    // Squares are draggable again.
    $( "square" ).draggable({ disabled: false });
  }
}

// Rectangle container that holds the squares.
var cont = document.getElementById("rectangle");

// Draggable squares restricted to the container.
$( function() {
  $( "square" ).draggable({ containment: "#container" });
  scroll: false
} );

// For the number of the square and the square the user booked.
var number = 0;
var ownsquareid = "";

// Create the squares from database values.
function returnOwn(x, y, availability, size) {
  // The number of the square.
  number++;

  // Set attributes for the square.
  let square = document.createElement("square");
  square.setAttribute("class", "square");
  square.setAttribute("class", "square" + (number));
  square.setAttribute("name", "square"+(number));
  square.setAttribute("id", ""+(number));

  // Access the user's email.
  var email = "<?php echo $_SESSION['name']; ?>";

  // Determine color of the square from its availability.
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

  // Text on square with its number.
  var h1 = document.createElement("h1");
  h1.textContent = number;

  // Set the height and width of the square from database value.
  var squareHeight = size;
  var squareWidth = size;

  $(square).height(squareHeight);
  $(square).width(squareWidth);

  // Set the position of the square from database value.
  var xposition = x;
  var yposition = y;

  $(square).css({top: yposition, left: xposition, position:'absolute'});

  // Append elements.
  square.appendChild(h1);
  $("body").append(square);

  // When a square is clicked.
  square.onclick = function(){
    // Check if the positions have been saved (squares are no longer draggable and positions were updated to database).
    if (saved == "yes"){
      // Ask user to confirm booking.
      document.getElementById("inquiry").innerHTML = "Do you want to book space "+square.id+"?"; 

      // Check if the square is yellow/available.
      var bg_color = window.getComputedStyle(square, null).backgroundColor;
      bg_color = bg_color.match(/\d+/g);

      function componentToHex(c) {
        var hex = c.toString(16);
        return hex.length == 1 ? "0" + hex : hex;
      }
      function rgbToHex(rgb) {
        return "#" + componentToHex(+rgb[0]) + componentToHex(+rgb[1]) + componentToHex(+rgb[2]);
      }
      // Open modal if square is available.
      if (rgbToHex(bg_color) == "#ffd96a"){
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        modal.style.display = "block";
        
        // Close modal if close button is clicked.
        span.onclick = function() {
          modal.style.display = "none";
        }
        if (event.target == modal) {
          modal.style.display = "none";
        }

        // Get confirm button for booking.
        var confirm = document.getElementById("confirm");

        // When confirm button to confirm booking is clicked.
        confirm.onclick = function() {  
          // Check if the user has previously booked a square in the room.
          if (ownsquareid != ""){
            // Get the previous square and assign it to a variable.
            var ownsquare = document.getElementById(ownsquareid);
            var previous = ownsquareid;

            // Change the colors of the squares. Previous one becomes available (yellow) and new one is booked (light blue).
            ownsquare.style.backgroundColor = "#FFD96A";
            square.style.backgroundColor = "#98A6E8";

            // Update availability of previously booked square and newly booked square in database.
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
            // Current square's id becomes the id of the square user has booked.
            ownsquareid = square.id;

            // Close the modal.
            modal.style.display = "none";
          }
          // The user has not booked a square in this room before. This is the first time.
          else {
            // Change only the color of the newly booked square (light blue).
            square.style.backgroundColor = "#98A6E8";

            $(document).ready(function(){
            $.post("newmapupdate.php",
            {
              functionname: 'addown', 
              'own' : ''+square.id
            },
            function(data,status){
            });
            });
            // Current square's id becomes the id of the square user has booked.
            ownsquareid = square.id;

            // Close the modal.
            modal.style.display = "none";
          }
        }
      }
    } 
  }
};

// Get the help modal.
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
</div>


<!--sidebar open button-->
<div id="main">
  <button id = "chicken" class="openbtn" onclick="openNav();">☰</button>  
</div>

<!--menu buttons-->
<div id="mySidebar" class="sidebar">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
  <a href="https://booklock.catherinezheng1.repl.co/enterCode.html" class="btnnewroom"><img class= "newroom" src='newroom.png' width="150" height= "100" display: "inline-block"/>New Room</a>
  <a href="profile.php" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Profiles</a>
  <a href="yourrooms.php" class="btnyourroom"><img class="yourroom" src='yourroom.png' width ="150" height="100" display:"inline-block"/>Your Room</a>
  <a href="https://Homepage.elainehuang3.repl.co" class="btnprofile"><img class="profile" src='profile.png' width ="150" height="100" display:"inline-block"/>Sign Out</a>
</div>

<script>
// Open menu bar and shift page content.
function openNav() {
  document.getElementById("main").style.marginLeft = "25%";
  document.getElementById("mySidebar").style.width = "25%";
  document.getElementById("mySidebar").style.display="block"; document.getElementById("chicken").style.display="none";
}

// Close menu bar and return page content.
function closeNav() {
  document.getElementById("main").style.marginLeft= "0%";
  document.getElementById("mySidebar").style.display = 'none';
  document.getElementById("chicken").style.display = 'inline-block';
}
</script>

<?php
session_start();
// Open database connection.
require ( '../connect_db.php' ) ;

if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Get all the squares and values associated with the room code.
$Quer = "SELECT id, x, y, size, availability FROM users WHERE code = " ."'" . $_SESSION['code'] . "'";

$Subj=mysqli_query($dbc, $Quer) or die( mysqli_error($dbc));

// While there is a row associated with the room code, execute.
while ($row = mysqli_fetch_array($Subj)) {
  // Call the function to create the square.
  echo '<script type="text/javascript">',
  'returnOwn('.$row['x'].','. $row['y']. ',' . "'".$row['availability']."'"."," . "'" . $row['size'] . "'" .');',
  '</script>';
}
$dbc->close();
?>

</body>
</html>