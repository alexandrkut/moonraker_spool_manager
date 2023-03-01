<?php
$pdo = new PDO('sqlite:spoolmanager.sqlite'); 

define ('id', "id");
define ('name', "name");
define ('material',"material");
define ('datetime',"datetime");
define ('printer',"printer");
define ('spool',"spool");
define ('spool_id',"spool_id");
define ('lenght',"lenght");
define ('weight',"weight");
define ('filename',"filename");
define ('activ_printer',"activ_printer");
define ('weight_tare',"weight_tare");
define ('printer_serial',"printer_serial");
define ('printer_id',"printer_id");
define ('density',"density");
define ('printer_name',"printer_name");
define ('spool_name',"spool_name");


$fold = "/sm";




function get_printer_id($printer_serial){
global $pdo;
$stmt = $pdo->prepare("SELECT id FROM printers WHERE serial=?");
$stmt->execute(array($printer_serial));
$name = $stmt->fetchColumn();
return $name;
}


function get_printer_name($printer_id){
global $pdo;
$stmt = $pdo->prepare("SELECT name FROM printers WHERE id=?");
$stmt->execute(array($printer_id));
$name = $stmt->fetchColumn();
return $name;
}




function get_activ_spool($printer_id){
global $pdo;
$stmt = $pdo->prepare("SELECT spool_id FROM activ_spool WHERE printer_id=?");
$stmt->execute(array($printer_id));
$name = $stmt->fetchColumn();
return $name;
}


function get_spool_name($spool_id){
global $pdo;
$stmt = $pdo->prepare('select material||" - "||name as name FROM "spools_view" where id=?');
$stmt->execute(array($spool_id));
$name = $stmt->fetchColumn();
return $name;
}



function get_filament_density($spool_id){
global $pdo;
$stmt = $pdo->prepare("SELECT density FROM spools_view WHERE id=?");
$stmt->execute(array($spool_id));
$name = $stmt->fetchColumn();
return $name;
}

?>