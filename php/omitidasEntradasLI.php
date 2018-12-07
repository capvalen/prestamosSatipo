<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipoproceso`
where idTipoProceso in (31,75, 80) order by tipoDescripcion asc");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
echo '<li><a href="#!" class="aLiProcesos" data-id="'.$row['idtipoproceso'].'"><i class="icofont icofont-chart-pie-alt" style="font-size: 13px;"></i> '.$row['tipoDescripcion'].'</a></li>';
}
mysqli_close($conection); //desconectamos la base de datos

?>