<?php 
//header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
include 'verificarMatrimonio.php';
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();

$log = mysqli_query($conection,"call buscarCliente('".$_GET['buscar']."');");
$cantRow= mysqli_num_rows($log);

$botonMatri='';
if($cantRow>0){
  while($row = mysqli_fetch_array($log, MYSQLI_ASSOC)){
    $respuesta= json_encode(verificarMatri($row['idCliente'], $cadena), true);
    
    if($respuesta==0 && $row['idEstadoCivil']==2 ){
      if($row['idEstadoCivil']=='2' && $row['cliSexo']=='1' ){
        $botonMatri='<button class="btn btn-sm btn-rojoFresa btn-outline btnLlamarEsposo" data-id="'.$row['idCliente'].'" data-sex="'.$row['cliSexo'].'"><i class="icofont-heart-alt"></i> Asociar esposa</button>';
      }
      if($row['idEstadoCivil']=='2' && $row['cliSexo']=='0' ){
        $botonMatri='<button class="btn btn-sm btn-rojoFresa btn-outline btnLlamarEsposo" data-id="'.$row['idCliente'].'" data-sex="'.$row['cliSexo'].'"><i class="icofont-heart-alt"></i> Asociar esposo</button>';
      }
    }else{
      $botonMatri='<a class="btn btn-sm btn-azul btn-outline btnAsignarSocio" href="creditos.php?titular='.$row["idCliente"].'"><i class="icofont-ui-add"></i> Crear solicitud</a>';
    }
  
  ?>
    <tr>
      <td><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']);?>">CL-<?= $row['idCliente']; ?></a></td>
      <td><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']);?>"><?= $row['cliDni']; ?></a></td>
      <td><a href="clientes.php?idCliente=<?= $base58->encode($row['idCliente']);?>"><?= ucwords($row['cliApellidoPaterno']).' '.ucwords($row['cliApellidoMaterno']).', '. ucwords($row['cliNombres']); ?></a></td>
      <td><?= ucwords($row['addrDireccion']); ?></td>
      <td><?= $row['cliCelularPersonal']; ?></td>
      <td><?= $row['civDescripcion']; ?></td>
      <td><?php if($row['judicializado']): ?> <span class="text-danger">Si</span> <?php else: ?> <span class="text-primary">No</span> <?php endif; ?></td>
      <td> <?php echo $botonMatri;?> </td>
    </tr>
  <?php
    
  }
}else{
	echo '<tr>
    <td>No hay resultados para: <strong>'.$_GET['buscar'].'</strong></td>
  </tr>';
}
/* liberar la serie de resultados */
mysqli_free_result($log);
/* cerrar la conexi贸n */
mysqli_close($conection);

?>