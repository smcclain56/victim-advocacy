<?php
  try {
    $conn = new PDO('sqlite:./myDB/victimAdv.db');
  	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  }catch(PDOException $e) {
    die('Exception : '.$e->getMessage());
  }
?>
