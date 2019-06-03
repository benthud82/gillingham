
<?php
include_once '../connection/NYServer.php';

$grid5 = $_POST['grid5'];


//detail data query
$result1 = $conn1->prepare("SELECT DISTINCT
    LOC_DIM,
    HEIGHT,
    DEPTH,
    WIDTH,
    CUBE,
    USE_HEIGHT,
    DEPTH,
    USE_WIDTH,
    USE_CUBE
FROM
    gillingham.location_master
WHERE
    LOC_DIM = '$grid5'");
$result1->execute();
$result1array = $result1->fetchAll(pdo::FETCH_ASSOC);

print_r($result1array);

?>


