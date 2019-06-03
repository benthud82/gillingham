<?php

//master to update all slotting recommendations
//need to add logic to scan FTP folder for new files and add if any are there
include 'tableupdate_locmaster.php';  //must update before slotmaster because tier is pulled from here for slotmaster
sleep(10);
include 'tableupdate_slotmaster.php';
sleep(10);
include 'tableupdate_itemmaster.php';
sleep(10);
include 'tableupdate_replen.php';
sleep(10);
include 'tableupdate_sales.php';
sleep(10);
include 'current_implied_moves.php';  //has to be called after tableupdate_slotmaster.php
sleep(10);
include 'DemandGrouping.php';
sleep(10);
include 'itemtruefits_ecap.php';
sleep(10);
include 'itemtruefits.php';
sleep(10);
include 'replenpercube.php';
sleep(10);
include'NPFMVC_Update.php';
sleep(10);
include'optimalbayloose.php';
sleep(10);
include'itemscore.php';

