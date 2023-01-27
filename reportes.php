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

		<title>Reporte - CrediBalbin Sistema Préstamos</title>

		<!-- Bootstrap Core CSS -->
		<?php include 'headers.php'; ?>
		<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css?version=1.0.1">
</head>

<body>

<style>
.input-group-addon{
	font-size: 12px;
	color:#fff;
	background-color: #a35bb4;
}
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
			<h2 class="purple-text text-lighten-1">Reportes </h2><hr>
			<p>Seleccione la opción de reporte que desea ver:</p>
			<div class="row">
				<div class="col-xs-6 col-md-3">
					<select name="" class="form-control" id="sltFiltroReporte">
						<option value="R3" class="optReporte">Créditos nuevos</option>
						<option value="R4" class="optReporte">Clientes con moras</option>
						<option value="R1" class="optReporte">Movimientos de entrada</option>
						<option value="R2" class="optReporte">Movimientos de Salida</option>
						<option value="R5" class="optReporte">Cuadro de ganancias</option>

					</select>
				</div>
				<div class="col-xs-6 col-md-6" id="divIntervaloFechas">
					<div class="sandbox-container">
						<div class="input-daterange input-group" id="datepicker">
							<input type="text" class=" form-control" id="inputFechaInicio" name="start" />
							<span class="input-group-addon">hasta</span>
							<input type="text" class=" form-control" id="inputFechaFin" name="end" />
						</div>
					</div>
				</div>
				<div class="col-xs-6 col-md-3">
					<button class="btn btn-success btn-outline" id="btnFiltrarReporte"><i class="icofont-search-1"></i> Filtrar reporte</button>
				</div>
			</div>
			<div style="padding-top: 30px;">
			<table class="table table-hover" id="resultadoReporte">
			</table>
			</div>

				
			<!-- Fin de contenido principal -->
			</div>
		</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<?php include 'footer.php'; ?>
<script src="js/bootstrap-material-datetimepicker.js?version=2.0.1"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$('#inputFechaInicio').val(moment().format('DD/MM/YYYY'));
$('#inputFechaFin').val(moment().format('DD/MM/YYYY'));

$('#inputFechaInicio').bootstrapMaterialDatePicker({
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
$('#inputFechaFin').bootstrapMaterialDatePicker({
	format: 'DD/MM/YYYY',
	lang: 'es',
	time: false,
	weekStart: 1,
	nowButton : true,
	switchOnClick : true,
	minDate : new Date( moment() ),
	// okButton: false,
	okText: '<i class="icofont-check-alt"></i> Aceptar',
	nowText: '<i class="icofont-bubble-down"></i> Hoy',
	cancelText : '<i class="icofont-close"></i> Cerrar'
});
$('#inputFechaInicio').change(function () {
	if( moment($('#inputFechaInicio').val(), 'DD/MM/YYYY').isValid()) {
		var fechaMin = moment($('#inputFechaInicio').val(), 'DD/MM/YYYY');
		$('#inputFechaFin').bootstrapMaterialDatePicker('setMinDate', fechaMin );
	}
});

$(document).ready(function(){

});
$('#btnFiltrarReporte').click(function() { //console.log('a')
	if( $('#sltFiltroReporte').val()!=-1 && moment($('#inputFechaInicio').val(), 'DD/MM/YYYY').isValid() && moment($('#inputFechaFin').val(), 'DD/MM/YYYY').isValid() ){
		$('#resultadoReporte').html('')
		$.ajax({url: 'php/reporteXCaso.php', type: 'POST', data: { caso: $('#sltFiltroReporte').val(), fInicio :  moment($('#inputFechaInicio').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'), fFinal: moment($('#inputFechaFin').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') }}).done(function(resp) {
			//console.log(resp);
			$('#resultadoReporte').html(resp);
		});
	}
});
$('#sltFiltroReporte').change(function() {
	if( $('#sltFiltroReporte').val()=='R4' ){
		$('#divIntervaloFechas').addClass('hidden');
	}else{
		$('#divIntervaloFechas').removeClass('hidden');
	}
});
</script>
<?php } ?>
</body>

</html>