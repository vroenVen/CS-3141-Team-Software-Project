<?php



    try 
    {
        $sql = "SELECT username, password FROM Users WHERE username = {$_POST['username']} AND password = {$_POST['password']}}";
        // Execute the SQL query
        $result = $conn->query($sql);
        // Process the result set
        if ($result->rowCount() > 0) 
        {
            echo "Account Found!";
        }
        else
        {
            echo "No account found...";
        }
    } 
    catch(PDOException $e) 
    {
    echo "Error: " . $e->getMessage();
    }


/*
    if ($_POST["username"] != NULL)
        header('Location: '. 'index.html');
        //die();*/


?>