<?php
include "header.php";
?>

<body>
 
<div class="main">
<?=$_SERVER["PATH_INFO"]?>
<a href="."><button>Назад</button></a><br>
<a href="<?=$fold.$_SERVER['PHP_SELF']?>"><button>Очистить</button></a><br>
<h1> Катушки</h1>

	 <form autocomplete="off" id="form" method="post" action="<?=$fold.$_SERVER['PHP_SELF']?>">
  <input  type="hidden" name="id" id="id">
  
  
  <div class="field">
  <label for="material">Материал:</label>
  <select  name="material" id="material">
<?php
$sql = $pdo->query('select id,name from materials order by name');
while ($row = $sql->fetch()){
echo '<option value="'.$row["id"].'">'.$row["name"].'</option><br>';
  } 
?>
 </select> 
</div>
   
  
<div class="field">
  <label for="name">Имя катушки:</label>
	<input required type="text" name="name" id="name" size="30"><br>
</div>

<div class="field">
  <label for="weight">Вес пластика:</label>                            
  <input required type="number" name="weight" id="weight" value="1000"><br>
</div>

<div class="field">
  <label for="weight_tare">Вес тары(без учета пластика):</label>                            
  <input required type="number" name="weight_tare" id="weight_tare" value="1000"><br>
</div>


<div class="field">
  <label for="reserve">Запас:</label>                            
  <input required type="number" name="reserve" id="reserve"><br>
</div>

<div class="field">
  <label for="flow">Поток:</label>                            
  <input required type="text" name="flow" id="flow"><br>
</div>
    



	    <div class="field" id="delete">
     <label id="delete_label" for="delete">Удалить:</label>
  <input type="checkbox" id="delete_checkbox" name="delete" onclick="toggle(this,'delete_label')">
    </div>

	<input class="button"  type="submit" id="button1" value="Добавить">
  </form>    
  
<?php
//обработка

//редактирование
echo '<script>';
if ( $_GET["edit"] == "1"){  
  $edit_id = $_GET["id"];
 $sql = $pdo->prepare("select id,material_id,name,weight,weight_tare,reserve,flow from spools where id = :id");
$sql->execute([
':id' => $edit_id
]);

$row = $sql->fetch();
//загрузка данных в форму

echo 'document.getElementById("id").value="'.$row["id"].'" ; ';
echo 'document.getElementById("material").value="'.$row["material_id"].'" ; ';
echo 'document.getElementById("name").value="'.$row["name"].'" ; ';
echo 'document.getElementById("weight").value="'.round($row["weight"],0).'" ; ';
echo 'document.getElementById("weight_tare").value="'.$row["weight_tare"].'" ; ';
echo 'document.getElementById("reserve").value="'.$row["reserve"].'" ; ';
echo 'document.getElementById("flow").value="'.$row["flow"].'" ; ';
//echo 'document.getElementById("primech").innerHTML="'.addcslashes($row["primech"], "\r\n").'" ; ';

echo 'document.getElementById("button1").value="Редактировать" ; ';
echo 'document.getElementById("delete").style.visibility="visible" ; ';
}else{
echo 'document.getElementById("delete").style.visibility="hidden" ; ';
}
echo '</script>';

//print_r($row);

//обновление
if ( $_POST["id"] != "" and $_POST["delete"] != "on"){

	
$sql = $pdo->prepare("update spools SET material_id = :material, name = :name, weight = :weight, weight_tare = :weight_tare, reserve = :reserve, flow = :flow where id = :id ");
//$sql->execute();
$sql->execute(array(
":id" => $_POST[id],
":material" => $_POST[material],
":name" => $_POST[name],
":weight" => $_POST[weight],
":weight_tare" => $_POST[weight_tare],
":reserve" => $_POST[reserve],
":flow" => str_replace(',','.',$_POST[flow]),
));


echo '<script>';
echo 'document.location.href = "'.$fold.$_SERVER['PHP_SELF'].'"';
echo '</script>';


//удаление
}elseif( $_POST["id"] != "" and $_POST["delete"] == "on"){
$sql = $pdo->prepare("delete from spools where id = :id ");
$sql->execute([
':id' => $_POST["id"]
]);
echo '<script>';
echo 'document.location.href = "'.$fold.$_SERVER['PHP_SELF'].'"';
echo '</script>';
//добавление
}elseif( $_POST["id"] == "" and $_POST["name"] != "") {
$sql = $pdo->prepare("insert into spools (material_id, name, weight, weight_tare,reserve,flow)  values( :material, :name, :weight, :weight_tare, :reserve, :flow) ");
//$sql->execute();
$sql->execute(array(
":material" => $_POST[material],
":name" => $_POST[name],
":weight" => $_POST[weight],
":weight_tare" => $_POST[weight_tare],
":reserve" => $_POST[reserve],
":flow" => str_replace(',','.',$_POST[flow]),
));
echo '<script>';
echo 'document.location.href = "'.$fold.$_SERVER['PHP_SELF'].'"';
echo '</script>';
}else{
  echo "ждем действий";

}
?>
</div>


    <table border="1">
      <caption><h2>Катушки</caption>
        
   <tr>
	<th>Принтер</th>
    <th>Материал</th>
    <th>Поток</th>
	<th>Вес</th>
	<th>Вес тары</th>
	<th>Запас</th>

    </tr>

<?php
$sql = $pdo->query('select id,material,name,weight, weight_tare, activ_printer, reserve, flow from spools_view order by material,name');
while ($row = $sql->fetch()){
echo '<tr class="center">';
echo '<td>'.$row[activ_printer].'</td>';	
#echo '<td><a class="edit_button" href=spool_editor.php?edit=1&id='.$row[id].'>редактировать</a></td>';
echo '<td><a class="edit_button" href=spool_editor.php?edit=1&id='.$row[id].' style="
    text-align: left;
padding-left: 10;
">'.$row[material].' - '.$row[name].'</a></td>';	
#echo '<td>'.$row[name].'</td>';	
echo '<td>'.$row[flow].'</td>';	
echo '<td>'.$row[weight].'</td>';	
echo '<td>'.$row[weight_tare].'</td>';	

echo '<td>'.$row[reserve].'</td>';	

echo  '</tr>';
}
  echo '</table>';

include "footer.php";
?>
