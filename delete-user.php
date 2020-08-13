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
  if(isset($_GET['username'])){
    $username = $_GET['username'];
    //prompt delete form
    deleteUserForm($db,$username);

    if(isset($_POST["delete-user"])){
      sbDeleteUser($username);
      $sql = "DELETE FROM User WHERE username = '".$username."';";
      $db->exec($sql);
      header("Location: viewAdvocates.php");
    }
  }else{
    header("Location: viewAdvocates.php");
  }

  //disconnect from database
  $db = null;
} catch(PDOException $e){
  die('Exception : '.$e->getMessage());
}


function deleteUserForm($db,$username){
  //get current data from database
  $query = "SELECT * FROM User WHERE username = '" . $username . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);

  ?>
  <form action="" style="border:1px solid #ccc" method="post">
  <div class="container">
    <h1>Delete Account</h1>
    <p>Are you sure you want to delete this account?</p>
    <hr>

    <div class="clearfix">
      <a href="viewAdvocates.php">
        <button type="button" class="cancelbtn"> Cancel</button>
      </a>
      <button type="submit" name="delete-user"  class="signupbtn">Delete</button>
    </div>


    </div>
  </div>
</form>
<?php }

function sbDeleteUser($USER_ID)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-1920207D-9A3E-4ADC-A8FF-F70D816B0E6F.sendbird.com/v3/users/$USER_ID",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            "Api-Token: 1378399f93c26da80722fab836b1134a545da721"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    //echo $response;
}
?>
</html>
