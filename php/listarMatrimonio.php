<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `matrimonio`
where (idEsposo = {$_POST['conyugue']} or idEsposa={$_POST['conyugue']}) and matrActivo=1;");

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