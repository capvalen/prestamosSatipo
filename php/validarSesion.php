<?php 
// ini_set("session.cookie_lifetime","7200");
// ini_set("session.gc_maxlifetime","7200");
session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
$clavePrivada= 'Es sencillo hacer que las cosas sean complicadas, pero difícil hacer que sean sencillas. Friedrich Nietzsche';

$fila = array(); $i=0;
//echo "select * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '".$_POST['user']."' and usuPass='".md5($_POST['pws'])."' and usuActivo=1;";

$log = mysqli_query($conection,"select * from  usuario u inner join sucursal s on s.idSucursal=u.idSucursal where usuNick = '".$_POST['user']."' and usuPass='".md5($_POST['pws'])."' and usuActivo=1;");
$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idUsuario']>=1){
	
	$expira=time()+60*60*24;
	setcookie('ckidSucursal', $row['idSucursal'], $expira, '/');
	setcookie('ckSucursal', $row['sucLugar'], $expira, '/');
	setcookie('ckAtiende', $row['usuNombres'], $expira, '/');
	setcookie('cknomCompleto', $row['usuNombres'].', '.$row['usuApellido'], $expira, '/');
	setcookie('ckPower', $row['usuPoder'], $expira, '/');
	setcookie('ckidUsuario', $row['idUsuario'], $expira, '/');
	
	$sqlConf = mysqli_query( $conection,  "SELECT * FROM `datosgenerales`");
	while($rowConf = mysqli_fetch_array($sqlConf,MYSQLI_ASSOC)){
		$fila[$i] = $rowConf;
		$i++;
	}

	setcookie('cknombreEmpresa', $fila[0]['datoValor'], $expira, '/');
	setcookie('ckrucEmpresa', $fila[1]['datoValor'], $expira, '/');
	setcookie('ckdireccionEmpresa', $fila[2]['datoValor'], $expira, '/');
	setcookie('cktelefonoEmpresa', $fila[3]['datoValor'], $expira, '/');
	setcookie('cksucursalEmpresa', $fila[4]['datoValor'], $expira, '/');

	echo $row['idUsuario'];
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

?>