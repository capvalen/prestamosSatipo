<?
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$entradas = array(80,33);
$salidas = array(43);

$sumaTodo=0; $sumaRecup =0; $sumaGananc=0;

switch ($_POST['caso']) {
	case 'R1':
		$sql="SELECT `idCaja`,c.`idPrestamo`,`idCuota`, `cajaValor`, pre.presMontoDesembolso, pre.preInteresPers, pre.presPeriodo, tp.tipoDescripcion, c.idtipoProceso  FROM `caja` c left join prestamo pre on pre.idPrestamo = c.idPrestamo inner join tipoproceso tp on tp.idtipoproceso = c.idtipoProceso where cajaFecha between '{$_POST['fInicio']} 00:00' and '{$_POST['fFinal']} 23:59:59' and c.idTipoProceso in (31, 81, 80, 33) and cajaActivo = 1 order by idPrestamo;";
		
		$resultado=$cadena->query($sql);
		?> 
		<thead>
				<tr>
					<th>Préstamo</th>
					<th>Cuota</th>
					<th>Proceso</th>
					<th>Monto Pagado</th>
					<th>Recuperación</th>
					<th>Ganancia</th>
				</tr>
			</thead>
			<tbody>
	<? while($row=$resultado->fetch_assoc()){ 
		$sumaTodo = $sumaTodo + $row['cajaValor']; ?>
			<tr>
				<td><a href="creditos.php?credito=<?= $base58->encode($row['idPrestamo']);?>">CR-<?= $row['idPrestamo'];?></a></td>
				<td><? if( $row['idCuota'] <>'0'){ echo 'SP-'.$row['idCuota']; } ?></td>
				<td><?= $row['tipoDescripcion'];?></td>
				<td>S/ <?= number_format($row['cajaValor'],2);?></td>
			<? if( in_array($row['idtipoProceso'], $entradas) ){
				$presto = $row['presMontoDesembolso'];
				$porcentaje = $row['preInteresPers'];
				$plazo = $row['presPeriodo'];

				$pagoTotal = $presto *(1+$porcentaje/100);
				$soloInteres = $presto*$porcentaje/100;
				$cuota = $pagoTotal / $plazo;

				$capitalUnit = $presto/$plazo;
				$interesUnit = $soloInteres /$plazo;

				

				if( round(floatval($row['cajaValor']),1, PHP_ROUND_HALF_UP)== round(floatval($cuota),1,PHP_ROUND_HALF_UP) ){ $sumaRecup = $sumaRecup + $capitalUnit; $sumaGananc = $sumaGananc + $interesUnit; ?>
				<td>S/ <?= number_format($capitalUnit,2);?></td>
				<td>S/ <?= number_format($interesUnit,2);?></td>
		<?	} else if(round(floatval($row['cajaValor']),1, PHP_ROUND_HALF_UP)< round(floatval($cuota),1, PHP_ROUND_HALF_UP)){ $sumaRecup = $sumaRecup + ($cuota-$row['cajaValor']); ?>
				<td>S/ <?= number_format($cuota-$row['cajaValor'],2); /* .  ' cuota es '.$cuota; */?></td>
				<td>S/ 0.00</td>
			<? }
      }else if($row['idtipoProceso']==81){  $sumaGananc = $sumaGananc + $row['cajaValor']; ?>
				<td></td>
				<td><?= 'S/ '. number_format($row['cajaValor'],2);?></td>
			<? } else{
				echo "<td></td><td></td>";
			} ?>
			</tr>
<? } //end de while ?> 
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td></td>
				
				<th>S/ <?= number_format($sumaTodo,2);?></th>
				<th>S/ <?= number_format($sumaRecup,2);?></th>
				<th>S/ <?= number_format($sumaGananc,2);?></th>	
			</tfoot>
		<?
		break;


		case 'R2':
		$sql="SELECT `idCaja`,c.`idPrestamo`,`idCuota`, `cajaValor`, pre.presMontoDesembolso, pre.preInteresPers, pre.presPeriodo, tp.tipoDescripcion, c.idtipoProceso, cajaObservacion  FROM `caja` c left join prestamo pre on pre.idPrestamo = c.idPrestamo inner join tipoproceso tp on tp.idtipoproceso = c.idtipoProceso where cajaFecha between '{$_POST['fInicio']} 00:00' and '{$_POST['fFinal']} 23:59:59' and c.idTipoProceso in (43,85,84,83,82,40,41) and cajaActivo = 1 order by idPrestamo;";
		
		$resultado=$cadena->query($sql);
		?> 
		<thead>
				<tr>
					<th>Préstamo</th>
					
					<th>Proceso</th>
					<th>Inversión</th>
					
				</tr>
			</thead>
			<tbody>
	<? while($row=$resultado->fetch_assoc()){ 
		$sumaTodo = $sumaTodo + $row['cajaValor']; ?>
			<tr>
				<td><a href="creditos.php?credito=<?= $base58->encode($row['idPrestamo']);?>">CR-<?= $row['idPrestamo'];?></a></td>
				<td><?= $row['tipoDescripcion']?> <? if($row['cajaObservacion']<>''){echo '<span class="mayucula">«'.$row['cajaObservacion'].'»</span>';}?></td>
				<td>S/ <?= number_format($row['cajaValor'],2);?></td>
			</tr>
<? } //end de while ?> 
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				
				<th>S/ <?= number_format($sumaTodo,2);?></th>
			</tfoot>
		<?
		break;


		
		case 'R3':
		$sql="SELECT `idCaja`,c.`idPrestamo`,`idCuota`, `cajaValor`, `cajaFecha`, pre.presMontoDesembolso, pre.preInteresPers, pre.presPeriodo, tp.tipoDescripcion, c.idtipoProceso, cliApellidoPaterno, cliApellidoMaterno, cliNombres
		FROM `caja` c inner join prestamo pre on pre.idPrestamo = c.idPrestamo inner join tipoproceso tp on tp.idtipoproceso = c.idtipoProceso
		inner join involucrados i on i.idPrestamo = c.idPrestamo inner join cliente cl on i.idCliente = cl.idCliente
		where cajaFecha between '{$_POST['fInicio']} 00:00' and '{$_POST['fFinal']} 23:59:59' and c.idTipoProceso in (43) and cajaActivo = 1 and i.idTipoCliente=1
		group by c.`idPrestamo`
		order by idPrestamo;";
		$resultado=$cadena->query($sql);
		?> 
		<thead>
				<tr>
					<th>Préstamo</th>
					<th>Cliente</th>
					<th>Proceso</th>
					<th>Inversión</th>
					<th>Fecha</th>
					
				</tr>
			</thead>
			<tbody>
	<? while($row=$resultado->fetch_assoc()){ 
		$sumaTodo = $sumaTodo + $row['cajaValor']; ?>
			<tr>
				<td><a href="creditos.php?credito=<?= $base58->encode($row['idPrestamo']);?>">CR-<?= $row['idPrestamo'];?></a></td>
				<td class='mayuscula'><?= $row['cliApellidoPaterno'].' '.$row['cliApellidoMaterno'].', '.$row['cliNombres'];?></td>
				<td><?= $row['tipoDescripcion']?></td>
				<td>S/ <?= number_format($row['cajaValor'],2);?></td>
				<td><? $fechaCaj= new DateTime($row['cajaFecha']); echo $fechaCaj->format('d/m/Y h:m a');?></td>
			</tr>
	<? } //end de while ?> 
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td></td>
				<th>S/ <?= number_format($sumaTodo,2);?></th>
				<td></td>
			</tfoot>
		<?
		break;



		case 'R4':
		$sql="SELECT idCuota, pc.idPrestamo, cuotFechaPago, cuotCuota, cuotPago, tip.tipoDescripcion, c.idCliente, lower( concat(c.cliApellidoPaterno, ' ', c.cliApellidoMaterno,' ', c.cliNombres) ) as cliNombres, c.cliCelularPersonal FROM `prestamo_cuotas` pc inner join involucrados i on i.idPrestamo = pc.idPrestamo inner join cliente c on c.idCliente = i.idCliente inner join tipoproceso tip on tip.idtipoproceso = pc.idTipoPrestamo where pc.cuotFechaPago <=curdate() and not pc.idTipoPrestamo in (43, 80) and i.idTipoCliente=1";
		$resultado=$cadena->query($sql);
		?> 
		<thead>
				<tr>
					<th>Fecha</th>
					<th>Préstamo</th>
					<th>Cliente</th>
					<th>Celular</th>
					<th>Proceso</th>
					<th>Deuda</th>
					<th>Pagó</th>
					
				</tr>
			</thead>
			<tbody>
	<? while($row=$resultado->fetch_assoc()){ 
		$sumaTodo = $sumaTodo + $row['cuotCuota']; ?>
			<tr>
				<td><? $fechaCaj= new DateTime($row['cuotFechaPago']); echo $fechaCaj->format('d/m/Y');?></td>
				<td><a href="creditos.php?credito=<?= $base58->encode('000'.$row['idPrestamo']);?>">CR-<?= '000'.$row['idPrestamo'];?></a></td>
				<td class='mayuscula'><a href="clientes.php?idCliente=<?=$base58->encode($row['idCliente']); ?>"><?= $row['cliNombres'];?></a></td>
				<td><?= $row['cliCelularPersonal']?></td>
				<td><?= $row['tipoDescripcion']?></td>
				<td>S/ <?= number_format($row['cuotCuota'],2);?></td>
				<td>S/ <?= number_format($row['cuotPago'],2);?></td>
			</tr>
	<? } //end de while ?> 
			</tbody>
			<tfoot>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<th>S/ <?= number_format($sumaTodo,2);?></th>
				<td></td>
			</tfoot>
		<?
		break;
	default:
		# code...
		break;
}
?>