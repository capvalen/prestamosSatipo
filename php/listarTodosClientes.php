<table class="table table-hover">
<thead>
	<tr>
		<th>Cod.</th>
		<th>D.N.I.</th>
		<th>Apellidos y nombres</th>
		<th>Dirección</th>
		<th>Celular</th>
		<th>Estado civil</th>
		<th>@</th>
	</tr>
</thead>
<tbody>



<?php 
require("conkarl.php");
$sql = mysqli_query($conection,"SELECT c.*, a.addrDireccion, a.addrNumero, ec.civDescripcion FROM `cliente` c inner join address a on a.idAddress = c.cliDireccionCasa inner join estadocivil ec on c.idEstadoCivil = ec.idEstadoCivil where cliActivo=1");
$botonMatri='';
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{ ?>
<tr>
	<td><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']); ?>">CL-<?= $row['idCliente'];?></a></td>
	<td><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']); ?>"><?= $row['cliDni'];?></a></td>
	<td class="mayuscula"><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']); ?>"><?= $row['cliApellidoPaterno'].' '.$row['cliApellidoMaterno'].' '.$row['cliNombres'];?></a></td>
	<td class="mayuscula"><?= $row['addrDireccion'].' '.$row['addrNumero'];?></td>
	<td><?= $row['cliCelularPersonal'];?></td>
	<td><?= $row['civDescripcion'];?></td>
	<td><a class="btn btn-sm btn-azul btn-outline btnAsignarSocio" href="creditos.php?titular=<?= $row["idCliente"];?>"><i class="icofont-ui-add"></i> Crear solicitud</a></td>
</tr>
 <?php
}
mysqli_close($conection); //desconectamos la base de datos
?>
</tbody>
</table>