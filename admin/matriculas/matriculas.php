<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
if(!(stristr($_SESSION['cargo'],'1') == TRUE) and !(stristr($_SESSION['cargo'],'7') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

include("/opt/e-smith/conf_principal.php");
include("../../funciones.php");
$connection = mysql_connect($db_host,$db_user,$db_pass) or die ("Imposible conectar con la Base de datos");
mysql_select_db($db) or die ("Imposible seleccionar base de datos!");

if($enviar =="Enviar los datos de la Matr�cula"){
	$cargo = "1";
	$opt41=array("Alem�n2_1", "Franc�s2_1", "Informatica_1");
	$opt42=array("Alem�n2_2", "Franc�s2_2", "Informatica_2", "EdPl�stica_2");
	$opt43=array("Alem�n2_3", "Franc�s2_3", "Informatica_3", "EdPl�stica_3");
	$opt44=array("Alem�n2_4", "Franc�s2_4", "Tecnolog�a_4");
	$nacimiento = str_replace("/","-",$nacimiento);
	$fecha0 = explode("-",$nacimiento);
	$fecha_nacimiento = "$fecha0[2]-$fecha0[1]-$fecha0[0]";
	$campos = "apellidos nombre nacido provincia nacimiento domicilio localidad padre dnitutor telefono1 telefono2 religion colegio optativa1 optativa2 optativa3 optativa4 sexo nacionalidad ";
	if (substr($curso,0,1)>1) {
		$campos.="optativa21 optativa22 optativa23 optativa24 ";
		if (substr($curso,0,1)=='4') {
		$campos.="optativa25 optativa26 optativa27 ";
	}
	}
	if (substr($curso,0,1)=='3') {
		$campos.="optativa5 optativa6 optativa7 ";
	}
	if (substr($curso,0,1)>3) {
		$campos.="itinerario ";
		
	if ($itinerario == '1' or $itinerario == '4') {
		$campos = str_replace($campos, "optativa4 ", "");
	}
	}

	foreach($_POST as $key => $val)
	{
		if(strstr($campos,$key." ")==TRUE){
			if($val == ""){
				$vacios.= $key.", ";
				$num+=1;
			}
		}
		
	}
	
	if ($itinerario) {
		foreach (${opt4.$itinerario} as $opt){
			foreach ($_POST as $clave=>$valor){
				if (strstr($clave,$opt)==TRUE) {
					$n_o+=1;
					${optativa.$n_o}=$valor;
					if(${optativa.$n_o} == ""){
					$vacios.= "optativa".$n_o.", ";
					$num+=1;
				}
				}									
			}
		}
		}
	if ($itinerario == '3' and empty($matematicas4)) {
		  $vacios.= "matematicas, ";
		  $num+=1;
	}
	if ($religion == "") {
		$vacios.= "religion, ";
		$num+=1;
	}
	if ($sexo == "") {
		$vacios.= "sexo, ";
		$num+=1;
	}
	// Control de errores
	if($num > 0){
		$adv = substr($vacios,0,-2);
		echo '
<script> 
 alert("Los siguientes datos son obligatorios y no los has rellenado en el formulario de inscripci�n:\n ';
		$num_cur = substr($curso,0,1);
		$num_cur_ant = $num_cur - 1;
		$cur_act = substr($curso,0,1)."� de ESO";
		$cur_ant = $num_cur_ant . "� de ESO";		
		for ($i=1;$i<8;$i++){
			$adv= str_replace("optativa2$i", "optativa de $cur_ant $i", $adv);
		}
		for ($i=1;$i<5;$i++){
			$adv= str_replace("optativa$i", "optativa de $cur_act  $i", $adv);
		}
		echo $adv.'.\n';
		echo 'Rellena los campos mencionados y env�a los datos de nuevo para poder registrar tu solicitud correctamente.")
 </script>
';
	}
	else{
		if (substr($curso,0,1)<5){
		for ($i = 1; $i < 8; $i++) {
				for ($z = $i+1; $z < 8; $z++) {
					if (${optativa.$i}>0) {
					if (${optativa.$i}==${optativa.$z}) {
$opt_rep="1";
				}
					}
								
				}
			}
		}
	if (substr($curso,0,1)<5){
		for ($i = 1; $i < 8; $i++) {
				for ($z = $i+1; $z < 8; $z++) {
					if (${optativa.$i}>0) {
					if (${optativa.$i}==${optativa.$z}) {
$opt_rep="1";
				}
					}
								
				}
			}
		}
	if (substr($curso,0,1)>1){
		for ($i = 1; $i < 8; $i++) {
				for ($z = $i+1; $z < 8; $z++) {
					if (${optativa2.$i}>0) {
					if (${optativa2.$i}==${optativa2.$z}) {
$opt_rep2="1";
				}
					}
								
				}
			}
		}
		if($colegio == "Otro Centro" and ($otrocolegio == "" or $otrocolegio == "Escribe aqu� el nombre del Centro")){
			$vacios.="otrocolegio ";
			echo '
<script> 
 alert("No has escrito el nombre del Centro del que procede el alumno.\n';
			echo 'Rellena el nombre del Centro y env�a los datos de nuevo para poder registrar tu solicitud correctamente.")
 </script>
';
		}
		elseif(strstr($nacimiento,"-") == FALSE){
			echo '
<script> 
 alert("ATENCI�N:\n ';
			echo 'La fecha de nacimiento que has escrito no es correcta.\nEl formato adecuado para la fecha  es: dia-mes-a�o (01-01-1998).")
 </script>
';
		}
		elseif(strlen($ruta_este) > 0 and strlen($ruta_oeste) > 0){
			echo '
<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado dos rutas incompatibles para el Transporte Escolar, y s�lo puedes seleccionar una ruta, hacia el Este o hacia el Oeste de Estepona.\nElige una sola parada y vuelve a enviar los datos.")
 </script>
';
			$ruta_error = "";
		}
	elseif(strlen($ruta_este) > 0 and strlen($ruta_oeste) > 0){
			echo '
<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado dos rutas incompatibles para el Transporte Escolar, y s�lo puedes seleccionar una ruta, hacia el Este o hacia el Oeste de Estepona.\nElige una sola parada y vuelve a enviar los datos.")
 </script>
';
			$ruta_error = "";
		}
	elseif ($opt_rep=="1"){
					echo '
						<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado el mismo n�mero de preferencia para varias optativas, y cada optativa debe tener un n�mero de preferencia distinto.\nElige las optativas sin repetir el n�mero de preferencia e int�ntalo de nuevo.")
 </script>
';
		}
	elseif ($opt_rep2=="1"){
					echo '
						<script> 
 alert("ATENCI�N:\n';
			echo 'Parece que has seleccionado el mismo n�mero de preferencia para varias optativas del curso anterior, y cada optativa debe tener un n�mero de preferencia distinto.\nElige las optativas del curso anterior sin repetir el n�mero de preferencia e int�ntalo de nuevo.")
 </script>
';
		}
		else{
			if (strlen($claveal) > 3) {$extra = " claveal = '$claveal'";}
			elseif (strlen($dni) > 3) {$extra = " dni = '$dni'";}
			else {$extra = " dnitutor = '$dnitutor' ";}

			// El alumno ya se ha registrado anteriormente
			$ya_esta = mysql_query("select id from matriculas where $extra");
			if (mysql_num_rows($ya_esta) > 0) {
				$ya = mysql_fetch_array($ya_esta);
				if (strlen($ruta_este) > 0 or strlen($ruta_oeste) > 0) {$transporte = '1';}
				if(!($itinerario=='3')){$matematicas4="";}
				mysql_query("update matriculas set apellidos='$apellidos', nombre='$nombre', nacido='$nacido', provincia='$provincia', nacimiento='$fecha_nacimiento', domicilio='$domicilio', localidad='$localidad', dni='$dni', padre='$padre', dnitutor='$dnitutor', madre='$madre', dnitutor2='$dnitutor2', telefono1='$telefono1', telefono2='$telefono2', religion='$religion', colegio='$colegio', optativa1='$optativa1', optativa2='$optativa2', optativa3='$optativa3', optativa4='$optativa4', otrocolegio='$otrocolegio', letra_grupo='$letra_grupo', idioma='$idioma',  religion = '$religion', act1='$act1', observaciones='$observaciones', exencion='$exencion', bilinguismo='$bilinguismo', observaciones = '$observaciones', optativa21='$optativa21', optativa22='$optativa22', optativa23='$optativa23', optativa24='$optativa24', act21='$act21', act22='$act22', act23='$act23', act24='$act24', promociona='$promociona', transporte='$transporte', ruta_este='$ruta_este', ruta_oeste='$ruta_oeste', curso='$curso', sexo = '$sexo', hermanos = '$hermanos', nacionalidad = '$nacionalidad', claveal = '$claveal', matematicas4 = '$matematicas4', itinerario = '$itinerario', optativa5='$optativa5', optativa6='$optativa6', optativa7='$optativa7', diversificacion='$diversificacion', optativa25='$optativa25', optativa26='$optativa26', optativa27='$optativa27' where id = '$ya[0]'");
			}
			else{
	
if (strlen($ruta) > 0) {$transporte = '1';}
mysql_query("insert into matriculas (apellidos, nombre, nacido, provincia, nacimiento, domicilio, localidad, dni, padre, dnitutor, madre, dnitutor2, telefono1, telefono2, colegio, otrocolegio, letra_grupo, correo, idioma, religion, optativa1, optativa2, optativa3, optativa4, act1, observaciones, curso, exencion, bilinguismo, fecha, optativa21, optativa22, optativa23, optativa24, act21, act22, act23, act24, promociona, transporte, ruta_este, ruta_oeste, sexo, hermanos, nacionalidad, claveal, matematicas4, itinerario, optativa5, optativa6, optativa7, diversificacion, optativa25, optativa26, optativa27) VALUES ('$apellidos',  '$nombre', '$nacido', '$provincia', '$fecha_nacimiento', '$domicilio', '$localidad', '$dni', '$padre', '$dnitutor', '$madre', '$dnitutor2', '$telefono1', '$telefono2', '$colegio', '$otrocolegio', '$letra_grupo', '$correo', '$idioma', '$religion', '$optativa1', '$optativa2', '$optativa3', '$optativa4', '$act1', '$observaciones', '$curso', '$exencion', '$bilinguismo', now(), '$optativa21', '$optativa22', '$optativa23', '$optativa24', '$act21', '$act22', '$act23', '$act24', '$promociona', '$transporte', '$ruta_este', '$ruta_oeste', '$sexo', '$hermanos', '$nacionalidad', '$claveal', '$matematicas4', '$itinerario', '$optativa5', '$optativa6', '$optativa7', '$diversificacion', '$optativa25', '$optativa26', '$optativa27')");
			}
			$ya_esta1 = mysql_query("select id from matriculas where $extra");
			$ya_id = mysql_fetch_array($ya_esta1);
			$id = $ya_id[0];
			if ($nuevo=="1") {
				include("imprimir.php");
			}
			else{
			?>
            <link href="http://iesmonterroso.org/estilo.css" rel="stylesheet" type="text/css" media="screen" />
			<div class="aviso3" align="left" style="padding:20px; margin-top:40px; line-height:20px;">Los datos de la Matr�cula se han registrado correctamente en la Base de datos,<br><br>
			<center><a href="./index.php">Volver al p�gina de Matr�culas</a></center></div>
            <?
			}
			exit();
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Matr�culas</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<LINK href="http://<? echo $dominio; ?>/<? echo $css1; ?>" rel="stylesheet" type="text/css">
<LINK href="http://<? echo $dominio; ?>/<? echo $css2; ?>" rel="stylesheet" type="text/css">
<link rel="STYLESHEET" type="text/css" href="http://<? echo $dominio; ?>/intranet/css/imprimir.css" media="print"" media="print"> 

<style type="text/css">
<!--
table {
	width: 991px;
	border: 1px solid #aaa;
	border-collapse: collapse;
}

td {
	border: 1px solid #aaa
}
td .it{
	padding:4px 6px;
	border-bottom:1px dotted #ccc;
	border-top:1px dotted #ccc;
}
-->
</style>

 <script type="text/javascript">
function confirmacion() {
	var answer = confirm("ATENCI�N:\n Los datos que est�s a punto de enviar no pueden ser modificados m�s tarde a trav�s de esta p�gina. \nSi est�s seguro que los datos son correctos y las opciones elegidas son las adecuadas, pulsa el bot�n ACEPTAR. De lo contrario, el boton CANCELAR te devuelve al formulario de matriculaci�n, donde podr�s realizar los cambios que consideres oportunos.")
	if (answer){
return true;
	}
	else{
return false;
	}
}
</script>
 
<script language="javascript">

function dimePropiedades(){ 
   	var indice = document.form1.colegio.selectedIndex 
   	var textoEscogido = document.form1.colegio.options[indice].text 
   	if(textoEscogido == "Otro Centro"){
    document.getElementById('otrocolegio').style.visibility='visible'; 
	}
	 }
	 
function borrar()
{
document.form1.otrocolegio.value = "";
}

function contar(form,name) {
	  n = document.forms[form][name].value.length;
	  t = 300;
	  if (n > t) {
	    document.forms[form][name].value = document.forms[form][name].value.substring(0, t);
	  }
	  else {
	    document.forms[form]['result'].value = t-n;
	  }
	}
	
</script>
</head>

<body>

<?

$cargo="1";

// Rellenar datos a partir de las tablas alma o matriculas.

if ($dni or $claveal or $id) {

	if (!empty($id)) {
		$conditio = " id = '$id'";
	}
	else{
		if (strlen($claveal) > 3) {$conditio = " claveal = '$claveal'"; $conditio1 = $conditio;}else{$conditio = " dni = '$dni' or dnitutor = '$dni' "; $conditio1 = " dni = '$dni' or dnitutor = '$dni' ";}
	}

	$curso = str_replace(" ","",$curso);	
	// Comprobaci�n de padre con varios hijos en el Centro
	$ya_matricula = mysql_query("select claveal, apellidos, nombre, id from matriculas where ". $conditio ."");
	$ya_primaria = mysql_query("select claveal, apellidos, nombre from alma_primaria where ". $conditio1 ."");
	$ya_alma = mysql_query("select claveal, apellidos, nombre, unidad from alma where (nivel='1E' or nivel='2E' or nivel='3E' or nivel='4E') and (". $conditio1 .")");

	// Comprobaci�n de padre con varios hijos en el Centro
	if (mysql_num_rows($ya_alma) > 1) {
		?>
<form id="form2" name="form1" method="post"
	action="matriculas.php<? if($cargo == "1"){echo "?cargo=1";}?>">
<div class="aviso3" align="left">Elige el Alumno que quieres matricular
en nuestro Centro:
<div style="height: 5px;"></div>
		<?
		while ($row_alma = mysql_fetch_array($ya_alma)) {
			?> <input type="radio" name="claveal"
	value="<? echo $row_alma[0]; ?>"
	style="margin: 6px 2px; line-height: 18px; vertical-align: top;"
	onclick="submit()" />
<div
	style="color: brown; line-height: 18px; display: inline; vertical-align: top;"><? echo $row_alma[2]." ".$row_alma[1]; ?></div>
<br />
			<?
		}
		?></div>
		<?
if (substr($row_alma[3],0,2)=="1E"){$curso="2ESO";}
if (substr($row_alma[3],0,2)=="2E"){$curso="3ESO";}
if (substr($row_alma[3],0,2)=="3E"){$curso="4ESO";}
if (substr($row_alma[3],0,2)=="4E"){$curso="4ESO";}
		?>
<input type="hidden" name="curso" value="<? echo $curso;?>" /> <?
echo "</form></div>";
exit();
	}

	// Comprobaci�n de padre con varios hijos en Primaria
	if (mysql_num_rows($ya_primaria) > 1) {
		?>
<form id="form2" name="form1" method="post"
	action="matriculas.php<? if($cargo == "1"){echo "?cargo=1";}?>">
<div class="aviso3" align="left">Elige el Alumno que quieres matricular
en nuestro Centro:
<div style="height: 5px;"></div>
		<?
		while ($row_alma = mysql_fetch_array($ya_primaria)) {
			?> <input type="radio" name="claveal"
	value="<? echo $row_alma[0]; ?>"
	style="margin: 6px 2px; line-height: 18px; vertical-align: top;"
	onclick="submit()" />
<div
	style="color: brown; line-height: 18px; display: inline; vertical-align: top;"><? echo $row_alma[2]." ".$row_alma[1]; ?></div>
<br />
			<?
		}
		?></div>
<input type="hidden" name="curso" value="<? echo $curso;?>" /> <?
echo "</form></div>";
exit();
	}

	// Comprobaci�n de padre con varios hijos en la tabla de matr�culas
	if (mysql_num_rows($ya_matricula) > 1) {
		?>
<form id="form2" name="form1" method="post"
	action="matriculas.php<? if($cargo == "1"){echo "?cargo=1";}?>">
<div class="aviso3" align="left">Elige el Alumno que quieres matricular
en nuestro Centro:
<div style="height: 5px;"></div>
		<?
		while ($row_alma = mysql_fetch_array($ya_matricula)) {
			?> <input type="radio" name="claveal"
	value="<? echo $row_alma[0]; ?>"
	style="margin: 6px 2px; line-height: 18px; vertical-align: top;"
	onclick="submit()" />
<div
	style="color: brown; line-height: 18px; display: inline; vertical-align: top;"><? echo $row_alma[2]." ".$row_alma[1]; ?></div>
<br />
			<?
		}
		?></div>
<input type="hidden" name="curso" value="<? echo $curso;?>" /> <?
echo "</form></div>";
exit();
	}


	// Comprobamos si el alumno se ha registrado ya
	$ya = mysql_query("select apellidos, id, nombre, nacido, provincia, nacimiento, domicilio, localidad, dni, padre, dnitutor, madre, dnitutor2, telefono1, telefono2, colegio, optativa1, optativa2, optativa3, optativa4, correo, exencion, bilinguismo, otrocolegio, letra_grupo, religion, observaciones, act1, act2, act3, act4, optativa21, optativa22, optativa23, optativa24, act21, act22, act23, act24, promociona, transporte, ruta_este, otrocolegio, ruta_oeste, sexo, hermanos, nacionalidad, claveal, matematicas4, itinerario, optativa5, optativa6, optativa7, diversificacion, optativa25, optativa26, optativa27, curso from matriculas where ". $conditio ."");
	
	// Ya se ha matriculado
	if (mysql_num_rows($ya) > 0) {
		$datos_ya = mysql_fetch_array($ya);
		$naci = explode("-",$datos_ya[5]);
		$nacimiento = "$naci[2]-$naci[1]-$naci[0]";
		$apellidos = $datos_ya[0]; $id = $datos_ya[1]; $nombre = $datos_ya[2]; $nacido = $datos_ya[3]; $provincia = $datos_ya[4]; $domicilio = $datos_ya[6]; $localidad = $datos_ya[7]; $dni = $datos_ya[8]; $padre = $datos_ya[9]; $dnitutor = $datos_ya[10]; $madre = $datos_ya[11]; $dnitutor2 = $datos_ya[12]; $telefono1 = $datos_ya[13]; $telefono2 = $datos_ya[14]; $colegio = $datos_ya[15]; $optativa1 = $datos_ya[16]; $optativa2 = $datos_ya[17]; $optativa3 = $datos_ya[18]; $optativa4 = $datos_ya[19]; $correo = $datos_ya[20]; $exencion = $datos_ya[21]; $bilinguismo = $datos_ya[22]; $otrocolegio = $datos_ya[23]; $letra_grupo = $datos_ya[24]; $religion = $datos_ya[25]; $observaciones = $datos_ya[26]; $act1 = $datos_ya[27]; $act2 = $datos_ya[28]; $act3 = $datos_ya[29]; $act4 = $datos_ya[30]; $optativa21 = $datos_ya[31]; $optativa22 = $datos_ya[32]; $optativa23 = $datos_ya[33]; $optativa24 = $datos_ya[34]; $act21 = $datos_ya[35]; $act22 = $datos_ya[36]; $act23 = $datos_ya[37]; $act24 = $datos_ya[38]; $promociona = $datos_ya[39]; $transporte = $datos_ya[40]; $ruta_este = $datos_ya[41]; $otrocolegio = $datos_ya[42]; $ruta_oeste = $datos_ya[43]; $sexo = $datos_ya[44]; $hermanos = $datos_ya[45]; $nacionalidad = $datos_ya[46]; $claveal = $datos_ya[47]; $matematicas4 = $datos_ya[48]; $itinerario = $datos_ya[49]; $optativa5 = $datos_ya[50];$optativa6 = $datos_ya[51];$optativa7 = $datos_ya[52]; $diversificacion = $datos_ya[53];$optativa25 = $datos_ya[54];$optativa26 = $datos_ya[55];$optativa27 = $datos_ya[56]; $curso = $datos_ya[57];
		$n_curso = substr($curso,0,1);
		if ($ruta_error == '1') {
			$ruta_este = "";
			$ruta_oeste = "";
		}
	}

	// Viene de Colegio de Primaria
	elseif (mysql_num_rows($ya_primaria) > 0){
		$alma = mysql_query("select apellidos, nombre, provinciaresidencia, fecha, domicilio, localidad, dni, padre, dnitutor, concat(PRIMERAPELLIDOTUTOR2,' ',SEGUNDOAPELLIDOTUTOR2,', ',NOMBRETUTOR2), dnitutor2, telefono, telefonourgencia, correo, concat(PRIMERAPELLIDOTUTOR,' ',SEGUNDOAPELLIDOTUTOR,', ',NOMBRETUTOR), curso, sexo, nacionalidad, grupo, claveal, colegio from alma_primaria where ". $conditio1 ."");

		if (mysql_num_rows($alma) > 0) {
			$al_alma = mysql_fetch_array($alma);
			$apellidos = $al_alma[0];  $nombre = $al_alma[1]; $nacido = $al_alma[5]; $provincia = $al_alma[2]; $nacimiento = $al_alma[3]; $domicilio = $al_alma[4]; $localidad = $al_alma[5]; $dni = $al_alma[6]; $padre = $al_alma[7]; $dnitutor = $al_alma[8];
			if (strlen($al_alma[9]) > 3) {$madre = $al_alma[9];	}else{ $madre = ""; }
			; $dnitutor2 = $al_alma[10]; $telefono1 = $al_alma[11]; $telefono2 = $al_alma[12]; $correo = $al_alma[13]; $padre = $al_alma[14];
			$n_curso_ya = $al_alma[15]; $sexo = $al_alma[16]; $nacionalidad = $al_alma[17]; $letra_grupo = $al_alma[18]; $claveal= $al_alma[19]; $colegio= $al_alma[20];
			$nacimiento= str_replace("/","-",$nacimiento);
			$curso="1ESO";
			$n_curso=substr($curso, 0, 1);
		}
	}

	// Es alumno del Centro
	elseif (mysql_num_rows($ya_alma) > 0){
		$alma = mysql_query("select apellidos, nombre, provinciaresidencia, fecha, domicilio, localidad, dni, padre, dnitutor, concat(PRIMERAPELLIDOTUTOR2,' ',SEGUNDOAPELLIDOTUTOR2,', ',NOMBRETUTOR2), dnitutor2, telefono, telefonourgencia, correo, concat(PRIMERAPELLIDOTUTOR,' ',SEGUNDOAPELLIDOTUTOR,', ',NOMBRETUTOR), curso, sexo, nacionalidad, grupo, claveal, unidad from alma where (nivel='1E' or nivel='2E' or nivel='3E' or nivel='4E') and (". $conditio1 .")");
		if (mysql_num_rows($alma) > 0) {
			$al_alma = mysql_fetch_array($alma);

			if (substr($al_alma[20],0,2)=="1E"){$curso="2ESO";}
			if (substr($al_alma[20],0,2)=="2E"){$curso="3ESO";}
			if (substr($al_alma[20],0,2)=="3E"){$curso="4ESO";}
			if (substr($al_alma[20],0,2)=="4E"){$curso="4ESO";}
			$n_curso = substr($curso,0,1);

			$apellidos = $al_alma[0];  $nombre = $al_alma[1]; $nacido = $al_alma[5]; $provincia = $al_alma[2]; $nacimiento = $al_alma[3]; $domicilio = $al_alma[4]; $localidad = $al_alma[5]; $dni = $al_alma[6]; $padre = $al_alma[7]; $dnitutor = $al_alma[8];
			if ($madre == "") { if (strlen($al_alma[9]) > 3) {$madre = $al_alma[9];	}else{ $madre = ""; }}
			if ($dnitutor2 == "") { $dnitutor2 = $al_alma[10];} if ($telefono1 == "") { $telefono1 = $al_alma[11]; } if ($telefono2 == "") { $telefono2 = $al_alma[12];} if ($correo == "") { $correo = $al_alma[13];} $padre = $al_alma[14];
			$n_curso_ya = $al_alma[15]; $sexo = $al_alma[16]; $nacionalidad = $al_alma[17]; $letra_grupo = $al_alma[18]; $claveal= $al_alma[19];
				
			if (substr($curso,0,1) == substr($n_curso_ya,0,1)) {
				echo '
<script> 
 if(confirm("ATENCI�N:\n ';
				echo 'Has elegido matricularte en el mismo Curso( ';
				echo strtoupper($n_curso_ya);
				echo ') que ya has estudiado este a�o. \nEsta situaci�n s�lo puede significar que est�s absolutamente seguro de que vas a repetir el mismo Curso. Si te has equivocado al elegir Curso para el pr�ximo a�o escolar, vuelve atr�s y selecciona el curso correcto. De lo contrario, puedes continuar.")){}else{history.back()};
 </script>
 
';
				$repetidor = '1';
			}
			$nacimiento= str_replace("/","-",$nacimiento);
			$colegio = 'IES Monterroso';
		}
	}
	$opt1 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Tecnolog�a Aplicada");
	$opt2 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","M�todos de la Ciencia");
	$opt3 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Cultura Cl�sica", "Taller T.I.C. III", "Taller de Cer�mica", "Taller de Teatro");
	$a1 = array("Actividades de refuerzo de Lengua Castellana", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C.", "Ampliaci�n: Taller de Teatro");
	$a2 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C. II", "Ampliaci�n: Taller de Teatro II");

		?> <br />
<div style="width: 990px; margin: auto; border: 2px solid #aaa"><img
	src="../../imag/encabezado.jpg" width="988" height="80"
	style="border-left: 1px solid #aaa;" />

<table align="center">
	<form id="form1" name="form1" method="post"
		action="matriculas.php<? if($cargo == "1"){echo "?cargo=1";}?>">

	<tr>
		<td colspan="3">
		<?
	if (empty($n_curso)){$n_curso = substr($curso,0,1);}
	if ($curso=="1ESO") {$curso_matricula="PRIMERO";}
	if ($curso=="2ESO") {$curso_matricula="SEGUNDO";}
	if ($curso=="3ESO") {$curso_matricula="TERCERO";}
	if ($curso=="4ESO") {$curso_matricula="CUARTO";}
		?>
		<p align=center
			style='text-align: center; font-size: 16px; font-weight: bold; line-height: 50px;'><b>SOLICITUD
		DE MATR&Iacute;CULA EN <? echo $curso_matricula; ?> DE E.S.O.</b></p>
		</td>
	</tr>

	<?
	if (substr($curso, 0, 1) > 1 and $cargo == '1') {
		?>
	<tr>
		<td colspan="3"
			style="text-align: center; font-size: 14px; font-weight: bold; background-color: #E0E0E0; height: 24px;">
		<b>Promoci�n a <?php echo $n_curso;?>� de ESO</b></td>
	</tr>
	<tr>
		<td valign=top colspan="2" style="line-height: 24px">El alumno <strong>promociona</strong>
		por la siguiente raz�n:<br />
		<input type="radio" name="promociona"
		<? if ($promociona=='1') {echo "checked"; }  ?> value='1'
			style="margin: 2px 2px" /> Tener 0, 1 o 2 suspensos<br> <input
			type="radio" name="promociona"
			<? if ($promociona=='2') {echo "checked"; }  ?> value='2'
			style="margin: 2px 2px" /> Repetir este a�o <? echo substr($curso, 0, 1); ?>� de ESO <br />
		
		</td>
		<td valign=top style="line-height: 24px">El alumno <strong>no
		promociona</strong> por la siguiente raz�n: </br>
		<input type="radio" name="promociona"
		<? if ($promociona=='3') {echo "checked"; }  ?> value='3'
			style="margin: 2px 2px"?> Tener m�s de 2 asignaturas suspensas</td>
	</tr>
	<?
	}
	?>

	<tr>
		<td colspan="3"
			style='text-align: center; font-size: 14px; font-weight: bold; background-color: #E0E0E0; height: 24px;'>
		<b>Datos personales del alumno y Representantes Legales</b></td>
	</tr>
	<tr>
		<td valign=top width="33%" colspan="2"><strong>Apellidos del alumno/a:<br />
		<input type="text" name="apellidos"
		<? echo "value = \"$apellidos\""; ?> size="32"
		<? if(strstr($vacios,"apellidos, ")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		</strong></td>
		<td valign=top width="33%"><strong>Nombre</strong>:<br />
		<input type="text" name="nombre" <? echo "value = \"$nombre\""; ?>
			size="24"
			<? if(strstr($vacios,"nombre, ")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		</td>
	</tr>
	<tr>
		<td valign=top><strong>Nacido en:<br />
		<input type="text" name="nacido" <? echo "value = \"$nacido\""; ?>
			size="32"
			<? if(strstr($vacios,"nacido, ")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		</strong></td>
		<td valign=top><strong>Provincia de</strong>:<br />
		<input type="text" name="provincia"
		<? echo "value = \"$provincia\""; ?> size="32"
		<? if(strstr($vacios,"provincia,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		</td>
		<td valign=top><strong>Fecha de nacimiento </strong>(dia-mes-a�o:
		01-01-1998)<br />
		<input type="text" name="nacimiento"
		<? echo "value = \"$nacimiento\""; ?> size="10" maxlength="10"
		<? if(strstr($vacios,"nacimiento,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		</td>
	</tr>
	<tr>
		<td align=top><strong>Domicilio</strong>:<br />
		<input type="text" name="domicilio"
		<? echo "value = \"$domicilio\""; ?> size="32"
		<? if(strstr($vacios,"domicilio,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		</td>
		<td valign=top><strong>Localidad</strong>:<br />
		<input type="text" name="localidad"
		<? echo "value = \"$localidad\""; ?> size="32"
		<? if(strstr($vacios,"localidad,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		</td>
		<td valign=top><strong>D.N.I.</strong><br />
		<input type="text" name="dni" <? echo "value = \"$dni\""; ?> size="13"
			maxlength="13"
			<? if(strstr($vacios,"dni,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		</td>
	</tr>
	<tr>
		<td valign="middle"><strong>Sexo</strong>:<br />
		<input type="radio" name="sexo" value="mujer" style="margin: 2px 2px"
			<? if($sexo == 'mujer' or $sexo == 'M'){echo "checked";} ?> /> Mujer
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio"
			name="sexo" value="hombre" style="margin: 2px 2px"
			<? if($sexo == 'hombre' or $sexo == 'H'){echo "checked";} ?> />
		Hombre <br />
		</td>
		<td valign=top><strong>N� de Hermanos</strong>:<br />
		<input type="text" name="hermanos" <? echo "value = \"$hermanos\""; ?>
			size="4" /> <br />
		</td>
		<td valign=top><strong>Nacionalidad</strong><br />
		<input type="text" name="nacionalidad"
		<? echo "value = \"$nacionalidad\""; ?> size="24"
		<? if(strstr($vacios,"nacionalidad,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		</td>
	</tr>
	<tr>
		<td valign=top colspan="2"><strong>Apellidos y nombre del
		Representante o Guardador legal 1</strong>(<span
			style="font-weight: normal;">con quien convive el alumno</span>):<br />
		<input type="text" name="padre" <? echo "value = \"$padre\""; ?>
			size="52"
			<? if(strstr($vacios,"padre,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		</td>
		<td valign=top><strong>DNI</strong>:<br />
		<input type="text" name="dnitutor" <? echo "value = \"$dnitutor\""; ?>
			size="13" maxlength="13"
			<? if(strstr($vacios,"dnitutor,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		<br />
		</td>
	</tr>
	<tr>
		<td valign=top colspan="2"><strong>Apellidos y nombre del
		Representante o Guardador legal 2</strong>:<br />
		<input type="text" name="madre" <? echo "value = \"$madre\""; ?>
			size="52" /></td>
		<td valign=top><strong>DNI</strong>:<br />
		<input type="text" name="dnitutor2"
		<? echo "value = \"$dnitutor2\""; ?> size="13" maxlength="13" /> <br />
		</td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2" valign=top><strong>Tel&eacute;fono casa</strong>:
		<input type="text" name="telefono1"
		<? echo "value = \"$telefono1\""; ?> size="10"
		<? if(strstr($vacios,"telefono1,")==TRUE){echo ' style="background-color:#FFFF66;"';}?> />
		<br />
		<strong>Tel&eacute;fono m&oacute;vil padres</strong>: <input type="text" name="telefono2" size="10" <? echo "value = \"$telefono2\""; ?> style="margin-top:6px;<? if(strstr($vacios,"telefono2")==TRUE){echo 'background-color:#FFFF66;';}?>" />
		<br />
		Nota: Es muy importante registrar un tel&eacute;fono m&oacute;vil para
		poder recibir comunicaciones del Centro v&iacute;a SMS.</td>
		<td valign=top align="center"><strong>Centro Escolar de procedencia:</strong><br />
		<select name="colegio" id="" onChange="dimePropiedades()">
			<option><? echo $colegio; ?></option>
			<?
			if ($curso == "1ESO") {
				?>
			<option>SANTO-TOMAS</option>
			<option>SIMON-FERNANDEZ</option>
			<option>GARCIA-LORCA</option>
			<option>JUAN-XXIII</option>
			<option>Otro Centro</option>
			<?
			}
			else{
				echo "<option>IES Monterroso</option>
      <option>Otro Centro</option>";            	
			}
			?>
		</select> <br />
		<center><input style="<?  if ($colegio == 'Otro Centro') {	echo "visibility:visible;";}else{	echo "visibility:hidden;background-color:#FFFF66;";}?>margin-top:6px;" id = "otrocolegio" name="otrocolegio" <? if(!($otrocolegio == 'Escribe aqu� el nombre del Centro')){echo "value = \"$otrocolegio\"";} ?>type="text" size="32" value="Escribe aqu� el nombre del Centro" onClick="borrar()" /></center>
		<input type="hidden" name="letra_grupo"
		<? echo "value = \"$letra_grupo\""; ?> /></td>
	</tr>

	<tr>
		<td valign=top colspan=""><span style="margin: 2 ' x 2px;"><strong>Correo
		electr&oacute;nico padres</strong>:</span> <input type="text"
			name="correo" <? echo "value = \"$correo\""; ?> size="48" /></td>
	</tr>

	<tr>
		<td style="background-color: #E0E0E0; height: 24px;" colspan="3"
			align="center"><strong>Solicitud de Transporte Escolar</strong></td>
	</tr>
	<tr>
		<td valign=top colspan='3'>
		<center>Ruta Este: <select name="ruta_este">
			<option><?php  echo $ruta_este;?></option>
			<option>Urb. Mar y Monte</option>
			<option>Urb. Diana - Isdabe</option>
			<option>Benamara - Benavista</option>
			<option>Bel Air</option>
			<option>Parada Bus Portillo Cancelada</option>
			<option>Parque Antena</option>
			<option>El Pirata</option>
			<option>El Veler�n</option>
			<option>El Padr�n</option>
			<option>Mc Donald's</option>
		</select> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ruta Oeste: <select
			name="ruta_oeste">
			<option><?php  echo $ruta_oeste;?></option>
			<option>Buenas Noches</option>
			<option>Costa Galera</option>
			<option>Bah�a Dorada</option>
			<option>Don Pedro</option>
			<option>Bah�a Azul</option>
			<option>G. Shell - H10</option>
			<option>Seghers Bajo (Babylon)</option>
			<option>Seghers Alto (Ed. Sierra Bermeja)</option>
		</select></center>
		</td>
	</tr>
	<tr>
		<td style="background-color: #E0E0E0; height: 24px;"><strong>Idioma
		Extranjero</strong></td>
		<td colspan="2" style="background-color: #E6E6E6; height: 24px;"><strong>OPCI&Oacute;N
		DE ENSE&Ntilde;ANZA DE RELIGI&Oacute;N O ALTERNATIVA </strong>(se&ntilde;ale
		una)</td>
	</tr>
	<tr>
		<td valign=top style="width: 332px;"><input type="text" name="idioma"
			value="Ingl�s" readonly size="8" /><br />
		Materia Obligatoria</td>
		<td valign=top style="width: 332px;"><input type="radio"
			name="religion" value="Religi�n Catolica" style="margin: 2px 2px"
		<? if($religion == 'Religi�n Catolica'){echo "checked";} ?> />
		Religi&oacute;n Cat&oacute;lica <br />
		<input type="radio" name="religion" value="Religi�n Isl�mica"
			style="margin: 2px 2px"
		<? if($religion == 'Religi�n Isl�mica'){echo "checked";} ?> />
		Religi&oacute;n Isl&aacute;mica<br />
		<input type="radio" name="religion" value="Religi�n Jud�a"
			style="margin: 2px 2px"
		<? if($religion == 'Religi�n Jud�a'){echo "checked";} ?> />
		Religi&oacute;n Jud&iacute;a</td>
		<td valign=top style="width: 332px;"><input type="radio"
			name="religion" value="Religi�n Evang�lica" style="margin: 2px 2px"
		<? if($religion == 'Religi�n Evang�lica'){echo "checked";} ?> />
		Religi&oacute;n Evang&eacute;lica<br />
		<input type="radio"
			name="religion" value="Historia de las Religiones" style="margin: 2px 2px"
		<? if($religion == 'Historia de las Religiones'){echo "checked";} ?> />
		Historia de las Religiones<br />
		<input type="radio"
			name="religion" value="Atenci�n Educativa" style="margin: 2px 2px"
		<? if($religion == 'Atenci�n Educativa'){echo "checked";} ?> />
		Atenci�n Educativa</td>
	</tr>
	<?
	if ($n_curso < 3) {
		?>
	<tr>
		<td style="background-color: #CCCCCC; width: 332px;"><strong>Asignatura
		Optativa </strong><br />
		<span style="font-size: 9px;">(marca con 1, 2, 3, y 4 por orden de
		preferencia)</span></td>
		<td style="background-color: #CCCCCC; width: 666px;" align="center"
			colspan="2" valign="top"><strong>Programa de Refuerzo o Ampliaci�n</strong><br />
		<span style="font-size: 9px;">Se elige una asignatura de refuerzo si
		el alumno tiene asignaturas suspensas del curso anterior; se elige
		asignatura de ampliaci�n si el alumno pasa de curso sin suspensos. El
		Depto. de Orientaci�n decide finalmente.</span></td>
	</tr>
	<tr>
		<td <? if ($opt_rep == "1") {
			echo " style='background-color:yellow;'";
		} ?>><?
		$num1="";
		for ($i = 1; $i < 5; $i++) {
			if (substr($curso, 0, 1) == $i) {
				foreach (${opt.$i} as $opt_1){
					$num1+=1;
					echo '<select name="optativa'.$num1.'" id="optativa'.$num1.'" >';
					
					echo '<option>'.${optativa.$num1}.'</option>';
					for ($z=1;$z<5;$z++){
						echo '<option>'.$z.'</option>';
					}
					echo '</select>';
					echo '<span style="margin:2px 2px" >'.$opt_1.'</span><br />';
				}
			}
		}
		?></td>
		<td valign=top bgcolor="#E6E6E6" colspan="2"><? 
		$num1="";
		for ($i = 1; $i < 5; $i++) {
			if (substr($curso, 0, 1) == $i) {
				foreach (${a.$i} as $act_1){
					$n_a = count(${a.$i})+1;
					$num1+=1;
					if (${act.$num1} == '0') {${act.$num1}='';}
					//    if (mysql_num_rows($ya_primaria) > '0' or $cargo == '1' ) { $sl = "";}else{$sl = " disabled ";}
					echo '<input type="radio" name = "act1" value="'.$num1.'"'; echo $sl; echo ' style="margin:2px 2px"';
					if($act1 == $num1){echo "checked";}
					echo " />";
					echo '<span style="margin:2px 2px" >'.$act_1.'</span><br />';
				}
			}
		}
		?></td>
	</tr>
	<?
	}
elseif ($n_curso == 3) {
		?>
	<tr>
		<td colspan="3" style="background-color: #CCCCCC;"  align="center"><strong>Asignaturas
		Optativas de 3� de ESO</strong><br />
		<span style="font-size: 9px;">(marca con 1, 2, 3, 4, etc. por orden de
		preferencia)</span></td>
	</tr>
	<tr>
		<td colspan="1"style='border-right:none;<? if ($opt_rep == "1") {
			echo "background-color:yellow;";
		} ?>'><?
		$num1="";
		for ($i = 1; $i < 8; $i++) {
			if (substr($curso, 0, 1) == $i) {
				foreach (${opt.$i} as $opt_1){
					$num1+=1;
					echo '<select name="optativa'.$num1.'" id="">';
					echo '<option>'.${optativa.$num1}.'</option>';
					for ($z=1;$z<8;$z++){
						echo '<option>'.$z.'</option>';
					}
					echo '</select>';
					echo '<span style="margin:2px 2px" >'.$opt_1.'</span><br />';
					if ($num1==4) {echo "</td><td colspan='2' style='border-left:none;";
					if ($opt_rep == "1") {
						echo "background-color:yellow;";
					}
					echo "' valign='top'>";}
				}
			}		
		}
		?>
	</td></tr>
	<?
	}

	else{
		// Peculiaridades de 4� de ESO
		?>
	<tr>
		<td style="background-color: #CCCCCC;" colspan="3" align="center"><strong>ELECCI�N DE
		ASIGNATURAS OPTATIVAS DE 4� DE ESO </strong><br />
		<span style="font-size: 9px;">(Debes marcar un Itinerario y luego seleccionar las asignaturas optativas ofrecidas para el mismo en su orden de preferencia: 1, 2, 3, etc.)</span></td>
	</tr>

	<?
	$it41 = array("(Bachillerato de Ciencias y Tecnolog�a - V�a de Ciencias de la Naturaleza y la Salud)", "F�sica y Qu�mica", "Biolog�a y Geolog�a", "Matem�ticas B", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Inform�tica");
	$it42 = array("(Bachillerato de Ciencias y Tecnolog�a - V�a de Ciencias e Ingenier�a)", "F�sica y Qu�mica", "Tecnolog�a", "Matem�ticas B", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Inform�tica", "Ed. Pl�stica y Visual");
	$it43 = array("(Bachillerato de Humanidades y Ciencias Sociales)", "Lat�n", "M�sica", "Matem�ticas A", "Matem�ticas B", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Inform�tica", "Ed. Pl�stica y Visual");
	$it44 = array("(Ciclos Formativos y Mundo Laboral)", "Inform�tica", "Ed. Pl�stica y Visual", "Matem�ticas A", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Tecnolog�a");
	
	$opt41=array("Alem�n2_1" => "Alem�n 2� Idioma", "Franc�s2_1" => "Franc�s 2� Idioma", "Informatica_1" => "Inform�tica");
	$opt42=array("Alem�n2_2" => "Alem�n 2� Idioma", "Franc�s2_2" => "Franc�s 2� Idioma", "Informatica_2" => "Inform�tica", "EdPl�stica_2" => "Ed. Pl�stica y Visual");
	$opt43=array("Alem�n2_3" => "Alem�n 2� Idioma", "Franc�s2_3" => "Franc�s 2� Idioma", "Informatica_3" => "Inform�tica", "EdPl�stica_3" => "Ed. Pl�stica y Visual");
	$opt44=array("Alem�n2_4" => "Alem�n 2� Idioma", "Franc�s2_4" => "Franc�s 2� Idioma", "Tecnolog�a_4" => "Tecnolog�a");
	echo " <tr><td colspan='3'>";
	echo "<table><tr>";
	for ($i = 1; $i < 5; $i++) {
		echo "<td align='center' width='25%' valign='top'><strong>";

		echo '<input type="radio" name="itinerario" value="'.$i.'" style="margin: 2px 2px" onClick="';
		foreach (${opt4.$i} as $optit_1 => $val_opt){
			echo 'document.form1.'.$optit_1.'.disabled = false;';
					/*$arriba = $i+1;	
					foreach (${opt4.$arriba} as $optit_2 => $val_opt2){
						echo 'document.form1.'.$optit_2.'.disabled = true;';
					}*/
					for ($m=1;$m<5;$m++){
						if($m<>$i) {		
					foreach (${opt4.$m} as $optit_3 => $val_opt3){
						echo 'document.form1.'.$optit_3.'.disabled = true;';
					}
						}
					}					
		}
		echo '"';
		if($itinerario == $i){echo " checked";} 
		echo ' /> ';
		echo " Itinerario $i</strong><br /><span style='font-size:9px;'>".${it4.$i}[0]."</span></td>";
	}
	echo "</tr><tr>";
	for ($i = 1; $i < 5; $i++) {
		echo "<td align='left' class='it'>".${it4.$i}[1]."<br />".${it4.$i}[2]."</td>";
	}
	echo "</tr><tr>";
	for ($i = 1; $i < 5; $i++) {
		echo "<td align='left' class='it'>";
		if ($i=='3') { 
			echo "<input type='radio' name = 'matematicas4' value='A' style='margin: 2px 2px' ";
			if ($matematicas4=="A") { echo "checked";}
			echo "/>".${it4.$i}[3]."<br /><input type='radio' name = 'matematicas4' value='B' style='margin: 2px 2px' ";
			if ($matematicas4=="B") { echo "checked";}
			echo "/>".${it4.$i}[4];	
		}
		else{ echo ${it4.$i}[3]; }	
		echo "</td>";
	}
	echo "</tr><tr>";
	for ($i = 1; $i < 5; $i++) {
		echo "<td align='left' class='it' valign='top'>";
		
		$num1="";
		$num_it=count(${opt4.$i});
		foreach (${opt4.$i} as $optit_1 => $nombre){
					$num1+=1;
					if (${optativa.$num1}=="0") {${optativa.$num1}="";}
					echo '<select name="'.$optit_1.'" id=""';
					if (!($itinerario == $i)) {
						echo 'disabled="disabled"';
					}
					echo '>';
					echo '<option>';
					if ($itinerario == $i) {
						echo ${optativa.$num1};
					}
					echo '</option>';
					for ($z=1;$z<$num_it+1;$z++){
						echo '<option>'.$z.'</option>';
					}
					echo '</select>';
					echo '<span style="margin:2px 2px" >'.$nombre.'</span><br />';
				}	 
			
		echo "</td>";
	}
	echo "</tr></table></td></tr>";
	}
	?> <?php
	if (substr($curso, 0, 1) > 1) {
		if ($n_curso == 4) {
		?>
	<tr>
		<td colspan="3" style="background-color: #CCCCCC;"  align="center"><strong>Asignaturas
		Optativas de 3� de ESO</strong><br />
		<span style="font-size: 9px;">(marca con 1, 2, 3, 4, etc. por orden de
		preferencia)</span></td>
	</tr>
	<tr>
		<td colspan="1"style='border-right:none;<? if ($opt_rep2 == "1") {
			echo "background-color:yellow";
		} ?>'><?
		$num1="";
		for ($i = 1; $i < 8; $i++) {
			if (substr($curso, 0, 1)-1 == $i) {
				foreach (${opt.$i} as $opt_1){
					$num1+=1;
					echo '<select name="optativa2'.$num1.'" id="">';
					echo '<option>'.${optativa2.$num1}.'</option>';
					for ($z=1;$z<8;$z++){
						echo '<option>'.$z.'</option>';
					}
					echo '</select>';
					echo '<span style="margin:2px 2px" >'.$opt_1.'</span><br />';
					if ($num1==4) {echo "</td><td colspan='2' style='border-left:none;";
					if ($opt_rep2 == "1") {
						echo "background-color:yellow";
					} 
					echo "' valign='top'>";}
				}
			}		
		}
		?>
	</td></tr>
	<?
	}
	else{
		?>
	<tr>
		<td colspan="3"
			style='text-align: center; font-size: 1.0em; font-weight: bold; background-color: 
		#E0E0E0;'>
		
		<b>ELECCI�N DE ASIGNATURAS OPTATIVAS DE <?php echo substr($curso, 0, 1) - 1;?>�
		DE ESO<br />
		(deben rellenarlo todos los alumnos, incluso si promocionan al curso
		siguiente)</b></td>
	</tr>
	<tr>
		<td style="background-color: #CCCCCC; width: 332px;"><strong>Asignatura
		Optativa </strong><br />
		<span style="font-size: 9px;">(marque con 1, 2, 3, y 4 por orden de
		preferencia)</span></td>
		<td style="background-color: #CCCCCC; width: 666px;" align="center"
			colspan="2" valign="top"><strong>Programa de refuerzo o alternativo</strong><br />
		<span style="font-size: 9px;">Estudios en funci�n del Informe de
		Tr&aacute;nsito elaborado por el Tutor y seleccionados por el Dept. de
		Orientaci�n.</span></td>
	</tr>
	<tr>
		<td<? if ($opt_rep2 == "1") {
			echo " style='background-color:yellow;'";
		} ?>><? 
		$num1="";
		for ($i = 1; $i < 5; $i++) {
			if ((substr($curso, 0, 1)-1) == $i) {
				foreach (${opt.$i} as $opt_1){
					$num1+=1;
					echo '<select name="optativa2'.$num1.'" id="">';
					echo '<option>'.${optativa2.$num1}.'</option>';
					for ($z=1;$z<5;$z++){
						echo '<option>'.$z.'</option>';
					}
					echo '</select>';
					echo '<span style="margin:2px 2px" >'.$opt_1.'</span><br />';
				}
			}
		}
		?></td>
		<td valign=top bgcolor="#E6E6E6" colspan="2"><?    
		$num1="";
		for ($i = 1; $i < 5; $i++) {
			if ((substr($curso, 0, 1) -1) == $i) {
				foreach (${a.$i} as $act_1){
					$n_a = count(${a.$i})+1;
					//	if($cargo == '1'){
					$num1+=1;
					if (${act.$num1} == '0') {${act.$num1}='';}
					echo '<input type="radio" name = "act21" value="'.$num1.'"'.$sl.' style="margin:2px 2px"';
					if($act1 == $num1){echo "checked";}
					echo " />";
					echo '<span style="margin:2px 2px" >'.$act_1.'</span><br />';
				}
			}
		}
	}
		?></td>
	</tr>
<? if(substr($curso, 0, 1)<3) { ?>
	<tr>
		<td colspan="3" style="background-color: #CCCCCC"><?
		echo '<input'; echo ' type="checkbox" name="exencion" value="1" '; echo " disabled"; if($exencion == '1'){echo "checked";} echo " />"; ?>
		<span style="font-weight: bold">Exenci&oacute;n de la asignatura
		optativa (a rellenar por el Dep. de Orientaci&oacute;n previo acuerdo
		con la familia)</span></td>
	</tr>
	<? }?>	
    <? if(substr($curso, 0, 1)>2) { ?>
    <tr>
		<td colspan="3" style="background-color: #CCCCCC"><?
		echo '<input'; echo ' type="checkbox" name="diversificacion" value="1" '; echo " disabled"; if($diversificacion == '1'){echo "checked";} echo " />"; ?>
		<span style="font-weight: bold">El alumno participa en el Programa de Diversificaci�n</span></td>
	</tr>
    <? } ?>
		<?
	}
	?>
	<?  if(substr($curso, 0, 1) < 3) { ?>
	<tr>
		<td colspan="3"><input type="checkbox" name="bilinguismo" value="Si"
		<? if($bilinguismo == 'Si'){echo "checked";} ?> /> El alumno/a
		solicita participar en el <strong>Programa de Biling&uuml;ismo </strong>(Ingl&eacute;s).</td>
	</tr>
	<? } ?>
	<tr>
		<td valign=top colspan="3"><strong>OBSERVACIONES</strong>: <br />
		Indique aquellas cuestiones que considere sean importantes para
		conocimiento del Centro (enfermedades,� situaci�n familiar, etc.) <br />
		<textarea name="observaciones" id="textarea" rows="5"
			style="width: 80%" onKeyDown="contar('form1','observaciones')"
			onkeyup="contar('form1','observaciones')"><? echo $observaciones; ?></textarea>
		</td>
	</tr>
	
	<tr>
		<td colspan="3" style="border-bottom: none">

		<center><br />
		<input type="hidden" name="curso" value="<? echo $curso;?>" /> 
		<input type="hidden" name="nuevo" value="<? echo $nuevo;?>" /> 		
		<input type="hidden" name="curso_matricula"	value="<? echo $curso_matricula;?>" /> 
		<input type="hidden" name="claveal" <? echo "value = \"$claveal\""; ?> />
		<input type="hidden" name="repite" value="<? echo $repetidor;?>" />  
		<input type="submit" name="enviar"	value="Enviar los datos de la Matr�cula" class="no_imprimir"  />
		<br />
		<br />
		</center>
		</td>
	</tr>
	</form>
</table>

</div>
	<?
}
else{

	if ($dni == "" and $dnitutor == '') {
		echo '<div class="aviso3">Ha surgido un problema con el Formulario de Inscripci�n: debes escribir el <strong style="color:brown">DNI</strong> del alumno o padre que solicita la Matriculaci�n en este Centro. Vuelve atr�s e int�ntalo de nuevo. </div>';
	}
	if ($curso == "") {
		echo '<div class="aviso3">Ha surgido un problema con el Formulario de Inscripci�n: debes seleccionar el <strong style="color:brown">Curso</strong> del alumno que solicita la Matriculaci�n en este Centro. Vuelve atr�s e int�ntalo de nuevo. </div>';
	}
}
?> <br />

</body>
</html>
