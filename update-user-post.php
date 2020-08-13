<?php
session_start();
$msg = "";
$currentUser = $_SESSION['username'];
try {
  //open the sqlite database file
  $db = new PDO('sqlite:./myDB/victimAdv.db');
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $query_user_str= "SELECT * FROM User WHERE username='".$currentUser."';";
  $result_role = $db->query($query_user_str);
  $tupleUser = $result_role->fetch(PDO::FETCH_ASSOC);
  $currentUserRole = $tupleUser['role'];

  //redirect to index.php if not logged in
  if (strcmp($currentUserRole, "advocate") != 0 && strcmp($currentUserRole, "admin") != 0) {
    header("location:index.php?error=invalidCredentials");
  }


  //Check if inputs are empty
  $required = array('username', 'fname', 'lname','role','email');
  //Loop over field names, make sure each one exists and is not empty
  $error = false;
  foreach($required as $field) {
    if (empty($_POST[$field])) {
      $error = true;
    }
  }
  if ($error) {
    $msg = "Please enter all fields";
    header("Location: update-user-form.php");
  }

  $username = $_POST['username'];
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $role = $_POST['role'];
  $email = $_POST['email'];
  $token = sbUpdateUser($username, " ");
  /*Doesnt change the primary key...*/
  $stmt = $db->prepare("UPDATE User SET username = :username, fname=:fname, lname=:lname, role=:role, email=:email, token=:token WHERE username='".$username."';");
  $stmt->bindParam(':username', $username);
  $stmt->bindParam(':role', $role);
  $stmt->bindParam(':fname', $fname);
  $stmt->bindParam(':lname', $lname);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':token', $token);

  $stmt->execute();

  $currentUser = $_SESSION['username'];
  $query = "SELECT role FROM User WHERE username = '".$currentUser."' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);
  $currentRole = $tuple['role'];

  if(strcmp($currentRole, "admin") == 0){
    header("Location: viewAdvocates.php?success=1");
  }else{
    header("Location: home-advocate.php?success=1");
  }
  echo $username;


  //disconnect from database
  $db = null;
}
catch(PDOException $e){
  die('Exception : '.$e->getMessage());
}



function sbUpdateUser($USER_ID, $NICKNAME)
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
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => "{\r\n            \"user_id\" : $USER_ID,\r\n            \"nickname\" : $NICKNAME,\r\n            \"profile_url\": null,\r\n            \"issue_access_token\": true,\r\n        }",
        CURLOPT_HTTPHEADER => array(
            "Api-Token: 1378399f93c26da80722fab836b1134a545da721",
            "Content-Type: text/plain"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
}
?>
