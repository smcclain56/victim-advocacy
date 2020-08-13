<?php
  session_start();
  $currentUser = $_SESSION['username'];
  $currentPage = "viewResources";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Resource</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./css/updateForm.css?<?php echo time(); ?>" />

</head>
<body>


<p>
    <?php

        //path to the SQLite database file
        $db_file = './myDB/victimAdv.db';

        try {
            //make sure resource id is given
            if(isset($_POST['ID'])){
              $ID = $_POST['ID'];
            }else{ //no ID
              header("Location: viewResources.php?err=error");
            }

            //establish database connection
            $db = new PDO('sqlite:'. $db_file);

            //set errormode to use exceptions
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //make sure user has proper credentials
            $query_user_str= "SELECT * FROM User WHERE username='".$currentUser."';";
            $result_role = $db->query($query_user_str);
            $User = $result_role->fetch(PDO::FETCH_ASSOC);
            $role = $User['role'];

            //redirect to index.php if not logged in
            if (strcmp($role, "advocate") != 0 && strcmp($role, "admin") != 0) {
              header("location:index.php?error=invalidCredentials");
            }

            //include header file
            include 'header-admin.php';

            //pull up update form
            updateResourceForm($db,$ID);

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
function updateResourceForm($db,$ID){
  //get current data from database
  $query = "SELECT * FROM Resource WHERE ID = '" . $ID . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);

  $contact = $tuple['contact'];
  $description = $tuple['description'];


  ?>
  <form action="./update-resource-post.php" style="border:1px solid #ccc" method="post">
  <div class="container">
    <h1>Update Resource Information</h1>
    <p>Please fill in this form to update this resource.</p>
    <hr>

    <input type="hidden" name="ID" value="<?php echo $ID; ?>">
    <label for="contact"><b>URL</b></label>
    <input type="text" placeholder="Enter URL" value="<?php echo $contact;?>" name="contact" required>

    <!--<label for="description"><b>Description</b></label>
    <textarea name="description" placeholder="Enter Description" value="<?php echo $description;?>" id="description"> </textarea>
-->
<label for="description"><b>Description</b></label>
<input type="text" name="description" placeholder="Description" value="<?php echo $description?>" required="Please enter the description">


    <div class="clearfix">
      <a href="viewResources.php">
        <button type="button" class="cancelbtn"> Cancel</button>
      </a>
      <button type="submit" class="signupbtn">Update</button>
    </div>


    </div>
  </div>
</form>
<?php }
?>
