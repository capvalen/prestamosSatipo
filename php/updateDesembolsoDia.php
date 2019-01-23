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
		$intervalo = new DateInterval('P1M'); //aumenta 30 día
		break;
	default:
		break;
}

if( $modo == '3'){
	//Saber la primera fecha de pago
	$sqlPrimera="SELECT cuotFechaPago FROM `prestamo_cuotas` where idPrestamo = {$idPrestamo} and cuotCuota<>0 order by cuotFechaPago asc limit 1";
	$resultadoPrimera=$prisionero->query($sqlPrimera);
	$rowPrimera=$resultadoPrimera->fetch_assoc();
	
	$fechaAnterior = new DateTime($rowPrimera['cuotFechaPago']);
	if( $fecha->diff($fechaAnterior)->format('%R%a')>0){
		$fecha = $fechaAnterior;
	}else{
		$fecha = new DateTime($fechaAnterior->add(new DateInterval('P1M'))->format('Y-m').'-01');
	}
	
}


$sqlCuotasFechas= "";

	$interesSumado=0;
	
	$j=0;
	for ($i=0; $i <= $plazo ; $i++) { //echo "entra ".$fecha->format('Y-m-d')."   ";
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
			// 	echo "Sábado ".": ". $fecha->format('d/m/Y'). "<br>";  ---------SI SE CUENTAN SABADOS EN ESTE SISTEMA---------
			}else{
				//echo "Día #".($i+1).": ". $fecha->format('d/m/Y') . "<br>";
				if($j==0){ $autoFechas[$j]['cuotFechaPago']= date('Y-m-d'); }
				else{ $autoFechas[$j]['cuotFechaPago']= $fecha->format('Y-m-d');}
				//echo "sale ".$autoFechas[$j]['cuotFechaPago']."\n";
				//--------------  HACER EL UPDATEEEEEEEEEEE --------------------- 
				$sqlCuotasFechas=$sqlCuotasFechas."UPDATE `prestamo_cuotas` SET `cuotFechaPago`='".$autoFechas[$j]['cuotFechaPago']."' WHERE `idCuota`=".$autoFechas[$j]['idCuota'].";";
				$j++;
				
				
				if($i<>0){$fecha->add($intervalo);}
				//echo $sql;
				//unset($conection);
			}
		}
	}// fin de for

	//echo $sqlCuotasFechas;
	$cadena->multi_query($sqlCuotasFechas);

	
	echo true;



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