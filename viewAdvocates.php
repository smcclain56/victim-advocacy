<html>

<head>
  <link rel="stylesheet" type="text/css" href="css/viewPage.css?<?php echo time(); ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script>

    // Get the modal
    var modalCreate = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modalCreate) {
            modal.style.display = "none";
          }
    }

</script>
</head>



<?php
session_start();
$db_file = './myDB/victimAdv.db';
$currentPage = 'viewAdvocates';

try {
  //open connection to the airport database file
  $db = new PDO('sqlite:' . $db_file);

  //set errormode to use exceptions
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (!isset($_SESSION['username'])) {
    header("Location: index.php");
  } else {
    $currentUser = $_SESSION['username'];
    $query = "SELECT role FROM User WHERE username = '" . $currentUser . "' LIMIT 1;";
    $result = $db->query($query);

    $tuple = $result->fetch(PDO::FETCH_ASSOC);

    $role = $tuple['role'];

    if (strcmp($role, "admin") != 0) {
      header("location:login.php?error=invalidCredentials");
    }
  }
  include 'header-admin.php';
?>

  <body>
    <?php
    if (isset($_GET['user_id'])) {
      echo "get request worked";
      updateAdvocateForm($db,$username);
    }
    showAdvocateTable($db);


    ?>

  </body>

<?php
} catch (PDOException $e) {
  die('Exception : ' . $e->getMessage());
}


function showAdvocateTable($db){
  $deleteVerification = true;
  $currentUser = $_SESSION['username'];
  $query_str = "select * from User;";
  $result_set = $db->query($query_str);
  ?>
  <div class="table-button">
  <div class="table-users">
    <div class="header">Advocates</div>

    <table cellspacing="0">
      <tr>
        <th>Username</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th></th>
        <th></th>
      </tr>
      <?php
      //loop through each tuple in result set and print out the data
      //ssn will be shown in blue (see below)
      foreach ($result_set as $tuple) {
        $user = $tuple['username'];
        if (strcmp($tuple['username'], "victimAdvocacyAdmin") != 0) { ?>
          <tr>
            <td> <?php echo $tuple['username']; ?></td>
            <td> <?php echo $tuple['fname'] . " " . $tuple['lname']; ?></td>
            <td> <?php echo $tuple['email'] ?></td>
            <td> <?php echo $tuple['role'] ?></td>

          <?php
          if (strcmp($currentUser, "victimAdvocacyAdmin") == 0) {  //root account can update/delete anyone ?>
            <td> <form action="./update-user-form.php" method="post"> <input type="hidden" name="username" value="<?php echo $tuple['username']; ?>"> <button type="submit" value ="Update">Update</button> </form> </td>
            <td> <form action="./delete-user.php" method="post"> <input type="hidden" name="username" value="<?php echo $tuple['username']; ?>"> <button type="submit" value ="Delete">Delete</button> </form> </td>

          <?php } else {
            if (strcmp($tuple['role'], "admin") == 0) {  //admin cannot update/delete another admin ?>
              <td></td>
              <td></td>
            <?php } else {  // admin can update/delete advocates ?>
              <td> <form action="./update-user-form.php" method="post"> <input type="hidden" name="username" value="<?php echo $tuple['username']; ?>"> <button type="submit" value ="Update">Update</button> </form> </td>
              <td> <form action="./delete-user.php?username=<?php echo $tuple['username'];?>" method="post">  <button type="submit" value ="Delete">Delete</button> </form> </td>
            <?php }
          } ?>
          </tr>
        <?php }
      } // end for loop ?>
      </table>
    </div>
    <?php createAdvocate(); ?>
  </div>
<?php
}

function createAdvocate(){ ?>
  <!-- Button to open the modal -->
<button id="create-button" onclick="document.getElementById('id01').style.display='block'">Create Advocate</button>

<!-- The Modal (contains the Sign Up form) -->
<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="./create_advocate.php" method="post">
    <div class="container">
      <h1>Sign Up</h1>
      <p>Please fill in this form to create an account.</p>
      <hr>

      <label for="fname"><b>First Name</b></label>
      <input type="text" placeholder="Enter First Name" name="fname" required>

      <label for="lname"><b>Last Name</b></label>
      <input type="text" placeholder="Enter Last Name" name="lname" required>

      <label for="email"><b>Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="username"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="username" required>

      <label for="password"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <input type="radio" required id="admin" name="role" value="admin"> Admin
      <input type="radio" id="advocate" name="role" value="advocate"> Advocate <br>

      <!--<label>
        <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
      </label>

      <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>
    -->
      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn">Sign Up</button>
      </div>
    </div>
  </form>
</div>
<?php }

function updateAdvocateForm($db, $username){
  echo "UPDATE FORM";
  $currentUser = $_SESSION['username'];
  $query = "SELECT * FROM User WHERE username = '" . $username . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);

  $fname = $tuple['fname'];
  $lname= $tuple['lname'];
  $email = $tuple['email'];
  $role = $tuple['role'];


  ?>
  <!-- Button to open the modal -->
<!--<button id="create-button" onclick="document.getElementById('id01').style.display='block'">Create Advocate</button> -->

<!-- The Modal (contains the Sign Up form) -->
<div id="id02" class="modal">
  <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="./update-user-post.php" method="post">
    <div class="container" id="updateForm">
      <h1>Update User Information</h1>
      <p>Please fill in this form to update this account.</p>
      <hr>

      <label for="username"><b>Username</b></label>
      <input type="text" placeholder="Username" value="<?php echo $username?>" required readonly>

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
        <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn">Update</button>
      </div>
    </div>
  </form>
</div>
<?php }

function deleteAdvocate($db, $username){ ?>

  <div id="id03" class="modal">
    <span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-content" method="post" action="./delete-user.php">
      <input type="hidden" name="username" value="<?php echo $username; ?>">
      <div class="container-delete">
        <h1>Delete Account</h1>
        <p>Are you sure you want to delete this account?</p>

        <div class="clearfix">
          <button type="button" onclick="document.getElementById('id03').style.display='none'" class="cancelbtn-grey">Cancel</button>
          <button type="submit" onclick="document.getElementById('id03').style.display='none'" class="deletebtn">Delete</button>
        </div>
      </div>
    </form>
  </div>
<?php } ?>

</html>
