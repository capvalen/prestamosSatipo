<?php 
require("conkarl.php");

$filas=array();
$sql = mysqli_query($conection,"SELECT * FROM `cliente` where cliSexo ={$_POST['sex']} and cliDni = {$_POST['dni']} and cliActivo=1;");
$i=0;

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}
mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);
?>