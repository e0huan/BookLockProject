<?php 
session_start(); // Start the session before headers are sent.
?>
<!-- This file creates the administrator version of the map page when the user creates a new room. It runs when the 'Next' button in the questions page is clicked. It created the customized room according to the user's choices. -->

<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BookLock</title>
  <link href="mappage.css" rel="stylesheet" type="text/css">
  <!--Google font Open Sans Light-->
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&display=swap" rel="stylesheet">
  <!--JQuery-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
</head>
<body>
<!-- Help button -->
<button id="newmyBtn"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle bi_custom" viewBox="0 0 16 16">
<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
</svg></button>

<!-- Room code -->
<p class="par1">
  <?php echo "Code: " . $_SESSION['code']; ?>
</p>

<!-- room name -->
<p class="par2">
  <?php echo "" . $_SESSION['class']; ?>
</p>

<!-- Help instructoins modal -->
<div id="newmyModal" class="newmodal">
  <!-- Modal content -->
  <div class="newmodal-content">
    <span class="newclose">&times;</span>
    <!--<p class="newbooking">Confirm your Booking</p>-->
    <p class="newinquiry" id="newinquiry">&nbsp;1. Click and drag the squares to their positions. <br><br>&nbsp;2. Click the save button when you are done.<br><br>&nbsp;3. Click the edit button to edit your map again.<br><br>&nbsp;4. Book a space by clicking on an available square. <br><br>&nbsp;5. Confirm your choice. <br><br>&nbsp;6. To switch, click a new square. </p>
  </div>
</div>

<!-- Confirm booking modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p class="booking">Confirm your Booking</p>
    <p class="inquiry" id="inquiry">Do you want to book space </p>
    <button class="confirm" id="confirm">Confirm</button>
  </div>
</div>

<!-- body background color -->
<body style="background-color:#DCE6ED;">

<!-- Rectangle to hold squares -->
<div class="rectangle" id="rectangle"></div>

<!-- Squares availability legend -->
<div class="availsquare"></div>
<div class="unavailsquare"></div>
<div class="ownsquare"></div>
<p class="textavail" style="font-family:'Open Sans', sans-serif">Available locker</p>
<p class="textunavail" style="font-family:'Open Sans', sans-serif">Unvailable locker</p>
<p class="textown" style="font-family:'Open Sans', sans-serif">Your locker</p>

<!-- container in rectangle to hold squares -->
<div id=container></div>

<!-- save/edit button -->
<button class= "edit" onclick="myFunction(this);">Save</button>

<!-- JQuery -->
<script type="text/javascript">
  document.write("\<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'>\<\/script>");
</script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
// Variable to determine when to allow the owner to book a space (for whatever reason that may be necessary). They should only be allowed when they have clicked the save button to save the positions of the spaces and the squares are no longer draggable. This is because when allowing this while the squares are draggable, it hinders dragging the squares and is inconvenient.
var saved = "no";

// Proceed when the save/edit button is clicked.
function myFunction(button) {
  // If the button is clicked to save the positions of the squares.
  if (button.innerHTML == "Save"){
    // The positions are saved, the save button becomes an edit button, and the squares are no longer draggable.
    saved = "yes";
    button.innerHTML = "Edit"
    $( "square" ).draggable({ disabled: true });

    // For the number of squares there are, obtain their positions.
    for (c = 0; c < (row * column); c++) {
      const chosenSquare = document.getElementById(""+(c+1));
      var chosSquare = chosenSquare.getBoundingClientRect();

      x = chosSquare.left;
      y = chosSquare.top;
      w = chosSquare.width;
      h = chosSquare.height;

      // Pass the data to the mapupdate.php file to update the positions into the database.
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
  // Else, the edit button was clicked to modify the map of the room again. The positions are not saved, the edit button becomes a save button, and the squares are draggable again.
  }
  else {
    saved = "no";
    button.innerHTML = "Save"
    $( "square" ).draggable({ disabled: false });
  }
}

// Access the number of rows and number of columns there are for the placement of the squares.
var row = "<?php echo $_SESSION['row']; ?>";
var column = "<?php echo $_SESSION['column']; ?>";

var cont = document.getElementById("rectangle");

// Restrict the draggable area to the container (inside the rectangle with a border).
$( function() {
  $( "square" ).draggable({ containment: "#container" });
  scroll: false
} );

// The user has yet to book a square.
var ownsquareid = "";

// Create the squares.
function makeRows(rows, cols) {
  cont.style.setProperty('--grid-rows', rows);
  cont.style.setProperty('--grid-cols', cols);
  // Create the number of squares according to the number of rows and columns.
  for (c = 0; c < (rows * cols); c++) {
    // Create the square and set its class and id.
    let square = document.createElement("square");
    square.setAttribute("class", "square");
    square.setAttribute("id", ""+ (c + 1));

    // Add the number of the square on each element created.
    var h1 = document.createElement("h1");
    h1.textContent = c + 1;

    // Append the elements.  
    square.appendChild(h1);
    cont.appendChild(square).className = "grid-item";

    // Get the height and width of the container for calculation of the size of the squares.
    var containerHeight = $('#container').innerHeight();
    var containerWidth = $('#container').innerWidth();

    // For each square created, half of the container should be empty space to enable the user to place the squares to desired positions.
    var squareHeight = (containerHeight) / (rows*2);
    var squareWidth = (containerWidth) / (cols*2);

    // Set the square's height and width to the smallest value between the two. Since the length of the container is larger than its height, we don't want the squares to overfill the height of the container if there are many squares and their lengths are too large.
    if (squareHeight < squareWidth){
      squareWidth = squareHeight;
    }
    else if (squareHeight > squareWidth){
      squareHeight = squareWidth;
    }

    $('square').height(squareHeight);
    $('square').width(squareWidth);

    // Proceed if any squares are clicked on the map page.
    square.onclick = function(){
    
      // Allow the owner to book a square by clicking on it only if the positions of the square are saved and they are not draggable anymore.
      if (saved == "yes"){
 
        // Display message with the square's number on the modal that pops up when a square is clicked.
        document.getElementById("inquiry").innerHTML = "Do you want to book space "+square.id+"?"; 

        // Get the color of the square that is clicked.
        var bg_color = window.getComputedStyle(square, null).backgroundColor;
        bg_color = bg_color.match(/\d+/g);

        function componentToHex(c) {
          var hex = c.toString(16);
          return hex.length == 1 ? "0" + hex : hex;
        }
        function rgbToHex(rgb) {
          return "#" + componentToHex(+rgb[0]) + componentToHex(+rgb[1]) + componentToHex(+rgb[2]);
        }

        // If the square is yellow (available), proceed.
        if (rgbToHex(bg_color) == "#ffd96a"){
          // Get the modal element and close button.
          var modal = document.getElementById("myModal");
          var span = document.getElementsByClassName("close")[0];

          modal.style.display = "block";

          // If the close button is clicked, the modal is closed.
          span.onclick = function() {
            modal.style.display = "none";
          }
          if (event.target == modal) {
            modal.style.display = "none";
          }

          // Confirm button on the modal to confirm the booking.
          var confirm = document.getElementById("confirm");

          // Change the color of the available square once booked and pass the values to mapupdate.php from post to update the databse.
          confirm.onclick = function() {  
          // If the user has already booked a square in this room.
            if (ownsquareid != ""){
            // Get the square that has already been booked from the id.
              var ownsquare = document.getElementById(ownsquareid);

              // Change the color of the previous square (ownsquare) to yellow - available again since not more than one space can be booked in a room. The new square become the user's booked space - light blue.
              ownsquare.style.backgroundColor = "#FFD96A";
              square.style.backgroundColor = "#98A6E8";

              // Pass the data to update the availability of the squaree in the database.
              var previous = ownsquareid;
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

              // The new square's id becomes the id of the uesr's booked space and the model is closed.
              ownsquareid = square.id;
              modal.style.display = "none";
            }
            // The user has not already booked a space in this room. 
            else {
              // This is the first time so the color of one square is changed. The square becomes light blue.
              square.style.backgroundColor = "#98A6E8";

              // Pass the information to update the database.
              $(document).ready(function(){
              $.post("newmapupdate.php",
              {
                functionname: 'addown', 
                'own' : ''+square.id
              },
              function(data,status){
              });
              });
              // The square's id becomes the id of the user's booked space and the model closes.
              ownsquareid = square.id;
              modal.style.display = "none";
            }
          }
        }
      }
    }
  };
};
// Create the rows and columns of squares
makeRows(row, column);

// Update the initial positions of the squares once the room is created (in case the user does not drag the squares to new positions and save).
function initialUpdate(){
    // For the number of squares there are, obtain their positions.
    for (c = 0; c < (row * column); c++) {
      const chosenSquare = document.getElementById(""+(c+1));
      var chosSquare = chosenSquare.getBoundingClientRect();

      x = chosSquare.left;
      y = chosSquare.top;
      w = chosSquare.width;
      h = chosSquare.height;

      // Pass the data to the mapupdate.php file to update the positions into the database.
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

// Call the function to update the initial positions of the squares after the page loads.
document.addEventListener('DOMContentLoaded', function() {
   initialUpdate();
}, false);

// Get the modal element
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


<!-- open menu button -->
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

// Open menu and shift page content.
function openNav() {
  document.getElementById("main").style.marginLeft = "25%";
  document.getElementById("mySidebar").style.width = "25%";
  document.getElementById("mySidebar").style.display="block"; document.getElementById("chicken").style.display="none";
}

// Close menu and return page content.
function closeNav() {
  document.getElementById("main").style.marginLeft= "0%";
  document.getElementById("mySidebar").style.display = 'none';
  document.getElementById("chicken").style.display = 'inline-block';
}
</script>

</body>
</html>