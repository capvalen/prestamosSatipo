<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$sql= "call insertarProcesoOmiso (".$_POST['tipo'].", ".$_POST['valor'].", '".$_POST['obs']."', ".$_COOKIE['ckidUsuario'].", ".$_POST['moneda']." )";
//echo $sql;
if ($conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.

	echo true;
}else{echo mysql_error( $conection);}


 ?>