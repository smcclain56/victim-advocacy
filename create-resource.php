<?php

session_start();
// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
}else{
  $currentUser = $_SESSION['username'];
}

//path to the SQLite database file
$db_file = './myDB/victimAdv.db';

try {
  //open connection to the airport database file
  $db = new PDO('sqlite:' . $db_file);

  //set errormode to use exceptions
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  //make sure user has proper credentials
  $query_user_str= "SELECT * FROM User WHERE username='".$currentUser."';";
  $result_role = $db->query($query_user_str);
  $User = $result_role->fetch(PDO::FETCH_ASSOC);
  $role = $User['role'];

  //redirect to index.php if not an admin
  if (strcmp($role, "admin") != 0) {
    header("location:index.php?error=invalidCredentials");
  }

  //make sure form was filled out
  if(isset($_POST['contact']) && isset($_POST['description'])){
    $contact = $_POST['contact'];
    $description = $_POST['description'];

    //prepared statement for inserting resource
    $query = "INSERT INTO Resource (contact, description) VALUES (:contact, :description)";
    $stmt = $db->prepare($query);
    //$stmt->bindParam(':ID', $newID);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':description', $description);

    //execute query
    if($stmt->execute()){
      header("Location: viewResources.php?err=success");
    }else{
      header("Location: viewResources.php?err=error");
    }
  }else{ //form was not properly filled out
    header("Location: viewResources.php?err=error");
  }



  //disconnect from db
  $db = null;

}

catch(PDOException $e) {
  die('Exception : '.$e->getMessage());
}

?>
