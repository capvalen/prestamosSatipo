<?php
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';

/* $idCliente='';
$log = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jCliente'][0]['dniCli']."';");
$row = mysqli_fetch_array($log, MYSQLI_ASSOC);

// Primero creamos o verificamos si el cliente ya se encuentra en las BD;
if( count($row)===1 ){
	$idCliente=$row['idCliente'];
}else{
	$newCliente= "INSERT INTO `cliente`(`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`, `cliFijo`, `cliCalificacion`) VALUES (null,'".$_POST['jCliente'][0]['apellidosCli']."','".$_POST['jCliente'][0]['nombreCli']."','".$_POST['jCliente'][0]['dniCli']."','".$_POST['jCliente'][0]['direccionCli']."','".$_POST['jCliente'][0]['correoCli']."','".$_POST['jCliente'][0]['celularCli']."','".$_POST['jCliente'][0]['cotroCelularCli']."',0)";
	$conection->query($newCliente);
	
	$log2 = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jCliente'][0]['dniCli']."';");
	$row2 = mysqli_fetch_array($log2, MYSQLI_ASSOC);
	$idCliente=$row2['idCliente'];
} */

$fecha = new DateTime($_POST['fDesembolso']);

$feriados = include "feriadosProximos.php";
$monto = $_POST['monto'];
$plazo = $_POST['periodo'];
$saldo = $_POST['monto'];
$saltoDia = new DateInterval('P1D'); //aumenta 1 día

//Para saber si es sábado(6) o domingo(0):  format('w') 


$lista1= '[{
	"numDia": 0,
	"fPago": "'.$fecha->format('Y-m-d').'",
	"razon": "Desembolso",
	"cuota": 0,
	"interes": 0,
	"amortizacion": 0,
	"saldo": '.$saldo.',
	"saldoReal": 0
	}]';
$jsonSimple= json_decode($lista1, true);
	

switch ($_POST['modo']){
	case "1": //DIARIO
		$interes = 0.0066;
		$cuota = round(($monto*$interes)/(1-pow((1+$interes),-$plazo)),1, PHP_ROUND_HALF_UP);
		$intervalo = new DateInterval('P1D'); //aumenta 1 día
		break;
	case "2": //SEMANAL
		$interes = 0.0152;
		$cuota = round(($monto*$interes)/(1-pow((1+$interes),-$plazo)),1, PHP_ROUND_HALF_UP);
		$intervalo = new DateInterval('P1W'); //aumenta 1 día
		break;
	case "4": //QUINCENAL
		$interes = 0.0295;
		$cuota = round(($monto*$interes)/(1-pow((1+$interes),-$plazo)),1, PHP_ROUND_HALF_UP);
		$intervalo = new DateInterval('P15D'); //aumenta 1 día
		break;
	case "3": //MENSUAL
		$tea = 0.4425;
		$itf= 0.00005;
		$tseg= 0.00038;

		$tem= pow((1+ $tea), 1/12)-1;
		$tEfecDiaria= pow(1+$tem, 1/30)-1;
		$tSegDiario= round($tseg/30,8);
		$fechaPago=new DateTime($_POST['primerPago']);

		$lista= '[{
			"numDia": 0,
			"fPago": "'.$fecha->format('Y-m-d').'",
			"dias": 0,
			"diasAcum": 0,
			"frc": 0,
			"sk": '.$monto.',
			"amortizacion": 0,
			"interes": 0,
			"seg1": 0,
			"segDef": 0,
			"cuotaSinItf": 0,
			"conItf": 0,
			"totalCuota": 0
		}]';
		
		$intervalo = new DateInterval('P30D'); //aumenta 30 día
		$jsonPagos= json_decode($lista, true);
		
		//print_r($jsonPagos[0]['fPago']);
		//$cuota = round(($monto*$interes)/(1-pow((1+$interes),-$plazo)),1, PHP_ROUND_HALF_UP);
		
		break;
	default:
	?> <tr><td>Datos inválidos</td></tr><?php
	break;
}

/* ?> 
<tr><td class='grey-text text-darken-2'><strong>0</strong></td> <td><?= $fecha->format('d/m/Y'); ?></td> <td>-</td><td>-</td> <td>-</td> <td><?= number_format($saldo,2);?></td></tr><?php */

if($_POST['modo']!=3){
	$interesSumado=0;
	$fecha->add($intervalo);
	//$cuota = round($monto*$interes/$plazo,2);
	for ($i=0; $i < $plazo ; $i++) {
/* 	?> <tr><?php */
		
		$razon = esFeriado($feriados, $fecha->format('Y-m-d'));
		if($razon!=false ){
			//echo "si es feriado";
			//echo "Feriado ".": ". $fecha->format('d/m/Y'). "<br>";
			/* ?>
			<td class='grey-text text-darken-2'>-</td> <td class='grey-text text-darken-2'><?= $fecha->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'><?= $razon; ?></td> <td></td> <td></td> <td></td>
			<?php */
			$i--;
			$jsonSimple[]=array(
				"numDia"=>'-',
				"fPago" => $fecha->format('Y-m-d'),
				"razon" => 'Feriado',
				"cuota" => $razon,
				"interes"=> '',
				"amortizacion"=> '',
				"saldo" => '',
				"saldoReal"=> ''
			);
			$fecha->add($saltoDia);
		}else{
			//echo "no es feriado";
			if( $fecha->format('w')=='0' ){
				//No hacer nada
				//echo "\nDomingo ".": ". $fecha->format('d/m/Y'). "<br>\n";
				$i--;
				/* ?>
				<td class='grey-text text-darken-2'>-</td> <td class='grey-text text-darken-2'><?= $fecha->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>Domingo</td> <td></td> <td></td> <td></td>
				<?php */
				$jsonSimple[]=array(
					"numDia"=>'-',
					"fPago" => $fecha->format('Y-m-d'),
					"razon" =>'Domingo',
					"cuota" => '',
					"interes"=> '',
					"amortizacion"=> '',
					"saldo" => '',
					"saldoReal"=> ''
				);
				$fecha->add($saltoDia);
			// }else if($fecha->format('w')=='6'){ 
			// 	//echo "Sábado ".": ". $fecha->format('d/m/Y'). "<br>";  ---------SI SE CUENTAN SABADOS EN ESTE SISTEMA---------
			// 	$i--;
			}else{
				//$suma+=$cuota;
				//$saldo = $saldo*$interes;
				$interesVariable= round($saldo * $interes, 1, PHP_ROUND_HALF_UP);
				$amortizacion = round($cuota-$interesVariable, 1, PHP_ROUND_HALF_UP);
				$saldo = $saldo -$amortizacion;
				$interesSumado+=$interesVariable;

				//echo "Día #".($i+1).": ". $fecha->format('d/m/Y') . "<br>";
				/* ?><td class='grey-text text-darken-2'><strong><?= $i+1; ?></strong></td> <td class='grey-text text-darken-2'><?= $fecha->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>S/ <?= number_format($cuota, 2); ?></td> <td class='grey-text text-darken-2'><?= number_format($interesVariable,2); ?></td> <td class='grey-text text-darken-2'><?= number_format($amortizacion,2); ?></td> <td class='grey-text text-darken-2'><?= number_format($saldo, 2);?></td> <?php */

				$jsonSimple[]=array(
					"numDia"=>$i+1,
					"fPago" => $fecha->format('Y-m-d'),
					"razon" =>'',
					"cuota" => $cuota,
					"interes"=> $interesVariable,
					"amortizacion"=> $amortizacion,
					"saldo" => $saldo,
					"saldoReal"=> 0
				);
				$fecha->add($intervalo);
			}
		}
	/* ?></tr><?php */
	}
	
	$jsonSimple[0]['saldoReal'] = $monto+$interesSumado;
	$dia=1;
	for ($j=0; $j <  count($jsonSimple) ; $j++) { ?><tr><?php
		
		$nueva= new DateTime ($jsonSimple[$j]['fPago']);

		if($jsonSimple[$j]['razon']==='Desembolso'){
			?> <td class='grey-text text-darken-2'>-</td> <td class='grey-text text-darken-2'><?= $nueva->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>Desembolso</td>  <td><?= number_format($jsonSimple[$j]['saldoReal'],2);?></td>
			<?php
		}else if($jsonSimple[$j]['razon']==='Domingo'){ $dia++;
			?> <td class='grey-text text-darken-2'>-</td> <td class='grey-text text-darken-2'><?= $nueva->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>Domingo</td> <td></td>  
			<?php
		}else if($jsonSimple[$j]['razon']==='Feriado'){ $dia++;
			?> <td class='grey-text text-darken-2'>-</td> <td class='grey-text text-darken-2'><?= $nueva->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'><?= $jsonSimple[$j]['cuota'];?> </td>  <td></td>
			<?php
		}else{
			if($j>=1){
				$jsonSimple[$j]['saldoReal'] = $jsonSimple[$j-$dia]['saldoReal']-$jsonSimple[$j]['cuota']; $dia=1;
			}

			?><td class='grey-text text-darken-2'><strong><?= $jsonSimple[$j]['numDia']; ?></strong></td> <td class='grey-text text-darken-2'><?= $nueva->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>S/ <?= number_format($jsonSimple[$j]['cuota'], 2); ?></td> <td class='grey-text text-darken-2 hidden'><?= number_format($jsonSimple[$j]['interes'],2); ?></td> <td class='grey-text text-darken-2 hidden'><?= number_format($jsonSimple[$j]['amortizacion'],2); ?></td> <td class='grey-text text-darken-2 hidden'><?= number_format($jsonSimple[$j]['saldo'], 2);?></td> <td><?= number_format($jsonSimple[$j]['saldoReal'], 2);?></td> <?php
		}
	?></tr><?php
	}
	
}//fin de if modo=3
else{
	?><tr class="grey-text text-darken-2">
		<td><strong>0</strong></td> <td><?php $fff= new DateTime($jsonPagos[0]['fPago']); echo $fff->format('d/m/Y'); ?></td> <td><?= number_format($jsonPagos[0]['sk'],2);?></td> <td></td> <td></td> <td></td> <td></td><td></td> 
	</tr><?php
	$sumaDias=0; $sumaFrc=0;
	for ($i=0; $i < $plazo ; $i++) {
		
		$fechaAnt = new DateTime( $jsonPagos[$i]['fPago']);
		
		$sumaDias+=$fechaPago->diff($fechaAnt)->days;
		$diasAhora= $fechaPago->diff($fechaAnt)->days;
		$frcCalc= round(1/(pow( 1+$tEfecDiaria , $sumaDias)), 6);
		$sumaFrc+=$frcCalc;
		$jsonPagos[]= array(
			'numDia'=>$i+1,
			'fPago'=>$fechaPago->format('Y-m-d'),
			'dias' => $diasAhora,
			'diasAcum' => $sumaDias,
			'frc'=> $frcCalc,
			"sk" => 0,
			"amortizacion" => 0,
			"interes" => 0, //round((pow( 1+ $tem , $diasAhora/30 )-1)*$jsonPagos[$i]['sk'], 2)
			"seg1" => 0,
			"segDef" => 0,
			"cuotaSinItf" => 0,
			"conItf" => 0,
			"totalCuota" => 0
		);
		$diffe = $fechaPago->format('t');
		$intervalo = new DateInterval('P'.$diffe.'D');
		$fechaPago->add($intervalo);
	}
	$sumaSeg=0;
	for ($i=1; $i <=$plazo ; $i++) { 
		
		$jsonPagos[$i]['interes']=round((pow( 1+ $tem , $jsonPagos[$i]['dias']/30 )-1)*$jsonPagos[$i-1]['sk'], 2);
		$jsonPagos[$i]['amortizacion']=round($monto/$sumaFrc-$jsonPagos[$i]['interes'],2);
		$jsonPagos[$i]['sk']=round($jsonPagos[$i-1]['sk']-$jsonPagos[$i]['amortizacion'],2);
		$segInst= round($jsonPagos[$i-1]['sk']*$jsonPagos[$i]['dias']*$tSegDiario,2);
		$sumaSeg+=$segInst;
		$jsonPagos[$i]['seg1']=$segInst;
		//echo $jsonPagos[$i]['interes']."\n";
	//	print_r(	$jsonPagos[$i] );
	}
	
	for ($j=1; $j <= $plazo ; $j++) { 
		$jsonPagos[$j]['seg1']=  round($jsonPagos[$j-1]['sk']*$jsonPagos[$j]['dias']*$tSegDiario,2);
		$jsonPagos[$j]['segDef']=round($sumaSeg/$plazo,2);
		$jsonPagos[$j]['cuotaSinItf'] = $jsonPagos[$j]['amortizacion']+$jsonPagos[$j]['interes']+$jsonPagos[$j]['segDef'];
		$jsonPagos[$j]['conItf']= $jsonPagos[$j]['cuotaSinItf']*$itf;
		$jsonPagos[$j]['totalCuota']=round($jsonPagos[$j]['cuotaSinItf'] +$jsonPagos[$j]['conItf'],2);
		?>
	<tr>
	<td class='grey-text text-darken-2'><strong><?= $j; ?></strong></td>
	<td class='grey-text text-darken-2'><?php $fechaDispl= strtotime($jsonPagos[$j]['fPago']); echo date('d/m/Y',$fechaDispl); ?></td>
	<td class='grey-text text-darken-2 hidden'><?= $jsonPagos[$j]['dias']; ?></td>
	<td class='grey-text text-darken-2 hidden'><?= $jsonPagos[$j]['diasAcum'];?></td>
	<td class='grey-text text-darken-2 hidden'><?= $jsonPagos[$j]['frc'];?></td>
	<td class='grey-text text-darken-2'><?= $jsonPagos[$j]['sk'];?></td>
	<td class='grey-text text-darken-2'><?= number_format($jsonPagos[$j]['amortizacion'],2);?></td>
	<td class='grey-text text-darken-2'><?= $jsonPagos[$j]['interes'];?></td>
	<td class='grey-text text-darken-2 hidden'><?= $jsonPagos[$j]['seg1'];?></td>
	<td class='grey-text text-darken-2'><?= $jsonPagos[$j]['segDef'];?></td>
	<td class='grey-text text-darken-2 hidden'><?= $jsonPagos[$j]['cuotaSinItf'];?></td>
	<td class='grey-text text-darken-2'><?= $jsonPagos[$j]['conItf'];?></td>
	<td class='grey-text text-darken-2'><?= number_format($jsonPagos[$j]['totalCuota'],2);?></td>

	<?php
	}
	
	print_r(	$jsonPagos ); //$jsonPagos[0]['fPago']
}

function esFeriado($feriados, $dia){
	foreach ($feriados as $llave => $valor) {
		if($valor["ferFecha"]==$dia){
			return $valor["ferDescripcion"]; break;
		}
	}
	return false;
}

?>