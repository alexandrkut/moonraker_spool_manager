<?php

include "functions.php";

#$ch = curl_init();
#include "header.php"

$values[printer_serial] = $_POST["printer_serial"];
#$values[printer_port] = $_POST["printer_port"];
#$values[printer_serial] = "UltiSteel2";
#$values[printer_port] = "7126";
$values[printer_id] = get_printer_id($values[printer_serial]);
$values[spool_id] = get_activ_spool($values[printer_id]);
$flow = get_spool_flow($values[spool_id])*100;
if ($flow == 0) {
$flow = 100;
}

$ret = get_material_retr(get_spool_material($values[spool_id]));
#exec("wget -b -q -O /dev/null http://localhost:".$values[printer_port]."/printer/gcode/script?script=M221%20S".$flow."> /dev/null 2>&1");



#$url = "http://localhost:".$values[printer_port]."/printer/gcode/script?script=M221%20S".$flow;
#curl_setopt($ch, CURLOPT_URL, $url);
#curl_setopt($ch, CURLOPT_HEADER, 0);

#curl_exec($ch);

$flow = array('flow' => $flow);

$arr = array_merge($flow,$ret);
#print_r($arr);

echo json_encode($arr);
?>