
let activeFile = null;

let openDropdown = null;
let clickListener = null;

function fileItemClicked(element){

     if(activeFile == element){
          return;
     }

     if(activeFile == null){
          element.classList.toggle("active");
          activeFile = element;
     } else{

          activeFile.classList.toggle("active"); //toggle old
          element.classList.toggle("active");  //toggle new

          activeFile = element;
     }

}

function editClick(element){

     if(openDropdown){
          openDropdown.style.display = "none";
          openDropdown = null;
     }

     element.children[1].style.display = "block";
     
 
     openDropdown = element.children[1];
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