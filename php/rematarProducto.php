<?php
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$idPrestamo = $base58->decode($_POST['credito']);
$sql="INSERT INTO `caja`(`idPrestamo`, `idCuota`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES ( '{$idPrestamo}', 0, 20, now(), {$_POST['valor']}, '', 1, 1, {$_COOKIE['ckidUsuario']}, 0);
UPDATE `prestamo` SET `rematado` = '1', presActivo=2, fechaFinPrestamo=now(), precioRemate= {$_POST['valor']} WHERE `idPrestamo` = '{$idPrestamo}';";
//echo $sql;

if($prisionero->multi_query($sql)){
	echo 'ok';
}else{
	echo 'error';
}

?>