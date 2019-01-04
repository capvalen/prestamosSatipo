<?
include 'conkarl.php';

$entradas = array(80,33);
switch ($_POST['caso']) {
	case 'R1':
		$sql="SELECT `idCaja`,c.`idPrestamo`,`idCuota`, `cajaValor`, pre.presMontoDesembolso, pre.preInteresPers, pre.presPeriodo, tp.tipoDescripcion, c.idtipoProceso  FROM `caja` c inner join prestamo pre on pre.idPrestamo = c.idPrestamo inner join tipoproceso tp on tp.idtipoproceso = c.idtipoProceso where cajaFecha between '{$_POST['fInicio']} 00:00' and '{$_POST['fFinal']} 23:59:59' and c.idTipoProceso in (81, 80, 33) and cajaActivo = 1 order by idPrestamo";
		
		$resultado=$cadena->query($sql);
		?> 
		<thead>
				<tr>
					<th>Prestamo</th>
					<th>idCuota</th>
					<th>Proceso</th>
					<th>Monto Pagado</th>
					<th>Capital</th>
					<th>Interés</th>
					<th>Recuperación</th>
					<th>Ganancia</th>
				</tr>
			</thead>
			<tbody>
	<? while($row=$resultado->fetch_assoc()){  ?>
			<tr>
				<td>CR-<?= $row['idPrestamo'];?></td>
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

				if( $row['cajaValor']== $cuota ){ ?>
				<td>S/ <?= number_format($capitalUnit,2);?></td>
				<td>S/ <?= number_format($interesUnit,2);?></td>
		<?	} else if($row['cajaValor']< $cuota){ ?>
				<td>S/ <?= number_format($capitalUnit-$row['cajaValor'],2);?></td>
				<td>S/ 0.00</td>
			<? }
      }else{
				echo "<td></td><td></td>";
			} ?>
			</tr>
<? } ?> 
			</tbody>
		<?
		break;
	
	default:
		# code...
		break;
}
?>