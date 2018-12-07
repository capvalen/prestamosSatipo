<?
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$sql="SELECT idCliente, lower(concat( cliApellidoPaterno, ' ' , cliApellidomaterno, ', ', cliNombres)) as cliNombres FROM `cliente`
where cliDni = {$_POST['texto']};";
$resultado=$cadena->query($sql);
$filas = $resultado->num_rows;
if($filas ==1 ){
  $row=$resultado->fetch_assoc();
  echo "<a class='mayuscula' href='clientes.php?idCliente=".$base58->encode($row['idCliente'])."'>".$row['cliNombres']."</a>";
}else{
  echo '0';
}

?>