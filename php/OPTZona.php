<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `zona`  where zonActivo=1 ORDER BY `zona`.`zonTipo` ASC;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optZona mayuscula" data-tokens="'.$row['idZona'].'" value="'.$row['idZona'].'">'.$row['zonTipo'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>