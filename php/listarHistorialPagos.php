<?php
require("conkarl.php");
date_default_timezone_set('America/Lima');

$sql="SELECT pre.idPrestamo, presMontoDesembolso, pc.cuotCuota, presFechaDesembolso, fechaFinPrestamo,  tpe.tpreDescipcion, presPeriodo, u.usuNick
FROM `prestamo` pre
inner join prestamo_cuotas pc on pc.idPrestamo = pre.idPrestamo
inner join tipoprestamo tpe on tpe.idTipoPrestamo = pre.idTipoPrestamo
inner join involucrados i on i.idPrestamo = pc.idPrestamo 
inner join usuario u on u.idUsuario = pre.idUsuario
where i.idCliente={$base58->decode($_GET['idCliente'])}  and cuotCuota<>0
group by pre.idPrestamo
order by cuotfechaPago desc;";


$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){ ?>
  <tr>
    <td>Huancayo</td>
    <td><a href="creditos.php?credito=<?= $base58->encode($row['idPrestamo']);?>"><?= $row['idPrestamo']; ?></a></td>
    <td><?= number_format($row['presMontoDesembolso'],2); ?></td>
    <td><?= number_format($row['cuotCuota'],2); ?></td>
    <td>k</td>
    <td><?php if($row['presFechaDesembolso'] <>'0000-00-00 00:00:00'){ $fechaJ= new DateTime( $row['presFechaDesembolso']); echo $fechaJ->format('d/m/Y'); }else{echo 'Pendiente';}?></td>
    <td><?php if(is_null($row['fechaFinPrestamo'])){ echo 'Vigente';}else{echo $row['fechaFinPrestamo'];} ?></td>
    <td><?= $row['tpreDescipcion']."-".$row['presPeriodo']; ?></td>

    <?php 
    $idPres= $row['idPrestamo'];
    $sqlCuot="SELECT i.idCliente, cuotFechaPago, cuotFechaCancelacion FROM `prestamo_cuotas` pc
    inner join involucrados i on i.idPrestamo = pc.idPrestamo 
    where i.idTipoCliente=1 and pc.idPrestamo = {$idPres} and cuotCuota>0";

    $fechaHoy= new DateTime(date("Y-m-d"));
    $i=1;
    $resultadoCuot=$esclavo->query($sqlCuot);
    while($rowCuot=$resultadoCuot->fetch_assoc()){ 
      if($i<=15){ $i++; }else{
        if($i==16){ echo '<tr><td colspan=8>'; }
        $i=2;
      }
      if($rowCuot['cuotFechaCancelacion']=='0000-00-00'):
        $fechaCuota= new DateTime($rowCuot['cuotFechaPago']);
        $interval = $fechaCuota->diff($fechaHoy);
        echo "<td><span class='tdVigente'>". $interval->format('%r%a')."</span></td>";
      else:
        $fechaCuota= new DateTime($rowCuot['cuotFechaCancelacion']);
        $fechaPago= new DateTime($rowCuot['cuotFechaPago']);
        $interval = $fechaPago->diff($fechaCuota);
        echo "<td><span class=''>". $interval->format('%r%a')."</span></td>";
      endif;
    }
    for ($j=$i; $j <=15 ; $j++) { 
      echo "<td></td>";
      if($i==15){
        echo '</tr>';
      }
    }
    
    ?>

  </tr>
<?php }

?>