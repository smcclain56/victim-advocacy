<?php

session_start();
// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
}

$db_file = './myDB/victimAdv.db';

try {
  //open connection to the airport database file
  $db = new PDO('sqlite:' . $db_file);

  //set errormode to use exceptions
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(!empty($_POST)){
    if(isset($_POST['Username']) && isset($_POST['Password'])){
      $username = $_POST['Username'];
      $password = $_POST['Password'];
    }

    $query = "SELECT * FROM User WHERE username = '".$username."' LIMIT 1;";
    $result = $db->query($query);
    $i = 0;

    foreach ($result as $tuple) {
      $i = $i +1;
      $password_hash = $tuple['password'];
      if (password_verify($password, $password_hash)) { //genuine password
        $_SESSION['username'] = $tuple['username'];
        if(strcmp($tuple['role'], "advocate") ==0){
          header("location:home-advocate.php?userid=". $username ." ");
        }else{
          header("location:home-admin.php?userid=". $username ." ");
        }
      }else{ //invalid password
        header("location:index.php?error=invalidPassword");
      }
    }

    if($i == 0){ //No user by that username
      header("location:index.php?error=noUser");
    }
  }


  // //disconnect from db
  $db = null;
}
catch(PDOException $e) {
  die('Exception : '.$e->getMessage());
}


?>
