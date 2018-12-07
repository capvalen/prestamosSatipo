<?php 
require 'variablesGlobales.php';
require("conkarl.php");
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$k=0;
$diasMora=0;
$dinero= $_POST['dinero'];
$idPrestamo = $base58->decode($_POST['credito']);
$sql= "SELECT * FROM prestamo_cuotas
where cuotFechaPago <=curdate() and idPrestamo = {$idPrestamo} and idTipoPrestamo in (33, 79)
order by cuotFechaPago asc;";

$resultado=$esclavo->query($sql);
$fechaHoy = new DateTime();

while($row=$resultado->fetch_assoc()){
	$fechaCuota = new DateTime($row['cuotFechaPago']);
	$diasDebe=$fechaHoy ->diff($fechaCuota);
	$restaDias= floatval($diasDebe->format('%a'));
	if($restaDias>0){
		//sumar Dia y Mora
		if($k==0){
			$diasMora = $restaDias;
			$primFecha = $fechaCuota->format('d/m/Y');
		}
	}
	$ultFecha = $fechaCuota->format('d/m/Y');
	$k++;
	
}
$resultado->data_seek(0);

if($diasMora>0){ //$diasMora-=1;
	$moraTotal = $diasMora*$mora;
	/* HACER INSERT a CAJA por MORA por X días*/
	
	$sqlMora="INSERT INTO `caja`(`idCaja`, `idPrestamo`, `idCuota`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`)
	VALUES (null,{$idPrestamo},0,81,now(),{$moraTotal},'Mora por {$diasMora} días por el periodo {$primFecha} y {$ultFecha}',1,1,{$_COOKIE['ckidUsuario']});";

//echo "mora pagada ".$moraTotal."\n";
$dinero -= $moraTotal;
}
$resultadoMora=$esclavo->query($sqlMora);

$sentenciaLarga ='';
while($row2=$resultado->fetch_assoc()){
	$debePendiente = $row2['cuotCuota']-$row2['cuotPago'];
	if($dinero >= $debePendiente){
		//echo 'Pagar el id: '.$row2['idCuota']." con total ".$debePendiente."\n";

		$sentenciaLarga = $sentenciaLarga. "UPDATE `prestamo_cuotas` SET 
			`cuotFechaCancelacion`= now(),
			`cuotPago` = `cuotPago`+ {$debePendiente},
			`idTipoPrestamo`=80
			WHERE `idCuota` = {$row2['idCuota']};
			INSERT INTO `caja`(`idCaja`, `idPrestamo`, `idCuota`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`)
			VALUES (null,{$idPrestamo},{$row2['idCuota']},80,now(),{$debePendiente},'',1,1,{$_COOKIE['ckidUsuario']});";
	}
	else{
		if( $dinero <= 0){
			break;
		}else{
			//echo 'Pagar un pedazo en id: '.$row2['idCuota']." solo adelanto ".$dinero."\n";
			$sentenciaLarga = $sentenciaLarga. "UPDATE `prestamo_cuotas` SET 
			`cuotFechaCancelacion`= now(),
			`cuotPago` = `cuotPago`+ {$dinero},
			`idTipoPrestamo`=33
			WHERE `idCuota` = {$row2['idCuota']};
			INSERT INTO `caja`(`idCaja`, `idPrestamo`, `idCuota`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`)
			VALUES (null,{$idPrestamo},{$row2['idCuota']},33,now(),{$dinero},'',1,1,{$_COOKIE['ckidUsuario']})";
		}
	}
	$dinero= round($dinero - $debePendiente,2);
}

$respuLargo=$prisionero->multi_query($sentenciaLarga);
if($respuLargo){
	echo true;
}

?>