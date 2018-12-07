<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `estadocivil` ORDER BY `estadocivil`.`civDescripcion` ASC;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optCivil mayuscula" data-tokens="'.$row['idEstadoCivil'].'" value="'.$row['idEstadoCivil'].'">'.$row['civDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>