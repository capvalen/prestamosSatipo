<?
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT `idCliente`, `cliDni`, lower(`cliNombres`) as `cliNombres`, lower(`cliApellidoPaterno`) as `cliApellidoPaterno`, lower(`cliApellidoMaterno`) as `cliApellidoMaterno`, `cliSexo`, `cliNumHijos`, `cliDireccionesIgual`,
ca.calDescripcion, lower(a.addrDireccion) as addrDireccion, lower(a.addrReferencia) as addrReferencia, a.addrNumero,
lower(di.distrito) as distrito, lower(pro.provincia) as provincia, lower(de.departamento) as departamento,
can.calDescripcion as ncalDescripcion, lower(an.addrDireccion) as naddrDireccion, lower(an.addrReferencia) as naddrReferencia, an.addrNumero as naddrNumero, 
lower(din.distrito) as ndistrito, lower(pron.provincia) as nprovincia, lower(den.departamento) as ndepartamento,
`cliCelularPersonal`, `cliCelularReferencia`, ec.civDescripcion, `cliActivo`, ec.idEstadoCivil, a.idZona, an.idZona as nidZona, a.idDepartamento, a.idProvincia, a.idDistrito,  an.idDepartamento as nidDepartamento, an.idProvincia as nidProvincia, an.idDistrito as nidDistrito, c.`cliDireccionCasa`, c.`cliDireccionNegocio`
FROM `cliente` c
inner join address a on a.idAddress= c.`cliDireccionCasa`
inner join distrito di on di.idDistrito = a.idDistrito
inner join provincia pro on pro.idProvincia = a.idProvincia
inner join departamento de on de.idDepartamento = a.idDepartamento
inner join calles ca on ca.idCalle = a.idCalle

inner join address an on an.idAddress= c.`cliDireccionNegocio`
inner join distrito din on din.idDistrito = an.idDistrito
inner join provincia pron on pron.idProvincia = an.idProvincia
inner join departamento den on den.idDepartamento = an.idDepartamento
inner join calles can on can.idCalle = a.idCalle

inner join estadocivil ec on ec.idEstadoCivil = c.idEstadoCivil
where idCliente ={$_POST['idCli']};");

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