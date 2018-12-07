<?php
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();


$idPrestamo = $base58->decode($_POST['credito']);

$sqlDesembolso= "UPDATE `prestamo` SET `presFechaDesembolso`=now() WHERE `idPrestamo`={$idPrestamo};
INSERT INTO `caja`(`idCaja`, `idPrestamo`, `idCuota`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`) 
select null,{$idPrestamo},0,43,now(),`presMontoDesembolso`,'<a href=creditos.php?credito={$_POST['credito']}>CR-{$idPrestamo}</a>',1,1,{$_COOKIE['ckidUsuario']},0
from prestamo 
where `idPrestamo`={$idPrestamo}
";

$respDesemb= $cadena->multi_query($sqlDesembolso);



$sqlCabecera = "SELECT `presMontoDesembolso`, `presPeriodo`, `idTipoPrestamo` FROM `prestamo` WHERE `idPrestamo`={$idPrestamo};";
$respCabez= $prisionero->query($sqlCabecera);
$rowCabez= $respCabez->fetch_assoc();


$monto = $rowCabez['presMontoDesembolso'];
$plazo = $rowCabez['presPeriodo'];
$saldo = $rowCabez['presMontoDesembolso'];
$modo= $rowCabez['idTipoPrestamo'];


$sqlRegistrado= "SELECT idCuota, cuotFechaPago FROM `prestamo_cuotas`
where idPrestamo= {$idPrestamo}
order by cuotFechaPago asc";
$respAuto = $esclavo->query($sqlRegistrado);

$autoFechas=array();
$i=0;
while($rowAuto= $respAuto->fetch_assoc() )
{
	$autoFechas[$i]= $rowAuto;
	$i++;
}



$fecha = new DateTime(); //fecha de hoy

$feriados = include "feriadosProximos.php";

$saltoDia = new DateInterval('P1D'); //aumenta 1 día

//Para saber si es sábado(6) o domingo(0):  format('w') 

switch ($modo){
	case "1": //DIARIO
		$intervalo = new DateInterval('P1D'); //aumenta 1 día
		break;
	case "2": //SEMANAL
		$intervalo = new DateInterval('P1W'); //aumenta 1 semana
		break;
	case "4": //QUINCENAL
		$intervalo = new DateInterval('P15D'); //aumenta 15 días
		break;
	case "3": //MENSUAL
		
	/* 	$tem= pow((1+ $tea), 1/12)-1;
		$tEfecDiaria= pow(1+$tem, 1/30)-1;
		$tSegDiario= round($tseg/30,8);
		$fechaPago=new DateTime('2018-11-01');

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
		
		$intervalo = new DateInterval('P30D'); //aumenta 1 día
		$jsonPagos= json_decode($lista, true); */
		
		//print_r($jsonPagos[0]['fPago']);
		//$cuota = round(($monto*$interes)/(1-pow((1+$interes),-$plazo)),1, PHP_ROUND_HALF_UP);
		
		break;
	default:
	break;
}

$sqlCuotasFechas= "";
if($modo!=3){
	$interesSumado=0;
	//$fecha->add($intervalo);
	//$cuota = round($monto*$interes/$plazo,2);
	$j=0;
	for ($i=0; $i <= $plazo ; $i++) { 
		$razon = esFeriado($feriados, $fecha->format('Y-m-d'));
		if($razon!=false ){
			//echo "si es feriado";
			//echo "Feriado ".": ". $fecha->format('d/m/Y'). "<br>";
			$i--;
			$fecha->add($saltoDia);
		}else{
			//echo "no es feriado";
			if( $fecha->format('w')=='0' ){
				//No hacer nada
				//echo "\nDomingo ".": ". $fecha->format('d/m/Y'). "<br>\n";
				$i--;
				$fecha->add($saltoDia);
			// 	//echo "Sábado ".": ". $fecha->format('d/m/Y'). "<br>";  ---------SI SE CUENTAN SABADOS EN ESTE SISTEMA---------
			}else{
				//echo "Día #".($i+1).": ". $fecha->format('d/m/Y') . "<br>";
				if($j==0){ $autoFechas[$j]['cuotFechaPago']= date('Y-m-d'); }
				else{ $autoFechas[$j]['cuotFechaPago']= $fecha->format('Y-m-d');}
				//************************ --------------  */HACER EL UPDATEEEEEEEEEEE --------------------- ****************
				$sqlCuotasFechas=$sqlCuotasFechas."UPDATE `prestamo_cuotas` SET `cuotFechaPago`='".$autoFechas[$j]['cuotFechaPago']."'
				WHERE `idCuota`=".$autoFechas[$j]['idCuota'].";";
				$j++;

				$fecha->add($intervalo);
				//echo $sql;
				//unset($conection);
			}
		}
	}
	//echo $sqlCuotasFechas;
	$cadena->multi_query($sqlCuotasFechas);
	//var_dump($autoFechas);
	


/* 	$jsonSimple[0]['saldoReal'] = $monto+$interesSumado;
	$dia=1;
	for ($j=0; $j <  count($jsonSimple) ; $j++) {
		
		$nueva= new DateTime ($jsonSimple[$j]['fPago']);

		if($jsonSimple[$j]['razon']==='Desembolso'){
			$sqlCuotas=$sqlCuotas."INSERT INTO `prestamo_cuotas`(`idCuota`, `idPrestamo`, `cuotFechaPago`, `cuotCuota`, `cuotFechaCancelacion`, `cuotPago`, `cuotSaldo`, `cuotVo`, `cuotObservaciones`,`idTipoPrestamo`) VALUES (null,$idPrestamo,'{$nueva->format('Y-m-d')}',0,'',0,{$jsonSimple[$j]['saldoReal']},'','', 43);";
		}else if($jsonSimple[$j]['razon']==='Domingo'){ $dia++;
		}else if($jsonSimple[$j]['razon']==='Feriado'){ $dia++;
		}else{
			if($j>=1){
				$jsonSimple[$j]['saldoReal'] = $jsonSimple[$j-$dia]['saldoReal']-$jsonSimple[$j]['cuota']; $dia=1;
			}
			$sqlCuotas=$sqlCuotas."INSERT INTO `prestamo_cuotas`(`idCuota`, `idPrestamo`, `cuotFechaPago`, `cuotCuota`, `cuotFechaCancelacion`, `cuotPago`, `cuotSaldo`, `cuotVo`, `cuotObservaciones`,`idTipoPrestamo`) VALUES (null,$idPrestamo,'{$nueva->format('Y-m-d')}',{$jsonSimple[$j]['cuota']},'',0,{$jsonSimple[$j]['saldoReal']},'','', 79);";
		}
	
	}
	
	 */
	 echo true;
}//fin de if modo=3
else{
	//echo "\n";
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
	
	for ($j=0; $j < count($jsonPagos) ; $j++) { 
		if($j>=1){
			$jsonPagos[$j]['seg1']=  round($jsonPagos[$j-1]['sk']*$jsonPagos[$j]['dias']*$tSegDiario,2);
			$jsonPagos[$j]['segDef']=round($sumaSeg/$plazo,2);
			$jsonPagos[$j]['cuotaSinItf'] = $jsonPagos[$j]['amortizacion']+$jsonPagos[$j]['interes']+$jsonPagos[$j]['segDef'];
			$jsonPagos[$j]['conItf']= $jsonPagos[$j]['cuotaSinItf']*$itf;
			$jsonPagos[$j]['totalCuota']=round($jsonPagos[$j]['cuotaSinItf'] +$jsonPagos[$j]['conItf'],2);	
		}
		$fechaDispl= strtotime($jsonPagos[$j]['fPago']); $fPagar= date('Y-m-d',$fechaDispl);

		$sqlCuotas=$sqlCuotas."INSERT INTO `prestamo_cuotas`(`idCuota`, `idPrestamo`, `cuotFechaPago`, `cuotCuota`, `cuotFechaCancelacion`, `cuotPago`, `cuotAmortizacion`, `cuotInteres`, `cuotSeg`, `cuotItf`, `cuotTotal`, `cuotObservaciones`,`idTipoPrestamo`) VALUES (null,$idPrestamo,'{$fPagar}',{$jsonPagos[$j]['sk']},'',0,{$jsonPagos[$j]['amortizacion']},{$jsonPagos[$j]['interes']},{$jsonPagos[$j]['segDef']},{$jsonPagos[$j]['conItf']},{$jsonPagos[$j]['totalCuota']},'',79);";

	}
	
	//echo $sqlCuotas;
	$cadena->multi_query($sqlCuotas);
}

function esFeriado($feriados, $dia){
	foreach ($feriados as $llave => $valor) {
		if($valor["ferFecha"]==$dia){
			return $valor["ferDescripcion"]; break;
		}
	}
	return false;
}
sleep ( 1 );
$idEncrip = '000000'.$idPrestamo;
$idEncrip = substr($idEncrip, -7);
//echo $base58->encode($idEncrip);

?>