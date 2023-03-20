<?php
include "header.php";
?>

<body>
 
<div class="main">
<a href="."><button>Назад</button></a><br>
<a href="<?=$fold.$_SERVER['PHP_SELF']?>"><button>Очистить</button></a><br>
<h1> Катушки</h1>

	 <form autocomplete="off" id="form" method="post" action="<?=$fold.$_SERVER['PHP_SELF']?>">
  <input  type="hidden" name="id" id="id">
  
  
  
<div class="field">
  <label for="name">Название материала:</label>
	<input required type="text" name="name" id="name" size="30"><br>
</div>

<div class="field">
  <label for="density">Плотность:</label>                            
  <input required type="text" name="density" id="density" value="1.1"><br>
</div>

<div class="field">
  <label for="RetLen">RETRACT_LENGTH:</label>                            
  <input required type="text" name="RetLen" id="RetLen" value="0"><br>
</div>

<div class="field">
  <label for="RetSp">RETRACT_SPEED:</label>                            
  <input required type="text" name="RetSp" id="RetSp" value="0"><br>
</div>

<div class="field">
  <label for="UnRetExtrLen">UNRETRACT_EXTRA_LENGTH:</label>                            
  <input required type="text" name="UnRetExtrLen" id="UnRetExtrLen" value="0"><br>
</div>

<div class="field">
  <label for="UnRetSp">UNRETRACT_SPEED:</label>                            
  <input required type="text" name="UnRetSp" id="UnRetSp" value="0"><br>
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
 $sql = $pdo->prepare("select id,name,density,RetLen,RetSp,UnRetExtrLen,UnRetSp from materials where id = :id");
$sql->execute([
':id' => $edit_id
]);

$row = $sql->fetch();
//загрузка данных в форму

echo 'document.getElementById("id").value="'.$row["id"].'" ; ';
echo 'document.getElementById("name").value="'.$row["name"].'" ; ';
echo 'document.getElementById("density").value="'.$row["density"].'" ; ';
//echo 'document.getElementById("primech").innerHTML="'.addcslashes($row["primech"], "\r\n").'" ; ';
echo 'document.getElementById("RetLen").value="'.$row["RetLen"].'" ; ';
echo 'document.getElementById("RetSp").value="'.$row["RetSp"].'" ; ';
echo 'document.getElementById("UnRetExtrLen").value="'.$row["UnRetExtrLen"].'" ; ';
echo 'document.getElementById("UnRetSp").value="'.$row["UnRetSp"].'" ; ';

echo 'document.getElementById("button1").value="Редактировать" ; ';
echo 'document.getElementById("delete").style.visibility="visible" ; ';
}else{
echo 'document.getElementById("delete").style.visibility="hidden" ; ';
}
echo '</script>';

//print_r($row);

//обновление
if ( $_POST["id"] != "" and $_POST["delete"] != "on"){

	
$sql = $pdo->prepare("update materials SET name = :name, density = :density, RetLen = :RetLen, RetSp = :RetSp, UnRetExtrLen = :UnRetExtrLen, UnRetSp = :UnRetSp    where id = :id ");
//$sql->execute();
$sql->execute(array(
":id" => $_POST[id],
":name" => $_POST[name],
":density" => $_POST[density],
":RetLen" => $_POST[RetLen],
":RetSp" => $_POST[RetSp],
":UnRetExtrLen" => $_POST[UnRetExtrLen],
":UnRetSp" => $_POST[UnRetSp],
));


echo '<script>';
echo 'document.location.href = "'.$fold.$_SERVER['PHP_SELF'].'"';
echo '</script>';


//удаление
}elseif( $_POST["id"] != "" and $_POST["delete"] == "on"){
$sql = $pdo->prepare("delete from materials where id = :id ");
$sql->execute([
':id' => $_POST["id"]
]);
echo '<script>';
echo 'document.location.href = "'.$fold.$_SERVER['PHP_SELF'].'"';
echo '</script>';
//добавление
}elseif( $_POST["id"] == "" and $_POST["name"] != "") {
$sql = $pdo->prepare("insert into materials (name, density, RetLen, RetSp, UnRetExtrLen, UnRetSp )  values(:name, :density, :RetLen, :RetSp, :UnRetExtrLen, :UnRetSp  ) ");
//$sql->execute();
$sql->execute(array(
":name" => $_POST[name],
":density" => $_POST[density],
":RetLen" => $_POST[RetLen],
":RetSp" => $_POST[RetSp],
":UnRetExtrLen" => $_POST[UnRetExtrLen],
":UnRetSp" => $_POST[UnRetSp],
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
      <caption><h2>Материалы</caption>
        
   <tr>
	<th>Название</th>
	<th>Плотность</th>
	<th>RETRACT_LENGTH</th>
	<th>RETRACT_SPEED</th>
	<th>UNRETRACT_EXTRA_LENGTH</th>
	<th>UNRETRACT_SPEED</th>
    </tr>

<?php
$sql = $pdo->query('select id,name,density,RetLen,RetSp,UnRetExtrLen,UnRetSp from materials order by name');
while ($row = $sql->fetch()){
echo '<tr class="center">';
echo '<td><a class="edit_button" href=material_editor.php?edit=1&id='.$row[id].'>'.$row[name].'</a></td>';	
echo '<td>'.$row[density].'</td>';	
echo '<td>'.$row[RetLen].'</td>';	
echo '<td>'.$row[RetSp].'</td>';	
echo '<td>'.$row[UnRetExtrLen].'</td>';	
echo '<td>'.$row[UnRetSp].'</td>';	

echo  '</tr>';
}
  echo '</table>';

include "footer.php";
?>