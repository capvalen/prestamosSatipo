<?php 
require("conkarl.php");

/* $sql="INSERT INTO `caja`(`idPrestamo`, `idCuota`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES ( '{$_POST['credit']}', 0, 33, now(), {$_POST['interes']}, 'Re-programación', 1, 1, {$_COOKIE['ckidUsuario']}, 0);";
if($conection->query($sql)){
	$sqlPrestamo = "SELECT preInteresPers FROM `prestamo` where idPrestamo='{$_POST['credit']}';";
	if($respPrestamo = $cadena->query($sqlPrestamo)){
		$rowPrestamo = $respPrestamo->fetch_assoc();
		echo $rowPrestamo['preInteresPers'];
	}
} */

$sqlPrestamo = "SELECT * FROM `prestamo` where idPrestamo='{$_POST['credit']}';";
if($respPrestamo = $cadena->query($sqlPrestamo)){
	$rowPrestamo = $respPrestamo->fetch_assoc();
	$modo = $rowPrestamo['idTipoPrestamo'];
	
	switch ($modo){
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
	
	$sqlUpdate = "";
	$fechTemporal = new DateTime($_POST['nuevaFecha']);
	

	$sqlCuotas = "SELECT *  FROM `prestamo_cuotas` WHERE `idPrestamo` = '{$_POST['credit']}'
	and idTipoPrestamo not in (43,80)"; //Listo todo lo que falta
	if($respCuotas = $esclavo->query($sqlCuotas)){
		while($rowCuotas = $respCuotas->fetch_assoc()){
			//$fechTemporal = new DateTime($rowCuotas['cuotFechaPago']);
			$sqlUpdate .= "UPDATE `prestamo_cuotas` SET `cuotFechaPago` = '". $fechTemporal->format('Y-m-d') ."' WHERE `prestamo_cuotas`.`idCuota` = {$rowCuotas['idCuota']};";
			$fechTemporal= $fechTemporal->add($intervalo);
		}
		if($sqlUpdate<>''){
			$respUpdate = $prisionero->multi_query($sqlUpdate);
			echo 'ok';
		}else{
			echo 'nada';
		}
	}

}