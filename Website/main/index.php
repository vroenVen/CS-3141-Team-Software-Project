<?php

     session_start();

?>

<!DOCTYPE html>
<html>
<head>

<link href="primary.css" media="all" rel="Stylesheet" type="text/css" />

</head>
<body>

<ul>
  <li onclick="handleClick(this)" ><a href="#new">+ New</a></li>
  <li onclick="handleClick(this)" ><a href="#home">Files</a></li>
  <?php
  if ($_SESSION["username"] != NULL) { ?>
     <li onclick="handleClick(this)" style="float:right"><a href="#profile">Profile</a></li>
     <li onclick="handleClick(this)" style="float:right"><a href="logout.php">Logout</a></li>
  <?php } else { ?>
     <li onclick="window.location.href='login.php'" style="float:right"><a href="#profile">Login</a></li>
  <?php } ?>
</ul>

</body>
</html>

<script>

let activeClick = null;

function handleClick(element) {
   element.classList.toggle("active");

   if(activeClick && activeClick != element){
        activeClick.classList.toggle("active");
   }
   if(activeClick == element){
        activeClick = null;
   } else{
        activeClick = element;
   }

}

</script>
