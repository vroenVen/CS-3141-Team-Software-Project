<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>shareFile debug</title>
</head>
<body>
<?php

    include_once '../../../init.php';

    session_start();

    if ($_SESSION["uid"] == NULL) {
        http_response_code(401);
        echo json_encode(["error" => "Not logged in."]);
        exit;
    }

    $targetUsername = $_POST["username"] ?? "";
    $fileUuid = $_POST["fileUuid"] ?? "";

    if ($targetUsername === "" || $fileUuid === "") {
        http_response_code(400);
        echo json_encode(["error" => "Missing username or file."]);
        exit;
    }

    $object = new Dbh;
    $conn = $object->connect();

    try {
        // Verify the file belongs to the current user
        $stmt = $conn->prepare("SELECT id FROM Files WHERE uuid = :uuid AND owner = :owner");
        $stmt->bindParam(":uuid", $fileUuid);
        $stmt->bindParam(":owner", $_SESSION["uid"]);
        $stmt->execute();
        $file = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$file) {
            http_response_code(403);
            echo json_encode(["error" => "File not found or you do not own it."]);
            exit;
        }

        // Look up the target user
        $stmt = $conn->prepare("SELECT id FROM Users WHERE username = :username");
        $stmt->bindParam(":username", $targetUsername);
        $stmt->execute();
        $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$targetUser) {
            echo json_encode(["error" => "User not found."]);
            exit;
        }

        if ($targetUser["id"] == $_SESSION["uid"]) {
            echo json_encode(["error" => "You cannot share a file with yourself."]);
            exit;
        }

        // Insert into SharedFiles (ignore duplicate)
        $stmt = $conn->prepare(
            "INSERT IGNORE INTO SharedFiles (file_id, shared_with) VALUES (:file_id, :shared_with)"
        );
        $stmt->bindParam(":file_id", $file["id"]);
        $stmt->bindParam(":shared_with", $targetUser["id"]);
        $stmt->execute();

        echo json_encode(["success" => "File shared with " . htmlspecialchars($targetUsername) . "."]);

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Server error."]);
    }

?>
</body>
</html>
