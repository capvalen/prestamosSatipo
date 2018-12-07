<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipoprestamo` ORDER BY `tipoprestamo`.`tpreDescipcion` ASC");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optZona mayuscula" data-tokens="'.$row['idTipoPrestamo'].'" value="'.$row['idTipoPrestamo'].'">'.$row['tpreDescipcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>