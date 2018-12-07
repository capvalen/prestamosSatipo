<?php 
//header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$log = mysqli_query($conection,"SELECT `idCliente`, `cliDni`, `cliNombres`, `cliApellidoPaterno`, `cliApellidoMaterno`, `cliDireccionesIgual`, `cliDireccionCasa`, `cliDireccionNegocio`,  `idEstadoCivil`, `cliActivo` FROM `cliente` WHERE 
`cliSexo` = {$_POST['sexoContra']} and `idEstadoCivil` = 1 and `cliActivo`=1
group by cliDni");
$cantRow= mysqli_num_rows($log);


if($cantRow>0){
	while($row = mysqli_fetch_array($log, MYSQLI_ASSOC)){
	?>
	<option class="mayuscula" value="<?= $row['idCliente'];?>"><?= $row['cliDni']." - ". $row['cliApellidoPaterno']." ".$row['cliApellidoMaterno'].', '.$row['cliNombres'];?></option>
	<?php
	}
}else{ ?>
	<option class="mayuscula" value="0">No hay resultados para: <?= $_GET['buscar'];?></option>
<?php }
/* liberar la serie de resultados */
mysqli_free_result($log);
/* cerrar la conexi贸n */
mysqli_close($conection);

?>