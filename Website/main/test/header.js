
function headerButtonClicked(element, newFileHeader) {
   element.classList.toggle("active");

   if(activeClick && activeClick != element){
        activeClick.classList.toggle("active");
   }
   if(activeClick == element){
        activeClick = null;
   } else{
        activeClick = element;
   }

   if(newFileHeader){
     window.location.href = newFileHeader;
   }

}