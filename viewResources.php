<html>
<head>
  <link rel="stylesheet" type="text/css" href="./css/viewPage.css?<?php echo time(); ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
</head>

<?php
session_start();
$db_file = './myDB/victimAdv.db';
$currentPage = 'viewResources';

try {
  //open connection to the airport database file
  $db = new PDO('sqlite:' . $db_file);

  //set errormode to use exceptions
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(!isset($_SESSION['username'])){
    header("Location:index.php");
  }else{
    $username = $_SESSION['username'];
    $query = "SELECT role FROM User WHERE username = '".$username."' LIMIT 1;";
    $result = $db->query($query);

    $tuple = $result->fetch(PDO::FETCH_ASSOC);

    $role = $tuple['role'];

    if(strcmp($role, "admin") !=0){
      header("location:index.php?error=invalidCredentials");
    }
  }
  include 'header-admin.php';
  ?>
  <body>
    <?php
    showResourceTable($db);
    ?>
  </body>

  <?php
}
catch(PDOException $e) {
  die('Exception : '.$e->getMessage());
}


function showResourceTable($db){
  $deleteVerification = true;
  $query_str = "select * from Resource;";
  $result_set = $db->query($query_str);

  ?>
  <div class="table-button">
  <div class="table-users">
    <div class="header">Resources</div>

    <table cellspacing="0">
      <tr>
        <th>Contact</th>
        <th width="230">Description</th>
        <th></th>
        <th></th>
      </tr>
      <?php
      //loop through each tuple in result set and print out the data
      //ssn will be shown in blue (see below)
      foreach ($result_set as $tuple) { ?>
          <tr>
            <td> <?php echo $tuple['contact']; ?></td>
            <td> <?php echo $tuple['description']; ?></td>
            <td> <form action="./update-resource-form.php" method="post"> <input type="hidden" name="ID" value="<?php echo $tuple['ID']; ?>" > <button type="submit" value="Update">Update </button> </form> </td>
            <td> <form action="./delete-resource.php?ID=<?php echo $tuple['ID'];?>" method="post"> <button type="submit" value="Delete">Delete </button> </form> </td>

          </tr>
      <?php } // end for loop ?>
    </table>
    </div>
    <?php createResource(); ?>
  </div>
<?php
}


function createResource(){ ?>
  <!-- Button to open the modal -->
<button id="create-button" onclick="document.getElementById('id01').style.display='block'">Create Resource</button>

<!-- The Modal (contains the Sign Up form) -->
<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="./create-resource.php" method="post">
    <div class="container">
      <h1>Sign Up</h1>
      <p>Please fill in this form to create a new resource.</p>
      <hr>

      <label for="contact"><b>Contact Information (link or phone number)</b></label>
      <input type="text" placeholder="Enter Contact" name="contact" required>

      <label for="description"><b>Description</b></label>
      <textarea name="description" placeholder="Enter Description" id="description"> </textarea>

      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn">Submit</button>
      </div>
    </div>
  </form>
</div>
<?php }


function updateResourceForm($db, $ID){
  $query = "SELECT * FROM Resource WHERE ID = '" . $ID . "' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);

  $contact = $tuple['contact'];
  $description = $tuple['description'];


  ?>
  <!-- Button to open the modal -->
<!--<button id="create-button" onclick="document.getElementById('id01').style.display='block'">Create Advocate</button> -->

<!-- The Modal (contains the Sign Up form) -->
<div id="id02" class="modal">
  <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="./update-resource-post.php" method="post">
    <div class="container" id="updateForm">
      <h1>Update Resource Information</h1>
      <p>Please fill in this form to update this resource.</p>
      <hr>

      <input type="hidden" name="ID" value="<?php echo $ID?>" required>

      <label for="fname"><b>Contact</b></label>
      <input type="text" name="contact" placeholder="Contact" value="<?php echo $contact?>" required="Please enter the first name">

      <label for="lname"><b>Description</b></label>
      <input type="text" name="description" placeholder="Description" value="<?php echo $description?>" required="Please enter the last name">

      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn">Update</button>
      </div>
    </div>
  </form>
</div>
<?php }

function deleteResource($db, $ID){ ?>

  <div id="id03" class="modal">
    <span onclick="document.getElementById('id03').style.display='none'" class="close" title="Close Modal">×</span>
    <form class="modal-content" method="post" action="./delete-resource.php">
      <input type="hidden" name="ID" value="<?php echo $ID; ?>">
      <div class="container-delete">
        <h1>Delete Resource</h1>
        <p>Are you sure you want to delete this resource?</p>

        <div class="clearfix">
          <button type="button" onclick="document.getElementById('id03').style.display='none'" class="cancelbtn-grey">Cancel</button>
          <button type="submit" onclick="document.getElementById('id03').style.display='none'" class="deletebtn">Delete</button>
        </div>
      </div>
    </form>
  </div>
<?php } ?>

</html>
