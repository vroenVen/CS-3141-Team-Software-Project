<?php

    //ini_set('display_errors', '1');

    include_once '../../../init.php';

    session_start();

    $object = new Dbh;
    $conn = $object->connect();

    $data = json_decode(file_get_contents('php://input'), true);
    $uuid = $data["fileUuid"];

    if ($_SESSION["uid"] != NULL)
    {
        try
        {
        
		$stmt = $conn->prepare("SELECT * FROM Files WHERE owner = :owner and uuid = :uuid");
	
	    $stmt->bindParam(":owner", $_SESSION["uid"]);
        $stmt->bindParam(":uuid", $uuid);
	    $stmt->execute();
	    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $num_rows = count($res);

        if($num_rows > 0){ //Found
            
            $result = file_get_contents("/tsp/userFiles/" . $_SESSION["uid"] . "/" . $uuid . "/text");
            echo json_encode($result);
        }
        else{
            echo "You do not have access to this file.";
        }

        }
        catch (Exception $e)
        {
            echo "Exception occurred...";
	        echo print_r($e);
        }
    }
    

?>
