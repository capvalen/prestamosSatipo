<?php 
require("conkarl.php");
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();

$sql = mysqli_query($conection,"SELECT ca.*, tp.tipoDescripcion, u.usuNombres FROM `caja` ca
inner join tipoproceso tp on tp.idTipoProceso = ca.idTipoProceso
inner join usuario u on u.idUsuario = ca.idUsuario
where idPrestamo= {$base58->decode($_POST['credito'])} and ca.idTipoProceso in (81, 86) and cajaActivo=1;");
$filas=$sql->num_rows;

if($filas > 0){
$k=1;
?>
<div class="row">
  <p class="purple-text text-lighten-1"><strong>Otros procesos</strong></p>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>N°</th>
        <th>Proceso</th>
        <th>Monto</th>
        <th>Observaciones</th>
        <th>Responsable</th>
      </tr>
    </thead>
    <tbody>
<?php

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{?>
<tr>
  <td><?= $k;?></td>
  <td><?= $row['tipoDescripcion'];?></td>
  <td>S/ <?= number_format($row['cajaValor'],2);?></td>
  <td><?= $row['cajaObservacion'];?></td>
  <td><?= $row['usuNombres'];?></td>
</tr>
<?php 
$k++;
}
mysqli_close($conection); //desconectamos la base de datos
?>
  </tbody>
</table>
</div>
<?php

}

?>