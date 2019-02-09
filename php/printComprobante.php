<?php 
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

$nomEmpresa = $_COOKIE['cknombreEmpresa'];
//$idPresPost = $base58->decode($_GET['prestamo']);


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
  .mayuscula{text-transform: capitalize;}

</style> 
  <div class="container-fluid">
  <div class="col-xs-6">
    <div class="row ">
      <div class="col-xs-10 text-center">
      <center>
        <img src="./../images/empresa.png" class="img-responsive">
      </center>
      </div>
      <div class="col-xs-12 text-center">
        <strong><h4><?= $_GET['tipo'];?></h4></strong>
        <hr>
      </div>
    </div>
    <div class="row ">
      <p><strong>Monto:</strong> S/ <?= $_GET['monto']; ?></p>
      <p><strong>Codigo:</strong> <? if($_GET['codigo']==''){ echo '-'; }else{echo $_GET['codigo'];} ?></p>
      <p><strong>Moneda:</strong> <?= $_GET['moneda']; ?></p>
      <? if($_GET['obs']<>''){ echo "<p class='mayuscula'><strong>Observaciones:</strong> {$_GET['obs']} </p>";}?>
      <hr>
      <p><strong>Fecha de emisión:</strong> <?= $_GET['fecha']; ?></p>
      <p><strong>Clave de validación:</strong> <?= $base58->encode($_GET['codigo']); ?></p>
      <p><strong>Atendido por:</strong> <span class="mayuscula"><?= $_COOKIE['cknomCompleto']; ?></span></p>
      <hr style="margin-top=5px; margin-bottom=5px; padding-top:0; padding-bottom:0;">
      <p><small>Jr. Augusto B. Leguía N° 569 - Satipo</small></p>
      <p><small>Cel: 966237843 - 934042974 - 9346962202</small></p>
    </div>
  </div>
    
  

   

  </div> <!-- Fin de container-fluid  -->



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