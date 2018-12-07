<?php 
$nomArchivo = basename($_SERVER['PHP_SELF']); ?>
<div id="sidebar-wrapper">
	<ul class="sidebar-nav">
		<div class="logoEmpresa ocultar-mostrar-menu">
			<img class="img-responsive" src="images/empresa.png?version=1.1" alt="">
		</div>
		<li <?php if($nomArchivo =='principal.php') echo 'class="active"'; ?>>
				<a href="#!"><i class="icofont-home"></i> Inicio</a>
		</li>
		<li <?php if($nomArchivo =='clientes.php') echo 'class="active"'; ?>>
				<a href="clientes.php"><i class="icofont-users"></i> Clientes</a>
		</li>
		<li <?php if($nomArchivo =='creditos.php') echo 'class="active"'; ?>>
				<a href="creditos.php"><i class="icofont-handshake-deal"></i> Créditos</a>
		</li>
		<li <?php if($nomArchivo =='caja.php') echo 'class="active"'; ?>>
				<a href="caja.php"><i class="icofont-shopping-cart"></i> Caja</a>
		</li>
		<li <?php if($nomArchivo =='verificacion.php') echo 'class="active"'; ?>>
				<a href="verificacion.php"><i class="icofont-checked"></i> Verificación</a>
		</li>
		<li <?php if($nomArchivo =='reportes.php') echo 'class="active"'; ?>>
				<a href="reportes.php"><i class="icofont-ui-copy"></i> Reportes</a>
		</li>
		<li <?php if($nomArchivo =='reportes.php') echo 'class="active"'; ?>>
				<a href="simulador.php"><i class="icofont icofont-robot"></i> Simulador</a>
		</li>
		<?php if( $_COOKIE['ckPower']==1){ ?>
		<li <?php if($nomArchivo =='usuarios.php') echo 'class="active"'; ?>>
				<a href="usuarios.php"><i class="icofont-users"></i> Usuarios</a>
		</li>
		<li <?php if($nomArchivo =='configuraciones.php') echo 'class="active"'; ?>>
				<a href="configuraciones.php"><i class="icofont-settings"></i> Configuraciones</a>
		</li>
		 <?php } ?>
		<li>
				<a href="#!" class="ocultar-mostrar-menu"><i class="icofont icofont-swoosh-left"></i> Ocultar menú</a>
		</li>
	</ul>
</div>
<div class="navbar-wrapper">
	<div class="container-fluid">
		<nav class="navbar navbar-fixed-top encoger">
			<div class="container">
				<div class="navbar-header ">
				<a class="navbar-brand ocultar-mostrar-menu" href="#"><img id="imgLogoInfocat" class="img-responsive" src="images/logoInfocat.png" alt=""></a>
					<button type="button" class="navbar-toggle collapsed" id="btnColapsador" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
				</div>
				<div id="navbar" class="navbar-collapse collapse ">
					<ul class="nav navbar-nav">
						<li class="hidden down"><a href="#" class="dropdown-toggle active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">HR <span class="caret"></span></a>
								<ul class="dropdown-menu">
										<li><a href="#">Change Time Entry</a></li>
										<li><a href="#">Report</a></li>
								</ul>
							</li>
					</ul>
					<ul class="nav navbar-nav navbar-right " style="padding:0 30px;">
						 <li>
							<div class="btn-group has-clear "><label for="txtBuscarNivelGod" class="text-muted visible-xs" style="color:white;">Buscar algo:</label>
								<input type="text" class="form-control" id="txtBuscarNivelGod" placeholder="&#xed11;">
								<span class="form-control-clear icofont icofont-close form-control-feedback hidden" style="color:#777;padding-top: 9px;"></span>
							</div>
						 </li>
						 <li class="dropdown" id="liDatosPersonales">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="padding-top: 20px;"><i class="icofont-user-alt-7"></i> <?php echo $_COOKIE['cknomCompleto']; ?> <span class="caret"></span></a>
							  <ul class="dropdown-menu">
							  	<li><a href="#"><i class="icofont-id-card"></i> Ver mi perfil</a></li>
								<li><a href="#"><i class="icofont-key"></i> Cambiar contraseña</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="php/desconectar.php"><i class="icofont icofont-ui-power"></i> <span class="">Cerrar Sesión</span></a></li>
							  </ul>
						 </li>
						 <li class="text-center"></li>
					</ul>

				</div>
		</div>
		</nav>
	</div>
</div>
<div id="overlay">
	<div class="text"><span id="hojita"><i class="icofont icofont-leaf"></i></span> <p id="pFrase"> Guardando los datos... <br> <span>«Pregúntate si lo que estás haciendo hoy <br> te acerca al lugar en el que quieres estar mañana» <br> Walt Disney</span></p></div>
</div>