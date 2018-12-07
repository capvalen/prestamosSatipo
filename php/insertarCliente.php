<?php 
require("conkarl.php");
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');


$sql= "call insertarDireccion('{$_POST['direccion']}',{$_POST['zona']}, '{$_POST['referencia']}', '{$_POST['numero']}', {$_POST['departam']}, {$_POST['provinc']}, {$_POST['distrit']}, 0, {$_POST['calle']} )";

$consultaDepos = $conection->prepare($sql);
$consultaDepos ->execute();
$resultadoDepos = $consultaDepos->get_result();
//$numLineaDeposs=$resultadoDepos->num_rows;
$rowDepos = $resultadoDepos->fetch_array(MYSQLI_NUM);
$idCasa= $rowDepos[0];

$consultaDepos->fetch();
$consultaDepos->close();

if($_POST['casa']==1){

  $sql= "call insertarDireccion('{$_POST['direccionNeg']}',{$_POST['zonaNeg']}, '{$_POST['referenciaNeg']}', '{$_POST['numeroNeg']}', {$_POST['departamNeg']}, {$_POST['provincNeg']}, {$_POST['distritNeg']}, 1, {$_POST['calleNeg']} )";

  $consultaDepos = $conection->prepare($sql);
  $consultaDepos ->execute();
  $resultadoDepos = $consultaDepos->get_result();
  //$numLineaDeposs=$resultadoDepos->num_rows;
  $rowDepos = $resultadoDepos->fetch_array(MYSQLI_NUM);
  $idNego= $rowDepos[0];

  $consultaDepos->fetch();
  $consultaDepos->close();

}else{ $idNego= $idCasa; }

if($idNego==$idCasa){ $igual =1;}else{ $igual =0;}


$sql= "call insertarCliente('{$_POST['dni']}','{$_POST['nombres']}', '{$_POST['paterno']}', '{$_POST['materno']}', {$igual}, {$_POST['hijos']}, {$_POST['sexo']}, {$idCasa}, {$idNego}, '{$_POST['celularPers']}', '{$_POST['celularRef']}', {$_POST['civil']} )";

$consultaDepos = $conection->prepare($sql);
$consultaDepos ->execute();
$resultadoDepos = $consultaDepos->get_result();
//$numLineaDeposs=$resultadoDepos->num_rows;
$rowDepos = $resultadoDepos->fetch_array(MYSQLI_NUM);
$idCliente= $rowDepos[0];

$consultaDepos->fetch();
$consultaDepos->close();


if($_POST['civil']=='2'):
  if( $_POST['sexo']=='1' ){
    $_POST['idVaron']= $idCliente;
    $_POST['idDama'] = $_POST['pareja'];
  }else{
    $_POST['idVaron']= $_POST['pareja'];
    $_POST['idDama'] = $idCliente;
  }
  include 'insertarMatrimonio.php';
endif;

echo $idCliente;
?>