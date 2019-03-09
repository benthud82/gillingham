<?php
include_once '../../globalincludes/google_connect.php';

//pull in available location volume
$usevol_sql = $conn1->prepare("SELECT 
                                SUM(USE_CUBE) AS avail_cube,
                                SUM(USE_CUBE) * .85 as cap_85
                            FROM
                                gillingham.location_master");  
$usevol_sql->execute();
$usevol_array = $usevol_sql->fetchAll(pdo::FETCH_ASSOC);

$capacity = $usevol_array[0]['cap_85'];

//pull in available location volume
$locsize_sql = $conn1->prepare("SELECT DISTINCT
                                    slotmaster_dimgroup,
                                    slotmaster_usedeep,
                                    slotmaster_usewide,
                                    slotmaster_usewide,
                                    slotmaster_usecube
                                FROM
                                    gillingham.slotmaster
                                ORDER BY slotmaster_usecube");  
$locsize_sql->execute();
$locsize_array = $locsize_sql->fetchAll(pdo::FETCH_ASSOC);

//Pull in all standard items and start with the smallest location that will fit one days worth of demand. This should be stored in a table



