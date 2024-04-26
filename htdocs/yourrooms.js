//This file is the javascript for the yourrooms.php file. It makes the menu sidebar open once the menu button is clicked and makes it close when the close button is clicked. It also opens or closes the Help instructions modal.

//Elaine
//opens menu
function openNav() {
  
  document.getElementById("main").style.marginLeft = "25%";
  document.getElementById("mySidebar").style.width = "25%";
  document.getElementById("mySidebar").style.display="block"; document.getElementById("open").style.display="none";
}
//closes menu
function closeNav() {
  
  document.getElementById("main").style.marginLeft= "0%";
  document.getElementById("mySidebar").style.display = 'none';
  document.getElementById("open").style.display = 'inline-block';
}
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