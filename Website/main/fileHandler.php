<?php
//ini_set('display_errors', '1');

session_start();

include_once '../../init.php';

$data = json_decode(file_get_contents('php://input'), true);
// submitting text
if ($data["text"] != NULL)


// check logged in
if ($_SESSION["uid"] != NULL)
{

    if ($data["editUuid"] != NULL){ //We're editing an existing file.
        
        if(verifyOwnership($_SESSION["uid"], $data["editUuid"])){
			try
			{
				$object = new Dbh;
				$conn = $object->connect();

				$stmt = $conn->prepare("UPDATE Files SET text = :text WHERE owner = :uid AND uuid = :uuid");
				$stmt->bindParam(":uid", $_SESSION["uid"]);
				$stmt->bindParam(":text", $data["text"]);
				$stmt->bindParam(":uuid", $data["editUuid"]);
				$stmt->execute();
				echo json_encode("Success");
			}
			catch (PDOException $e) 
			{
				print "Error!" . $e->getMessage();
				die();
			}
        }

    }
    else{
        // create new entry in files table
        $uuid = registerNewFile($_SESSION["uid"]);

        // write contents
		try
		{
			$object = new Dbh;
			$conn = $object->connect();

			$stmt = $conn->prepare("UPDATE Files SET text = :text WHERE owner = :uid AND uuid = :uuid");
			$stmt->bindParam(":uid", $_SESSION["uid"]);
			$stmt->bindParam(":text", $data["text"]);
			$stmt->bindParam(":uuid", $uuid);
			$stmt->execute();
			echo json_encode("Success");
		}
		catch (PDOException $e) 
		{
			print "Error!" . $e->getMessage();
			die();
		}
    }

}

function verifyOwnership($user, $fileUuid){
    try
    {
        $object = new Dbh;
        $conn = $object->connect();

		$stmt = $conn->prepare("SELECT * FROM Files WHERE owner = :owner and uuid = :uuid");
	
	    $stmt->bindParam(":owner", $user);
        $stmt->bindParam(":uuid", $fileUuid);
	    $stmt->execute();
	    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $num_rows = count($res);

        if($num_rows > 0){ //We own this file.
            return true;
        }else{
            return false;
        }
        
    }
    catch (PDOException $e) 
    {
        $conn->rollBack();
        print "Error!" . $e->getMessage();
        die();
    }
}

function registerNewFile($user)
{
    try
    {
        $object = new Dbh;
        $conn = $object->connect();
	$conn->beginTransaction();
	$statement = $conn->prepare("INSERT into Files (owner, uuid) "
	  . "values (:owner, UUID())");
	$statement->bindParam(":owner", $user);
	$statement->execute();
	$id = $conn->query("SELECT LAST_INSERT_ID()")->fetch(PDO::FETCH_NUM)[0];
	$statement = $conn->prepare("SELECT uuid FROM Files WHERE id = :id");
	$statement->bindParam(":id", $id);
	$statement->execute();
	$result = $statement->fetch(PDO::FETCH_ASSOC);
	$conn->commit();
	return $result['uuid'];
    }
    catch (PDOException $e) 
    {
        $conn->rollBack();
        print "Error!" . $e->getMessage();
        die();
    }
}

?>
