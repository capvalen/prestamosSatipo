<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"call buscarCliente('".$_POST['buscar']."');");

$filas=array();
$i=0;
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}

mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);

?>