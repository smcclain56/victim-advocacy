<!DOCTYPE html>
<html lang="en">
  <?php session_start(); ?>
<head>
<title>Secure Chat</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="favicon(1).ico">
<link rel="stylesheet" href="css/chatStyle.css">
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Acme&display=swap" rel="stylesheet">
<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<style>
* {
  box-sizing: border-box;
}
.title {
  font-family: 'Acme', sans-serif;
}
.button1 {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
}

.button2 {

    padding: 10px 24px;
    display:block;
    background-color: #555555;
    color: white;
    text-align:center;
    position: absolute;
    top: 10px;
    right: 10px;

}

body * {
  /*font-family: Arial, Helvetica, sans-serif;*/
  font-family: 'Lato', sans-serif;
}

/* Style the header */
.header {
  background-color: #333;
  padding: 15px;
  text-align: center;
  font-size: 30px;
  color: white;
  margin-bottom: 1em;
}

.div1 {
  margin: 0 auto;
  width: 300px;
  height: 200px;
  background: #8a9da6;
  border: 5px solid;
  border-color: #628c6d;
  border-radius: 25px;
  text-align: center;
}
/* Position the Popup form */
      .login-popup {
      position: relative;
      text-align: center;
      width: 100%;
      }
      /* Hide the Popup form */
      .form-popup {
      display: none;
      position: fixed;
      left: 45%;
      top:5%;
      transform: translate(-45%,5%);
      border: 2px solid #666;
      z-index: 9;
      }
      /* Styles for the form container */
      .form-container {
      max-width: 300px;
      padding: 20px;
      background-color: #fff;
      }
      /* Full-width for input fields */
      .form-container input[type=text], .form-container input[type=password] {
      width: 100%;
      padding: 10px;
      margin: 5px 0 22px 0;
      border: none;
      background: #eee;
      }
      /* When the inputs get focus, do something */
      .form-container input[type=text]:focus, .form-container input[type=password]:focus {
      background-color: #ddd;
      outline: none;
      }
      /* Style submit/login button */
      .form-container .btn {
      background-color: #8ebf42;
      color: #fff;
      padding: 12px 20px;
      border: none;
      cursor: pointer;
      width: 100%;
      margin-bottom:10px;
      opacity: 0.8;
      }
      /* Style cancel button */
      .form-container .cancel {
      background-color: #cc0000;
      }
      /* Hover effects for buttons */
      .form-container .btn:hover, .open-button:hover {
      opacity: 1;
      }


.dropbtn {
  background-color: #3498DB;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #2980B9;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  overflow: auto;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}



/************************************/
/****** Style for popup login ******/
/************************************/

/* Full-width input fields */
.modal input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
.modal button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

.modal button:hover {
  opacity: 0.8;
}

/* Extra styles for the cancel button */
.modal .cancelbtn {
  background-color: #f44336;
  width: 20%;
  padding: 10px 18px;
  margin: 0 auto;
}

/* Position the close button */

.modal .container {
  padding: 16px;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 40%; /* Could be more or less, depending on screen size */
}

.modal .container {
  font-size: 50%;
  text-align: left;
}

.modal h1 {
  font-size: 65%;
  color: black;
}


/* The Close Button (x) */
.modal .close {
  position: absolute;
  right: 25px;
  top: 0;
  color: #000;
  font-size: 35px;
  font-weight: bold;
  margin: 0 auto;
}

.modal .close:hover,
.close:focus {
  color: red;
  cursor: pointer;
}

/* Add Zoom Animation */
.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)}
  to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
  from {transform: scale(0)}
  to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.password {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<body>

<div class="header">
  <h2 class="title">Secure Chat</h2>
  <!--<input type="button" class="button2" onclick="window.location.href = '/~advuser/login.php';" value="Advocate Login">
  -->
  <button class="button2" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Advocate Login</button>

<div id="id01" class="modal">

  <form class="modal-content animate" action="./authenticate-user.php" method="post">
    <h1> Victim Advocacy Login </h1>
    <div class="container">
      <label for="Username"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="Username" required>

      <label for="Password"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="Password" required>

      <button type="submit">Login</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
    </div>
  </form>
</div>

  <?php
      //session_start();
      //path to the SQLite database file
      $db_file = './myDB/victimAdv.db';

        try {
            //open connection to the airport database file
            $db = new PDO('sqlite:' . $db_file);

            //set errormode to use exceptions
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //return all Students and store the result set
            //$query_str = "select * from Students
            $query_str = "select * from Resource;";
            $result_set = $db->query($query_str);

                //echo "<th>Delete</th>";
            echo "<div class=\"dropdown\">";
            echo "<button onclick=\"myFunction()\" class=\"dropbtn\">Other Resources</button>";
            echo "<div id=\"myDropdown\" class=\"dropdown-content\">";

            //loop through each tuple in result set and print out the data
            //ssn will be shown in blue (see below)
            foreach($result_set as $tuple) {
                echo "<a href=\"$tuple[contact]\" target=\"_blank\">$tuple[description]</a>";
                //echo "<a href=\"$tuple[contact]\">$tuple[description]</a>";
            }
            echo "</div>";
            echo "</div>";
            if ( isset($_GET['success']) && $_GET['success'] == 1 ){
                // treat the succes case ex:
                echo "Success! :)";
            }
            //disconnect from db
            $db = null;
        }
        catch(PDOException $e) {
            die('Exception : '.$e->getMessage());
        }
?>

</div>

<div class="div1">
    <h2>Chat with an Advocate</h2>
    <div class="open-btn">
      <button class="button" onclick="openForm()"><strong>Create an ID</strong></button>
    </div>
    <div class="login-popup">
      <div class="form-popup" id="popupForm">
        <form action="chatVictim.php" class="form-container">
          <h2>Please Create a Nickname</h2>
          <label for="nickname">
          <strong>nickname</strong>
          </label>
          <input type="nickname" id="nickname" placeholder="Your Nickname" name="nickname">
          <button type="submit" class="btn">Log in</button>
          <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
        </form>
      </div>
    </div>
    <script>
      function openForm() {
        document.getElementById("popupForm").style.display="block";
      }

      function closeForm() {
        document.getElementById("popupForm").style.display="none";
      }

      /* When the user clicks on the button,
      toggle between hiding and showing the dropdown content */
      function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
      }

      // Close the dropdown if the user clicks outside of it
      window.onclick = function(event) {
      if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
    </script>

  </div>



</body>
</html>
