<?
require("conkarl.php");

if($_POST['estado']==='true'){ $estado=1;}else{$estado=0;}


$sql="UPDATE `cliente` SET `judicializado` = '{$estado}' WHERE `idCliente` = '{$_POST['idCli']}';";
//echo $sql;

if($prisionero->query($sql)){
	echo 'ok';
}else{
	echo 'error';
}