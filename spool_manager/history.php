<?php
include "header.php";
?>
<div class="main">
<h1>Универсал Спул манагер</h1>

    <table border="1">
      <caption><h2>История заданий</caption>
        
   <tr>
    <th>Дата</th>
	<th>Принтер</th>
    <th class="col_spool">Катушка</th>
	<th>Длина, м.</th>
	<th>Вес, г.</th>
	<th class="col_filename">Имя файла</th>
    </tr>

<?php
$sql = $pdo->query('select datetime,printer_name,spool_name,lenght,weight,filename from history order by datetime DESC limit 100');
while ($row = $sql->fetch()){
echo '<tr class="center">';
echo '<td>'.$row[datetime].'</td>';	
echo '<td>'.$row[printer_name].'</td>';	
echo '<td>'.$row[spool_name].'</td>';	
echo '<td>'.$row[lenght].'</td>';	
echo '<td>'.$row[weight].'</td>';	
echo '<td>'.$row[filename].'</td>';	
echo  '</tr>';
}
  echo '</table>';
?>




<?php
include "footer.php";
?>