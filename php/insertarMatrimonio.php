<?php 
session_start();
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");


$sql= "INSERT INTO `matrimonio`(`idMatrimonio`, `idEsposo`, `idEsposa`, `matrActivo`) VALUES (null, {$_POST['idVaron']},{$_POST['idDama']},1);";
//echo $sql;

if ($llamadoSQL = $esclavo->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
  $last_id = $esclavo->insert_id;

  $sqlEsp="UPDATE `cliente` SET 
  `idEstadoCivil`=2
  WHERE `idCliente` in ({$_POST['idVaron']}, {$_POST['idDama']});";
  $resultadoEsp=$cadena->query($sqlEsp);
	
  return $last_id;
	/* liberar el conjunto de resultados */
}else{return '0';}



?>