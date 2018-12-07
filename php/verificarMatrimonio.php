<?php 

function verificarMatri($soltero, $cadena){
  $linea= "SELECT * FROM `matrimonio`
  where (idEsposo = {$soltero} or idEsposa = {$soltero})
  and matrActivo=1";
//  echo $linea;

  //$filas=array();
  //$contar=0;

  if ($sql = $cadena->query($linea)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
    /* obtener el array de objetos */
    
    $contar = $sql->num_rows;
    return $contar;
    // if($contar==1){
    //   $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
    //   $filas[0]= $row;
    // }

    /* liberar el conjunto de resultados */
  }/* else{echo -1;} */
  //return json_encode($filas);
}
?>