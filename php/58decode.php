<?php 
require_once('../vendor/autoload.php');
$base58 = new StephenHill\Base58();

echo $base58->decode($_POST['texto']);
?>
