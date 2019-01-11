<?php 
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'php/conkarl.php';
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();
include "php/variablesGlobales.php";
$hayCaja= require_once("php/comprobarCajaHoy.php");
$fechaHoy = new DateTime();
?>

<!DOCTYPE html>
<html lang="es">

<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Créditos - Sistema Préstamos</title>

		<!-- Bootstrap Core CSS -->
		<?php include 'headers.php'; ?>
		<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css?version=1.0.1">
</head>

<body>

<style>
#contenedorCreditosFluid label{font-weight: 500;}
#contenedorCreditosFluid p, #contenedorCreditosFluid table{color: #a35bb4;}
.modal p{color: #333;}
.spanIcono{font-size:16px; margin: 0 5px;}
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
			<div class="panel panel-default hidden">
			<div class="panel-body">
				<div class="row col-sm-6 col-md-3">
					<p><strong>Filtro de Créditos:</strong></p>
					<input type="text" class="form-control" placeholder="CR-00**" id="txtSoloBuscaCreditos">
				</div>
			</div>
		</div>
	<?php if( isset($_GET['credito']) ):
		$codCredito=$base58->decode($_GET['credito']); ?>

		<h3 class="purple-text text-lighten-1" id="h3Codigo" data-id="<?= $codCredito; ?>">Crédito CR-<?= $codCredito; ?></h3>

	<?php

	$sqlCr="SELECT presFechaAutom, presMontoDesembolso, presPeriodo, tpr.tpreDescipcion,
	u.usuNombres, preInteresPers,
	case presFechaDesembolso when '0000-00-00 00:00:00' then 'Desembolso pendiente' else presFechaDesembolso end as `presFechaDesembolso`,
	case presAprobado when 0 then 'Sin aprobar' when 2 then 'Rechazado' else 'Aprobado' end as `presAprobado`, 
	case when ua.usuNombres is Null then '-' else ua.usuNombres end  as `usuarioAprobador`, pre.idTipoPrestamo
	FROM `prestamo` pre
	inner join usuario u on u.idUsuario = pre.idUsuario
	left join usuario ua on ua.idUsuario = pre.idUsuarioAprobador
	inner join tipoprestamo tpr on tpr.idTipoPrestamo = pre.idTipoPrestamo
	where pre.idPrestamo='{$codCredito}'"; ?>
		<!-- <table class="table table-hover">
		<thead>
			<tr>
				<th></th>
			</tr>
		</thead>
		<tbody>
		</tbody> -->
		<?php if( $respuesta = $conection->query($sqlCr)){
			$contadorF = $respuesta->num_rows;
			$rowCr = $respuesta->fetch_assoc();
			
			if($contadorF!=0):
			$_POST['plazos'] = $rowCr['presPeriodo'];
			$_POST['periodo'] = $rowCr['presPeriodo'];
			$_POST['monto']= $rowCr['presMontoDesembolso'];
			$_POST['modo']= $rowCr['idTipoPrestamo'];
			$intBase = $rowCr['presMontoDesembolso']*$rowCr['preInteresPers']/100;
			?>
		<div class="container-fluid" id="contenedorCreditosFluid">
			<p><strong>Datos de crédito</strong></p>
			<div class="row">
				<div class="col-sm-2"><label for="">Verificación</label><p><?= $rowCr['presAprobado']; ?></p></div>
				<div class="col-sm-2"><label for="">Verificador</label><p><?= $rowCr['usuarioAprobador']; ?></p></div>
			</div>
			<div class="row">
				<div class="col-sm-2"><label for="">Fecha préstamo</label><p><?php $fechaAut= new DateTime($rowCr['presFechaAutom']); echo $fechaAut->format('j/m/Y h:m a'); ?></p></div>
				<div class="col-sm-2"><label for="">Fecha desemboslo</label><p><?php if($rowCr['presFechaDesembolso']=='Desembolso pendiente'){echo $rowCr['presFechaDesembolso'];}else{$fechaDes= new DateTime($rowCr['presFechaDesembolso']); echo $fechaDes->format('j/m/Y h:m a');} ?></p></div>
				<div class="col-sm-2"><label for="">Desembolso</label><p>S/ <?= number_format($rowCr['presMontoDesembolso'],2); ?></p></div>
				<div class="col-sm-2"><label for="">Periodo</label><p><?= $rowCr['tpreDescipcion']; ?></p></div>
				<div class="col-sm-2"><label for="">Interés</label><p><?= $rowCr['preInteresPers']."%"; ?></p></div>
				<div class="col-sm-2"><label for="">Analista</label><p><?= $rowCr['usuNombres']; ?></p></div>
			</div>

			<hr>
			
			<p><strong>Clientes asociados a éste préstamo:</strong></p>

			<div class="row">
				<ul>
		<?php $sqlInv= "SELECT i.idPrestamo, lower(concat(c.cliApellidoPaterno, ' ', c.cliApellidoMaterno, ', ', c.cliNombres)) as `datosCliente` , tpc.tipcDescripcion, i.idCliente FROM `involucrados` i
				inner join cliente c on i.idCliente = c.idCliente
				inner join tipocliente tpc on tpc.idTipoCliente = i.idTipoCliente
				where idPrestamo ='{$codCredito}'";
				$k=0;
				if( $respuestaInv=$conection->query($sqlInv) ){
					while( $rowInv=$respuestaInv->fetch_assoc() ){  ?>
						<li class="mayuscula"><a href="clientes.php?idCliente=<?= $base58->encode(substr('000000'.$rowInv['idCliente'], -7));?>"><span id="<? if($k==0){echo 'spanTitular';} ?>" ><?= $rowInv['datosCliente']; ?></span><?= " [".$rowInv['tipcDescripcion']."]"?></a></li>
			<?php $k++; }
				}
			?>
				</ul>
			</div>

			<hr>

			<div class="container row" id="rowBotonesMaestros">
				<button class="btn btn-negro btn-outline btn-lg " id="btnImpresionPrevia" data-pre="<?= $_GET['credito'];?>"><i class="icofont-print"></i> Imprimir cronograma</button>
			<?php if(isset($_GET['credito']) && $rowCr['presAprobado']== 'Sin aprobar'): ?>
				<button class="btn btn-success btn-outline btn-lg" id="btnShowVerificarCredito"><i class="icofont-check-circled"></i> Aprobar crédito</button>
				<button class="btn btn-danger btn-outline btn-lg" id="btnDenyVerificarCredito"><i class="icofont-thumbs-down"></i> Denegar crédito</button>
			<?php endif; ?>

			<?php if(isset($_GET['credito']) && $rowCr['presAprobado']<> 'Sin aprobar' && $rowCr['presAprobado']<> "Rechazado" && in_array($_COOKIE['ckPower'], $soloAdmis)): ?>
			<?php if( $hayCaja==true ):
				if($rowCr['presFechaDesembolso']=='Desembolso pendiente'): ?>
				<button class="btn btn-warning btn-outline btn-lg" id="btnDesembolsar"><i class="icofont-money"></i> Desembolsar</button>
			<?php else:?>
				<button class="btn btn-infocat btn-outline btn-lg" id="btnsolicitarDeuda"><i class="icofont-money"></i> Pago global</button>
			<?php endif; ?>
			<?php else: ?> 
				<div class="col-xs-12 col-md-7"><br>
					<div class="alert alert-morado container-fluid" role="alert">
						<div class="col-xs-4 col-sm-2 col-md-3">
							<img src="images/ghost.png" alt="img-responsive" width="100%">
						</div>
						<div class="col-xs-8">
							<strong>Alerta</strong> <p>No se encuentra ninguna caja aperturada.</p>
							<a class="btn btn-default btn-lg btn-outline pull-left" href="caja.php" style="color:#333"><i class="icofont icofont-rounded-double-right"></i> Ir a caja</a>
						</div>
					</div>
				</div>
			<?php endif; //if de hay caja ?>
			<?php endif; //if de soloadmins  ?>
		
			</div>
			<hr>

			<p><strong>Cuotas planificadas:</strong></p>
			<table class="table table-hover" id="tableSubIds">
				<thead>
				<tr>
					<th>Sub-ID</th>
					<th>Fecha programada</th>

					<th>Capital</th>
					<th>Interés</th>
					<th>Cuota</th>
					<th>Cancelación</th>
					<th>Pago</th>
					<th class="hidden">Saldo</th>
					<th>@</th>
				</tr>
				</thead>
				<tbody>
			<?php 
			$sqlCuot= "SELECT prc.*, pre.preInteresPers, pre.presMontoDesembolso, pre.presPeriodo FROM prestamo_cuotas prc
			inner join prestamo pre on pre.idPrestamo = prc.idPrestamo
			where prc.idPrestamo = {$codCredito}
			order by cuotFechaPago asc";
			if($respCuot = $cadena->query($sqlCuot)){ $k=0;
				$sumCapital = 0; $sumInteres =0; $sumCuota =0;
				while($rowCuot = $respCuot->fetch_assoc()){
					$monto = $rowCuot['presMontoDesembolso'];
					$interes = $rowCuot['preInteresPers'];
					$plazo = $rowCuot['presPeriodo'];
					$capitalPartido = $monto/$plazo;
					$intGanado = $monto*$interes/100/$plazo;
					$cuotaGanado = $capitalPartido + $intGanado;
					
					if($k>=1) {$sumCapital = $sumCapital+$capitalPartido;
					$sumInteres = $sumInteres+$intGanado;
					$sumCuota = $sumCuota+$cuotaGanado;}

					?>
				<tr>
					<td>SP-<?= $rowCuot['idCuota']; ?></td>
					<td><?php $fechaCu= new DateTime($rowCuot['cuotFechaPago']); echo $fechaCu->format('d/m/Y'); ?></td>
					<td><? if($k>=1) {echo number_format($capitalPartido,2);} ?></td>
					<td><? if($k>=1) {echo number_format($intGanado,2);} ?></td>
					<td><? if($k>=1) {echo number_format($cuotaGanado,2);} ?></td>
					<td><?php if($rowCuot['cuotCuota']=='0.00' && $rowCuot['cuotPago']=='0.00'): echo "Desembolso"; elseif($rowCuot['cuotFechaCancelacion']=='0000-00-00'): echo 'Pendiente'; else: echo $rowCuot['cuotFechaCancelacion']; endif;  ?></td>
					<td class="tdPagoCli" data-pago="<?= number_format($rowCuot['cuotPago'],2); ?>"><? if($k>=1) {echo number_format($rowCuot['cuotPago'],2);} ?></td>
					<td class="hidden"><?= number_format($rowCuot['cuotSaldo'],2); ?></td>
					<td><?php if( in_array($_COOKIE['ckPower'], $soloAdmis) &&  $rowCuot['idTipoPrestamo']=='79' && $rowCr['presFechaDesembolso']<>'Desembolso pendiente' && $k>=1):
					$diasDebe2=$fechaHoy ->diff($fechaCu);
						if( floatval($diasDebe2->format('%R%a')) < 0 ){
						?> <p class="red-text text-darken-1">Cuota fuera de fecha</p>
						<!-- <button class="btn btn-primary btn-outline btn-sm btnPagarCuota"><i class="icofont-money"></i> Pagar</button> --> <?php
						}else{
							?> <p class="blue-text text-accent-2">Cuota en buena fecha</p><?php
						}
						endif;
						if($rowCuot['cuotPago']<>'0.00' && $rowCr['presFechaDesembolso']<>'Desembolso pendiente'): 
							if( $rowCuot['idTipoPrestamo'] ==33 ){ ?>
								<span class="mitoolTip spanIcono" data-toggle="tooltip" title="Pago parcial"><i class="icofont-warning-alt"></i></span>
								<span class="amber-text text-darken-2 mitoolTip spanIcono spanPrint" data-print="parcial" data-toggle="tooltip" title="Imprimir"><i class="icofont-print"></i></span>
							<? }
							if($rowCuot['idTipoPrestamo'] ==80){ ?>
								<span class="mitoolTip spanIcono" data-toggle="tooltip" title="Pago completo"><i class="icofont-verification-check"></i></span>
								<span class="amber-text text-darken-2 mitoolTip spanIcono spanPrint" data-print="completo" data-toggle="tooltip" title="Imprimir"><i class="icofont-print"></i></span>
							<?php }
						endif;?>
					</td>
				</tr>
			<?php $k++; }
			} ?>
				</tbody>
				<tfoot>
					<tr>
						<th></th> <th></th>
						<th><?= number_format($sumCapital,2); ?></th>
						<th><?= number_format($sumInteres,2); ?></th>
						<th><?= number_format($sumCuota,2); ?></th>
						<th></th> <th></th><th> </th>
						
					</tr>
				</tfoot>
			</table>
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
						<?php $_POST['credito']=$_GET['credito']; include 'php/listarOtrospagos.php'; ?>
					</tbody>
				</table>
			</div>

		</div><!-- Fin de contenedorCreditosFluid -->
			

		<?php else: //else de contadorF!=0 ?>
				<p>El código solicitado no está asociado a ningún crédito, revise el código o comuníquelo al área responsable. </p>
		<?php endif; //Fin de if $contadorF 
		} //Fin de if $respuesta 	?>
		<!-- </table> -->

		<?php else: //else de si existe GET['credidto]
		if(isset($_GET['record'])):
			$idCli = $base58->decode($_GET['record']);
			$_GET['idCliente'] = $_GET['record']; 
			$sql="SELECT  `cliDni`, lower(`cliNombres`) as `cliNombres`, lower(`cliApellidoPaterno`) as `cliApellidoPaterno`, lower(`cliApellidoMaterno`) as `cliApellidoMaterno`
			FROM `cliente` WHERE `idCliente`={$idCli} and `cliActivo`=1";
			$resultado=$cadena->query($sql);
			$row=$resultado->fetch_assoc();

			?>
			<h3 class="purple-text text-lighten-1">Record de créditos</h3><hr>
				<p><strong>Código de cliente:</strong> <a href="clientes.php?idCliente=<?= $_GET['record']?>">CL-<?= $idCli; ?></a></p>
				<p><strong>Nombres completos: </strong> <span class="mayuscula"><a href="clientes.php?idCliente=<?= $_GET['record']?>"><?= $row['cliApellidoPaterno'].' '.$row['cliApellidoMaterno'].", ".$row['cliNombres']; ?></a></span></p>
				<p><strong>D.N.I.: </strong> <?= $row['cliDni']; ?></p>
				<div class="container-fluid row">
					<label for="">Préstamos solicitados:</label>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Agencia</th>
									<th>N° Crédito</th>
									<th>Monto desembolsado</th>
									<th>Cuota</th>
									<th>Saldo k</th>
									<th>Fecha de desembolso</th>
									<th>Fecha de cancelación</th>
									<th>Forma de pago</th>
									<?php 
									for ($i=0; $i < 15 ; $i++) { 
										echo "<th>". ($i+1)."</th>";
									}
									?>
								</tr>
							</thead>
							<tbody>
								<?php include 'php/listarHistorialPagos.php' ?>
							</tbody>
						</table>
					</div>

		
		<?php endif; //if de GET record
		if(isset( $_GET['titular'])): ?>
			<h3 class="purple-text text-lighten-1">Asignar crédito</h3><hr>
			<div class="panel panel-default">
				<div class="panel-body">
				<p><strong>Involucrar más clientes:</strong></p>
					<div class="row">
						<div class="col-xs-6 col-sm-3">
							<input type="text" id="txtAddCliente" class="form-control" placeholder="Apellidos o DNI">
						</div>
						<div class="col-xs-3">
							<button class="btn btn-primary btn-outline" id="btnBuscarClientesDni"><i class="icofont-search-1"></i> Buscar</button>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-body">
					<p><strong>Involucrados</strong></p>
					<table class="table" style="margin-bottom: 0px;">
						<thead>
							<tr>
								<th>D.N.I.</th>
								<th>Apellidos y nombres</th>
								<th>Estado civil</th>
								<th>Cargo</th>
							</tr>
						</thead>
						<tbody id="tbodySocios"></tbody>
					</table>

				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-body">
					<p><strong>Cálculos</strong></p>
					<div class="row">
						<div class="col-xs-6 col-sm-3">
							<label for="">Tipo de préstamo:</label>
							<select class="form-control selectpicker" id="sltTipoPrestamo" title="Seleccione un préstamo" data-width="100%" data-live-search="true" data-size="15">
								<?php include 'php/OPTTipoPrestamo.php'; ?>
							</select>
						</div>
						<div class="col-xs-6 col-sm-3">
							<label for="">Interés</label>
							<input type="number" class="form-control esNumero noEsDecimal text-center" id="txtInteres" value=0>
						</div>
						<div class="col-xs-6 col-sm-3">
							<label for="">Periodo</label>
							<input type="number" class="form-control esNumero noEsDecimal text-center" id="txtPeriodo" value=0>
						</div>
						<div class="col-xs-6 col-sm-3">
							<label for="">Monto</label>
							<input type="number" class="form-control esMoneda text-center" id="txtMontoPrinc" value=0.00>
						</div>
						<div class="col-xs-6 col-sm-3">
							<label for="">Fecha Desembolso</label>
							<input type="text" id="dtpFechaIniciov3" class="form-control text-center" placeholder="Fecha para controlar citas" autocomplete="off">
						</div>
						<div class="col-xs-6 col-sm-3 hidden" id="divPrimerPago">
							<label for="">Fecha primer pago</label>
							<input type="text" id="dtpFechaPrimerv3" class="form-control text-center" placeholder="Fecha para controlar citas" autocomplete="off">
						</div>
						
						<div class="col-xs-6 col-sm-3">
							<button class="btn btn-azul btn-lg btn-outline btnSinBorde" style="margin-top: 10px;" id="btnSimularPagos"><i class="icofont-support-faq"></i> Simular</button>
							<button class="btn btn-infocat btn-lg btn-outline btnSinBorde" style="margin-top: 10px;" id="btnGuardarCred"><i class="icofont-save"></i> Guardar</button>
						
						</div>
						<label class="orange-text text-darken-1 hidden" id="labelFaltaCombos" for=""><i class="icofont-warning"></i> Todas las casillas tienen que estar rellenadas para proceder</label>
					</div>
				</div>
			
			</div>
			<div class="panel panel-default">
			<div class="panel-body">
				<p><strong>Resultados:</strong></p>
				<div class="container row" id="divVariables">
				</div>
				<table class="table table-hover" id="tableSimulacion">
				<!-- <thead id="theadResultados">
				</thead>
				<tbody id="tbodyResultados"></tbody>-->
				</table> 
				</div>
			</div>
		
			
		<? endif; //fin de get titular ?>
		<? endif; //fin de get Credito ?>
		<? if( !isset($_GET['titular']) && !isset($_GET['credito']) && !isset($_GET['record']) ): ?>
		<h3 class="purple-text text-lighten-1">Zona créditos</h3><hr>
		<p>Comience buscando un crédito en la parte superior.</p>
		<? endif; ?>
				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
</div></div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->
    

<!-- Modal para mostrar los clientes coincidentes -->
<div class="modal fade" id="mostrarResultadosClientes" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-lg" role="document">
	<div class="modal-content">
		<div class="modal-header-indigo">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Resultados de la búsqueda</h4>
		</div>
		<div class="modal-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>D.N.I.</th>
						<th>Apellidos y nombres</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody id="rowClientesEncontrados">
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>

<?php if(isset($_GET['credito']) && $rowCr['presAprobado']<> 'Sin aprobar' && $rowCr['presAprobado']<> "Rechazado" && $rowCr['presFechaDesembolso']<>'Desembolso pendiente' && in_array($_COOKIE['ckPower'], $soloAdmis)): ?>
<!-- Modal para realizar un pago automtico combo -->
<div class="modal fade" id="mostrarRealizarPagoCombo" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-infocat">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Deudas pendientes</h4>
		</div>
		<div class="modal-body">
			<p>Los siguientes cálculos son calculados al día de hoy:</p>
			<div style="padding-left:20px">
				<p>Cuotas pendientes: <strong><span id="spaCPendientes"></span></strong></p>
				<p>Costo de cuota: <strong><span id="spaCCosto"></span></strong></p>
				<p>Cuota: <strong>S/ <span id="spaCPrecioCuota"></span></strong></p>
				<p>Días de mora: <strong><span id="spaCMora"></span></strong></p>
				<p>Mora: <strong>S/ <span id="spaCPrecioMora"></span></strong></p>
				<hr style="margin-top: 10px; margin-bottom: 10px; border-top: 1px solid #c1c1c1;margin-right: 50px;">
				<p>Pago total: <strong>S/ <span id="spaCTotal"></span></strong></p>
			</div>
			<div class="">
				<div class="checkbox checkbox-infocat checkbox-circle">
					<input id="chkExonerar" class="styled" type="checkbox" >
					<label for="chkExonerar"> Exonerar mora </label>
				</div>
				<label for="">¿Cuánto dinero dispone el cliente?</label>
				<input type="number" class="form-control input-lg text-center inputGrande esMoneda" id="txtPagaClienteVariable" style="margin: 0;">
			</div>
		</div>
		<div class="modal-footer">
			<div class="divError text-left animated fadeIn hidden" style="margin-bottom: 20px;"><i class="icofont-cat-alt-2"></i> Lo sentimos, <span class="spanError">La cantidad de ingresada no puede ser cero o negativo.</span></div>
			<button class="btn btn-infocat btn-outline" id="btnRealizarDeposito" data-dismiss="modal"><i class="icofont-ui-rate-add"></i> Realizar depósito</button>
		</div>
	
	</div>
</div>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
<script src="js/bootstrap-material-datetimepicker.js?version=2.0.1"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){ ?>
<script>
datosUsuario();
$('.selectpicker').selectpicker();
$('.mitoolTip').tooltip();

$(document).ready(function(){
<?php
if(isset($_GET['titular'])){
?>
agregarClienteCanasta('<?= $_GET['titular']; ?>', 1);
<?php
}
?>

$('#dtpFechaIniciov3').val('<?php
	$date = new DateTime();
	echo  $date->format('d/m/Y');
?>');
$('#dtpFechaIniciov3').bootstrapMaterialDatePicker({
	format: 'DD/MM/YYYY',
	lang: 'es',
	time: false,
	weekStart: 1,
	nowButton : true,
	switchOnClick : true,
	//minDate : new Date(),
	// okButton: false,
	okText: '<i class="icofont-check-alt"></i> Aceptar',
	nowText: '<i class="icofont-bubble-down"></i> Hoy',
	cancelText : '<i class="icofont-close"></i> Cerrar'
});
$('#dtpFechaPrimerv3').val('<?php
	$date = new DateTime();
	$saltoDia = new DateInterval('P1D');
	$date->add($saltoDia);
	echo  $date->format('d/m/Y');
?>');
$('#dtpFechaPrimerv3').bootstrapMaterialDatePicker({
	format: 'DD/MM/YYYY',
	lang: 'es',
	time: false,
	weekStart: 1,
	nowButton : false,
	switchOnClick : true,
	minDate :  moment().add(1, 'days'),
	// okButton: false,
	okText: '<i class="icofont-check-alt"></i> Aceptar',
	nowText: '<i class="icofont-bubble-down"></i> Hoy',
	cancelText : '<i class="icofont-close"></i> Cerrar'
});
$('#txtAddCliente').keypress(function (e) {
	if(e.keyCode == 13){ $('#btnBuscarClientesDni').click(); }
});
$('#btnBuscarClientesDni').click(function () {
	if( $('#txtAddCliente').val()!='' ){
		
			$('#rowClientesEncontrados').children().remove();
			$.ajax({url: 'php/ubicarCliente.php', type: 'POST', data: { buscar: $('#txtAddCliente').val() }}).done(function(resp) {
				//console.log(resp);
				var json=JSON.parse(resp);
				if(json.length==0){
					$('#rowClientesEncontrados').append(`<tr">
							<td>No se encontraron coincidencias</td>
						</tr>`);
				}else{
					$.each( JSON.parse(resp) , function(i, dato){
						$('#rowClientesEncontrados').append(`<tr data-cli="${dato.idCliente}">
								<td>${dato.cliDni}</td>
								<td class="mayuscula">${dato.cliApellidoPaterno} ${dato.cliApellidoMaterno} ${dato.cliNombres} </td>
								<td><button class="btn btn-success btn-sm btn-outline btnSelectCliente" data-id="${dato.idCliente}" ><i class="icofont-ui-add"></i></button></td>
							</tr>`);				
					});
					}
				});
			$('#mostrarResultadosClientes').modal('show');
		
	}
});
$('#rowClientesEncontrados').on('click','.btnSelectCliente', function() {
	agregarClienteCanasta($(this).attr('data-id'), 3);
	$('#mostrarResultadosClientes').modal('hide');
});
$('#tbodySocios').on('click','.btnRemoveCanasta',function() {
	$(this).parent().parent().remove();
	//console.log( $(this).parent().parent().html() );
});
$('#tableSubIds tr').last().find('td').eq(5).text('0.00');


}); //Fin de Document ready

$('#btnSimularPagos').click(function() {
	if( $('#sltTipoPrestamo').val()=='' || $('#txtPeriodo').val()=='' || $('#txtMontoPrinc').val()=='' ||  parseFloat($('#txtPeriodo').val())==0 || parseFloat($('#txtMontoPrinc').val())==0 ){
		//console.log('falta algo')
		$('#labelFaltaCombos').removeClass('hidden');
	}else{
		$('#labelFaltaCombos').addClass('hidden');
	$.ajax({url: 'php/simularPrestamoOnline.php', type: 'POST', data: {
		modo: $('#sltTipoPrestamo').val(),
		periodo: $('#txtPeriodo').val(),
		monto: $('#txtMontoPrinc').val(),
		tasaInt: $('#txtInteres').val(),
		fDesembolso: moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
		primerPago: moment($('#dtpFechaPrimerv3').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
		}}).done(function(resp) { //console.log(resp)
		$('#tableSimulacion').html(resp);
	//	$('#tbodyResultados td').last().text('0.00');
	});
	$('#divVariables').children().remove();
	/* switch ($('#sltTipoPrestamo').val()) {
		
		case "1":
			// $('#divVariables').append(`<p><strong>TED:</strong> <span>0.66%</span></p>`);
			$('#theadResultados').html(`	<th>#</th>
					<th>Fecha</th>
					<th>Capital</th>
					<th>Interés</th>
					<th class="hidden">Amortización</th>
					<th>Cuota</th>
					<th class="hidden">Saldo Real</th>`);
			break;
		case "2":
			// $('#divVariables').append(`<p><strong>TES:</strong> <span>1.52%</span></p>`);
			$('#theadResultados').html(`	<th>#</th>
					<th>Fecha</th>
					<th>Capital</th>
					<th>Interés</th>
					<th class="hidden">Amortización</th>
					<th>Cuota</th>
					<th class="hidden">Saldo Real</th>`);
			break;
		case "4":
		case "3":
			// $('#divVariables').append(`<p><strong>TEQ:</strong> <span>2.95%</span></p>`);
			$('#theadResultados').html(`	<th>#</th>
					<th>Fecha</th>
					<th>Capital</th>
					<th>Interés</th>
					<th class="hidden">Amortización</th>
					<th>Cuota</th>
					<th class="hidden">Saldo Real</th>`);
			break;
		case "99":
			$('#theadResultados').html(`	<th>#</th>
					<th>Fecha pago</th>
					<th class="hidden">Días</th>
					<th class="hidden">Días Acum.</th>
					<th class="hidden">FRC</th>
					<th>Saldo de Capital</th>
					<th>Amortización</th>
					<th>Interés</th>
					<th class="hidden">Seg 1</th>
					<th class="hidden">Seg Def</th>
					<th>Cuota sin ITF</th>
					<th>ITF</th>
					<th>Total Cuota</th>`);
			break;
		default:
			break;
	} */
	} //fin de else
});
$('#txtSoloBuscaCreditos').keypress(function (e) { 
	var valor = $('#txtSoloBuscaCreditos').val().toUpperCase();
	if(e.keyCode == 13){ 
		if( valor.indexOf('CR-')==0 ){
			$.post('php/58encode.php', {texto: valor.replace('CR-', '') }, function(resp) {
				window.location.href = 'creditos.php?credito='+resp;
			});
		}
	}
});
function agregarClienteCanasta(idCl, cargo) { //console.log( idCl );
	$.ajax({url: 'php/ubicarDatosCliente.php', type: 'POST', data: { idCli: idCl }}).done(function(resp) {
	//console.log(resp);
	var dato = JSON.parse(resp);
	var botonDelete;
	if(cargo!=1){
		botonDelete='<button class="btn btn-danger btn-sm btn-outline btn-sinBorde btn-circle btnRemoveCanasta" data-id="${dato.idCliente}" ><i class="icofont-close"></i></button>';
	}else{botonDelete="";}
	$('#tbodySocios').append(`<tr data-cli="${dato[0].idCliente}">
			<td>${dato[0].cliDni}</td>
			<td class="mayuscula">${dato[0].cliApellidoPaterno} ${dato[0].cliApellidoMaterno} ${dato[0].cliNombres} </td>
			<td>${dato[0].civDescripcion}</td>
			<td><select class="form-control"><?php include 'php/OPTTipoCliente.php';?></select></td>
			<td>${botonDelete}</td>
		</tr>`);

		if(cargo==1 || cargo==2){
			$(`[data-cli="${dato[0].idCliente}"]`).find('select').val(cargo).attr('disabled','true');
		}else{
			$(`[data-cli="${dato[0].idCliente}"]`).find('select').val(cargo);
			$(`[data-cli="${dato[0].idCliente}"]`).find('select').find('[value="1"]').attr('disabled', 'true');
			$(`[data-cli="${dato[0].idCliente}"]`).find('select').find('[value="2"]').attr('disabled', 'true');
		}
			
		
});
if(cargo==1){
	$.ajax({url: 'php/listarMatrimonio.php', type: 'POST', data: { conyugue: idCl }}).done(function(resp) { //console.log(resp)
		var datoMatri= JSON.parse(resp); console.log( idCl ); 
		if(datoMatri.length==1){

			if(datoMatri[0].idEsposo==parseFloat(idCl)){
			//	console.info('esposo') //listar a la esposa
				agregarClienteCanasta(datoMatri[0].idEsposa, 2);
			}else{
				//console.info('esposa') //listar al esposo
				agregarClienteCanasta(datoMatri[0].idEsposo, 2);
			}
		}
	});
}
}//fin de function
$('#btnGuardarCred').click(function() {
	if( $('#sltTipoPrestamo').val()=='' || $('#txtPeriodo').val()=='' || $('#txtInteres').val()=='' || $('#txtMontoPrinc').val()=='' ||  parseFloat($('#txtPeriodo').val())==0 || parseFloat($('#txtMontoPrinc').val())==0 ){
		//console.log('falta algo')
		$('#labelFaltaCombos').removeClass('hidden');
	}else{
		$('#labelFaltaCombos').addClass('hidden');

		var clientArr = [];
		$.each( $('#tbodySocios tr') , function(i, objeto){
			clientArr.push( { 'id': $(objeto).attr('data-cli'), 'grado':  $(objeto).find('select').val()}  )
		});

		$.ajax({url: 'php/insertarPrestamoOnline.php', type: 'POST', data: {
			clientes: clientArr,
			modo: $('#sltTipoPrestamo').val(),
			periodo: $('#txtPeriodo').val(),
			monto: $('#txtMontoPrinc').val(),
			tasaInt: $('#txtInteres').val(),
			fDesembolso: moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'),
			primerPago: moment($('#dtpFechaPrimerv3').val(), 'DD/MM/YYYY').format('YYYY-MM-DD')
		}}).done(function(resp) {
			console.log(resp)
			if( parseInt(resp)>0 ){

				$.post("php/58decode.php", {texto: resp}, function(data){ console.log(data);
					$('#spanBien').text('Código de préstamo:')
					$('#h1Bien').html(`<a href="creditos.php?credito=`+resp+`">CR-`+data+`</a> <br> <button class="btn btn-default " id="btnImpresionPrevia" data-pre="`+resp+`"><i class="icofont-print"></i> Imprimir</button>`)
					$('#modalGuardadoCorrecto').modal('show');
				});
			}
		});
	}
});
$('#h1Bien').on('click', '#btnImpresionPrevia', function(){
		var dataUrl="php/printCronogramaPagos.php?prestamo="+$(this).attr('data-pre');
		window.open(dataUrl, '_blank' );
});
$('#rowBotonesMaestros').on('click', '#btnImpresionPrevia', function(){
		var dataUrl="php/printCronogramaPagos.php?prestamo="+$(this).attr('data-pre');
		window.open(dataUrl, '_blank' );
});
$('#sltTipoPrestamo').change(function() {
	if( $(this).val()==3 ){
		$('#divPrimerPago').removeClass('hidden');
	}else{
		$('#divPrimerPago').addClass('hidden');
	}
});
$('#dtpFechaIniciov3').change(function() {
	$('#dtpFechaPrimerv3').bootstrapMaterialDatePicker( 'setMinDate', moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').add(1, 'days') );
});
<?php if(isset( $_GET['credito'])): ?>
$('#btnDesembolsar').click(function() {
	$.ajax({url: 'php/updateDesembolsoDia.php', type: 'POST', data:{ credito: '<?= $_GET['credito'];?>' }}).done(function(resp) {
		console.log(resp)
		if(resp==true){
			location.reload();
		}
	});
});
$('#chkExonerar').change(function(){
	
	var total = parseFloat($('#spaCTotal').text());

	if( $('#chkExonerar').prop('checked') ){
		var mora= parseFloat($('#spaCPrecioMora').text());
		$('#spaCPrecioMora').attr('data-mora', $('#spaCPrecioMora').text());
		$('#spaCTotal').text((total-mora).toFixed(2));
		$('#spaCPrecioMora').text('0.00');
	}else{
		$('#spaCPrecioMora').text( $('#spaCPrecioMora').attr('data-mora'));
		var mora= parseFloat($('#spaCPrecioMora').text());
		$('#spaCTotal').text((total+mora).toFixed(2));
	}
});
$('.spanPrint').click(function() {
	var padre = $(this).parent().parent();
	var queEs= $(this).attr('data-print');
	switch(queEs){
		case 'parcial':
			$.post("http://localhost/prestamosSatipo/impresion/ticketCuotaParcial.php", {
				cknombreEmpresa: '<?= $_COOKIE['cknombreEmpresa'];?>',
				ckLemaEmpresa: '<?= $_COOKIE['ckLemaEmpresa'];?>',
				queMichiEs: 'Adelanto de cuota',
				hora: moment().format('DD/MM/YYYY h:mm a'),
				cliente: $('#spanTitular').text(),
				codPrest: $('#h3Codigo').attr('data-id'),
				monto: padre.find('.tdPagoCli').attr('data-pago'),
				usuario: '<?= $_COOKIE["ckAtiende"];?>',
				ckcelularEmpresa: '<?= $_COOKIE['ckcelularEmpresa'];?>',
				cktelefonoEmpresa: '<?= $_COOKIE['cktelefonoEmpresa'];?>'
			}, function(resp){ console.log(resp)});
		break;
		case 'completo':
			$.post("http://localhost/prestamosSatipo/impresion/ticketCuotaParcial.php", {
				cknombreEmpresa: '<?= $_COOKIE['cknombreEmpresa'];?>',
				ckLemaEmpresa: '<?= $_COOKIE['ckLemaEmpresa'];?>',
				queMichiEs: 'Cuota cancelada',
				hora: moment().format('DD/MM/YYYY h:mm a'),
				cliente: $('#spanTitular').text(),
				codPrest: $('#h3Codigo').attr('data-id'),
				monto: padre.find('.tdPagoCli').attr('data-pago'),
				usuario: '<?= $_COOKIE["ckAtiende"];?>',
				ckcelularEmpresa: '<?= $_COOKIE['ckcelularEmpresa'];?>',
				cktelefonoEmpresa: '<?= $_COOKIE['cktelefonoEmpresa'];?>'
			}, function(resp){ console.log(resp)});
		break;
	}
});
<?php endif; ?>
<?php if(isset($_GET['credito']) && $rowCr['presAprobado']=== 'Sin aprobar'): ?>
$('#btnShowVerificarCredito').click(function() {
	$('#modalVerificarCredito').modal('show');
});
$('#btnDenyVerificarCredito').click(function() {
	$('#modalDenegarCredito').modal('show');
});
$('#btnVerificarCredito').click(function() {
	$.ajax({url: 'php/updateVerificarCredito.php', type: 'POST', data: { credit: '<?= $codCredito; ?>' }}).done(function(resp) { //console.log(resp)
		if(resp==1){
			location.reload();
		}
	});
});
$('#btnDenegarCredito').click(function() {
	$.ajax({url: 'php/updateDenegarCredito.php', type: 'POST', data: { credit: '<?= $codCredito; ?>', razon: $('#txtDenegarRazon').val() }}).done(function(resp) { //console.log(resp)
		if(resp==1){
			location.reload();
		}
	});
});
<?php endif;
if( in_array($_COOKIE['ckPower'], $admis) ){ ?>

$('.btnPagarCuota').click(function() {
	var code= $(this).parent().parent().children().first().text();
	$('#strSubCredito').text( code );
	$('#btnPagarCreditoCompleto').attr('data-id', code.replace('SP-', ''));
	$('#modalPagoCreditoCompleto').modal('show');
});
$('#btnPagarCreditoCompleto').click(function() {
	$.ajax({url: 'php/pagarCreditoCompleto.php', type: 'POST', data: { idCred: $(this).attr('data-id') }}).done(function(resp) {
		console.log(resp)
		if(resp==true){
			location.reload();
		}
	});
});
$('#btnsolicitarDeuda').click(function() {
	$.ajax({url: 'php/solicitarDeudasHoy.php', type: 'POST', data: { credito: '<?php if(isset ($_GET['credito'])){echo $_GET['credito'];}else{echo '';}; ?>' }}).done(function(resp) {
		console.log(resp);
		var data=JSON.parse(resp);
		if(data.diasMora==0){
			$('#spaCMora').parent().parent().addClass("hidden");
			$('#spaCPrecioMora').parent().parent().addClass("hidden");
		}else{
			$('#spaCMora').parent().parent().removeClass("hidden");
			$('#spaCPrecioMora').parent().parent().removeClass("hidden");
		}
		$('#spaCPendientes').text(data.tantasCuotas);
		$('#spaCCosto').text(data.precioCuotas.toFixed(2));
		$('#spaCMora').text(data.diasMora);
		$('#spaCPrecioCuota').text(data.deudaCuotas.toFixed(2));
		$('#spaCPrecioMora').text(data.precioMora.toFixed(2));
		$('#spaCTotal').text(data.paraFinalizar.toFixed(2));
		$('#mostrarRealizarPagoCombo').modal('show');
		
	});
});
$('#btnRealizarDeposito').click(function() {
	pantallaOver(true);
	$('#h1Bien2').children().remove();
	if( $('#txtPagaClienteVariable').val()<=0 ){
		$('#mostrarRealizarPagoCombo .divError').removeClass('hidden').find('.spanError').text('No se permiten valores negativos o ceros.');
	}else if($('#txtPagaClienteVariable').val() > parseFloat($('#spaCTotal').text())  ){
		$('#mostrarRealizarPagoCombo .divError').removeClass('hidden').find('.spanError').html('El monto máximo que se puede depositar es <strong>S/ '+$('#spaCTotal').text()+'</strong> .');
	}else if( $('#txtPagaClienteVariable').val() < parseFloat($('#spaCPrecioMora').text()) ){
		$('#mostrarRealizarPagoCombo .divError').removeClass('hidden').find('.spanError').html('Debe adeltar y cubrir mínimo la mora <strong>S/ '+$('#spaCPrecioMora').text()+'</strong> .');
	}else{
		$.ajax({url: 'php/pagarCreditoCombo.php', type: 'POST', data: {credito: '<?php if(isset ($_GET['credito'])){echo $_GET['credito'];}else{echo '';}; ?>', dinero: $('#txtPagaClienteVariable').val(), exonerar: $('#chkExonerar').prop('checked') }}).done(function(resp) { console.log( resp );
			var data = JSON.parse(resp); 
			if( data.length >0 ){
				if(data[0].diasMora>0){
					$('#tituloPeque2').text('Items cancelados');
					$('#h1Bien2').append(`<span  data-quees='${data[0].queEs}' data-monto='${data[0].montoCuota}' data-id='0'>Mora: S/ `+ parseFloat(data[0].sumaMora).toFixed(2) +`</span><br>`);
					for(i=1; i<data.length; i++){$('#h1Bien2').append(`<span data-quees='${data[i].queEs}' data-monto='${data[i].montoCuota}' data-id='${data[i].cuota}'>SP-`+ data[i].cuota +`: S/ `+ parseFloat(data[i].montoCuota).toFixed(2) +`</span><br>`);}
				}else{
					for(i=0; i<data.length; i++){$('#h1Bien2').append(`<span data-quees='${data[i].queEs}' data-monto='${data[i].montoCuota}' data-id='${data[i].cuota}'>SP-`+ data[i].cuota +`: S/ `+ parseFloat(data[i].montoCuota).toFixed(2) +`</span><br>`);}
				}
				$('#modalGuardadoCorrecto2').modal('show');
				
			}
			// if(resp==true){
			// 	location.reload();
			// }

		});
	}
	pantallaOver(false);
});
<?php } ?>
</script>
<?php } ?>
</body>

</html>