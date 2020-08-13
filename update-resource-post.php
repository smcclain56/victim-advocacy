<?php
$msg = "";

// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
} else {
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

 //redirect to index.php if not logged in
 if (strcmp($role, "admin") != 0) {
   header("location:index.php?error=invalidCredentials");
 }


//Check if inputs are empty
$required = array('ID', 'contact', 'description');
//Loop over field names, make sure each one exists and is not empty
$error = false;
foreach($required as $field) {
  if (empty($_POST[$field])) {
    $error = true;
  }
}
if ($error) {
	$msg = "Please enter all fields";
  header("Location: update-resource-form.php");
}


echo $msg;
$ID = $_POST['ID'];
$contact = $_POST['contact'];
$description = $_POST['description'];
/*Doesnt change the primary key...*/
$stmt = $db->prepare("UPDATE Resource SET contact=:contact, description=:description where ID='".$ID."';");
$stmt->bindParam(':contact', $_POST['contact']);
$stmt->bindParam(':description', $_POST['description']);

$stmt->execute();




 //disconnect from database
 $db = null;
 }
catch(PDOException $e)
 {
 die('Exception : '.$e->getMessage());
 }
//redirect user to another page
header("Location: viewResources.php?success=1");

?>
