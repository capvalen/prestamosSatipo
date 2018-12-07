<?php 
header('Content-Type: text/html; charset=utf8');
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT `idDepartamento`, lower( `departamento`) as `departamento` FROM `departamento` where  `idDepartamento` <>99;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optDepartamento mayuscula" data-tokens="'.$row['idDepartamento'].'">'.$row['departamento'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>