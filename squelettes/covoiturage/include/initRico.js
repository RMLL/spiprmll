/* Rico configuration script */

function initRico()
{
   /* Draggable box */
   dndMgr.registerDraggable( new Rico.Draggable('Custom','title', 'titleTitle') );
  // dndMgr.registerDraggable( new Rico.Draggable('Custom','legend', 'legendTitle') );
   dndMgr.registerDraggable( new Rico.Draggable('Custom','overview', 'overviewTitle') );
}
