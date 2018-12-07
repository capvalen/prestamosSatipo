<?php 
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");


$sql= "UPDATE `prestamo` SET `presAprobado` = 2, `idUsuarioAprobador`={$_COOKIE['ckidUsuario']}, `presObs` = '{$_POST['razon']}' WHERE `idPrestamo` = {$_POST['credit']};";
//echo $sql;

if ($conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	echo true;
}else{echo false;}



?>