<?php

include "functions.php";
#include "header.php"
$dia = "1.75";

$values[printer_serial] = $_POST["printer_serial"];
$values[printer_id] = get_printer_id($values[printer_serial]);
$values[spool_id] = get_activ_spool($values[printer_id]);
$values[printer_name] = get_printer_name($values[printer_id]);
$values[spool_name] = get_spool_name($values[spool_id]);
$values[lenght] = round($_POST["lenght"],0);
//$values[weight] = $_POST["weight"];
$values[filename] = $_POST["filename"];


#print $values["filename"]." - ".$values["lenght"]." - "$values["weight"];

$density_mm = get_filament_density($values[spool_id]);
$radius = $dia/2;
$values[weight] = round( pi()/4 * $dia *$dia * $values[lenght] * $density_mm / 1000,2);

$sql = $pdo->prepare("INSERT INTO history (printer_id,spool_id,lenght,weight,filename,printer_name,spool_name) values (:printer_id, :spool_id, :lenght, :weight, :filename, :printer_name, :spool_name)");
$sql->execute(array(
":printer_id" => $values["printer_id"],
":spool_id" => $values["spool_id"],
":lenght" => $values["lenght"],
":weight" => $values["weight"],
":filename" => $values["filename"],
":printer_name" => $values["printer_name"],
":spool_name" => $values["spool_name"],
));

$sql_spool = $pdo->prepare("update spools set weight = weight - :weight where id = :spool_id");
$sql_spool->execute(array(
":weight" => $values["weight"],
":spool_id" => $values["spool_id"],
));


$done = "1";
echo json_encode($done);
?>