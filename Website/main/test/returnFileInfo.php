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
        
		$stmt = $conn->prepare(
            "SELECT f.uuid, f.owner FROM Files f
             WHERE f.uuid = :uuid
             AND (f.owner = :owner
                  OR EXISTS (
                      SELECT 1 FROM SharedFiles sf
                      WHERE sf.file_id = f.id AND sf.shared_with = :owner2
                  ))"
        );

	
	    $stmt->bindParam(":owner", $_SESSION["uid"]);
        $stmt->bindParam(":owner2", $_SESSION["uid"]);
        $stmt->bindParam(":uuid", $uuid);
	    $stmt->execute();
	    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $num_rows = count($res);

        if($num_rows > 0){ //Found
			$stmt = $conn->prepare("SELECT text FROM Files WHERE uuid = :uuid");
			$stmt->bindParam(":uuid", $uuid);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_NUM)[0];
            echo json_encode($result);
        }
        else{
            echo "You do not have access to this file.";
        }

        }
        catch (Exception $e)
        {
			echo "owner: " . $fileOwner;
			echo "uuid: " . $uuid . "\n";
            echo "Exception occurred...";
	        echo print_r($e);
        }
    }
    

?>
