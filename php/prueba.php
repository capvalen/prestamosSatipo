<?php
$fecha = new DateTime();

$fechaAnterior = new DateTime('2019-02-01');

	if( $fecha->diff($fechaAnterior)->format('%R%a')>0)
	{ echo 'No hacer nada';
	}else{
		$fecha = new DateTime($fechaAnterior->add(new DateInterval('P1M'))->format('Y-m').'-01');
	}

?>