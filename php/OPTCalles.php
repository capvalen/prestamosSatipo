<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `calles`  
WHERE calActivo=1
ORDER BY `calles`.`calDescripcion` ASC");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="mayuscula" value="'.$row['idCalle'].'">'.$row['calDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>