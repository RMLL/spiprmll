/* Toogle boxes script */

function toggleBox(boxID, lang)
{
   var elt = document.getElementById(boxID + 'Body');
   var cmd = document.getElementById(boxID + 'Toggle');
   var titleMinimize, titleMaximize;

   switch (lang) {
      case 'fr': 
         titleMinimize = 'R\u00e9duire';
         titleMaximize = 'Agrandir';
         break;
      case 'en': 
         titleMinimize = 'Minimize';
         titleMaximize = 'Maximize';
         break;
      default:
         titleMinimize = 'Minimize';
         titleMaximize = 'Maximize';
         break;
   }

   if (elt && cmd)
   {
      if (elt.style.display == 'none')
      {
         elt.style.display = 'block';
         cmd.style.backgroundImage = 'url(./include/icon_minimize.png)';
         cmd.title = titleMinimize;
      }
      else
      {
         elt.style.display = 'none';
         cmd.style.backgroundImage = 'url(./include/icon_maximize.png)';
         cmd.title = titleMaximize;
      }
   }
   return false;
}

function closeBox(boxID)
{
   document.getElementById(boxID).style.display = 'none';
}
