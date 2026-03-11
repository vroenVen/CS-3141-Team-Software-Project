<?php

    //ini_set('display_errors', '1');


    include_once '../../init.php';

    session_start();

    $object = new Dbh;
    $conn = $object->connect();

    if ($_POST["username"] != NULL && $_POST["password"] != NULL)
    {
        try
        {
            $username = $_POST["username"];
            $password = $_POST["password"];
            //echo "The entered username: ".$username."\r\n";
            //echo "The entered password: ".$password."\r\n";
            //$hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
            $stmt->execute([$username]);
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $num_rows = count($res);

            if ($num_rows > 0)
            {
                $row = $res[0];
                $db_password = $row["password"];
                //echo "The db hashed password: ".$db_pass;
                if (password_verify($password, $db_password))
                {
                    $_SESSION["username"] = $username;
                    header('location: index.php');
                    die();
                }
                else
                {
                    echo "Incorrect password\n";
                }
                //$_POST["username"] = NULL;
                //$_POST["password"] = NULL;
            }
            else
            {
                echo "Account not found!";
            }
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
                        Please Login
                    </h2>
                    <form action="login.php" method="post">
                        <label>Username:</label>
                        <input class="input-login" type="text" placeholder="Enter Username" name="username" required>
                        <label>Password:</label>
                        <input class="input-login" type="password" placeholder="Enter Password" name="password" required>
                        <input type="submit" value="Login">
                    </form>

                    <button onClick="window.location.href='index.php'">Back</button>
                    <button onClick="window.location.href='register.php'">Register</button>
                    


                </div>
            </div>
        </div>
    </body>


</html>