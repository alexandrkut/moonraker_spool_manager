
// установка активной катушки для принтера
function set_activ_spool(printer_id, spool_id){

	
  if(printer_id ){
	 // 	alert (printer_id)
	//alert (spool_id)
    
$.ajax({
    url: "set_activ_spool.php",
    type: "POST",
    dataType: 'json',
    data: "printer_id="+printer_id+"&spool_id="+spool_id
	})
	.done(function( msg ) {
  //alert( "Data Saved: " + msg );
  //location.reload();
   document.location.reload();
});

    };

}

// обработка галки удалить
function toggle(box,theId) {
   if(document.getElementById) {
      var cell = document.getElementById(theId);
      if(box.checked) {
         cell.className = "delete_on";
         document.getElementById("button1").value="Удалить";
      }
      else {
         cell.className = "delete_off";
         document.getElementById("button1").value="Редактировать";
      }
   }
}




