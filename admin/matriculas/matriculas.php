<?
session_start();
include("../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}
if(!(stristr($_SESSION['cargo'],'1') == TRUE) and !(stristr($_SESSION['cargo'],'7') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);



include("../../funciones.php");

$connection = mysql_connect($db_host,$db_user,$db_pass) or die ("Imposible conectar con la Base de datos");
mysql_select_db($db) or die ("Imposible seleccionar base de datos!");
if (isset($_GET['curso'])) {$curso = $_GET['curso'];}elseif (isset($_POST['curso'])) {$curso = $_POST['curso'];}else{$curso="";}
if (isset($_GET['dni'])) {$dni = $_GET['dni'];}elseif (isset($_POST['dni'])) {$dni = $_POST['dni'];}else{$dni="";}
if (isset($_GET['claveal'])) {$claveal = $_GET['claveal'];}elseif (isset($_POST['claveal'])) {$claveal = $_POST['claveal'];}else{$claveal="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}


// Centros adscritos
$centros_adscritos = array(
												array(
													'id'     => 'SANTO-TOMAS',
													'nombre' => 'C.E.I.P. Santo Tom�s de Aquino',
												),
												array(
													'id'     => 'SIMON-FERNANDEZ',
													'nombre' => 'C.E.I.P. Sim�n Fern�ndez',
												),
												array(
													'id'     => 'GARCIA-LORCA',
													'nombre' => 'C.E.I.P. Federico Garc�a Lorca',
												),
												array(
													'id'     => 'JUAN-XXIII',
													'nombre' => 'Colegio Juan XXIII',
												),
											);

$transporte_este = array(
											array(
												'id'     => 'Urb. Mar y Monte',
												'nombre' => 'Urb. Mar y Monte',
											),
											array(
												'id'     => 'Urb. Diana - Isdabe',
												'nombre' => 'Urb. Diana - Isdabe',
											),
											array(
												'id'     => 'Benamara - Benavista',
												'nombre' => 'Benamara - Benavista',
											),
											array(
												'id'     => 'Bel Ai',
												'nombre' => 'Bel Ai',
											),
											array(
												'id'     => 'Parada Bus Portillo Cancelada',
												'nombre' => 'Parada Bus Portillo Cancelada',
											),
											array(
												'id'     => 'Parque Antena',
												'nombre' => 'Parque Antena',
											),
											array(
												'id'     => 'El Pirata',
												'nombre' => 'El Pirata',
											),
											array(
												'id'     => 'El Veler�n',
												'nombre' => 'El Veler�n',
											),
											array(
												'id'     => 'El Padr�n',
												'nombre' => 'El Padr�n',
											),
											array(
												'id'     => 'Mc Donald\'s',
												'nombre' => 'Mc Donald\'s',
											),
										);
										
$transporte_oeste = array(
											array(
												'id'     => 'Buenas Noches',
												'nombre' => 'Buenas Noches',
											),
											array(
												'id'     => 'Costa Galera',
												'nombre' => 'Costa Galera',
											),
											array(
												'id'     => 'Bah�a Dorada',
												'nombre' => 'Bah�a Dorada',
											),
											array(
												'id'     => 'Don Pedro',
												'nombre' => 'Don Pedro',
											),
											array(
												'id'     => 'Bah�a Azul',
												'nombre' => 'Bah�a Azul',
											),
											array(
												'id'     => 'G. Shell - H10',
												'nombre' => 'G. Shell - H10',
											),
											array(
												'id'     => 'Seghers Bajo (Babylon)',
												'nombre' => 'Seghers Bajo (Babylon)',
											),
											array(
												'id'     => 'Seghers Alto (Ed. Sierra Bermeja)',
												'nombre' => 'Seghers Alto (Ed. Sierra Bermeja)',
											),
										);

// Se han enviado datos para procesar....
if(isset($_POST['enviar'])){
		foreach($_POST as $key => $val)
	{
		${$key} = $val;
	}
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
		$msg_error = "Los siguientes datos son obligatorios y no los has rellenado en el formulario de inscripci�n:";
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
		$msg_error .= " $adv";
		$msg_error .= "Rellena los campos mencionados y env�a los datos de nuevo para poder registrar tu solicitud correctamente.";
	}
	else{
		if (substr($curso,0,1)<5){
		for ($i = 1; $i < 8; $i++) {
				for ($z = $i+1; $z < 8; $z++) {
					if (${optativa.$i}>0) {
					if (${optativa.$i}==${optativa.$z}) {
						$opt_rep = 1;
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
						$opt_rep = 1;
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
						$opt_rep2= 1 ;
				}
					}
								
				}
			}
		}
		if($colegio == "Otro Centro" and ($otrocolegio == "" or $otrocolegio == "Escribe aqu� el nombre del Centro")){
			$vacios.="otrocolegio ";
			$msg_error = "No has escrito el nombre del Centro del que procede el alumno. Rellena el nombre del Centro y env�a los datos de nuevo para poder registrar tu solicitud correctamente.";
		}
		elseif(strstr($nacimiento,"-") == FALSE){
			$msg_error = "La fecha de nacimiento que has escrito no es correcta. El formato adecuado para la fecha es DD-MM-YYYY (Por ejemplo: 01-01-2000).";
		}
		elseif(strlen($ruta_este) > 0 and strlen($ruta_oeste) > 0){
			$msg_error = "Parece que has seleccionado dos rutas incompatibles para el Transporte Escolar, y s�lo puedes seleccionar una ruta, hacia el Este o hacia el Oeste de '.$localidad_del_centro.'.Elige una sola parada y vuelve a enviar los datos.";

			$ruta_error = "";
		}
	elseif ($opt_rep=="1"){
			$msg_error = "Parece que has seleccionado el mismo n�mero de preferencia para varias optativas, y cada optativa debe tener un n�mero de preferencia distinto. Elige las optativas sin repetir el n�mero de preferencia e int�ntalo de nuevo.";
		}
	elseif ($opt_rep2=="1"){
			$msg_error = "Parece que has seleccionado el mismo n�mero de preferencia para varias optativas del curso anterior, y cada optativa debe tener un n�mero de preferencia distinto. Elige las optativas del curso anterior sin repetir el n�mero de preferencia e int�ntalo de nuevo.";
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
				exit();
			}
			else{
				$msg_success = "La solicitud de matr�cula se ha guardado correctamente.";
			}
		}
	}
}
?>
<!DOCTYPE html>  
<html lang="es">  
  <head>  
    <meta charset="iso-8859-1">  
    <title>Intranet &middot; <? echo $nombre_del_centro; ?></title>  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <meta name="description" content="Intranet del <? echo $nombre_del_centro; ?>">  
    <meta name="author" content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">
      
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/otros.css" rel="stylesheet">
       
    <link href="../../css/datepicker.css" rel="stylesheet">
    <link href="../../css/DataTable.bootstrap.css" rel="stylesheet">    
    <link href="../../css/font-awesome.min.css" rel="stylesheet" >           
</head>

<body style="padding-top: 10px;">

	<div class="container">
		
		<!-- MENSAJES -->
		<?php if(isset($msg_error)): ?>
		<div class="alert alert-danger">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_success)): ?>
		<div class="alert alert-success">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
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
<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Elige el alumno que quieres matricular en nuestro Centro:
		<?
		while ($row_alma = mysql_fetch_array($ya_alma)) {
			?> <input type="radio" name="claveal"
	value="<? echo $row_alma[0]; ?>"
	style="margin: 6px 2px; line-height: 18px; vertical-align: top;"
	onclick="submit()" />
</div></div><br />  

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
<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Elige el alumno que quieres matricular en nuestro Centro:
		<?
		while ($row_alma = mysql_fetch_array($ya_primaria)) {
			?> <input type="radio" name="claveal"
	value="<? echo $row_alma[0]; ?>"
	style="margin: 6px 2px; line-height: 18px; vertical-align: top;"
	onclick="submit()" />
</div></div><br />  

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
<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Elige el alumno que quieres matricular en nuestro Centro:
		<?
		while ($row_alma = mysql_fetch_array($ya_matricula)) {
			?> <input type="radio" name="claveal"
	value="<? echo $row_alma[0]; ?>"
	style="margin: 6px 2px; line-height: 18px; vertical-align: top;"
	onclick="submit()" />
</div></div><br />  

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
			$colegio = $nombre_del_centro;
		}
	}
	$opt1 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Tecnolog�a Aplicada");
	$opt2 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","M�todos de la Ciencia");
	$opt3 = array("Alem�n 2� Idioma","Cambios Sociales y G�nero", "Franc�s 2� Idioma","Cultura Cl�sica", "Taller T.I.C. III", "Taller de Cer�mica", "Taller de Teatro");
	$a1 = array("Actividades de refuerzo de Lengua Castellana", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C.", "Ampliaci�n: Taller de Teatro");
	$a2 = array("Actividades de refuerzo de Lengua Castellana ", "Actividades de refuerzo de Matem�ticas", "Actividades de refuerzo de Ingl�s", "Ampliaci�n: Taller T.I.C. II", "Ampliaci�n: Taller de Teatro II");

		?>
	
	<!-- FORMULARIO MATRICULA -->
	<form method="post" action="matriculas.php<? if($cargo == "1"){echo "?cargo=1";}?>" id="form1" name="form1">
	
		<table align="center" class="table table-bordered">
			<!-- CABECERA: LOGOTIPO -->
			<thead>
				<tr>
					<td colspan="2" style="border-right: 0; height: 90px;">
						<img class="img-responsive" src="../../img/encabezado.jpg" alt="" width="350">
					</td>
					<td colspan="2">
						<h4 class="text-uppercase"><strong>Consejer�a de Educaci�n, Cultura y Deporte</strong></h4>
						<h5 class="text-uppercase"><strong><?php echo $nombre_del_centro; ?></strong></h5>
					</td>
				</tr>
			</thead>
			
			<!-- CUERPO -->
			<tbody>
<?php
// CURSO MATRICULA
if (empty($n_curso)) $n_curso = substr($curso,0,1);

switch ($curso) {
	case '1ESO' : $curso_matricula="PRIMERO"; break;
	case '2ESO' : $curso_matricula="SEGUNDO"; break;
	case '3ESO' : $curso_matricula="TERCERO"; break;
	case '4ESO' : $curso_matricula="CUARTO";  break;
}
?>			
				<tr>
					<td colspan="4">
						<h4 class="text-center text-uppercase">SOLICITUD DE MATR�CULA EN <?php echo $curso_matricula; ?> DE E.S.O.</h4>
					</td>
				</tr>
				
				
				<?php if(substr($curso, 0, 1) > 1 && $cargo==1): ?>
				<!-- PROMOCION -->
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						<strong>Promoci�n a <?php echo $n_curso; ?>� de ESO</strong>
					</th>
				</tr>
				<tr>
					<td colspan="2">
						El alumno <strong>promociona</strong> por la siguiente raz�n:
						
						<div class="form-group">
							<div class="radio">
								<label>
									<input type="radio" name="promociona" value="1" <?php echo ($promociona == 1)  ? 'checked' : ''; ?>> Tener 0, 1 o 2 suspensos
								</label>
							</div>
						</div>
						
						<div class="form-group">
							<div class="radio">
								<label>
									<input type="radio" name="promociona" value="2" <?php echo ($promociona == 2)  ? 'checked' : ''; ?>> Repetir este a�o <?php echo substr($curso, 0, 1); ?>� de ESO 
								</label>
							</div>
						</div>
					</td>
					<td colspan="2">
						El alumno <strong>no promociona</strong> por la siguiente raz�n:
						
						<div class="form-group">
							<div class="radio">
								<label>
									<input type="radio" name="promociona" value="3" <?php echo ($promociona == 3)  ? 'checked' : ''; ?>> Tener m�s de 2 asignaturas suspensas
								</label>
							</div>
						</div>
					</td>
				</tr>
				<?php endif; ?>
				
				<!-- DATOS PERSONALES DEL ALUMNO -->
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						Datos personales del alumno o alumna
					</th>
				</tr>
				<tr>
					<td class="col-sm-3">
						<div class="form-group <?php echo (strstr($vacios,"apellidos, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="apellidos">Apellidos</label>
							<input type="text" class="form-control" id="apellidos" name="apellidos" value="<?php echo (isset($apellidos)) ? $apellidos : ''; ?>" maxlength="60">
						</div>
					</td>
					<td class="col-sm-3">
						<div class="form-group <?php echo (strstr($vacios,"nombre, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="nombre">Nombre</label>
							<input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo (isset($nombre)) ? $nombre : ''; ?>" maxlength="30">
						</div>
					</td>
					<td class="col-sm-3">
						<div class="form-group <?php echo (strstr($vacios,"nacimiento, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="nacimiento">Fecha de nacimiento</label>
							<input type="text" class="form-control" id="nacimiento" name="nacimiento" value="<?php echo (isset($nacimiento)) ? $nacimiento : ''; ?>" maxlength="11" data-date-format="dd-mm-yyyy" data-date-viewmode="years">
						</div>
					</td>
					<td class="col-sm-3">
						<div class="form-group <?php echo (strstr($vacios,"dni, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="dni">DNI / Pasaporte o equivalente</label>
							<input type="text" class="form-control" id="dni" name="dni" value="<?php echo (isset($dni)) ? $dni : ''; ?>" maxlength="10">
						</div>
					</td>
				</tr>
				<tr>
					<td class="col-sm-3">
						<div class="form-group <?php echo (strstr($vacios,"nacionalidad, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="nacionalidad">Nacionalidad</label>
							<input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="<?php echo (isset($nacionalidad)) ? $nacionalidad : ''; ?>" maxlength="30">
						</div>
					</td>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"nacido, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="nacido">Nacido en</label>
							<input type="text" class="form-control" id="nacido" name="nacido" value="<?php echo (isset($nacido)) ? $nacido : ''; ?>" maxlength="30">
						</div>
					</td>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"provincia, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="provincia">Provincia</label>
							<input type="text" class="form-control" id="provincia" name="provincia" value="<?php echo (isset($provincia)) ? $provincia : ''; ?>" maxlength="30">
						</div>
					</td>
					<td>
						<p><strong>Sexo</strong></p>
						<div class="form-inline">
							<div class="form-group <?php echo (strstr($vacios,"sexo, ")==TRUE) ? 'has-error' : ''; ?>">
								<div class="radio">
									<label>
										<input type="radio" name="sexo" value="hombre" <?php echo (isset($sexo) && $sexo == 'hombre' || $sexo == 'H') ? 'checked' : ''; ?>> &nbsp;Hombre 
									</label>
								</div>
							</div>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<div class="form-group <?php echo (strstr($vacios,"sexo, ")==TRUE) ? 'has-error' : ''; ?>">
								<div class="radio">
									<label>
										<input type="radio" name="sexo" value="mujer" <?php echo (isset($sexo) && $sexo == 'mujer' || $sexo == 'M') ? 'checked' : ''; ?>> &nbsp;Mujer
									</label>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="form-group <?php echo (strstr($vacios,"domicilio, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="domicilio">Domicilio, calle, plaza o avenida y n�mero</label>
							<input type="text" class="form-control" id="domicilio" name="domicilio" value="<?php echo (isset($domicilio)) ? $domicilio : ''; ?>" maxlength="60">
						</div>
					</td>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"localidad, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="localidad">Municipio / Localidad</label>
							<input type="text" class="form-control" id="localidad" name="localidad" value="<?php echo (isset($localidad)) ? $localidad : ''; ?>" maxlength="30">
						</div>
					</td>
					<td>
						<div class="form-group">
							<label for="hermanos">N� de hermanos</label>
							<input type="number" class="form-control" id="hermanos" name="hermanos" value="<?php echo (isset($hermanos)) ? $hermanos : '0'; ?>" min="0" max="99" maxlength="2">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"telefono1, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="telefono1">Tel�fono</label>
							<input type="text" class="form-control" id="telefono1" name="telefono1" value="<?php echo (isset($telefono1)) ? $telefono1 : ''; ?>" maxlength="9">
						</div>
					</td>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"telefono2, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="telefono2">Tel�fono urgencias</label>
							<input type="text" class="form-control" id="telefono2" name="telefono2" value="<?php echo (isset($telefono2)) ? $telefono2 : ''; ?>" maxlength="9">
						</div>
					</td>
					<td>
						<div class="form-group">
							<label for="correo">Correo electr�nico</label>
							<input type="text" class="form-control" id="correo" name="correo" value="<?php echo (isset($correo)) ? $correo : ''; ?>" maxlength="120">
						</div>
					</td>
					<td rowspan="2">
						<div class="form-group <?php echo (strstr($vacios,"colegio, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="colegio">Centro de procedencia</label>
							<select class="form-control" id="colegio" name="colegio">
								<?php if($curso == "1ESO"): ?>
								<option value=""></option>
								<?php for ($i = 0; $i < count($centros_adscritos); $i++): ?>
								<option value="<?php echo $centros_adscritos[$i]['id']; ?>" <?php echo (isset($colegio) && $colegio == $centros_adscritos[$i]['id']) ? 'selected' : ''; ?>><?php echo $centros_adscritos[$i]['nombre']; ?></option>
								<?php endfor; ?>
								<?php else: ?>
								<option value="<?php echo $nombre_del_centro; ?>"><?php echo $nombre_del_centro; ?></option>
								<?php endif; ?>
								<option value="Otro Centro">Otro Centro</option>
							</select>
						</div>
						<div id="form-otrocolegio" class="form-group <?php echo (isset($otrocolegio) && !empty($otrocolegio)) ? '' : 'hidden'; ?>">
							<label for="otrocolegio">Colegio</label>
							<input type="text" class="form-control" id="otrocolegio" name="otrocolegio" value="<?php echo (isset($otrocolegio)) ? $otrocolegio : ''; ?>" maxlength="60" placeholder="Escribe aqu� el nombre del colegio">
							
							<input type="hidden" name="letra_grupo" value="<?php echo (isset($letra_grupo)) ? $letra_grupo : ''; ?>">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<p class="help-block"><small>El centro podr� enviar comunicaciones v�a SMS si proporciona el n�mero de un tel�fono m�vil o por correo electr�nico.</small></p>
					</td>
				</tr>
				<!-- DATOS DE LOS REPRESENTANTES O GUARDADORES LEGALES -->
				<tr>
					<th class="active text-center text-uppercase" colspan="4">Datos de los representantes o guardadores legales</th>
				</tr>
				<tr>
					<td colspan="3">
						<div class="form-group <?php echo (strstr($vacios,"padre, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="padre">Apellidos y nombre del representante o guardador legal 1 <small>(con quien conviva el alumno/a y tenga atribuida su guarda y custodia)</small></label>
							<input type="text" class="form-control" id="padre" name="padre" value="<?php echo (isset($padre)) ? $padre : ''; ?>" maxlength="60">
						</div>
					</td>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"dnitutor, ")==TRUE) ? 'has-error' : ''; ?>">
							<label for="dnitutor">DNI / NIE</label>
							<input type="text" class="form-control" id="dnitutor" name="dnitutor" value="<?php echo (isset($dnitutor)) ? $dnitutor : ''; ?>" maxlength="10">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<div class="form-group">
							<label for="madre">Apellidos y nombre del representante o guardador legal 2</label>
							<input type="text" class="form-control" id="madre" name="madre" value="<?php echo (isset($madre)) ? $madre : ''; ?>" maxlength="60">
						</div>
					</td>
					<td>
						<div class="form-group <?php echo ((isset($madre) && !empty($madre)) && (isset($dnitutor2) && empty($dnitutor2))) ? 'has-error' : ''; ?>">
							<label for="dnitutor2">DNI / NIE</label>
							<input type="text" class="form-control" id="dnitutor2" name="dnitutor2" value="<?php echo (isset($dnitutor2)) ? $dnitutor2 : ''; ?>" maxlength="10">
						</div>
					</td>
				</tr>
				
				<?php if($mod_transporte): ?>
				<!-- TRANSPORTE ESCOLAR -->
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						Solicitud de transporte escolar
					</th>
				</tr>
				<tr>
					<td class="text-center" colspan="4">
						<div class="form-inline">
							
							<div class="form-group">
								<label for="ruta_este">Ruta Este:</label>
								<select class="form-control" id="ruta_este" name="ruta_este">
									<option value=""></option>
									<?php for ($i = 0; $i < count($transporte_este); $i++): ?>
									<option value="<?php echo $transporte_este[$i]['id']; ?>" <?php echo (isset($ruta_este) && $ruta_este == $transporte_este[$i]['id']) ? 'selected' : ''; ?>><?php echo $transporte_este[$i]['nombre']; ?></option>
									<?php endfor; ?>
								</select>
							</div>
				
							&nbsp;&nbsp;&nbsp;&nbsp;
				
							<div class="form-group">
								<label for="ruta_oeste">Ruta Oeste:</label>
								<select class="form-control" id="ruta_oeste" name="ruta_oeste">
									<option value=""></option>
									<?php for ($i = 0; $i < count($transporte_oeste); $i++): ?>
									<option value="<?php echo $transporte_oeste[$i]['id']; ?>" <?php echo (isset($ruta_oeste) && $ruta_oeste == $transporte_oeste[$i]['id']) ? 'selected' : ''; ?>><?php echo $transporte_oeste[$i]['nombre']; ?></option>
									<?php endfor; ?>
								</select>
							</div>
							
						</div>
					</td>
				</tr>
				<?php endif; ?>
				
				<!-- PRIMER IDIOMA Y RELIGION O ALTERNATIVA -->
				<tr>
					<th class="active text-center text-uppercase" colspan="2">
						Idioma extranjero
					</th>
					<th class="active text-center text-uppercase" colspan="2">
						Opci�n de ense�anza de religi�n o alternativa<br><small>(se�ale una)</small>
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<div class="form-group">
							<input type="text" class="form-control" name="idioma" value="Ingl�s" readonly>
							<p class="help-block"><small>Materia obligatoria</small></p>
						</div>
					</td>
					<td style="border-right: 0;">
					 	<div class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
							<div class="radio">
								<label>
									<input type="radio" name="religion" value="Religi�n Catolica" <?php if($religion == 'Religi�n Catolica'){echo "checked";} ?>> Religi�n Catolica
								</label>
							</div>
						</div>
						
						<div class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
							<div class="radio">
								<label>
									<input type="radio" name="religion" value="Religi�n Isl�mica" <?php if($religion == 'Religi�n Isl�mica'){echo "checked";} ?>> Religi�n Isl�mica
								</label>
							</div>
						</div>
						
						<div class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
							<div class="radio">
								<label>
									<input type="radio" name="religion" value="Religi�n Jud�a" <?php if($religion == 'Religi�n Jud�a'){echo "checked";} ?>> Religi�n Jud�a
								</label>
							</div>
						</div>
					</td>
					<td>
						<div class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
							<div class="radio">
								<label>
									<input type="radio" name="religion" value="Religi�n Evang�lica" <?php if($religion == 'Religi�n Evang�lica'){echo "checked";} ?>> Religi�n Evang�lica
								</label>
							</div>
						</div>
						
						<div class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
							<div class="radio">
								<label>
									<input type="radio" name="religion" value="Historia de las Religiones" <?php if($religion == 'Historia de las Religiones'){echo "checked";} ?>> Historia de las Religiones
								</label>
							</div>
						</div>
						
						<div class="form-group <?php echo (strstr($vacios,"religion, ")==TRUE) ? 'has-error' : ''; ?>">
							<div class="radio">
								<label>
									<input type="radio" name="religion" value="Atenci�n Educativa"  <?php if($religion == 'Atenci�n Educativa'){echo "checked";} ?>> Atenci�n Educativa
								</label>
							</div>
						</div>
					</td>
				</tr>
				
				<!-- OPTATIVAS: 1 Y 2 ESO -->
				<?php if($n_curso < 3): ?>
				<tr>
					<th class="active text-center" colspan="2">
						<span class="text-uppercase">Asignatura optativa</span><br><small>(marca con 1, 2, 3, y 4 por orden de preferencia)</small>
					</th>
					<th class="active text-center" colspan="2">
						<span class="text-uppercase">Programa de Refuerzo o Ampliaci�n</span><br><small>Se elige una asignatura de refuerzo si el alumno tiene asignaturas suspensas del curso anterior; se elige asignatura de ampliaci�n si el alumno pasa de curso sin suspensos. El Departamento de Orientaci�n decide finalmente.</small>
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<div class="form-horizontal">
								<?php $num1 = ''; ?>
								<?php for ($i = 1; $i < 5; $i++): ?>
									<?php if (substr($curso, 0, 1) == $i): ?>
										<?php foreach (${opt.$i} as $opt_1): ?>
											<?php $num1 += 1; ?>
											<div class="form-group <?php echo (strstr($vacios,"optativa$num1, ")==TRUE) ? 'has-error' : ''; ?> <?php echo (isset($opt_rep) && $opt_rep == 1) ? 'has-error"' : '' ; ?>">
												<div class="col-sm-2">
													<select class="form-control" name="optativa<?php echo $num1; ?>" id="optativa<?php echo $num1; ?>" >
														<option value=""></option>
														<?php for ($z = 1; $z < 5; $z++): ?>
														<option value="<?php echo $z; ?>" <?php echo (${optativa.$num1} == $z) ? 'selected': ''; ?>><?php echo $z; ?></option>
														<?php endfor; ?>
													</select>
												</div>
												<label for="optativa<?php echo $num1; ?>" class="col-sm-10 control-label"><div class="text-left"><?php echo $opt_1; ?></div></label>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endfor; ?>
						</div>
					</td>
					<td colspan="2">
						<?php $num1 = ''; ?>
						<?php for ($i = 1; $i < 5; $i++): ?>
							<?php if (substr($curso, 0, 1) == $i): ?>
								<?php foreach (${a.$i} as $act_1): ?>
									<?php $n_a = count(${a.$i})+1; ?>
									<?php $num1 += 1; ?>
									<?php if (${act.$num1} == 0) ${act.$num1} = ''; ?>
									<div class="form-group">
										<div class="radio">
											<label>
												<input type="radio" name="act1" value="<?php echo $num1; ?>" <?php echo ($act1 == $num1) ? 'checked' : ''; ?>> <?php echo $act_1; ?>
											</label>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endfor; ?>
					</td>
				</tr>
				
				<!-- OPTATIVAS: 3 ESO -->
				<?php elseif($n_curso == 3): ?>
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						Asignaturas optativas de 3� de ESO<br><small>(marca con 1, 2, 3, 4, etc. por orden de preferencia)</small>
					</th>
				</tr>
				<tr>
					<td colspan="2" style="border-right: 0;">
						<div class="form-horizontal">
							<?php $num1 = ""; ?>
							<?php for ($i = 1; $i < 8; $i++): ?>
								<?php if (substr($curso, 0, 1) == $i): ?>
									<?php foreach (${opt.$i} as $opt_1): ?>
										<?php $num1 += 1; ?>
										<div class="form-group">
											<div class="col-sm-2">
												<select class="form-control" id="optativa<?php echo $num1; ?>" name="optativa<?php echo $num1; ?> <?php echo (isset($opt_rep) && $opt_rep == 1) ? 'has-error"' : '' ; ?>">
													<option value=""></option>
													<?php for ($z = 1; $z < 8; $z++): ?>
													<option value="<?php echo $z; ?>" <?php echo (${optativa.$num1} == $z) ? 'selected' : ''; ?>><?php echo $z; ?></option>
													<?php endfor; ?>
												</select>
											</div>
											<label class="col-sm-10 control-label"><div class="text-left"><?php echo $opt_1; ?></div></label>
										</div>
										
										<?php echo ($num1%4 == 0) ? '</div></td><td colspan="2"><div class="form-horizontal">' : ''; ?>
										
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endfor; ?>
						</div>
					</td>
				</tr>
	
				<!-- OPTATIVAS: 4 ESO -->
				<?php else: ?>
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						Elecci�n de asignaturas optativas de 4� de ESO<br><small>(Debes marcar un Itinerario y luego seleccionar las asignaturas optativas ofrecidas para el mismo en su orden de preferencia: 1, 2, 3, etc.)</small>
					</th>
				</tr>

	<?php
	// ITINERARIOS DE 4 ESO
	$it41 = array("(Bachillerato de Ciencias y Tecnolog�a - V�a de Ciencias de la Naturaleza y la Salud)", "F�sica y Qu�mica", "Biolog�a y Geolog�a", "Matem�ticas B", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Inform�tica");
	$it42 = array("(Bachillerato de Ciencias y Tecnolog�a - V�a de Ciencias e Ingenier�a)", "F�sica y Qu�mica", "Tecnolog�a", "Matem�ticas B", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Inform�tica", "Ed. Pl�stica y Visual");
	$it43 = array("(Bachillerato de Humanidades y Ciencias Sociales)", "Lat�n", "M�sica", "Matem�ticas A", "Matem�ticas B", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Inform�tica", "Ed. Pl�stica y Visual");
	$it44 = array("(Ciclos Formativos y Mundo Laboral)", "Inform�tica", "Ed. Pl�stica y Visual", "Matem�ticas A", "Alem�n 2� Idioma", "Franc�s 2� Idioma", "Tecnolog�a");
	
	$opt41=array("Alem�n2_1" => "Alem�n 2� Idioma", "Franc�s2_1" => "Franc�s 2� Idioma", "Informatica_1" => "Inform�tica");
	$opt42=array("Alem�n2_2" => "Alem�n 2� Idioma", "Franc�s2_2" => "Franc�s 2� Idioma", "Informatica_2" => "Inform�tica", "EdPl�stica_2" => "Ed. Pl�stica y Visual");
	$opt43=array("Alem�n2_3" => "Alem�n 2� Idioma", "Franc�s2_3" => "Franc�s 2� Idioma", "Informatica_3" => "Inform�tica", "EdPl�stica_3" => "Ed. Pl�stica y Visual");
	$opt44=array("Alem�n2_4" => "Alem�n 2� Idioma", "Franc�s2_4" => "Franc�s 2� Idioma", "Tecnolog�a_4" => "Tecnolog�a");
	?>
				<tr>
					<?php for ($i = 1; $i < 5; $i++): ?>
					<td class="text-center">
						<div class="radio">
							<label>
								<input type="radio" id="itinerario<?php echo $i; ?>" name="itinerario" value="<?php echo $i; ?>">
								<span class="text-uppercase"><strong>Itinerario <?php echo $i; ?></strong></span><br>
								<small class="text-info"><?php echo ${it4.$i}[0]; ?></small>
							</label>
						</div>
						
					</td>
					<?php endfor; ?>
				</tr>
				<tr>
					<?php for ($i = 1; $i < 5; $i++): ?>
					<td>
					
						<!-- ASIGNATURAS DE MODALIDAD -->
						<p class="form-control-static"><?php echo ${it4.$i}[1]; ?></p>
						<p class="form-control-static"><?php echo ${it4.$i}[2]; ?></p>
						
						<!-- MATEMATICAS -->
						<?php if($i == 3): ?>
							<div class="form-group">
								<div class="radio">
									<label>
										<input type="radio" class="itinerario<?php echo $i; ?>" name="matematicas4" value="A" <?php echo ($matematicas4 == 'A') ? 'checked' : '' ; ?> <?php echo ($itinerario != $i) ? 'disabled' : ''; ?>> <?php echo ${it4.$i}[3]; ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<div class="radio">
									<label>
										 <input type="radio" class="itinerario<?php echo $i; ?>" name="matematicas4" value="B" <?php echo ($matematicas4 == 'B') ? 'checked' : '' ; ?> <?php echo ($itinerario != $i) ? 'disabled' : ''; ?>> <?php echo ${it4.$i}[4]; ?>
									</label>
								</div>
							</div>
						<?php else: ?>
							 <p class="form-control-static"><?php echo ${it4.$i}[3]; ?></p>
						<?php endif; ?>
						
					</td>
					<?php endfor; ?>
				</tr>
				<tr>
					<?php for($i = 1; $i < 5; $i++): ?>
					<td style="border-top: 0;">
						<div class="form-horizontal">
							<?php $num1 = ""; ?>
							<?php $num_it = count(${opt4.$i}); ?>
							<?php foreach(${opt4.$i} as $optit_1 => $nombre): ?>
								<?php $num1 += 1; ?>
								<?php if (${optativa.$num1} == 0) ${optativa.$num1} = ""; ?>
									<div class="form-group">
										<div class="col-sm-4">
											<select class="form-control itinerario<?php echo $i; ?>" id="<?php echo $optit_1; ?>" name="<?php echo $optit_1; ?>" <?php echo ($itinerario != $i) ? 'disabled' : ''; ?>>
												<option value=""></option>
												<?php for($z = 1; $z <= $num_it; $z++): ?>
												<option value="<?php echo $z; ?>" <?php echo ($optit_1 == $z) ? 'selected' : '' ; ?>><?php echo $z; ?></option>
												<?php endfor; ?>
											</select>
										</div>
										<label for="<?php echo $optit_1; ?>" class="col-sm-8 control-label"><div class="text-left"><?php echo $nombre; ?></div></label>
									</div>
							<?php endforeach; ?>
						</div>
					</td>
					<?php endfor; ?>
				</tr>
								
				<?php endif; ?>
				
				<?php if(substr($curso, 0, 1) > 1) { ?>
				
				
				<!-- OPTATIVAS SI REPITE 3 ESO -->
				<?php if($n_curso == 4): ?>
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						Asignaturas optativas de 3� de ESO<br><small>(marca con 1, 2, 3, 4, etc. por orden de preferencia)</small>
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<div class="form-horizontal">
							<?php $num1 = ""; ?>
							<?php for($i = 1; $i < 8; $i++): ?>
								<?php if (substr($curso, 0, 1)-1 == $i): ?>
									<?php foreach (${opt.$i} as $opt_1): ?>
										<?php $num1 += 1; ?>
										<div class="form-group <?php echo (isset($opt_rep2) && $opt_rep2 == 1) ? 'has-error"' : '' ; ?>">
											<div class="col-sm-2">
												<select class="form-control" id="optativa2<?php echo $num1; ?>" name="optativa2<?php echo $num1; ?>">
													<option value=""></option>
													<?php for ($z = 1; $z < 8; $z++): ?>
													<option value="<?php echo $z; ?>" <?php echo (${optativa2.$num1} == $z) ? 'selected' : ''; ?>><?php echo $z; ?></option>
													<?php endfor; ?>
												</select>
											</div>
											<label for="optativa2<?php echo $num1; ?>" class="col-sm-10 control-label"><div class="text-left"><?php echo $opt_1; ?></div></label>
										</div>
									
										<?php echo ($num1 == 4) ? '</div></td><td colspan="2"><div class="form-horizontal">' : ''; ?>
										
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endfor; ?>
						</div>
					</td>
				</tr>
		
				<?php else: ?>
				
				<!-- OPTATIVAS EN CASO DE REPETIR CURSO -->
				<tr>
					<th class="active text-center text-uppercase" colspan="4">
						Elecci�n de asignaturas optativas de <?php echo substr($curso, 0, 1) - 1; ?>� de ESO<br><small>(Deben rellenarlo todos los alumnos, incluso si promocionan al curso siguiente)</small>
					</th>
				</tr>
				<tr>
					<th class="text-center text-uppercase" colspan="2">
						Asignaturas optativas<br><small>(marque con 1, 2, 3, y 4 por orden de preferencia)</small>
					</th>
					<th class="text-center text-uppercase" colspan="2">
						Programa de refuerzo o alternativo<br><small>Estudios en funci�n del Informe de tr�nsito elaborado por el tutor y seleccionados por el Departamento de
						Orientaci�n.</small>
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<div class="form-horizontal">
							<?php $num1 = ""; ?>
							<?php for($i = 1; $i < 5; $i++): ?>
								<?php if((substr($curso, 0, 1)-1) == $i): ?>
									<?php foreach(${opt.$i} as $opt_1): ?>
										<?php $num1 += 1; ?>
										<div class="form-group <?php echo (isset($opt_rep2) && $opt_rep2 == 1) ? 'has-error"' : '' ; ?>">
											<div class="col-sm-2">
												<select class="form-control" id="optativa2<?php echo $num1; ?>" name="optativa2<?php echo $num1; ?>">
													<option value=""></option>
													<?php for ($z = 1; $z < 5; $z++): ?>
													<option value="<?php echo $z; ?>" <?php echo (${optativa2.$num1} == $z) ? 'selected' : ''; ?>><?php echo $z; ?></option>
													<?php endfor; ?>
												</select>
											</div>
											<label for="optativa2<?php echo $num1; ?>" class="col-sm-10 control-label"><div class="text-left"><?php echo $opt_1; ?></div></label>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endfor; ?>
						</div>
					</td>
					<td colspan="2">
						<?php $num1 = ""; ?>
						<?php for($i = 1; $i < 5; $i++): ?>
							<?php if((substr($curso, 0, 1) -1) == $i): ?>
								<?php foreach (${a.$i} as $act_1): ?>
									<?php $n_a = count(${a.$i})+1; ?>
									<?php $num1 += 1; ?>
									<?php if (${act.$num1} == '0') ${act.$num1}=''; ?>
									<div class="form-group">
										<div class="radio">
											<label>
												<input type="radio" name="act21" value="<?php echo $num1; ?>" <? echo ($act21 == $num1) ? 'checked' : ''; ?>> <?php echo $act_1; ?>
											</label>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endfor; ?>
					</td>
				</tr>
				
				<?php endif; ?>
				
				<!-- EXENCI�N DE ASIGNATURA OPTATIVA -->
				<?php if(substr($curso, 0, 1) < 3): ?>
				<tr>
					<td class="active" colspan="4">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="exencion" value="1" <?php echo ($exencion == 1) ? 'checked' : ''; ?> disabled> Exenci�n de la asignatura optativa (a rellenar por el Departamento de Orientaci�n previo acuerdo con la familia)
								</label>
							</div>
						</div>
					</td>
				</tr>
				<?php endif; ?>	
				
				<!-- DIVERSIFICACI�N -->
		    <?php if(substr($curso, 0, 1)>2): ?>
		    <tr>
					<td class="active" colspan="4">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="diversificacion" value="1" <?php echo ($diversificacion == 1) ? 'checked' : ''; ?> disabled> El alumno participa en el programa de Diversificaci�n
								</label>
							</div>
						</div>
					</td>
				</tr>
		    <?php endif; ?>
				
				<?php } ?>
				
				<!-- BILING�ISMO -->
				<?php if(substr($curso, 0, 1) < 2): ?>
				<tr>
					<td class="active" colspan="4">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox" name="bilinguismo" value="Si" <? if($bilinguismo == 'Si'){echo "checked";} ?>> El alumno/a solicita participar en el programa de biling�ismo (Ingl�s)
								</label>
							</div>
						</div>
					</td>
				</tr>
				<?php endif; ?>
				
				<!-- OBSERVACIONES -->
				<tr>
					<th colspan="4">
						<span class="text-uppercase">Observaciones:</span><br>
						Indique aquellas cuestiones que considere sean importantes para conocimiento del centro (enfermedades, situaci�n familiar, etc.)
					</th>
				</tr>
				<tr>
					<td colspan="4" style="border-top: 0;">
						<textarea class="form-control"  id="observaciones" name="observaciones" rows="5"><?php echo (isset($observaciones)) ? $observaciones : ''; ?></textarea>
					</td>
				</tr>
				
			</tbody>
		</table>
				
		<!-- CAMPOS OCULTOS Y ENVIO DE FORMULARIO -->
		<div class="text-center" colspan="4">
			<input type="hidden" name="curso" value="<?php echo (isset($curso)) ? $curso : ''; ?>"> 
			<input type="hidden" name="nuevo" value="<?php echo (isset($nuevo)) ? $nuevo : ''; ?>"> 		
			<input type="hidden" name="curso_matricula"	value="<?php echo (isset($curso_matricula)) ? $curso_matricula : ''; ?>"> 
			<input type="hidden" name="claveal" value="<?php echo (isset($claveal)) ? $claveal : ''; ?>">
			<input type="hidden" name="repite" value="<?php echo (isset($repetidor)) ? $repetidor : ''; ?>">  
			
			<button type="submit" class="btn btn-primary" name="enviar">Guardar cambios</button>
			<button type="reset" class="btn btn-default">Cancelar</button>
		</div>
			
	</form>
	
<?php } else { ?>

	<?php if($dni == '' || $dnitutor == ''): ?>
		<div class="alert alert-danger">
			Debes introducir el DNI / Pasaporte o equivalente del alumno/a o tutor legal que solicita la matriculaci�n en este centro.
		</div>
	<?php endif; ?>
	
	<?php if($curso == ''): ?>
	<div class="alert alert-danger">
		Debes seleccionar el curso del alumno/a que solicita la matriculaci�n en este centro.
	</div>
	<?php endif; ?>
	
<?php } ?>

	</div><!-- /.container -->
	
<?php include("../../pie.php"); ?>
	
	<script>
	$(document).ready(function() {
		
		// Fecha de nacimiento
		$('#nacimiento').datepicker();		
		
		// Selector de colegio
		$('#colegio').change(function() {
			if($('#colegio').val() == 'Otro Centro') {
				$('#form-otrocolegio').removeClass('hidden');
			}
			else {
				$('#form-otrocolegio').addClass('hidden');
			}
		});
		
		// Selector de itinerarios
		$('#itinerario1').click(function() { 
			if($('#itinerario1').is(':checked')) {  
				$('.itinerario1').prop('disabled', false);
				
				$('.itinerario2').prop('disabled', true);
				$('.itinerario3').prop('disabled', true);
				$('.itinerario4').prop('disabled', true);
			}
		});
		
		$('#itinerario2').click(function() { 
			if($('#itinerario2').is(':checked')) {  
				$('.itinerario2').prop('disabled', false);
				
				$('.itinerario1').prop('disabled', true);
				$('.itinerario3').prop('disabled', true);
				$('.itinerario4').prop('disabled', true);
			}
		});
		
		$('#itinerario3').click(function() { 
			if($('#itinerario3').is(':checked')) {  
				$('.itinerario3').prop('disabled', false);
				
				$('.itinerario1').prop('disabled', true);
				$('.itinerario2').prop('disabled', true);
				$('.itinerario4').prop('disabled', true);
			}
		});
		
		$('#itinerario4').click(function() { 
			if($('#itinerario4').is(':checked')) {  
				$('.itinerario4').prop('disabled', false);
				
				$('.itinerario1').prop('disabled', true);
				$('.itinerario2').prop('disabled', true);
				$('.itinerario3').prop('disabled', true);
			}
		});
		
	});
	</script>

</body>
</html>
