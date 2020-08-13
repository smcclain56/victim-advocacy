<?php

session_start();
// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
}else{
  $currentUser = $_SESSION['username'];
}
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

  //make sure values were set in form
  if(isset($_POST['username']) && isset($_POST['fname']) && isset($_POST['lname']) &&
    isset($_POST['email']) && isset($_POST['role']) && isset($_POST['role']) &&
    isset($_POST['password'])){
      $username = $_POST['username'];
      $firstName = $_POST['fname'];
      $lastName = $_POST['lname'];
      $email = $_POST['email'];
      $role = $_POST['role'];
      $token = sbCreateUser($username, "Advocate: ". $firstName);
      $password = $_POST['password'];
      $hash = password_hash($password, PASSWORD_ARGON2I );

      $query = "INSERT INTO User (username, fname, lname, email, role, token, password) VALUES (:username, :fname, :lname, :email, :role, :token, :password)";
      $stmt = $db->prepare($query);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':fname', $firstName);
      $stmt->bindParam(':lname', $lastName);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':role', $role);
      $stmt->bindParam(':token', $token);
      $stmt->bindParam(':password', $hash);

      if($stmt->execute()){
        header("Location: viewAdvocates.php?err=success");
      }else{
        header("Location: viewAdvocates.php?err=error");
      }
    }else{ //not all values were put in
      header("Location: viewAdvocates.php?err=error");
    }

  //disconnect from db
  $db = null;

}

catch(PDOException $e) {
  die('Exception : '.$e->getMessage());
}

function sbCreateUser($USER_ID, $NICKNAME)
{
  echo "creating user ";

  $DATA = array("user_id" => $USER_ID,  "nickname"=>$NICKNAME, "profile_url"=> "null",  "issue_access_token" =>"true");
  $DATA_STRING = json_encode($DATA);

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api-1920207D-9A3E-4ADC-A8FF-F70D816B0E6F.sendbird.com/v3/users",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $DATA_STRING,
    CURLOPT_HTTPHEADER => array(
      "Api-Token: 1378399f93c26da80722fab836b1134a545da721",
      "Content-Type: application/json, charset=utf8"
    ),
  ));
  $response = curl_exec($curl);
  $responseArray = json_decode($response,true);
  $tokenString = $responseArray["access_token"];
  return $tokenString;

  //setting as operator of VICTIM_CHAT
  setOperator($USER_ID);

}
function setOperator($USER_ID){
  $DATA = array("channel_custom_types" => ["VICTIM_CHAT"]);
  $DATA_STRING = json_encode($DATA);

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api-1920207D-9A3E-4ADC-A8FF-F70D816B0E6F.sendbird.com/v3/users/$USER_ID/operating_channel_custom_types",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $DATA_STRING,
    CURLOPT_HTTPHEADER => array(
      "Api-Token: 1378399f93c26da80722fab836b1134a545da721",
      "Content-Type: application/json, charset=utf8"
    ),
  ));
  curl_exec($curl);
}
  ?>
