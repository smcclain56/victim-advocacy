<?php

session_start();

// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
} else {
  $currentUser = $_SESSION['username'];
}

///set current page for header
$currentPage = "modifyAccount";

?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="./css/updateForm.css?<?php echo time(); ?>" />
</head>
<body>

    <?php

    //path to the SQLite database file
    $db_file = './myDB/victimAdv.db';

    try {
      //open connection to the  database file
      $db = new PDO('sqlite:'.$db_file);

      //set errormode to use exceptions
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $query_user_str= "SELECT * FROM User WHERE username='".$currentUser."';";
      $result_role = $db->query($query_user_str);
      $User = $result_role->fetch(PDO::FETCH_ASSOC);

      $role = $User['role'];


      //add appropiate header based on role
      if(strcmp($role,"advocate")==0){
        include 'header-advocate.php';
      }else if(strcmp($role,"admin")==0){
        include 'header-admin.php';
      }

      $password_hash = $User['password'];


      if(isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST["verify_new_password"])){
        //throw error if password doesn't match db
        if (!password_verify($_POST['current_password'], $password_hash)) { //genuine password
          header("Location: update-password.php?error=0");
        }
        //throw error if new passwords dont match
        if(strcmp($_POST['new_password'],$_POST['verify_new_password']) !=0){
          header("Location: update-password.php?error=1");
        }

        // hash new password and update the database
        $new_password = password_hash($_POST['new_password'], PASSWORD_ARGON2I );
        $stmt = $db->prepare("UPDATE User SET password=:password WHERE username='".$currentUser."';");
        $stmt->bindParam(':password', $new_password);

        //Successfully changed password
        header("Location: update-password.php?error=2");

      }
      //disconnect from db
      $db = null;
    }
    catch(PDOException $e) {
      die('Exception : '.$e->getMessage());
    }

    ?>
    <form action="" style="border:1px solid #ccc" method="post">
    <div class="container">
      <h1>Update Password</h1>
      <p>Please fill in this form to update the password on this account</p>
      <hr>

      <div class="input">
        <label>Current Password</label>
        <input type="password" name="current_password"  required>
        <?php
        if(isset($_GET['error']) && $_GET['error'] == 0){
          ?> <p class="error"> Invalid password entered </p> <?php
        }
         ?>
      </div>

      <div class="input">
        <label>New Password</label>
        <input type="password" name="new_password"  required>
      </div>

      <div class="input">
        <label>Confirm Password</label>
        <input type="password" name="verify_new_password" required>

        <?php
        if(isset($_GET['error']) && $_GET['error'] == 1){
          ?> <p class="error"> Passwords do not match </p> <?php
        }

        if(isset($_GET['error']) && $_GET['error'] == 2){
          ?> <p class="success"> Successfully changed password </p> <?php
        }
        ?>
      </div>

      <div class="clearfix">
        <?php if(strcmp($role,"admin")==0){ //admin ?>
          <a href="home-admin.php">
            <button type="button" class="cancelbtn"> Cancel</button>
          </a>
        <?php }else{ //advocate ?>
          <a href="home-advocate.php">
            <button type="button" class="cancelbtn"> Cancel</button>
          </a>
        <?php } ?>

        <button type="submit" class="signupbtn">Update</button>
      </div>

      </div>
  </form>


</body>

</html>
