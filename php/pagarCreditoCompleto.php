<?php
require("conkarl.php");

$sql= "CALL `pagarCreditoCompleto`({$_POST['idCred']}, {$_COOKIE['ckPower']});";
//echo $sql;

if ($conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	echo true;
}else{echo false;}

?>