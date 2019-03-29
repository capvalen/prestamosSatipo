<?php 
include "php/variablesGlobales.php";
 ?>
<!DOCTYPE html>
<html lang="es">
<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Principal - CrediBalbin Sistema Préstamos</title>

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
			<h2 class="purple-text text-lighten-1">Simulador de créditos <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
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

				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>
<script src="js/bootstrap-material-datetimepicker.js?version=2.0.1"></script>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$(document).ready(function(){
	
});
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
		}}).done(function(resp) {// console.log(resp)
		$('#tableSimulacion').html(resp);
	//	$('#tbodyResultados td').last().text('0.00');
	});
	$('#divVariables').children().remove();
	} //fin de else
});
$('#dtpFechaIniciov3').change(function() {
	$('#dtpFechaPrimerv3').bootstrapMaterialDatePicker( 'setMinDate', moment($('#dtpFechaIniciov3').val(), 'DD/MM/YYYY').add(1, 'days') );
});

</script>
<?php } ?>
</body>

</html>