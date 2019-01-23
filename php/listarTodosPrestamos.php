<?
$sql="SELECT presMontoDesembolso, presPeriodo, tpr.tpreDescipcion,
u.usuNombres, preInteresPers, i.idCliente, pre.idPrestamo,
case presFechaDesembolso when '0000-00-00 00:00:00' then 'Desembolso pendiente' else presFechaDesembolso end as `presFechaDesembolso`,
case presAprobado when 0 then 'Sin aprobar' when 2 then 'Rechazado' else 'Aprobado' end as `presAprobado`,
	lower(concat (c.cliApellidoPaterno, ' ', c.cliApellidoMaterno, ' ', c.cliNombres)) as cliNombres
FROM `prestamo` pre
	inner join involucrados i on i.idPrestamo = pre.idPrestamo
	inner join cliente c on c.idCliente = i.idCliente
inner join usuario u on u.idUsuario = pre.idUsuario
inner join tipoprestamo tpr on tpr.idTipoPrestamo = pre.idTipoPrestamo
	where presActivo =1 and presFechaDesembolso <> '0000-00-00 00:00:00' and presAprobado =1 and i.idTipoCliente=1
	order by pre.idPrestamo asc;";
$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){ 
	$fecha = new DateTime($row['presFechaDesembolso']);
	?>
	<tr>
		<td><a href="creditos.php?credito=<?= $base58->encode($row['idPrestamo']);?>">CR-<?= $row['idPrestamo'];?></a></td>
		<td class="mayuscula"><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']); ?>"><?= $row['cliNombres'];?></a></td>
		<td><?= $row['tpreDescipcion'];?></td>
		<td>S/ <?= number_format($row['presMontoDesembolso'],2);?></td>
		<td><?= $row['presPeriodo'];?></td>
		<td><?= $row['preInteresPers'];?></td>
		<td><?= $fecha->format('d/m/Y');?></td>
	</tr>
<? }
?>