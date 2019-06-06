<?php

//master to update all slotting recommendations
//need to add logic to scan FTP folder for new files and add if any are there
date_default_timezone_set('Europe/London');
include 'tableupdate_locmaster.php';  //must update before slotmaster because tier is pulled from here for slotmaster

include 'tableupdate_slotmaster.php';

include 'tableupdate_itemmaster.php';

include 'tableupdate_replen.php';

include 'tableupdate_sales.php';

include 'current_implied_moves.php';  //has to be called after tableupdate_slotmaster.php

include 'DemandGrouping.php';  //NPTSLD file creation

include 'itemtruefits_ecap.php';

include 'itemtruefits.php';

include 'replenpercube.php';

include'NPFMVC_Update.php';

include'optimalbayloose.php';

include'itemscore.php';
date_default_timezone_set('America/New_York');
