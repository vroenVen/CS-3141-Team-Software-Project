<?php

    include_once '../init.php';

    $object = new Dbh;
    $object->connect();

?>



<!DOCTYPE html>
<html class="align">
    <head>
        <link href="primary.css" media="all" rel="Stylesheet" type="text/css" />
        <script src="db.js"></script>
    </head>

    <body class="align">
        <div class="container-login">
            <div class="login">
                <div>
                    <form action="login.php" method="post">
                        <label>Username:</label>
                        <input class="input-login" type="text" placeholder="Enter Username" name="username" required>
                        <label>Password:</label>
                        <input class="input-login" type="password" placeholder="Enter Password" name="password" required>
                        <input type="submit" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </body>


</html>