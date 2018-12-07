<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipocliente` order by tipcDescripcion asc");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="mayuscula" value="'.$row['idTipoCliente'].'">'.$row['tipcDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>