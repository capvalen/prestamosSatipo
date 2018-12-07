<?php 
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'php/conkarl.php';
include "php/variablesGlobales.php";
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();
?>
<!DOCTYPE html>
<html lang="es">

<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Principal - Sistema Préstamos</title>

		<!-- Bootstrap Core CSS -->
		<?php include 'headers.php'; ?>
</head>

<body>

<style>

</style>
<div id="wrapper">
	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	<!-- /#sidebar-wrapper -->
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido principal -->
			<h3 class="purple-text text-lighten-1">Verificación de operaciones </h3><hr>

			<div class="panel panel-default">
				<div class="panel-body">
					<a href="verificacion.php?pendientes" class="btn btn-lg btn-azul btn-outline"><i class="icofont-black-board"></i> Solicitudes pendientes </a>
					<a href="verificacion.php?solicitudes" class="btn btn-lg btn-morado btn-outline"><i class="icofont-certificate-alt-2"></i> Mis solicitudes aprobadas </a>
				</div>
			</div>

			<?php if (isset($_GET['pendientes'])): ?>

			<p>Solicitudes pendientes de aprobación</p>
			
			<table class='table table-hover'>
			<thead>
				<tr>
					<th>Cod. préstamo</th>
					<th>Fecha solicitud</th>
					<th>Titular</th>
					<th>Monto</th>
					<th>Periodo</th>
					<th>Analista</th>
				</tr>
			</thead>
			<?php 
		

			$sql = "SELECT pre.idPrestamo, presFechaAutom, presMontoDesembolso, tpr.tpreDescipcion,
			u.usuNombres, lower (concat(c.cliApellidoPaterno, ' ', c.cliApellidoMaterno, ', ', c.cliNombres )) as `titular`
			FROM `prestamo` pre
			inner join usuario u on u.idUsuario = pre.idUsuario
			inner join tipoprestamo tpr on tpr.idTipoPrestamo = pre.idTipoPrestamo
			inner join involucrados i on i.idPrestamo = pre.idPrestamo
			inner join cliente c on c.idCliente = i.idCliente
			where presAprobado=0 and presActivo=1 and i.idTipoCliente=1 order by pre.idPrestamo asc";
			if( $resultado = $conection->query($sql) ){
				$count=$resultado->num_rows; //Cuenta desde el primero
				if($count>=1){
					while( $row= $resultado->fetch_assoc()  ){
						?>
						<tr>
							<td><a href="creditos.php?credito=<?= $base58->encode($row['idPrestamo']); ?>">CR-<?= $row['idPrestamo']; ?></a></td>
							<td><?php $fechaN = new DateTime($row['presFechaAutom']); echo $fechaN->format('j/m/Y H:m a'); ?></td>
							<td class="mayuscula"><?= $row['titular']; ?></td>
							<td><?= str_replace(',', '', number_format($row['presMontoDesembolso'],2)); ?></td>
							<td><?= $row['tpreDescipcion']; ?></td>
							<td><?= $row['usuNombres']; ?></td>
						</tr>
						<?php
					}
				}else{
					?>
					<tr>
						<td>No hay campos que verificar</td>
					</tr>
					<?php
				}
			}
			?>
			</table>
			<?php endif; //fin de if pendientes
			if (isset($_GET['solicitudes'])): ?>
			<table class="table">
				<thead>
					<tr>
						<th>N° Crédito</th>
						<th>Estado</th>
						<th>Solicitante</th>
						<th>Desembolso</th>
						<th>Monto</th>
					</tr>
				</thead>
				<tbody>
			<?php
				$sqlMis="SELECT pre.idPrestamo, presMontoDesembolso, presFechaAutom,
				case presFechaDesembolso when '0000-00-00 00:00:00' then 'Desembolso pendiente' else presFechaDesembolso end as `presFechaDesembolso`,
				concat (c.cliApellidoPaterno, ' ', c.cliApellidoMaterno, ', ', c.cliNombres) as cliNombres, i.idCliente,
				case presAprobado when 0 then 'Sin aprobar' when 2 then 'Rechazado' else 'Aprobado' end as `presAprobado`
				FROM `prestamo` pre
				inner join involucrados i on i.idPrestamo= pre.idPrestamo
				inner join cliente c on c.idCliente = i.idCliente
				where `idUsuario` = {$_COOKIE['ckidUsuario']} and i.idTipoCliente=1
				order by idPrestamo desc";
				$resultadoMis=$cadena->query($sqlMis);
				while($rowMis=$resultadoMis->fetch_assoc()){ ?>
					<tr>
						<td><a href="creditos.php?credito=<?= $base58->encode($rowMis['idPrestamo']);?>">CR-<?= $rowMis['idPrestamo']; ?></a></td>
						<td><?= $rowMis['presAprobado']; ?></td>
						<td><?= $rowMis['cliNombres']; ?></td>
						<td><?= $rowMis['presFechaDesembolso']; ?></td>
						<td><?= $rowMis['presMontoDesembolso']; ?></td>
					</tr>
			
			<?php } endif; //fin de if solicitudes ?>
				</tbody>
			</table>
				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$(document).ready(function(){
	
});

</script>
<?php } ?>
</body>

</html>