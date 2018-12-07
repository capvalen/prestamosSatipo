<?php 
	require_once('vendor/autoload.php');
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

		<title>Usuarios - Sistema Préstamos</title>

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
			<h2 class="purple-text text-lighten-1"><i class="icofont icofont-options"></i> Panel de configuraciones generales</h2>

			<ul class="nav nav-tabs">
			<li class="active"><a href="#tabAgregarLabo" data-toggle="tab">Listado de usuarios</a></li>

			</ul>

			<div class="tab-content">
			<!--Panel para buscar productos-->
				<!--Clase para las tablas-->
				<div class="tab-pane fade in active container-fluid" id="tabAgregarLabo">
				<!--Inicio de pestaña 01-->
					<div class="row" style="padding-bottom: 15px">
						<div class="col-xs-4">
							<button class="btn btn-success btn-outline btn-lg" id="btnAddNewUser"><i class="icofont icofont-chef"></i> Agregar nuevo usuario</button>
						</div>
						<div class="col-xs-4"></div>
						<div class="col-xs-4"></div>
					</div>
					<div class="row"><strong>
						<div class="col-xs-2">Nivel</div>
						<div class="col-xs-3">Usuario</div>
						<div class="col-xs-2">Nick</div>
						<div class="col-xs-1">@</div></strong>
					</div>
					<div id="divUsuariosListado">
						
					</div>

				<!--Fin de pestaña 01-->
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

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();

$(document).ready(function(){
	
});

</script>
<?php } ?>
</body>

</html>