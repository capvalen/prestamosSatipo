<?php
//session_start();
unset($_COOKIE['ckidUsuario']);
unset($_COOKIE['ckidSucursal']);
unset($_COOKIE['ckSucursal']);
unset($_COOKIE['ckAtiende']);
unset($_COOKIE['cknomCompleto']);
unset($_COOKIE['ckPower']);
unset($_COOKIE['ckoficina']);

unset($_COOKIE['cknombreEmpresa']);
unset($_COOKIE['ckrucEmpresa']);
unset($_COOKIE['ckdireccionEmpresa']);
unset($_COOKIE['cktelefonoEmpresa']);
unset($_COOKIE['cksucursalEmpresa']);


setcookie("ckidUsuario", "", time() - 3600, '/');
setcookie("ckidSucursal", "", time() - 3600, '/');
setcookie("ckSucursal", "", time() - 3600, '/');
setcookie("ckAtiende", "", time() - 3600, '/');
setcookie("cknomCompleto", "", time() - 3600, '/');
setcookie("ckPower", "", time() - 3600, '/');
setcookie("ckoficina", "", time() - 3600, '/');
setcookie("ckInventario", "", time() - 3600, '/');

setcookie("cknombreEmpresa", "", time() - 3600, '/');
setcookie("ckrucEmpresa", "", time() - 3600, '/');
setcookie("ckdireccionEmpresa", "", time() - 3600, '/');
setcookie("cktelefonoEmpresa", "", time() - 3600, '/');
setcookie("cksucursalEmpresa", "", time() - 3600, '/');

if ($_SESSION['Sucursal']) {
	session_destroy();
	
}
header("location:..\index.php");
?>