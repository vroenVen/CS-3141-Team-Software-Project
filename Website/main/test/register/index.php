<?php

    //ini_set('display_errors', '1');

    include_once '../../init.php';

    session_start();

    $object = new Dbh;
    $conn = $object->connect();

    if ($_POST["username"] != NULL && $_POST["email"] != NULL && $_POST["password"] != NULL)
    {
        try
        {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);
            //$res = $stmt->fetchAll();

            header('location: login.php');
            die();
        }
        catch (Exception $e)
        {
            echo "Exception occurred...";
            $_POST["username"] = NULL;
            $_POST["password"] = NULL;
        }
    }

?>



<!DOCTYPE html>
<html class="align">
    <head>
        <link href="primary.css" media="all" rel="Stylesheet" type="text/css" />
    </head>

    <body class="align">
        <div class="container-login">
            <div class="login">
                <div>
                    <h2>
                        Please Register
                    </h2>
                    <form action="register.php" method="post">
                        <label>Username:</label>
                        <input class="input-login" type="text" placeholder="Enter Username" name="username" required>
                        <label>Email:</label>
                        <input class="input-login" type="text" placeholder="Enter Email" name="email" required>
                        <label>Password:</label>
                        <input class="input-login" type="password" placeholder="Enter Password" name="password" required>
                        <input type="submit" value="Register">
                    </form>

                    <button onClick="window.location.href='index.php'">Back</button>
                    


                </div>
            </div>
        </div>
    </body>


</html>