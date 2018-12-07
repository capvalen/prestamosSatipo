<?php 
//header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$sql= "SELECT `idCliente`,  `cliDni`, lower(`cliNombres`) as `cliNombres`, lower(`cliApellidoPaterno`) as `cliApellidoPaterno`, lower(`cliApellidoMaterno`) as `cliApellidoMaterno`, lower(addrDireccion) as `addrDireccion`,  `cliCelularPersonal`, e.civDescripcion
FROM `cliente` c inner join estadocivil e on c.`idEstadoCivil`= e.idEstadoCivil
inner join address a on a.idAddress = c.cliDireccionCasa
where `cliActivo`=1
order by idCliente desc
limit 20";

$log = mysqli_query($conection, $sql );
while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{?>
  
	<tr>
    <td><?= $row['idCliente']; ?></td>
    <td><?= $row['cliDni']; ?></td>
    <td><?= ucwords($row['cliApellidoPaterno']).' '.ucwords($row['cliApellidoMaterno']).', '. ucwords($row['cliNombres']); ?></td>
    <td><?= ucwords($row['addrDireccion']); ?></td>
    <td><?= $row['cliCelularPersonal']; ?></td>
    <td><?= $row['civDescripcion']; ?></td>
    <td> <a class="btn btn-sm btn-azul btn-outline btnAsignarSocio" href="solicitud.php?titular=<?= $row['idCliente'];?>"><i class="icofont-ui-add"></i> Crear solicitud</a> </td>
  </tr>
<?php

}
/* liberar la serie de resultados */
mysqli_free_result($log);
/* cerrar la conexion */
mysqli_close($conection);

?>