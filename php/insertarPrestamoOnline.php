<?php
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$pagoTotal = $_POST['monto']*(1+$_POST['tasaInt']/100);
$sql="INSERT INTO `prestamo`(`idPrestamo`, `presFechaAutom`, `presFechaDesembolso`, `presPeriodo`, `preInteresPers`,`presMontoDesembolso`, `idTipoPrestamo`, `presActivo`, `idUsuario`, `preSaldoDebe`) VALUES (null, now(), {$_POST['fDesembolso']}, {$_POST['periodo']}, {$_POST['tasaInt']},{$_POST['monto']}, {$_POST['modo']}, 1, {$_COOKIE['ckidUsuario']}, {$pagoTotal});";

if($conection->query($sql)){
	//$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
	$idPrestamo = $conection->insert_id;
	
}else{
	echo "hubo un error";
}


$clientes=$_POST['clientes'];
$sqlClie='';
foreach ($clientes as $cliente ) {
	$sqlClie=$sqlClie . "INSERT INTO `involucrados`(`idPrestamo`, `idCliente`, `idTipoCliente`)
	VALUES ($idPrestamo, {$cliente['id']}, {$cliente['grado']});";
}
$esclavo->multi_query($sqlClie);



$fecha = new DateTime($_POST['fDesembolso']);

$feriados = include "feriadosProximos.php";
$monto = $_POST['monto'];
$plazo = $_POST['periodo'];
$saldo = $_POST['monto'];
$saltoDia = new DateInterval('P1D'); //aumenta 1 día
$interes = 1+$_POST['tasaInt']/100;
$sqlCuotas='';

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
		$intervalo = new DateInterval('P1D'); //aumenta 1 día
		break;
	case "2": //SEMANAL
		$intervalo = new DateInterval('P1W'); //aumenta 1 día
		break;
	case "4": //QUINCENAL
		$intervalo = new DateInterval('P15D'); //aumenta 1 día
		break;
	case "3": //MENSUAL
		$intervalo = new DateInterval('P1M'); //aumenta 1 día
		break;
	default:
	break;
}
$cuota = round(($monto*$interes)/$plazo,1, PHP_ROUND_HALF_UP);


$interesSumado=0;
if( $_POST['modo']=='3'){
	$fecha = new DateTime($_POST['primerPago']);
}else{
	$fecha->add($intervalo);
}
//$cuota = round($monto*$interes/$plazo,2);
for ($i=0; $i < $plazo ; $i++) {

	
	$razon = esFeriado($feriados, $fecha->format('Y-m-d'));
	if($razon!=false ){
		//echo "si es feriado";
		//echo "Feriado ".": ". $fecha->format('d/m/Y'). "<br>";
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
			//echo "Día #".($i+1).": ". $fecha->format('d/m/Y') . "<br>";
			$fecha->add($intervalo);
			//echo $sql;
			
			//unset($conection);
			
		
		}
	}

}
//echo $sqlCuotas;



$jsonSimple[0]['saldoReal'] = round($monto * $interes, 1, PHP_ROUND_HALF_UP);
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

$cadena->multi_query($sqlCuotas);


function esFeriado($feriados, $dia){
	foreach ($feriados as $llave => $valor) {
		if($valor["ferFecha"]==$dia){
			return $valor["ferDescripcion"]; break;
		}
	}
	return false;
}

$idEncrip = '000000'.$idPrestamo;
$idEncrip = substr($idEncrip, -7);
echo $base58->encode($idEncrip);

?>