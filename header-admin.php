<?php
// redirect if not logged in
if (!isset($_SESSION['username'])) {
  header("Location:login.php?error=invalidCredentials");
}
?>

<html>
<head>
  <!-- Load an icon library to show a hamburger menu (bars) on small screens -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="./css/header.css?<?php echo time(); ?>" />
  <script>
  /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
  function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }
  </script>
</head>

<body>

  <div class="topnav" id="myTopnav">
    <a class="<?php if($currentPage =='home'){echo 'active';}?>" href="home-admin.php?userid=<?php echo $_SESSION['username'];?>">Home</a>
    <a class="<?php if($currentPage =='viewAdvocates' || $currentPage == 'updateAdvocate'){echo 'active';}?>" href="./viewAdvocates.php">Advocates</a>
    <a class="<?php if($currentPage =='viewResources'){echo 'active';}?>" href="./viewResources.php">Resources</a>
    <a class="<?php if($currentPage =='modifyAccount'){echo 'active';}?>" href="update-user-form.php?username=<?php echo $_SESSION['username']?>">Modify Account</a>
    <a class="logout" href="logout.php">Log Out </a>
    <a href="javascript:void(0);" class="icon" onclick="myFunction()">
      <i class="fa fa-bars"></i>
    </a>
  </div>

</body>
</html>
