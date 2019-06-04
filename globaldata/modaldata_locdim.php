
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
    USE_DEPTH,
    USE_WIDTH,
    USE_CUBE
FROM
    gillingham.location_master
WHERE
    LOC_DIM = '$grid5'");
$result1->execute();
$result1array = $result1->fetchAll(pdo::FETCH_ASSOC);

$LOC_DIM = $result1array[0]['LOC_DIM'];
$HEIGHT = $result1array[0]['HEIGHT'];
$DEPTH = $result1array[0]['DEPTH'];
$WIDTH = $result1array[0]['WIDTH'];
$CUBE = $result1array[0]['CUBE'];
$USE_HEIGHT = $result1array[0]['USE_HEIGHT'];
$USE_DEPTH = $result1array[0]['USE_DEPTH'];
$USE_WIDTH = $result1array[0]['USE_WIDTH'];
$USE_CUBE = $result1array[0]['USE_CUBE'];
?>

<div class="h3">Dimensions for Dim Group <?php echo $LOC_DIM; ?> </div>

<div class="row">
    <div class="col-md-6">
        <div class="h4">Location Actual Dimensions</div>
        <div class="h5">Height: <?php echo $HEIGHT?></div>
        <div class="h5">Depth: <?php echo$DEPTH ?></div>
        <div class="h5">Width: <?php echo $WIDTH?></div>
        <div class="h5">Cube:<?php echo $CUBE?> </div>
    </div>
    <div class="col-md-6">
        <div class="h4">Location Use Dimensions</div>
        <div class="h5">Use Height: <?php echo $USE_HEIGHT?></div>
        <div class="h5">Use Depth: <?php echo $USE_DEPTH?></div>
        <div class="h5">Use Width: <?php echo $USE_WIDTH?></div>
        <div class="h5">Use Cube:<?php echo $USE_CUBE?> </div>
    </div>
</div>



