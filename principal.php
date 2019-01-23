<?php include "php/variablesGlobales.php"; ?>
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
.pBalvin{
	font-size: 15px;padding-right: 50px;
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
			<div class="container">
				<h2 class="purple-text text-lighten-1">Bienvenido al sistema <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
				<div class="imagen text-center">
					<img src="images/mujer-con-billetes-619x346.jpg" alt="">
				</div>
					<h3 class="purple-text text-lighten-1">¿Por qué CrediBalvin?</h3>
					<p class="pBalvin">CrediBalvin es una microempresa de créditos que permite conocer la oferta del mercado y
					comparar las características de cada uno según las necesidades de los usuarios. <br> Somos
					Gente de confianza y reconocida en la Provincia de Satipo con absoluta rapidez, discreción
					y seguridad.</p>
					<h3 class="purple-text text-lighten-1">Dinero rápido</h3>
					<p class="pBalvin">Entendemos que necesitas dinero urgente y para eso te ayudamos a lograrlo súper rápido
					y fácil. Una vez que confirmas la mejor opción de tus mini préstamos, la institución te
					contesta en solamente 15 minutos.</p>
					<h3 class="purple-text text-lighten-1">Crédito rápido</h3>
					<p class="pBalvin">Además de recibir su crédito inmediato, si requieres nuevamente de los servicios de
					CrediBalvin, los límites de tus créditos personales van creciendo gradualmente y podrás
					pedir hasta S/ 15 000.00</p>
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