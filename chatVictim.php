<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db_file = './myDB/victimAdv.db';

try {
  //open connection to the airport database file
  $db = new PDO('sqlite:' . $db_file);

  //set errormode to use exceptions
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Base files
  require 'PHPMailer-master/src/Exception.php';
  require 'PHPMailer-master/src/PHPMailer.php';
  require 'PHPMailer-master/src/SMTP.php';
  // create object of PHPMailer class with boolean parameter which sets/unsets exception.
  $mail = new PHPMailer(true);

  //grab sender email from db
  $query = "SELECT * FROM User WHERE username = 'victimAdvocacyAdmin' LIMIT 1;";
  $result = $db->query($query);
  $tuple = $result->fetch(PDO::FETCH_ASSOC);
  $senderEmail = $tuple['email'];

  //TODO CHANGE SO THAT IT GRABS USERNAME OF EVERY ADVOCATE
  $query = "SELECT * FROM User WHERE username != 'victimAdvocacyAdmin';";
  $result = $db->query($query);

  try {
    foreach ($result as $tuple) {
      $mail->isSMTP(); // using SMTP protocol
      $mail->Host = 'smtp.mail.com'; // SMTP host as mail.com
      $mail->SMTPAuth = true;  // enable smtp authentication
      $mail->Username = 'victimadvocacy@mail.com';  // sender gmail host
      $mail->Password = '3coolPeople!'; // sender gmail host password
      $mail->SMTPSecure = 'tls';  // for encrypted connection
      $mail->Port = 587;   // port for SMTP
      $mail->IsHTML(true);

      $recieverEmail = $tuple['email'];
      $recieverFirstName = $tuple['fname'];
      $recieverLastName = $tuple['lname'];
      $mail->setFrom('' . $senderEmail . '', "Victim Advocacy Hub"); // sender's email and name
      $mail->addAddress('' . $recieverEmail . '', "Advocate");  // receiver's email and name

      $mail->Subject = 'New Chat Available For View';
      $mail->Body    = '<h6>Hello ' . $recieverFirstName . ' ' . $recieverLastName . '</h6>, <p>a new message has arrived in Victim Advocacy Hub. Please log on right away to address this chat.</p>';

      $mail->send();

      //clear all addresses and attachements for next email
      $mail->ClearAddresses();
      $mail->ClearAttachments();
    }
  } catch (Exception $e) { // handle error.
    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
  }

  $db = null;
} catch (PDOException $e) {
  die('Exception : ' . $e->getMessage());
}

?>

<head>
  <meta charset="utf-8">
  <!-- Chat window Template adapted from Fabio Ottavianis sample chat window on Code Pen -->
  <link rel="stylesheet" type="text/css" href="css/header.css?<?php echo time(); ?>" />
  <link rel="stylesheet" href="css/chatStyle.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>

<body>
  <div class="topnav">
    <a class="<?php if ($currentPage == 'home') {
                echo 'active';
              } ?>" href="index.php">Home</a>
  </div>
  <div>

    <div class="wrapper">
      <div class="loader">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <div class="wait"> Please wait while we connect you with an advocate </div>

    <style>
      .chat {
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        width: 50vw;
        height: 80vh;
        max-height: 500px;
        z-index: 2;
        overflow: hidden;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.2);
        background: rgba(0, 0, 0, 0.5);
        border-radius: 20px;
        display: -webkit-box;
        display: flex;
        -webkit-box-pack: justify;
        justify-content: space-between;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        flex-direction: column;
      }
    </style>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.3/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="node_modules/sendbird/SendBird.min.js"></script>
    <script src="chatVictimController.js" async> </script>
</body>
