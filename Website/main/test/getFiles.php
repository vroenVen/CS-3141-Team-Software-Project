<?php

    //ini_set('display_errors', '1');

    include_once '../../../init.php';

    session_start();

    $object = new Dbh;
    $conn = $object->connect();

    if ($_SESSION["uid"] != NULL)
    {
        try
        {

        $stmt = $conn->prepare(
            "SELECT f.id, f.uuid, f.filename, f.lastaccess, LENGTH(f.text) as file_size_bytes, u.username AS owner_name 
            FROM Files f 
            INNER JOIN Users u ON u.id = f.owner
            WHERE f.owner = :owner

            UNION

            SELECT f.id, f.uuid, f.filename, f.lastaccess, LENGTH(f.text) as file_size_bytes, u.username AS owner_name 
            FROM Files f
            INNER JOIN Users u ON u.id = f.owner
            INNER JOIN SharedFiles sf ON sf.file_id = f.id
            WHERE sf.shared_with = :owner2"
        );
	
	    $stmt->bindParam(":owner", $_SESSION["uid"]);
	    $stmt->bindParam(":owner2", $_SESSION["uid"]);
        $stmt->execute();
	    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    foreach($res as $file){
		
                $fileName = "Unnamed file";    //if nothing, have a default name.

                if($file["filename"] != NULL){
                    $fileName = $file["filename"];
                }

                $accessTime = "N/A";    //if nothing, have a default name.

                if($file["lastaccess"] != NULL){

                    $date = new DateTime($file["lastaccess"]);

                    $accessTime = $date->format('F jS, g:i A');
                }
		
                echo '<tr onclick = "fileItemClicked(this)" ondblclick = "openFileClicked(this)" class = "file-table-item"';

                echo 'id = "';
                echo $file["uuid"];  //internal id
                echo '">';
            
                echo '<td class = "file-table-item-name"> <svg style = "margin: 0px;" xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 14 16"><path d="M266 416h-7a2.006 2.006 0 0 0-2 2v12a2.006 2.006 0 0 0 2 2h10a2.006 2.006 0 0 0 2-2v-9a5 5 0 0 0-5-5m3 14h-10v-12h5v5h5zm-3-9v-3a3.01 3.01 0 0 1 3 3z" transform="translate(-257 -416)" style="fill-rule:evenodd"/></svg>';
                
                echo $fileName; //file name
                echo '</td>';

                echo '<td>';
                echo $accessTime;       //Date Here
                echo '</td>';

                echo '<td>';
                echo $file["file_size_bytes"];             //File size here
                echo 'B </td>';

                echo '<td onclick = "editClick(this)" class = "file-table-item-edit"> <svg style = "margin-top: 15px;" xmlns="http://www.w3.org/2000/svg" width="15" height="8" viewBox="0 0 10 2"><path d="M645 744a1 1 0 1 1-1-1 1 1 0 0 1 1 1m8 0a1 1 0 1 1-1-1 1 1 0 0 1 1 1m-4 0a1 1 0 1 1-1-1 1 1 0 0 1 1 1" transform="translate(-643 -743)" style="fill-rule:evenodd"/></svg>';
          
                echo '<div class="dropdown-content"> <div class = "dropdown-option" onclick="renameClicked(this)"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="m44 418 2 2-10 10h-2v-2zm0-2a2 2 0 0 0-1.413.586l-9.99 10a2 2 0 0 0-.589 1.414v2a2 2 0 0 0 2 2h2a2 2 0 0 0 1.413-.586l9.99-10a2 2 0 0 0 0-2.828l-2-2A2 2 0 0 0 44 416m1 7a1 1 0 0 1-.706-.293l-3-3a1 1 0 0 1 1.413-1.414l3 3a1 1 0 0 1-.712 1.707Z" transform="translate(-32 -416)" style="fill-rule:evenodd"/></svg> <a> Rename </a> </div>';

                //echo '<div class = "dropdown-option"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="M295 430h-5v-12h5v5h5a1 1 0 0 0 2 0v-2a5 5 0 0 0-5-5h-7a2.006 2.006 0 0 0-2 2v12a2.006 2.006 0 0 0 2 2h5a1 1 0 0 0 0-2m2-12a3.01 3.01 0 0 1 3 3h-3zm6 10h-1v-1a1 1 0 0 0-2 0v1h-1a1 1 0 0 0 0 2h1v1a1 1 0 0 0 2 0v-1h1a1 1 0 0 0 0-2" transform="translate(-288 -416)" style="fill-rule:evenodd"/></svg> <a> Copy </a> </div>';

                echo '<div class = "dropdown-divider"> </div> <div class = "dropdown-option" onclick="deleteClicked(this)"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="M47 547h-4a3 3 0 0 0-6 0h-4a1 1 0 1 0 0 2h1v9a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-9h1a1 1 0 0 0 0-2m-7-1a1 1 0 0 1 1 1h-2a1 1 0 0 1 1-1m4 12h-8v-9h8z" transform="translate(-32 -544)" style="fill-rule:evenodd"/></svg> <a> Delete </a> </div> </div>';
                echo '</td>';

                
                echo '<td>
                <button onclick="event.stopPropagation(); this.nextElementSibling.style.display = this.nextElementSibling.style.display === \'none\' ? \'block\' : \'none\'">Share</button>
                <div style="display:none; margin-top:6px;">
                    <form method="POST" action="../shareFile.php" onclick="event.stopPropagation()">
                        <input type="hidden" name="fileUuid" value="' . $file["uuid"] . '" />
                        <input type="text" name="username" placeholder="Enter username" style="padding:4px; margin-right:4px;" />
                        <button type="submit">Send</button>
                    </form>
                </div>
                </td>';
                if ($file["owner_name"] != $_SESSION["username"])
                {
                    echo '<td>' . htmlspecialchars($file["owner_name"]) . '</td>';
                }
                else if ($file["owner_name"] == $_SESSION["username"])
                {
                    echo '<td>' . "You" . '</td>';
                }
            }

        }
        catch (Exception $e)
        {
            echo "Exception occurred...";
	    echo print_r($e);
        }
    }

?>
