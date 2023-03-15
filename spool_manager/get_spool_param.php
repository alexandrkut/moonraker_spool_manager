<?php

include "functions.php";


$values[printer_serial] = $_POST["printer_serial"];

$values[printer_id] = get_printer_id($values[printer_serial]);
$values[spool_id] = get_activ_spool($values[printer_id]);
$flow = get_spool_flow($values[spool_id])*100;
if ($flow == 0) {
$flow = 100;
}

$ret = get_material_retr(get_spool_material($values[spool_id]));

$flow = array('flow' => $flow);

$arr = array_merge($flow,$ret);

echo json_encode($arr);
?>
