<?php

include "functions.php";
$printer_id = intval($_POST["printer_id"]);
$spool_id = intval($_POST["spool_id"]);

//$printer_id = 1;
//$spool_id = 1;

$sql = $pdo->prepare("update activ_spool SET spool_id = :spool where printer_id = :printer ");
//$sql->execute();

$sql->execute(array(
":spool" => $spool_id,
":printer" => $printer_id,
));


$done = "1";
echo json_encode($done);
?>