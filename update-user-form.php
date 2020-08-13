<?php

session_start();

// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
} else {
  $currentUser = $_SESSION['username'];
}

// get username information
if(isset($_GET['username'])){ //modify own account
  $username = $_GET['username'];
}else if(isset($_POST['username'])){ //modify a different advocate account
  $username = $_POST['username'];
}else{
  header("Location: viewAdvocates.php?err=error");
}

if(strcmp($username, $currentUser) == 0){ //if user is editing own account
  $currentPage = "modifyAccount";
}else{
  $currentPage = "updateAdvocate";
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Update User</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="./css/updateForm.css?<?php echo time(); ?>" />
</head>
<body>

  <p>
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
      $tupleUser = $result_role->fetch(PDO::FETCH_ASSOC);
      $currentUserRole = $tupleUser['role'];

      if(strcmp($currentUserRole,"admin") == 0){ //an admin
        include 'header-admin.php';
      }else if(strcmp($currentUserRole,"advocate")==0){ //an advocate
        include 'header-advocate.php';
      }else{ //redirect to homepage since not proper credentials
        header("location:index.php?error=invalidCredentials");
      }

      updateAdvocateForm($db,$username,$currentPage);
      // //$query_str = "SELECT * FROM Student WHERE studentId='$_GET[studentId]';";
      // $query_str = "SELECT * FROM User WHERE username='".$username."';";
      // $result = $db->query($query_str);
      //
      // $query_user_str= "SELECT * FROM User WHERE username='".$currentUser."';";
      // $result_role = $db->query($query_user_str);
      // $tupleUser = $result_role->fetch(PDO::FETCH_ASSOC);
      // $currentUserRole = $tupleUser['role'];
      //
      // $username = $fname = $lname = $role = ""; //global var for html to use
      // foreach ($result as $tuple) {
      //   $username = $tuple['username'];
      //   $fname = $tuple['fname'];
      //   $lname = $tuple['lname'];
      //   $role = $tuple['role'];
      //   $email = $tuple['email'];
      // }

      //disconnect from db
      $db = null;
    }
    catch(PDOException $e) {
      die('Exception : '.$e->getMessage());
    }
    ?>



</body>

</html>

<?php
function updateAdvocateForm($db,$username,$currentPage){
  $currentUser = $_SESSION['username'];
  $query = "SELECT * FROM User WHERE username = '" . $currentUser . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);
  $currentUserRole = $tuple['role'];

  $query = "SELECT * FROM User WHERE username = '" . $username . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);

  $fname = $tuple['fname'];
  $lname= $tuple['lname'];
  $email = $tuple['email'];
  $role = $tuple['role'];


  ?>
  <form action="./update-user-post.php" style="border:1px solid #ccc" method="post">
  <div class="container">
    <h1>Update User Information</h1>
    <p>Please fill in this form to update this account.</p>
    <hr>

    <label for="username"><b>Username</b></label>
    <input type="text" name="username" placeholder="username" value="<?php echo $username?>" required readonly>

    <label for="fname"><b>First Name</b></label>
    <input type="text" name="fname" placeholder="First Name" value="<?php echo $fname?>" required="Please enter the first name">

    <label for="lname"><b>Last Name</b></label>
    <input type="text" name="lname" placeholder="Last Name" value="<?php echo $lname?>" required="Please enter the last name">

    <label for="email"><b>Email</b></label>
    <input type="text" name="email" placeholder="Email" value="<?php echo $email?>" required="Please enter the email">

    <div class="role">
        <input type="radio" id="admin" name="role" value="admin"  <?php
        if(strcmp($role, "admin")==0){
          echo "checked= \"checked\"";
        }else if(strcmp($currentUser,"victimAdvocacyAdmin") != 0){
          echo "onclick=\"return false;\"";
        } ?> > Admin

        <input type="radio" id="advocate" name="role" value="advocate" <?php
        if(strcmp($role, "advocate")==0){
          echo "checked= \"checked\" ";
        }else if(strcmp($currentUser,"victimAdvocacyAdmin") != 0){
          echo "onclick=\"return false;\"";
        } ?> > Advocate

    </div>
    <div class="clearfix">
      <?php if(strcmp($currentUserRole,"admin") == 0 && $currentPage == 'updateAdvocate'){ //admin account edits advcocate?>
        <a href="viewAdvocates.php">
          <button type="button" class="cancelbtn"> Cancel</button>
        </a>
      <?php }else if(strcmp($currentUserRole,"admin") == 0 && $currentPage != 'updateAdvocate'){ //admin account edits own account ?>
        <a href="home-admin.php">
          <button type="button" class="cancelbtn"> Cancel</button>
        </a>
      <?php }else{ //advocate edits own account?>
        <a href="home-advocate.php">
          <button type="button" class="cancelbtn"> Cancel</button>
        </a>
      <?php } ?>

      <!--<button type="button" class="cancelbtn">Cancel</button>-->
      <button type="submit" class="signupbtn">Update</button>

      <?php
      if(strcmp($currentPage,"modifyAccount")==0){ //modify own account ?>
        <a href="update-password.php">
          <button type="button" class="passwordbtn"> Update Password</button>
        </a>
      <?php } ?>
    </div>
  </div>
</form>
<?php }
?>
