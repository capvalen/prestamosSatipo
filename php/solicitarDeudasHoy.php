<?
require 'variablesGlobales.php';
include 'conkarl.php';
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

// $mora -> 2.00
$fechaHoy = new DateTime();
$deudaAHoy =0;
$filas=array();
$k=0;
$sumaSa=0;
$diasMora =0;
$precioCuota=0;

$sql="SELECT idCuota, cuotFechaPago, cuotCuota, cuotPago FROM `prestamo_cuotas`
where cuotFechaPago <=curdate() and cuotCuota<>0 and idTipoPrestamo in (33, 79)
and idPrestamo={$base58->decode($_POST['credito'])}
order by cuotFechaPago asc;";

$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){
	$precioCuota=floatval($row['cuotCuota']-$row['cuotPago']);
	$fechaCuota = new DateTime($row['cuotFechaPago']);
	$diasDebe=$fechaHoy ->diff($fechaCuota);
	$restaDias= floatval($diasDebe->format('%a'));

	$sumaSa+=floatval($precioCuota);
//echo $restaDias."\n";
	if($restaDias>0){
		//sumar Dia y Mora
		if($k==0){
			$diasMora = $restaDias;
		}
		// array_push($filas, array(
		// 	cuotFechaPago=> $row['cuotFechaPago'],
		// 	cuotCuota=> floatval($row['cuotCuota']),
		// 	diasDebe=>$restaDias,
		// 	mora=>$mora
		// ));
		// $sumaSa+=(floatval($row['cuotCuota'])+$mora*$restaDias);
	}
	//else{
	//	$diasMora -= 1;
		//  sólo sumar día
		//$filas[$k]=
		// array_push($filas, array(
		// 	cuotFechaPago=> $row['cuotFechaPago'],
		// 	cuotCuota=> floatval($row['cuotCuota']),
		// 	diasDebe=>0,
		// 	mora=>0
		// ));
		// $sumaSa+=floatval($row['cuotCuota']);
	//}

	$k++;
}
// echo "Total de días de mora: ". $diasMora;
// echo "Suma total: ".$sumaSa;
// echo "El cliente debe pagar para finalizar:".($sumaSa+ $diasMora*$mora );
//if($diasMora<>0){$diasMora-=1;}
$filas = array(
	'tantasCuotas'=> $k,
	'precioCuotas'=> $precioCuota,
	'diasMora' =>$diasMora,
	'deudaCuotas' => round($sumaSa,2),
	'precioMora' =>$diasMora*$mora,
	'paraFinalizar' => round($sumaSa+ $diasMora*$mora,2)
);

echo json_encode($filas);

?>