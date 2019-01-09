<?
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$entradas = array(80,33);
$salidas = array(43);
switch ($_POST['caso']) {
	case 'R1':
		$sql="SELECT `idCaja`,c.`idPrestamo`,`idCuota`, `cajaValor`, pre.presMontoDesembolso, pre.preInteresPers, pre.presPeriodo, tp.tipoDescripcion, c.idtipoProceso  FROM `caja` c inner join prestamo pre on pre.idPrestamo = c.idPrestamo inner join tipoproceso tp on tp.idtipoproceso = c.idtipoProceso where cajaFecha between '{$_POST['fInicio']} 00:00' and '{$_POST['fFinal']} 23:59:59' and c.idTipoProceso in (81, 80, 33) and cajaActivo = 1 order by idPrestamo;";
		
		$resultado=$cadena->query($sql);
		$sumaTodo=0; $sumaRecup =0; $sumaGananc=0;
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
				<td><?= $row['tipoDescripcion']?></td>
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

				if( floatval($row['cajaValor'])== floatval($cuota) ){ $sumaRecup = $sumaRecup + $capitalUnit; $sumaGananc = $sumaGananc + $interesUnit; ?>
				<td>S/ <?= number_format($capitalUnit,2);?></td>
				<td>S/ <?= number_format($interesUnit,2);?></td>
		<?	} else if(floatval($row['cajaValor'])< floatval($cuota)){ $sumaRecup = $sumaRecup + ($cuota-$row['cajaValor']); ?>
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
		$sql="SELECT `idCaja`,c.`idPrestamo`,`idCuota`, `cajaValor`, pre.presMontoDesembolso, pre.preInteresPers, pre.presPeriodo, tp.tipoDescripcion, c.idtipoProceso  FROM `caja` c inner join prestamo pre on pre.idPrestamo = c.idPrestamo inner join tipoproceso tp on tp.idtipoproceso = c.idtipoProceso where cajaFecha between '{$_POST['fInicio']} 00:00' and '{$_POST['fFinal']} 23:59:59' and c.idTipoProceso in (43) and cajaActivo = 1 order by idPrestamo;";
		
		$resultado=$cadena->query($sql);
		$sumaTodo=0; $sumaRecup =0; $sumaGananc=0;
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
				<td><?= $row['tipoDescripcion']?></td>
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
	default:
		# code...
		break;
}
?>