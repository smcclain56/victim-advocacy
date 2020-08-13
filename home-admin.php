<?php
session_start();
$db_file = './myDB/victimAdv.db';
$currentPage = 'home';

try {
  //open connection to the airport database file
  $db = new PDO('sqlite:' . $db_file);

  //set errormode to use exceptions
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(!isset($_SESSION['username'])){
    header("Location:login.php?invalidCredentials");
  }else{
    $username = $_SESSION['username'];
    $query = "SELECT role FROM User WHERE username = '".$username."' LIMIT 1;";
    $result = $db->query($query);

    $tuple = $result->fetch(PDO::FETCH_ASSOC);

    $role = $tuple['role'];

    if(strcmp($role, "admin") !=0){
      header("location:login.php?error=invalidCredentials");
    }
  }
  include 'header-admin.php';

  $db = null;
}
catch(PDOException $e) {
  die('Exception : '.$e->getMessage());
}
?>

<html>

<head>
  <meta charset="utf-8">
  <!-- Chat window Template adapted from Fabio Ottavianis sample chat window on Code Pen -->
  <link rel="stylesheet" href="css/chatStyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<div id="dialog">

  <div class="chat-menu">
    <div class="chat-menu-title">
      <h1>Available chats</h1>
    </div>
  </div>
  <div class="chat">
    <div class="chat-title">
      <h1 id="chatUserName"></h1>
      <h2 id="chatId"></h2>
      <span class="avatar material-icons">person</span>
    </div>
    <div class="messages">
      <div class="messages-content"></div>
    </div>
    <div class="message-box">
      <textarea type="text" class="message-input" placeholder="Type message..."></textarea>
      <button type="submit" class="message-submit">Send</button>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="node_modules/sendbird/SendBird.min.js"></script>
  <script type="module" src="chatController.js" async> </script>
  </html>
