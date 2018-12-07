<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT `idCliente`, `cliDni`, lower(`cliNombres`) as `cliNombres`, lower(`cliApellidoPaterno`) as `cliApellidoPaterno`, lower(`cliApellidoMaterno`) as `cliApellidoMaterno`, e.civDescripcion, `cliDireccionesIgual`, `cliDireccionCasa`, `cliDireccionNegocio`
FROM `cliente` c inner join estadocivil e on e.idEstadoCivil = c.`idEstadoCivil` WHERE `cliActivo`=1 and idCliente ='{$_POST['idCli']}';");

$filas=array();
$i=0;
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	
	$filas[$i]= $row;
	$i++;

}
//echo "Cobrar Und. => ".$cuantosDebe;
mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);

?>