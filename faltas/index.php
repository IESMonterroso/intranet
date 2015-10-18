<?php
require('../bootstrap.php');

$pr = $_SESSION['profi'];
$prof1 = "SELECT distinct no_prof FROM horw where prof = '$pr'";
$prof0 = mysqli_query($db_con, $prof1);
$filaprof0 = mysqli_fetch_array($prof0);
$profesi = $filaprof0[0]."_ ".$pr;
$_SESSION['todo_profe'] = $profesi;

if (isset($_POST['fecha_dia'])) {$fecha_dia = $_POST['fecha_dia'];}elseif (isset($_GET['fecha_dia'])) {$fecha_dia = $_GET['fecha_dia'];}
if (isset($_POST['hora_dia'])) {$hora_dia = $_POST['hora_dia'];}elseif (isset($_GET['hora_dia'])) {$hora_dia = $_GET['hora_dia'];}

if(empty($hora_dia)){
	$hora = date("G");// hora ahora
	$minutos = date("i");

	// Se han importado los daos de la tramos escolar desde S�neca
	$jor = mysqli_query($db_con,"select hora, hora_inicio, hora_fin from tramos");
	if(mysqli_num_rows($jor)>0){
		while($jornad = mysqli_fetch_array($jor)){
			$hora_real = $hora."".$minutos;
			$h_ini = str_replace(":", "",$jornad[1]);
			$h_fin = str_replace(":", "",$jornad[2]);

			if( $hora_real > $h_ini and $hora_real < $h_fin){
				$hora_dia = $jornad[0];
				break;
			}
			else{
				$hora_dia = $jornad[0];
			}
		}

	}
	else{
		// No se han importado: se asume el horario del Monterroso

		if(($hora == '8' and $minutos > 15 ) or ($hora == '9' and $minutos < 15 ) ){$hora_dia = '1';}
		elseif(($hora == '9' and $minutos > 15 ) or ($hora == '10' and $minutos < 15 ) ){$hora_dia = '2';}
		elseif(($hora == '10' and $minutos > 15 ) or ($hora == '11' and $minutos < 15 ) ){$hora_dia = '3';}
		elseif(($hora == '11' and $minutos > 15 ) and ($hora == '11' and $minutos < 45 ) ){$hora_dia = 'R';}
		elseif(($hora == '11' and $minutos > 45 ) or ($hora == '12' and $minutos < 45 ) ){$hora_dia = '4';}
		elseif(($hora == '12' and $minutos > 45 ) or ($hora == '13' and $minutos < 45 ) ){$hora_dia = '5';}
		elseif(($hora == '13' and $minutos > 45 ) or ($hora == '14' and $minutos < 45 ) ){$hora_dia = '6';}
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
	$ndia = date("w");// n� de d�a de la semana (1,2, etc.)
	$hoy = date("Y-m-d");
	$hoy_actual = "$diames-$nmes-$nano";
}

if($ndia == "1"){$nom_dia = "Lunes";}
if($ndia == "2"){$nom_dia = "Martes";}
if($ndia == "3"){$nom_dia = "Mi�rcoles";}
if($ndia == "4"){$nom_dia = "Jueves";}
if($ndia == "5"){$nom_dia = "Viernes";}
?>
<?php
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
<h2 style="display:inline;">Faltas de Asistencia <small> Poner faltas</small></h2>
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
	class="fa fa-calendar"></span></span></div>
</div>
<div class="form-group"><label for="grupo">Grupo</label> <select
	class="form-control" id="hora_dia" name="hora_dia" onChange=submit()>
	<?php

	for ($i = 1; $i < 7; $i++) {
		$gr_hora = mysqli_query($db_con,"select a_grupo, asig from horw_faltas where hora = '$i' and dia='$ndia' and prof = '$pr' and a_grupo not like '' and c_asig not in (select distinct idactividad from actividades_seneca where idactividad not like '2' and idactividad not like '21' and idactividad not like '386')");
		if (mysqli_num_rows($gr_hora)>0) {

			while ($grupo_hora = mysqli_fetch_array($gr_hora)) {
				$grup.="$grupo_hora[0] ";
				$asign = $grupo_hora[1];
			}
			$grupos = "$grup ($asign)";

		}

		//echo "<option>select a_grupo, asig from horw_faltas where hora = '$i' and dia='$ndia' and prof = '$pr' and a_grupo not like ''</otion>";
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

<button type="submit" class="btn btn-primary" name="aceptar">Aceptar</button>

</fieldset>

</form>
</div>
</div>

<div class="col-md-7">
<div align="left"><?php
//$hora1 = "select distinct c_asig, a_grupo, asig from horw where no_prof = '30' and dia = '1' and hora = '1'";

if ($ndia>5) {
	?>
<h2 class="text-muted text-center"><span class="fa fa-clock-o fa-5x"></span>
<br>
Fuera de horario escolar</h2>
	<?php
}
else{
	$hora1 = "select distinct c_asig, a_grupo, asig from horw_faltas where no_prof = '$filaprof0[0]' and dia = '$ndia' and hora = '$hora_dia' and a_grupo not like ''";
	$hora0 = mysqli_query($db_con, $hora1);
	if (mysqli_num_rows($hora0)<1) {
		?>
<h2 class="text-muted text-center"><span class="fa fa-clock-o fa-5x"></span>
<br>
Sin alumnos en esta hora (<?php echo $hora_dia;  if (is_numeric($hora_dia)) echo "�";?>)</h2>
		<?php
	}
}
while($hora2 = mysqli_fetch_row($hora0))
{
	$c_a="";
	$codasi= $hora2[0];
	if (empty($hora2[1])) {
		$curso="";
	}
	else{
		$curso = $hora2[1];
	}
	$asignatura = $hora2[2];

	$nivel_curso = substr($curso,0,1);

	//	Problemas con Diversificaci�n (4E-Dd)
	$diversificacion = "";
	$profe_div = mysqli_query($db_con, "select * from profesores where grupo = '$curso'");
	if (mysqli_num_rows($profe_div)<1) {

		$grupo_div = mysqli_query($db_con, "select distinct unidad from alma where unidad like '$nivel_curso%' and (combasi like '%25204%' or combasi LIKE '%25226%' OR combasi LIKE '%135785%')");
		if (mysqli_num_rows($grupo_div)>0) {
			$diversificacion = 1;
			$div = $curso;
			$grupo_diver = mysqli_fetch_row($grupo_div);
			$curso = $grupo_diver[0];
			$c_a="(combasi like '%25204%' or combasi LIKE '%25226%' OR combasi LIKE '%135785%') or ";
		}
	}
	?>
<form action="poner_falta.php<?php echo $extra;?>" method="post"
	name="Cursos"><?php

	// Codigo del profe
	//echo "$hora_dia -- $ndia -- $hoy -- $codasi -- $pr -- $clave<br>";
	
	$res = "select distinctrow FALUMNOS.CLAVEAL, FALUMNOS.NC, FALUMNOS.APELLIDOS, FALUMNOS.NOMBRE, alma.MATRICULAS, alma.combasi from FALUMNOS, alma WHERE FALUMNOS.CLAVEAL = alma.CLAVEAL and FALUMNOS.unidad = '$curso' and ( ";
	//$n_curs10 = "select distinct c_asig from horw where no_prof = '30' and dia = '1' and hora = '1'";
	$n_curs10 = "select distinct c_asig from horw_faltas where no_prof = '$filaprof0[0]' and dia = '$ndia' and hora = '$hora_dia'";
	$n_curs11 = mysqli_query($db_con, $n_curs10);
	$nm = mysqli_num_rows($n_curs11);
	if (strlen($c_a)>0) {}else{
	while ($nm_asig0=mysqli_fetch_array($n_curs11)){
		$c_a.="combasi like '%".$nm_asig0[0]."%' or ";
	}
	}

	$res.=substr($c_a,0,strlen($c_a)-3);
	$res.=") order by NC";
	//echo $res;
	$result = mysqli_query($db_con, $res);
	if ($result) {
		$t_grupos = $curs;

		echo "<br><table class='table table-striped table-bordered table-condensed table-hover'>\n";
		$filaprincipal = "<thead><tr><th colspan='3'><h4 align='center' class='text-info'>";

		//$filaprincipal.= substr($t_grupos,0,-2);

		$filaprincipal.= $curso." ($asignatura)";

		/*	if(!($t_grupos=="")){
		 $filaprincipal.= "<br><small><strong>Fecha:</strong> $hoy_actual &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>D�a:</strong> $nom_dia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Hora:</strong> $hora_dia";
		 if(!($hora_dia == "Fuera del Horario Escolar")){$filaprincipal. "� hora";}
		 echo "</small>";
		 }
		 */
		if(!($t_grupos=="")){
			$filaprincipal.= "<br><small><strong>Fecha:</strong> ";
			if(isset($fecha_dia)){$filaprincipal.= $fecha_dia;}else{ $filaprincipal.= date('d-m-Y');$fecha_dia=date('d-m-Y');$hoy=date('Y-m-d');}
			$filaprincipal.= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>D�a:</strong> $nom_dia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Hora:</strong> $hora_dia";
			if(!($hora_dia == "Fuera del Horario Escolar")){$filaprincipal. "� hora";}
			echo "</small>";
		}
		echo "</h4></th></tr></thead>";
		if ($diversificacion!==1) {
			$curso = $hora2[1];
		}
		echo $filaprincipal;

		while($row = mysqli_fetch_array($result)){
			$n+=1;
			$chkT="";
			$chkF="";
			$chkJ="";
			$chkR="";
			$combasi = $row[5];
			if ($row[5] == "") {}
			else{
				echo "<tr>";
				$foto = '../xml/fotos/'.$row[0].'.jpg';
				if (file_exists($foto)) {
					echo '<td class="text-center" width="70"><img src="'.$foto.'" width="50" height="60" alt=""></td>';
				}
				else {
					echo '<td><span class="fa fa-user fa-fw fa-3x"></span></td>';
				}

				echo "<td style='vertical-align:middle'>
				<label for='falta_".$row[1]."_".$curso."' style='display:block;'>
					<span class='label label-info'>$row[1]</span>
					&nbsp;&nbsp;$row[2], $row[3]
				";
				if ($row[4] == "2" or $row[4] == "3") {echo " (R)";}
			}
			echo "<span class='pull-right' style='margin-right:5px'>";


			$fecha_hoy = date('Y')."-".date('m')."-".date('d');

			// Tiene actividad extraescolar en la fecha
			$hay_actividad="";
			$extraescolar=mysqli_query($db_con, "select cod_actividad from actividadalumno where claveal = '$row[0]' and cod_actividad in (select id from calendario where date(fechaini) >= date('$hoy') and date(fechafin) <= date('$hoy'))");
			if (mysqli_num_rows($extraescolar) > '0') {
				while($actividad = mysqli_fetch_array($extraescolar)){
					$tr = mysqli_query($db_con,"select * from calendario where id = '$actividad[0]' and horaini<= (select hora_inicio from tramos where tramo = '$hora_dia') and horafin>= (select hora_fin from tramos where tramo = '$hora_dia')");
					if (mysqli_num_rows($tr)>0) {
						$hay_actividad = 1;
					}
				}
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
			}
			?> 

			<div style="width:120px; display:inline;" <?php echo $chkT; ?>>
			<span class="text-danger">F</span> <input type="radio" id="falta_<?php echo $row[1]."_".$curso;?>"	name="falta_<?php echo $row[1]."_".$curso;?>" <?php echo $chkF; ?> value="F" onClick="uncheckRadio(this)" /> &nbsp; 
			<span class="text-success">J</span> <input type="radio" id="falta_<?php echo $row[1]."_".$curso;?>" name="falta_<?php echo $row[1]."_".$curso;?>" <?php echo $chkJ; ?> value="J" onClick="uncheckRadio(this)"/> &nbsp; 
			<span class="text-warning">R</span> <input type="radio" id="falta_<?php echo $row[1]."_".$curso;?>" name="falta_<?php echo $row[1]."_".$curso;?>" <?php echo $chkR; ?> value="R" onClick="uncheckRadio(this)" /></div>

			<?php
			echo "</span></label></td>";
			?>
<td><?php 
$faltaT_F = mysqli_query($db_con,"select falta from FALTAS where profesor = (select distinct no_prof from horw where prof ='$pr') and FALTAS.codasi='$codasi' and claveal='$row[0]' and falta='F'");

$faltaT_J = mysqli_query($db_con,"select falta from FALTAS where profesor = (select distinct no_prof from horw where prof ='$pr') and FALTAS.codasi='$codasi' and claveal='$row[0]' and falta='J'");
$f_faltaT = mysqli_num_rows($faltaT_F);
$f_justiT = mysqli_num_rows($faltaT_J);
?> <div class="label label-danger" data-bs='tooltip'
	title='Faltas de Asistencia en esta Asignatura'><?php if ($f_faltaT>0) {echo "".$f_faltaT."";}?></div>
<?php
if ($f_faltaT>0) {echo "<br><br>";}
?> <div class="label label-success" data-bs='tooltip'
	title='Faltas Justificadas'><?php if ($f_faltaT>0) {echo "".$f_justiT."";}?></div>
</td>
<?php
echo "</tr>";
		}
	}
	?>
	<?php
	echo '</table>';
}
echo '<input name="nprofe" type="hidden" value="';
echo $filaprof0[0];
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
// Clave
echo '<input name="clave" type="hidden" value="';
echo $clave;
echo '" />';
echo '<input name="fecha_dia" type="hidden" value="';
echo $fecha_dia;
echo '" />';
if($result){echo '<button name="enviar" type="submit" value="Enviar datos" class="btn btn-primary btn-large"><i class="fa fa-check"> </i> Registrar faltas de asistencia</button>';}

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
El m�dulo de Faltas de Asistencia debe ser activado en la Configuraci�n general de la Intranet para poder accede a estas p�ginas, y ahora mismo est� desactivado.
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
