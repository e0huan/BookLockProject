// This file is associated with the profile.php file. It tells the menu what to do and displays the user's email on the profile page. It also creates the rows in the profile table from data passed by profile.php. When the save/edit button is clicked, it also determines when the locker combinations are editable or not.

/*Elaine's and Catherine*/

// User's email.
var email = document.getElementById("email");
email.innerText = "Email: "+googlemail;

// Id of the row in the table.
var total = 0;

// Create a new row in the table.
function addCell(combo, roomname, number){
  // The odd rows in the table are a different color.
  $(document).ready(function()
  {
  $("tr:odd").css({
    "background-color":"#f3d6d6a6",
    "color":"#e61111"});
  });

  // Insert a new row in the table.
  var table = document.getElementById("table");
  var newRow = table.insertRow();
  var newCell1 = newRow.insertCell();
  var newCell2 = newRow.insertCell();
  var newCell3 = newRow.insertCell();

  total++;

  // Create the room name column in the row.
  var room = document.createElement("p");
  room.className = "column";
  room.innerHTML = roomname;
  room.setAttribute("id", "roomname"+total);
  newCell1.appendChild(room);

  // Create the locker number column in the row.
  var locknum = document.createElement("p");
  locknum.className = "column";
  locknum.innerHTML = number;
  locknum.setAttribute("id", ""+total);
  newCell2.appendChild(locknum);
    
  // Create the editable locker combination column in the row.
  var par = document.createElement("p");
  par.contentEditable = "true";
  par.setAttribute("id", "combo"+total);
  par.innerHTML = combo;
  par.className = "input";
  newCell3.appendChild(par);

  // Only numbers are allowed in the editable locker combination column. 
  $(par).keypress(function(e) {
    var x = event.charCode || event.keyCode;
    if (isNaN(String.fromCharCode(e.which)) && x!=46 || x===32 || x===13 || (x===46 && event.currentTarget.innerText.includes('.'))) e.preventDefault();
  });
  // The delete key can also be used. The user can only input a maximum of 6 characters for the locker combination.
  $(par).on('keydown paste', function(event) { 
    //Prevent on paste as well 
    //You can add delete key event code as well over here for windows users.
    if($(this).text().length === 6 && event.keyCode != 8) { 
      event.preventDefault();
    }
  });
}

// When the save/edit button is clicked.
function myFunction(button) {
  var i;
  // Execute for every row.
  for (i=1; i<(total+1); i++){
    // Get the information in the row.
    var x = document.getElementById("combo"+i);
    var num = document.getElementById(""+i);
    var roomnum = num.textContent;
    var nam = document.getElementById("roomname"+i);
    var roomnam = nam.textContent;

    // If the locker combination paragraphs are editable when the button was clicked. This means that the "save" button was clicked.
    if (x.contentEditable == "true"){
      // The paragraphs are no longer editable and the button becomes an "edit" button.
      x.contentEditable = "false";
      button.innerHTML = "edit";

      // Update the database.
      $(document).ready(function(){
      $.post("profileupdatecombo.php",
      {
        functionname: 'update', 
        'lockercombo': ''+x.innerHTML, 
        'roomname' : ''+roomnam, 
        'roomnumber' : ''+roomnum 
      },
      function(data,status){
      });
      });
    }
    // The "edit" button was clicked. It becomes a "save" button and the locker combinations are editable.
    else {
      x.contentEditable = "true";
      button.innerHTML = "save";
    }
  }
}

// Open the menu bar, shift page content.
function openNav() {
  document.getElementById("main").style.marginLeft = "25.75%";
  document.getElementById("mySidebar").style.width = "25%";
  document.getElementById("mySidebar").style.display="block"; document.getElementById("open").style.display="none";
}

// Close the menu bar, return page content.
function closeNav() {
  document.getElementById("main").style.marginLeft= "0%";
  document.getElementById("mySidebar").style.display = 'none';
  document.getElementById("open").style.display = 'inline-block';
}
