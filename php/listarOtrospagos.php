<?php 
require("conkarl.php");
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();

$sql = mysqli_query($conection,"SELECT ca.*, tp.tipoDescripcion FROM `caja` ca
inner join tipoproceso tp on tp.idTipoProceso = ca.idTipoProceso
where idPrestamo= {$base58->decode($_POST['credito'])} and ca.idTipoProceso=81 and cajaActivo=1;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo "<p> <strong>{$row['tipoDescripcion']}:</strong> <span>S/ ".number_format($row['cajaValor'],2)."</span> {$row['cajaObservacion']} <p>";

}
mysqli_close($conection); //desconectamos la base de datos

?>