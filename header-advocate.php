<?php
// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
}
?>

<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/header.css?<?php echo time(); ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="topnav">
    <a class="<?php if($currentPage =='home'){echo 'active';}?>" href="home-advocate.php?userid=<?php echo $_SESSION['username'];?>">Home</a>
    <a class="<?php if($currentPage =='modifyAccount'){echo 'active';}?>" href="update-user-form.php?username=<?php echo $_SESSION['username']?>">Modify Account</a>
    <a class="logout" href="logout.php">Log Out </a>
  </div>

</body>
</html>
