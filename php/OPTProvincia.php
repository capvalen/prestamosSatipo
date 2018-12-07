<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT  `idProvincia`, lower(`provincia`) as `provincia`, `idDepartamento` FROM `provincia` where idDepartamento={$_POST['depa']}");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optProvincia mayuscula" data-tokens="'.$row['idProvincia'].'">'.$row['provincia'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>