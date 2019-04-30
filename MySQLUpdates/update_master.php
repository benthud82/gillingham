<?php

//master to update all slotting recommendations
//need to add logic to scan FTP folder for new files and add if any are there

//include 'tableupdate_slotmaster.php';
//include 'tableupdate_itemmaster.php';
//include 'tableupdate_replen.php';
//include 'tableupdate_sales.php';
//include 'tableupdate_locmaster.php';
//include 'current_implied_moves.php';  //has to be called after tableupdate_slotmaster.php

//include 'DemandGrouping.php';

//Should no sales update be placed here?

include 'itemtruefits.php';
include 'replenpercube.php';
include'NPFMVC_Update.php';

//optimal bay update here?
