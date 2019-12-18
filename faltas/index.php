<?php
require('../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}


if (isset($_POST['fecha_dia'])) {$fecha_dia = $_POST['fecha_dia'];}elseif (isset($_GET['fecha_dia'])) {$fecha_dia = $_GET['fecha_dia'];}
if (isset($_POST['hora_dia'])) {$hora_dia = $_POST['hora_dia'];}elseif (isset($_GET['hora_dia'])) {$hora_dia = $_GET['hora_dia'];}
if (isset($_POST['profe_ausente'])) {$profe_ausente = $_POST['profe_ausente'];}elseif (isset($_GET['profe_ausente'])) {$profe_ausente = $_GET['profe_ausente'];}


$pr = $_SESSION['profi'];

$prof1 = "SELECT distinct c_prof FROM horw where prof = '$pr'";
$prof0 = mysqli_query($db_con, $prof1);
$filaprof0 = mysqli_fetch_array($prof0);
$c_prof = $filaprof0[0];
if (empty($c_prof)) {
	$c_prof = '0';
	$msg_error_no_c_prof = 1;
}

if(empty($hora_dia)){
	$hora_real = strtotime(date("H:i:s"));

	// Se han importado los datos de la tramos escolar desde Séneca
	$result_jornada = mysqli_query($db_con, "SELECT hora, hora_inicio, hora_fin FROM tramos ORDER BY idjornada ASC, horini ASC");
		while($jornada = mysqli_fetch_array($result_jornada)){
				$h_inicio = strtotime($jornada[1]);
				$h_fin = strtotime($jornada[2]);
				//echo "$h_inicio : $hora_real : $h_fin<br>";

			if( $hora_real > $h_inicio && $hora_real < $h_fin){
				$hora_dia = $jornada[0];
				break;
			}
			else{
				$hora_dia = $jornada[0];
			}
		}
}

if (isset($fecha_dia)) {
	$tr_fech = explode("-", $fecha_dia);
	$di = $tr_fech[0];
	$me = $tr_fech[1];
	$an = $tr_fech[2];
	$ndia = date("N", mktime(0, 0, 0, $me, $di, $an));
	$hoy = "$an-$me-$di";
	$hoy_actual = "$di-$me-$an";

	//echo "$ndia $hora_dia $fecha_dia $hoy $an-$me-$di";
}
else {
	$ndia = date("w");// nº de día de la semana (1,2, etc.)
	$hoy = date("Y-m-d");
	$hoy_actual = "$diames-$nmes-$nano";
}

if($ndia == "1"){$nom_dia = "Lunes";}
if($ndia == "2"){$nom_dia = "Martes";}
if($ndia == "3"){$nom_dia = "Miércoles";}
if($ndia == "4"){$nom_dia = "Jueves";}
if($ndia == "5"){$nom_dia = "Viernes";}

if ($config['mod_asistencia']) {
	include("../menu.php");
	if (isset($_GET['menu_cuaderno'])) {
		include("../cuaderno/menu.php");
		echo "<br>";
	}
	else {
		include("menu.php");
	}
?>

<div class="container">

<div class="page-header">
<h2 style="display: inline;">Faltas de Asistencia <small> Poner faltas</small></h2>
</div>

<div class="row"><?php

if($mensaje){
	echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Las Faltas han sido registradas correctamente.
          </div></div>';
}
?>
<div class="col-md-5"><br>
<div class="well"><?php if (isset($_GET['menu_cuaderno'])) {
	$extra = "?menu_cuaderno=1&profesor=".$_SESSION['profi']."&dia=$dia&hora=$hora&curso=$curso&asignatura=$asignatura";
}
?>
<form id="form1" method="post" action="index.php<?php echo $extra;?>">

<fieldset><legend>Seleccione fecha y grupo</legend>

<div class="form-group" id="datetimepicker1"><label for="fecha_dia">Fecha</label>
<div class="input-group"><input type="text" class="form-control"
	id="fecha_dia" name="fecha_dia"
	value="<?php echo (isset($fecha_dia)) ? $fecha_dia : date('d-m-Y'); ?>"
	data-date-format="DD-MM-YYYY"> <span class="input-group-addon"><span
	class="far fa-calendar"></span></span></div>
</div>

<div class="form-group"><label for="grupo">Grupo</label>
	<?php
	?>
	<select	class="form-control" id="hora_dia" name="hora_dia" onChange=submit()>
	<?php
	for ($i = 1; $i < 7; $i++) {
		$gr_hora = mysqli_query($db_con,"select a_grupo, asig, c_asig from horw where hora = '$i' and dia='$ndia' and prof = '".$_SESSION['profi']."' and a_asig not like 'GUCON' and c_asig not in (select distinct idactividad from actividades_seneca where requnidadactividad = 'N' AND idactividad <> 25) ORDER BY a_grupo ASC");
		if (mysqli_num_rows($gr_hora)>0) {

			while ($grupo_hora = mysqli_fetch_array($gr_hora)) {
				if ($grupo_hora['c_asig']=="25") {
					$grup="SGUA";
				}
				else{
					$grup.="$grupo_hora[0] ";
				}

				$asign = $grupo_hora[1];
			}
			$grupos = "$grup ($asign)";
		}

		if (!empty($grupos)) {
			if (isset($hora_dia) and $hora_dia==$i) {
				echo "<option value='$i' selected>$grupos</option>";
			}
			else{
				echo "<option value='$i'>$grupos</option>";
			}
		}
		$grupos="";
		$grup="";
		$asign="";
	}
	?>
</select></div>

<?php


$gu = mysqli_query($db_con,"select distinct c_asig, a_asig from horw where prof = '$pr' and hora='$hora_dia' and dia='$ndia'");
$sg = mysqli_fetch_array($gu);
$cod_guardia = $sg['c_asig'];
if (($sg['c_asig']=="25" and stristr($sg['a_asig'],"CON")==FALSE)) { ?>

<div class="form-group"><label for="profe">Profesor</label> <select
	class="form-control" id="profe" name="profe_ausente" onChange=submit()>
	<?php
	echo "<option>".$_POST['profe_ausente']."</option>";
		$gu_hora = mysqli_query($db_con,"select distinct prof from horw_faltas where hora = '$hora_dia' and dia='$ndia' order by prof");
		if (mysqli_num_rows($gu_hora)>0) {

			while ($grupo_gu = mysqli_fetch_array($gu_hora)) {
				echo "<option>$grupo_gu[0]</option>";
			}
		}
	?>
</select></div>

<?php if (!empty($_POST['profe_ausente']) or $cod_guardia == "25") {?>

<input type="hidden" name="hora_guardia" value="<?php echo $hora_dia;?>">

<?php } ?>

<?php } ?>

<div class="row">
<div class="col-sm-12">
<button type="submit" class="btn btn-primary" name="aceptar">Aceptar</button>
</div>
</fieldset>

</form>
</div>
</div>

<div class="col-md-7">

	<?php if (isset($msg_error_no_c_prof) && $idea != 'admin'): ?>
	<div class="alert alert-danger">
		<strong>Error:</strong> No se ha encontrado el código de profesor en la base de datos.
	</div>
	<?php endif; ?>

<div align="left"><?php

if ($ndia>5) {
	?>
<h2 class="text-muted text-center"><span class="far fa-clock fa-5x"></span>
<br>
Fuera de horario escolar</h2>
	<?php
}
elseif (!empty($_POST['profe_ausente']) and $_POST['hora_dia']==$_POST['hora_guardia']){
	//echo "Tarari";
	$s_profe_ausente = preg_replace('([^A-Za-z0-9])', '', $_POST['profe_ausente']);
	$prof2 = "SELECT distinct c_prof, prof FROM horw where prof = '".$s_profe_ausente."'";
	$prof20 = mysqli_query($db_con, $prof2);
	$filaprof2 = mysqli_fetch_array($prof20);
	$c_profe = $filaprof2[0];
	$c_prof=$c_profe;
	$profesor_ausente = $filaprof2[1];
	$hora1 = "select distinct c_asig, a_grupo, asig, prof from horw_faltas where c_prof = '$c_profe' and dia = '$ndia' and hora = '$hora_dia' and a_grupo not like '' ORDER BY a_grupo ASC";
	//echo $hora1;
	$hora0 = mysqli_query($db_con, $hora1);
}
else{
	$hora1 = "select distinct c_asig, a_grupo, asig from horw where c_prof = '$c_prof' and dia = '$ndia' and hora = '$hora_dia' and a_grupo not like '' ORDER BY a_grupo ASC";
	//echo $hora1;
	$hora0 = mysqli_query($db_con, $hora1);
	if (mysqli_num_rows($hora0)<1) {
		?>
<h2 class="text-muted text-center"><span class="far fa-clock fa-5x"></span>
<br>
Sin alumnos en esta hora (<?php echo $hora_dia;  if (is_numeric($hora_dia)) echo "ª";?>)</h2>
		<?php
	}
}

$array_unidades = array();
while($hora2 = mysqli_fetch_row($hora0)) {
	$found_key = array_search($hora2[1], array_column($array_unidades, 1));
	if ($found_key === false) array_push($array_unidades, $hora2);
}

foreach ($array_unidades as $hora2) {
	$c_a="";
	$c_b="";
	$codasi= $hora2[0];
	if (empty($hora2[1])) {
		$curso="";
	}
	else{
		$curso = $hora2[1];
	}

	$asignatura = $hora2[2];

	$nivel_curso = substr($curso,0,1);

	?>

	<form action="poner_falta.php<?php echo $extra;?>" method="post" name="Cursos">

	<?php
	// Problema con PMAR
	$pmar_2 = mysqli_query($db_con,"select distinct codigo from asignaturas where nombre like '%**%' and abrev not like '%\_%' and curso like '2%' limit 1");
	$c_pmar2 = mysqli_fetch_array($pmar_2);
	$codigo_pmar2 = $c_pmar2[0];

	$pmar_3 = mysqli_query($db_con,"select distinct codigo from asignaturas where nombre like '%**%' and abrev not like '%\_%' and curso like '3%' limit 1");
	$c_pmar3 = mysqli_fetch_array($pmar_3);
	$codigo_pmar3 = $c_pmar3[0];

	$res = "select distinctrow alma.CLAVEAL, alma.unidad, alma.APELLIDOS, alma.NOMBRE, alma.MATRICULAS, alma.combasi from alma WHERE alma.unidad = '$curso' and ( ";

	$curs_bach = mysqli_query($db_con,"select distinct curso from alma where unidad = '$curso'");
	$curso_bach = mysqli_fetch_array($curs_bach);
	$curso_asig_0 = $curso_bach['curso'];
	$curso_asig = substr($curso_bach[0],3,15);

	//if (stristr($curso_bach['curso'],"Bachiller")==TRUE) {

		$asignat="";
		$cod_asig_bach="";
		// Cursos con dos códigos distintos de una misma asignatura o Bachillerato.
		$n_bach = mysqli_query($db_con, "select distinct c_asig from horw where c_prof = '$c_prof' and dia = '$ndia' and hora = '$hora_dia'");
		$asig_bch = mysqli_fetch_array($n_bach);
		$asignat = $asig_bch[0];

		$asig_bach = mysqli_query($db_con,"select distinct codigo from materias where nombre like (select distinct nombre from materias where codigo = '$asignat' limit 1) and grupo like '$curso' and codigo not like '$asignat' and abrev not like '%\_%'");

		if (mysqli_num_rows($asig_bach)>0) {
			$esBachillerato = 1;
			$as_bach=mysqli_fetch_array($asig_bach);
			$cod_asig_bach = $as_bach[0];
			$res.=" combasi like '%$asignat:%' or combasi like '%$cod_asig_bach:%'";
			$fal_e =" FALTAS.codasi='$asignat' or FALTAS.codasi='$cod_asig_bach'";
			$cod_asig = " asignatura like '$asignat' or asignatura like '$cod_asig_bach'";
		}
		else{

			if ($asignat == "2" || $asignat == "21" || $asignat == "136" || $asignat == "820" || $asignat == "861") {
				if ($asignat=="861") {
					$res.="combasi like '%$codigo_pmar2:%' OR combasi like '%$codigo_pmar3:%' ";
					$cod_asig = "asignatura like '$codigo_pmar2' OR asignatura like '$codigo_pmar3'";
				}
				else{
					$res.="1=1 ";
					$cod_asig = "asignatura like '$asignat'";
				}
			}
			else{
				$res.="combasi like '%".$asignat."%'";
				$cod_asig = "asignatura like '$asignat'";
			}
			$fal_e =" FALTAS.codasi='$asignat' ";
		}

	$res.=") order by alma.apellidos ASC, alma.nombre ASC";
	 // echo $res;
	$result = mysqli_query($db_con, $res);
	if ($result) {
		$t_grupos = $curs;
		?>
		<br>
		<table class='table table-striped'>
		<?php
		$filaprincipal = "<thead><tr><th colspan='3'><h4 class=\"text-center\">";


		$filaprincipal.= $curso." ($asignatura)</h4></th></tr></thead><tbody>";

		if(!($t_grupos=="")){
			$filaprincipal.= "<br><small><strong>Fecha:</strong> ";
			if(isset($fecha_dia)){$filaprincipal.= $fecha_dia;}else{ $filaprincipal.= date('d-m-Y');$fecha_dia=date('d-m-Y');$hoy=date('Y-m-d');}
			$filaprincipal.= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Día:</strong> $nom_dia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Hora:</strong> $hora_dia";
			if(!($hora_dia == "Fuera del Horario Escolar")){$filaprincipal. "ª hora";}
			echo "</small>";
		}
		echo "";
		if ($diversificacion!==1) {
			$curso = $hora2[1];
		}
		echo $filaprincipal;
		$n="";
		while($row = mysqli_fetch_array($result)){
			$n+=1;
			$chkT="";
			$chkF="";
			$chkJ="";
			$chkR="";
			$combasi = $row[5];

			$nc_grupo = $row[0];

			$sel = mysqli_query($db_con,"select alumnos from grupos where profesor like (select distinct prof from horw where c_prof = $c_prof) and curso = '$curso' and ($cod_asig)");
			$hay_grupo = mysqli_num_rows($sel);
			if ($hay_grupo>0) {
				$alumnos_separado_comas = "";
				while($sel_al = mysqli_fetch_array($sel)){
					$alumnos_separado_comas .= $sel_al[0].',';
				}

				$alumnos_separado_comas = rtrim($alumnos_separado_comas, ',');

				$al_sel = explode(",",$alumnos_separado_comas);
				$hay_al="";
				foreach($al_sel as $num_al){
					if ($num_al == $nc_grupo) {
						$hay_al = "1";
					}
				}
			}

			$result_bil = mysqli_query($db_con, "SELECT `bilinguismo` FROM `matriculas` WHERE `claveal` = '".$row['CLAVEAL']."' LIMIT 1");
			$row_bil = mysqli_fetch_array($result_bil);
			$esBilingue = ($row_bil['bilinguismo'] == 'Si') ? 1 : 0;

			$result_cc = mysqli_query($db_con, "SELECT `fecha` FROM `compromiso_convivencia` WHERE `nie` = '".$row['CLAVEAL']."' LIMIT 1");
			$esCompromisoConvivencia = (mysqli_num_rows($result_cc) > 0) ? 1 : 0;

			if ($hay_al=="1" or $hay_grupo<1) {
				if ($row[5] != "") {
					?>

					<tr>
					<td class="text-center" width="70">
 
					<?php

					if ($foto = obtener_foto_alumno($row['CLAVEAL'])) {
						echo '<img class="img-thumbnail" src="../xml/fotos/'.$foto.'" style="width: 45px !important;" alt="">';
					}
					else {
						echo '<span class="img-thumbnail far fa-user fa-fw fa-2x" style="width: 45px !important;"></span>';
					}
					echo '</td>';

					// Código para eliminar el curso 2019
					if ($config['mod_matriculacion']==1 and $asignat=='141346') {
						$extra_tic="";
						$result_tic = mysqli_query($db_con, "SELECT optativa1, exencion FROM `matriculas` WHERE (`claveal` = '".$row['CLAVEAL']."' or (apellidos='$row[2]' and nombre='$row[3]')) and optativa1 = '3' LIMIT 1");
						if (mysqli_num_rows($result_tic)>0) {
							$exe = mysqli_fetch_array($result_tic);
							if ($exe[1]==1) {
								//$extra_tic = 'background-color:yellow;';
							}
							else {
								$extra_tic = 'background-color:yellow;';
							}							
						}
					}


					echo "<td style='vertical-align:middle;$extra_tic'>
				<label for='falta_".$row[0]."_".$curso."' style='display:block;'>
					<span class='label label-info'>$n</span>
					&nbsp;&nbsp;$row[2], $row[3]
				";
					if ($row[4] == "2" or $row[4] == "3") {echo " <span class=\"label label-default\" data-bs=\"tooltip\" title=\"Repetidor\">(R)</span>";}
					if ($esBilingue) echo ' <span class="label label-warning" data-bs="tooltip" title="Bilingüe">(B)</span>';
					if ($esCompromisoConvivencia) echo ' <span class="label label-info" data-bs="tooltip" title="Compromiso de convivencia">(CC)</span>';
				}
				echo "<span class='pull-right' style='margin-right:5px'>";


				$fecha_hoy = date('Y')."-".date('m')."-".date('d');

				// Festivos, curso escolar y futuro
				$diames = date("j");
				$nmes = date("n");
				$nano = date("Y");
				$hoy_hoy = mktime(0,0,0,$nmes,$diames,$nano);

				$fecha0 = explode('-',$hoy);
				$dia0 = $fecha0[0];
				$mes0 = $fecha0[1];
				$ano0 = $fecha0[2];

				$hoy2 = strtotime($hoy);

				$comienzo_del_curso = strtotime($config['curso_inicio']);

				// Es festivo
				$fiesta=mysqli_query($db_con, "select fecha from festivos where date(fecha) = date('$hoy')");

				if (mysqli_num_rows($fiesta) > '0') {
					$dia_festivo='1';
				}

				$hoy_num = strtotime($hoy);
				$inicio_num = strtotime($config['curso_inicio']);
				$fin_num = strtotime($config['curso_fin']);

				// Tiene actividad extraescolar en la fecha
				$hay_actividad="";
				$extraescolar=mysqli_query($db_con, "select cod_actividad from actividadalumno where claveal = '$row[0]' and cod_actividad in (select id from calendario where date(fechaini) <= date('$hoy') and date(fechafin) >= date('$hoy'))");
				if (mysqli_num_rows($extraescolar) > '0') {
					while($actividad = mysqli_fetch_array($extraescolar)){
						$tr = mysqli_query($db_con,"select * from calendario where id = '$actividad[0]' and  (hour(horaini)<= (select hora_inicio from tramos where hora = '$hora_dia') or hour(horaini)='00:00:00') and (hour(horafin)>= (select hora_fin from tramos where hora = '$hora_dia') or hour(horafin)='00:00:00' )");
						if (mysqli_num_rows($tr)>0) {
							$hay_actividad = 1;
						}
					}
				}

				// Expulsado del Centro o Aula de Convivencia en la fecha
				$hay_expulsión="";
				$extra_act="";
				$exp=mysqli_query($db_con, "select expulsion, aula_conv from Fechoria where claveal = '$row[0]' and ((expulsion > '0' and date(inicio) <= date('$hoy') and date(fin) >= date('$hoy')) or (aula_conv > '0' and date(inicio_aula) <= date('$hoy') and date(fin_aula) >= date('$hoy')))");
				if (mysqli_num_rows($exp) > '0') {
							$hay_expulsión = 1;
				}

				$falta_d = mysqli_query($db_con, "select distinct falta from FALTAS where dia = '$ndia' and hora = '$hora_dia' and claveal = '$row[0]' and fecha = '$hoy'");
				$falta_dia = mysqli_fetch_array($falta_d);
				if ($falta_dia[0] == "F") {
					$chkF = "checked";
				}
				elseif ($falta_dia[0] == "J"){
					$chkJ = 'checked';
					$chkT = 'data-bs="tooltip" data-placement="right" title="Justificada por el Tutor"';
				}
				elseif ($falta_dia[0] == "R"){
					$chkR = 'checked';
					$chkT = 'data-bs="tooltip" data-placement="right" title="Justificada por el Tutor"';
				}
				elseif ($hay_actividad==1){
					$chkF = 'id="disable" disabled';
					$chkJ = 'id="disable" disabled';
					$chkR = 'id="disable" disabled';
					$chkT = 'data-bs="tooltip" data-placement="right" title="Actividad Extraescolar o Complementaria"';
					$extra_act = 'background-color:#ddd;padding:10px;';
				}
				elseif ($hay_expulsión==1){
					$chkF = 'id="disable" disabled';
					$chkJ = 'id="disable" disabled';
					$chkR = 'id="disable" disabled';
					$chkT = 'data-bs="tooltip" data-placement="right" title="Alumno expulsado del Centro o en el Aula de Convivencia"';
					$extra_act = 'background-color:#eea;padding:10px;';
				}
				elseif ($hoy2 > $hoy_hoy) {
					$chkF = 'id="disable" disabled';
					$chkJ = 'id="disable" disabled';
					$chkR = 'id="disable" disabled';
					$chkT = 'data-bs="tooltip" data-placement="right" data-html="true" title="No es posible poner Faltas en el <b>Futuro</b>.<br>Comprueba la Fecha."';
				}

?>

<div style="width: 120px; display: block;<?php echo $extra_act; ?>" <?php echo $chkT; ?>>
<span class="text-danger">F</span>
<input type="radio"	id="falta_<?php echo $row[0]."_".$curso;?>"
	name="falta_<?php echo $row[0]."_".$curso;?>" <?php echo $chkF; ?>
	value="F" onClick="uncheckRadio(this)" /> &nbsp;
<span class="text-success">J</span>
<input type="radio" id="falta_<?php echo $row[0]."_".$curso;?>"
	name="falta_<?php echo $row[0]."_".$curso;?>" <?php echo $chkJ; ?>
	value="J" onClick="uncheckRadio(this)" /> &nbsp;
<span class="text-warning">R</span>
<input type="radio"	id="falta_<?php echo $row[0]."_".$curso;?>"
	name="falta_<?php echo $row[0]."_".$curso;?>" <?php echo $chkR; ?>
	value="R" onClick="uncheckRadio(this)" />
</div>

</span></label>

</td>

<td>

<?php

//Cuaderno correcta
$faltaT_F = mysqli_query($db_con,"select falta from FALTAS where profesor = (select distinct c_prof from horw where prof ='$pr') and ($fal_e) and claveal='$row[0]' and falta='F'");

$faltaT_J = mysqli_query($db_con,"select falta from FALTAS where profesor = (select distinct c_prof from horw where prof ='$pr') and ($fal_e) and claveal='$row[0]' and falta='J'");
$f_faltaT = mysqli_num_rows($faltaT_F);
$f_justiT = mysqli_num_rows($faltaT_J);
?>
<div class="label label-danger" data-bs='tooltip'
	title='Faltas de Asistencia en esta Asignatura'><?php if ($f_faltaT>0) {echo "".$f_faltaT."";}?></div>
<?php
if ($f_faltaT>0 OR $f_justiT>0) {echo "<br>";}
?>
<div class="label label-success" data-bs='tooltip'
	title='Faltas Justificadas'><?php echo $f_justiT;?></div>
</td>
</tr>

<?php
			}
		}
	}
?>

<?php
	echo '</tbody></table>';
}
echo '<input name="nprofe" type="hidden" value="';
echo $c_prof;
echo '" />';
// Hora escolar
echo '<input name="hora" type="hidden" value="';
echo $hora_dia;
echo '" />';
// dia de la semana
echo '<input name="ndia" type="hidden" value="';
echo $ndia;
echo '" />';
// Hoy
echo '<input name="hoy" type="hidden" value="';
echo $hoy;
echo '" />';
// Codigo asignatura
echo '<input name="codasi" type="hidden" value="';
echo $codasi;
echo '" />';
// Profesor
echo '<input name="profesor" type="hidden" value="';
echo $pr;
echo '" />';
if (!empty($profesor_ausente)) {
	// Profesor ausente
echo '<input name="profesor_ausente" type="hidden" value="';
echo $profesor_ausente;
echo '" />';

echo '<div class="well"
		<div class="checkbox">
			<label class="text-danger">
			   <input type="checkbox" name="reg_guardias" value="1" checked> Registrar la sustitución del Profesor ausente
			</label>
		</div>
	</div><br>';

}

// Clave
echo '<input name="clave" type="hidden" value="';
echo $clave;
echo '" />';
echo '<input name="fecha_dia" type="hidden" value="';
echo $fecha_dia;
echo '" />';

if($result){echo '<button name="enviar" type="submit" value="Enviar datos" class="btn btn-primary btn-large"><i class="fas fa-check"> </i> Registrar faltas de asistencia</button>';}

?></form>
</div>
</div>

</div>
</div>
<?php
}

else {
	echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El módulo de Faltas de Asistencia debe ser activado en la Configuración general de la Intranet para poder accede a estas páginas, y ahora mismo está desactivado.
          </div></div>';
	echo "<div style='color:brown; text-decoration:underline;'>Las Faltas han sido registradas.</div>";
}
?>
<?php
include("../pie.php");
?>

<?php
$exp_inicio_curso = explode('-', $config['curso_inicio']);
$inicio_curso = $exp_inicio_curso[2].'/'.$exp_inicio_curso[1].'/'.$exp_inicio_curso[0];

$exp_fin_curso = explode('-', $config['curso_fin']);
$fin_curso = $exp_fin_curso[2].'/'.$exp_fin_curso[1].'/'.$exp_fin_curso[0];

$result = mysqli_query($db_con, "SELECT fecha FROM festivos ORDER BY fecha ASC");
$festivos = '';
while ($row = mysqli_fetch_array($result)) {
	$exp_festivo = explode('-', $row['fecha']);
	$dia_festivo = $exp_festivo[2].'/'.$exp_festivo[1].'/'.$exp_festivo[0];

	$festivos .= '"'.$dia_festivo.'", ';
}

$festivos = substr($festivos,0,-2);
?>
<script>
	$(function ()
	{
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false,
			minDate:'<?php echo $inicio_curso; ?>',
			maxDate:'<?php echo $fin_curso; ?>',
			disabledDates: [<?php echo $festivos; ?>],
			daysOfWeekDisabled:[0,6]
		});
	});

	$('#datetimepicker1').change(function() {
	  $('#form1').submit();
	});
	</script>
<script>
$('#disable').tooltip('show')
</script>
<script>

function seleccionar_todo(){
	for (i=0;i<document.Cursos.elements.length;i++)
		if(document.Cursos.elements[i].type == "checkbox")
			document.Cursos.elements[i].checked=1
}
function deseleccionar_todo(){
	for (i=0;i<document.Cursos.elements.length;i++)
		if(document.Cursos.elements[i].type == "checkbox")
			document.Cursos.elements[i].checked=0
}
</script>

<script>
var checkedradio;
function uncheckRadio(rbutton) {
    if (checkedradio == rbutton) {
        rbutton.checked = false;
        checkedradio = null;
    }
    else {checkedradio = rbutton;}
}
</script>
</body>
</html>
