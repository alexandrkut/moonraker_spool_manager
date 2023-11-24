<?php
include "header.php";






?>
<div class="main">
<h1>Универсал Спул манагер</h1>

<div>
<form autocomplete="off" id="form" method="post" action="<?=$_SERVER['PHP_SELF']?>">
<?php
$sql_p = $pdo->query('select id,name from printers order by id ASC');
while ($row_p = $sql_p->fetch()){
echo "<div class=\"field\">";
echo '<label for="activ_'.$row_p[id].'">Активная катушка '.$row_p[name].'</label>';
//echo '<select name="activ_1"  onchange="set_activ_spool('.$row_p[id].',this.value);>"';
//echo '<select name="activ_'.$row_p[id].'"  onchange="alert(this.value);">';
echo '<select name="activ_'.$row_p[id].'"  onchange="set_activ_spool('.$row_p[id].',this.value);">';
$selected = get_activ_spool($row_p[id]);
$sql = $pdo->query('select id,material,name,weight,flow from spools_view order by material,name');
while ($row = $sql->fetch()){
echo '<option value="'.$row["id"].'"'.($row["id"] == $selected ? " selected=\"selected\">" : ">").$row["material"].' - '.$row["name"].' - '.$row["weight"].'гр. - F='.$row["flow"].'</option><br>';
}  
echo '</select>';
echo '</div>';
}
?>
</form>

<div>
<a href = "spool_editor.php"><button>Редактор катушек</a></a><br>
<a href = "material_editor.php"><button>Редактор материалов</a></a><br>
</div>
</div>
</div>

    <table border="1">
      <caption><h2>Катушки</caption>
        
   <tr>
	<th>Принтер</th>
	<th>Материал</th>
	<th>Поток</th>
	<th>Вес</th>
	<th>Запас</th>

    </tr>

<?php
$sql = $pdo->query('select id,material,name,weight, activ_printer,reserve,flow from spools_view order by material,name');
while ($rowz = $sql->fetch()){
	if (isset($rowz[material])){
echo '<tr class="center">';
echo '<td>'.$rowz[activ_printer].'</td>';
echo '<td><a class="edit_button" href=spool_editor.php?edit=1&id='.$rowz[id].' style="
    text-align: left;
padding-left: 10;
">'.$rowz["material"].' - '.$rowz["name"].'</a></td>';
echo '<td>'.$rowz["flow"].'</td>';	
echo '<td>'.$rowz["weight"].'</td>';	

echo '<td>'.$rowz["reserve"].'</td>';
echo  '</tr>';
	}
}
  echo '</table>';
?>


    <table border="1">
      <caption><h2><a href="history.php">История заданий</a></h2></caption>
        
   <tr>
    <th>Дата</th>
	<th>Принтер</th>
    <th class="col_spool">Катушка</th>
	<th>Длина, м.</th>
	<th>Вес, г.</th>
	<th class="col_filename">Имя файла</th>
    </tr>

<?php
$sql = $pdo->query('select datetime,printer_name,spool_name,lenght,weight,filename from history order by datetime DESC limit 10');
while ($row = $sql->fetch()){
echo '<tr class="center">';
echo '<td>'.$row[datetime].'</td>';	
echo '<td>'.$row[printer_name].'</td>';	
echo '<td>'.$row[spool_name].'</td>';	
echo '<td>'.round($row[lenght]/1000,3).'</td>';	
echo '<td>'.round($row[weight],0).'</td>';	
echo '<td>'.$row[filename].'</td>';	
echo  '</tr>';
}
  echo '</table>';
?>




<?php
include "footer.php";
?>