<?
$total0 = count($_POST);	
$clavesw = array_values($_POST);
// Misma operaci�n que en el fichero insertar.php
$x=4;
while($x < $total0 - 2)
{
	// Dividimos los valores en grupos de 6, cada uno conteniendo todos los datos necesarios para una hora de un dia de la semana, con su fecha, nivel grupo, etc.
$trozos0 = array_slice($clavesw, $x, 6);
// Pasamos fecha espa�ola a formato MySql
$fecha0 = explode('-',$trozos0[0]);
$dia0 = $fecha0[0];
$mes = $fecha0[1];
$ano = $fecha0[2];
$fecha1 = $ano . "-" . $mes . "-" . $dia0;
$profe22 = "select no_prof from horw where prof = '$profesor'";
$profe20 = mysql_query($profe22);
$profe21 = mysql_fetch_row($profe20);
$profe23 = $profe21[0];
$fecha_dia = date('N', strtotime($fecha1)); 

// Borramos registros que no coincidan con los que se mantienen.
$del ="DELETE FROM FALTAS WHERE FECHA = '$fecha1' and NIVEL = '$trozos0[3]' AND GRUPO = '$trozos0[1]' and hora = '$trozos0[5]' and dia =  '$fecha_dia' and PROFESOR = '$profe23' and FALTA = 'F' and NC not like '$trozos0[2]'";
$del0 = mysql_query($del);	
// Pasamos al siguiente bloque de 6 variables hasta el final
$x += 6;	
}	
//exit();
?>