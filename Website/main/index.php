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

<div id="new">
     <ul class="save-bar">
          <li id="save" onclick="handleClick(this)" ><a href="#">Save</a></li>
     </ul>
  <textarea id="more-info" class="hidden-content"></textarea>
</div>

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

function saveContent() {
  const content = document.getElementById("more-info").value;
  fetch("http://141.219.196.40/fileHandler.php", {
	method: "POST",
	headers: {
	  'Content-Type': 'application/json'
	},
	body: JSON.stringify({text: content})
  })
  .then(response => response.json())
  .then(data => console.log('Success:', data))
  .catch((error) => console.error('Error on saveContent:', error));
}

document.getElementById('save').addEventListener('click', saveContent);

</script>
