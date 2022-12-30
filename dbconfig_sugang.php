<?php
  $link = mysqli_connect("localhost", "duho", "dlengh6874", "sugang");
  
  // Check connection
  if($link === false){
      die("ERROR: Could not connect. " . mysqli_connect_error());
  }
?>