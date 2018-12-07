<?php 
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$nomEmpresa = $_COOKIE['cknombreEmpresa'];
$idPresPost = $base58->decode($_GET['prestamo']);

$sql = "SELECT * FROM `prestamo`
WHERE idPrestamo = {$idPresPost}";

if($llamado= $conection->query($sql)){
  $respuesta = $llamado->fetch_assoc();
}



?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Impresion de Cronograma Pagos</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<style>
  h5{font-weight: 700;}
</style> 
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-3"><img src="./../images/empresa.PNG" class="img-responsive"> </div>
      <div class="col-xs-8 text-center">
        <strong><h5><?= $nomEmpresa; ?></h5></strong>
        <strong><h5>Cronograma de pagos</h5></strong>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-7">
        <p><strong>Cliente:</strong> <span>Carlos Alex Pariona Valencia</span></p>
        <p><strong>DNI:</strong> <span>44475064</span></p>
        <p><strong>N° Crédito:</strong> <span>CR-<?= $idPresPost; ?></span></p>
        <p><strong>Dirección de negocio:</strong> <span>Jr. Ciro Alegría 141 Dtpo 501 - Huancayo</span></p>
      </div>
      <div class="col-xs-5">
        <p><strong>Oficina:</strong> <span><?= $_COOKIE['cksucursalEmpresa'] ?></span></p>
        <p><strong>Asesor:</strong> <span><?= $_COOKIE['ckAtiende'] ?></span></p>
        <p><strong>Periodo:</strong> <span>-</span></p>
        <p><strong>F. Desembolso:</strong> <span>18/10/2018</span></p>
      </div>
    </div>
    <div class="row">
      <table class="table">
       
          <?php 
           switch ($respuesta['idTipoPrestamo']) {
            case '1':
            case '2':
            case '4':
            ?>
            <thead><tr><th>N°</th> <th>F. Pago</th> <th>Cuota</th> <th>Cancelación</th> <th>Pago</th> <th>Saldo</th> <th>Vo</th> </tr></thead>
            <tbody> <?php
            $i=0;
            $sql2 = "SELECT * FROM `prestamo_cuotas` WHERE `idPrestamo` = {$idPresPost}";
            if($llamado2 = $cadena->query($sql2)){
              $totalRows = $llamado2->num_rows;
              while($respuesta2 = $llamado2->fetch_assoc()){
                ?>
                <tr>
                  <td><?= $i; ?></td>
                  <td><?php $fecha = new DateTime($respuesta2['cuotFechaPago']); echo $fecha->format('d/m/Y');?></td>
                  <td><?= number_format(round($respuesta2['cuotCuota'], 1, PHP_ROUND_HALF_UP),2);?></td>
                  <td></td>
                  <td></td>
                  <td><?php if($totalRows==($i+1)){ echo '0.00';} else { echo number_format(round($respuesta2['cuotSaldo'], 1, PHP_ROUND_HALF_UP),2);}?></td>
                  <td></td>
                </tr>
                <?php
                $i++;
              }
            }
            ?> </tbody> <?php
            
            
              break;
            case '3':
            $i=0;
            $sql2 = "SELECT * FROM `prestamo_cuotas` WHERE `idPrestamo` = {$idPresPost}";
              ?> 
              <thead><tr><th>N°</th> <th>F. Pago</th> <th>Saldo de capital</th> <th>Amotización</th> <th>Interés</th> <th>SEG</th> <th>ITF</th> <th>Total Cuota</th> </tr></thead>
              <tbody>
              <?php 
              if($llamado2 = $cadena->query($sql2)){
              $totalRows = $llamado2->num_rows;
              while($respuesta2 = $llamado2->fetch_assoc()){ ?>
                <tr>
                  <td><?= $i; ?></td>
                  <td><?php $fecha = new DateTime($respuesta2['cuotFechaPago']); echo $fecha->format('d/m/Y');?></td>
                  <td><?= number_format(round($respuesta2['cuotCuota'], 1, PHP_ROUND_HALF_UP),2);?></td>
                  <td><?= number_format(round($respuesta2['cuotAmortizacion'], 1, PHP_ROUND_HALF_UP),2);?></td>
                  <td><?= number_format(round($respuesta2['cuotInteres'], 1, PHP_ROUND_HALF_UP),2);?></td>
                  <td><?= number_format(round($respuesta2['cuotSeg'], 1, PHP_ROUND_HALF_UP),2);?></td>
                  <td><?= number_format(round($respuesta2['cuotItf'], 1, PHP_ROUND_HALF_UP),2);?></td>
                  <td><?= number_format(round($respuesta2['cuotTotal'], 1, PHP_ROUND_HALF_UP),2);?></td>
                </tr>
              <?php 
                $i++;
                }
              } ?>
              </tbody> <?php
            default:
              # code...
              break;
          }
          ?>
        
      </table>
    </div>
  </div>



<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
window.print();	//Activa la impresion apenas cargo todo
});
/*Determina si se imprimio o se cancelo, para cerrar la pesataña activa*/
(function () {
	var afterPrint = function () {
	window.top.close();
	};
	if (window.matchMedia) {
		var mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function (mql) {
				//alert($(mediaQueryList).html());
				if (mql.matches) {
				} else { afterPrint(); }
		});
	}
	window.onafterprint = afterPrint;
}());
</script>
</body>
</html>