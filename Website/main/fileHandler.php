<?php
// ini_set('display_errors', '1');

session_start();

include_once '../../init.php';

// submitting text
if ($_POST["text"] != NULL)

// check logged in
if ($_SESSION["uid"] != NULL)
{
    // create new entry in files table
	$uuid = registerNewFile($_SESSION["uid"]);
	echo "Entry: " . $uuid;

	// create directory for file
	$result = mkdir("/tsp/userFiles/" . $_SESSION["uid"] . "/" . $uuid, 0660, true);
	echo "Directory: " . $result;

	// write contents
	$result = file_put_contents("/tsp/userFiles/" . $_SESSION["uid"] . "/" . $uuid . "/text", $_POST["text"]);
	echo "Write: " . $result;

}




function registerNewFile($user)
{
    try
    {
        $object = new Dbh;
        $conn = $object->connect();
		$conn->beginTransaction();
		$statement = $conn->prepare("INSERT into Files (owner, uuid)"
		  . "values (:owner, UUID())");
		$statement->bindParam(":owner", $user);
		$statement->execute();
		$id = $conn->query("SELECT LAST_INSERT_ID()")->fetch(PDO::FETCH_ASSOC);
		$statement = $conn->prepare("SELECT uuid FROM Files WHERE id = :id");
		$statement->bindParam(":id", $id);
		// get UUID of new file
		$result = $statement->execute();
		return $result;
    }
	catch (PDOException $e) 
	{
		print "Error!" . $e->getMessage();
	}
}

?>
