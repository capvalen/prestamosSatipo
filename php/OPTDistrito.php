<?php 
//header('Content-Type: text/html; charset=utf8');
require("conkarl.php");
//echo "SELECT * FROM `distrito` where idProvincia={$_POST['distri']}";
$sql = mysqli_query($conection,"SELECT `idDistrito`, lower(`distrito`) as `distrito`, `idProvincia` FROM `distrito` where idProvincia={$_POST['distri']}");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optDistrito mayuscula" data-tokens="'.$row['idDistrito'].'">'.$row['distrito'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>