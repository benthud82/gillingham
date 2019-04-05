<?php
//master to update all slotting recommendations
//need to add logic to scan FTP folder for new files and add if any are there

include 'tableupdate_slotmaster.php';

include 'DemandGrouping.php';

include 'current_implied_moves';

include 'itemtruefits.php';

include 'replenpercube.php';

include'NPFMVC_Update';
