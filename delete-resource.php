<html>

<head>
  <link rel="stylesheet" type="text/css" href="css/updateForm.css?<?php echo time(); ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
</head>


<?php
session_start();

// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
}else{
  $currentUser = $_SESSION['username'];
}

try {

  //open the sqlite database file
  $db = new PDO('sqlite:./myDB/victimAdv.db');
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

  //find username of advocate to delete
  if(isset($_GET['ID'])){
    $ID = $_GET['ID'];
    //prompt delete form
    deleteResourceForm($db,$ID);

    if(isset($_POST["delete-resource"])){
      $sql = "DELETE FROM Resource WHERE ID = '".$ID."';";
      $db->exec($sql);
      header("Location: viewResources.php");
    }
  }else{
    header("Location: viewResources.php");
  }

  //disconnect from database
  $db = null;
} catch(PDOException $e){
  die('Exception : '.$e->getMessage());
}


function deleteResourceForm($db,$ID){
  //get current data from database
  $query = "SELECT * FROM Resource WHERE ID = '" . $ID . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);

  ?>
  <form action="" style="border:1px solid #ccc" method="post">
  <div class="container">
    <h1>Delete Resource</h1>
    <p>Are you sure you want to delete this resource?</p>
    <hr>

    <div class="clearfix">
      <a href="viewResources.php">
        <button type="button" class="cancelbtn"> Cancel</button>
      </a>
      <button type="submit" name="delete-resource"  class="signupbtn">Delete</button>
    </div>


    </div>
  </div>
</form>
<?php } ?>

</html>
