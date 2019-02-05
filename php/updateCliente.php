<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

var_dump($_POST['jcCliente']);
// $idCli= $_POST['idCli'];

// $sql= "UPDATE `cliente` SET 
// `cliDni`={$_POST['dni']},`cliNombres`={$_POST['nombres']},`cliApellidoPaterno`={$_POST['apellidoPaterno']},`cliApellidoMaterno`={$_POST['apellidoMaterno']},
// `cliSexo`={$_POST['sexo']},`cliNumHijos`={$_POST['hijos']},
// `cliDireccionesIgual`={$_POST['esCasa']},
// `cliCelularPersonal`={$_POST['celPersonal']},`cliCelularReferencia`={$_POST['celRefencia']},`idEstadoCivil`={$_POST['estadocivil']}
// WHERE `idCliente`= {$idCli};";
// echo $sql;

// if ($conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
// 	/* obtener el array de objetos */
// 	echo true;
// }else{echo false;}


?>