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
    // create new entry in files table
    $uuid = registerNewFile($_SESSION["uid"]);

    // create directory for file
    $result = mkdir("/tsp/userFiles/" . $_SESSION["uid"] . "/" . $uuid, 0770, true);

    // write contents
    $result = file_put_contents("/tsp/userFiles/" . $_SESSION["uid"] . "/" . $uuid . "/text", $data["text"]);

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
