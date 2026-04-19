
let activeFile = null;
let activeUuid = null;
let renameOpen = false;

let openDropdown = null;
let clickListener = null;
let newFile = false;

function fileItemClicked(element){

     if(activeFile == element){
          return;
     }

     if(activeFile == null){
          element.classList.toggle("active");
          activeFile = element;

          activeUuid = element.id;

     } else{

          activeFile.classList.toggle("active"); //toggle old
          element.classList.toggle("active");  //toggle new

          activeFile = element;
          activeUuid = element.id;
     }

}

function renameClicked(element){

     if(activeUuid == null){ // No active file, cannot rename.
          return;
     }

     document.getElementById("deleteFileBox").classList.add("hidden");  // If this is open close it

     document.getElementById("renameFileBox").classList.toggle("hidden");
     document.getElementById("editFileUuidHidden").value = activeUuid;

}

function deleteClicked(element){

     if(activeUuid == null){ // No active file, cannot rename.
          return;
     }

     document.getElementById("renameFileBox").classList.add("hidden");  // If this is open close it

     document.getElementById("deleteFileBox").classList.toggle("hidden");
     document.getElementById("deleteFileUuidHidden").value = activeUuid;

}

function closeModify(element){
     document.getElementById("renameFileBox").classList.add("hidden"); 
     document.getElementById("deleteFileBox").classList.add("hidden");  
}

function editClick(element){

     if(openDropdown){
          openDropdown.style.display = "none";
          openDropdown = null;
     }

     element.children[1].style.display = "block";
     
 
     openDropdown = element.children[1];
}

function openFileClicked(element){

     document.location.href = `../editor?file=${element.id}`;

}

function newFolderClicked(element){
     document.getElementById("addFolderBox").classList.toggle("hidden");
}

function shareFile(btn, fileUuid) {
     const input = btn.previousElementSibling;
     const msg = btn.nextElementSibling;
     const username = input.value.trim();

     if (!username) {
          msg.style.color = "red";
          msg.textContent = "Enter a username.";
          return;
     }

     fetch("../shareFile.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ username: username, fileUuid: fileUuid })
     })
     .then(r => r.json())
     .then(data => {
          if (data.success) {
               msg.style.color = "green";
               msg.textContent = data.success;
               input.value = "";
          } else {
               msg.style.color = "red";
               msg.textContent = data.error || "Failed to share.";
          }
     })
     .catch(() => {
          msg.style.color = "red";
          msg.textContent = "Request failed.";
     });
}

addEventListener("click", (event) => {  // This will look for clicks outside of the dropdown box, and close if one is open.
     if(openDropdown == null){
          return;
     }

     let wasFound = false;
     for(let editButton of document.getElementsByClassName('file-table-item-edit')){
          if(editButton.contains(event.target)){
               wasFound = true;
               break;
          }
     }
     if(!wasFound && openDropdown != null){
          openDropdown.style.display = "none";
          openDropdown = null;
     }

})
