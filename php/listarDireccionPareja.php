<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT `cliDireccionesIgual`, a.addrDireccion, a.idCalle, a.idZona, a.addrReferencia, a.addrNumero, a.idDepartamento, a.idProvincia, a.idDistrito, an.addrDireccion as naddrDireccion, an.idCalle as nidCalle, an.idZona as nidZona, an.addrReferencia as naddrReferencia, an.addrNumero as naddrNumero, an.idDepartamento as nidDepartamento, an.idProvincia as nidProvincia, an.idDistrito as nidDistrito FROM `cliente` c inner join address a on a.idAddress= c.`cliDireccionCasa` inner join address an on an.idAddress= c.`cliDireccionNegocio` WHERE `idCliente` = {$_POST['idCli']};");

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