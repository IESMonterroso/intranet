<?php
require('../bootstrap.php');


if (isset($_GET['recurso'])) {

	switch ($_GET['recurso']) {
		case 'aula_grupo'    : $naulas = $num_aula_grupo+1; $nombre_rec = "Aulas y Dependencias del Centro"; break;
	}

}
else {
	header('Location:'.'index_aula.php?recurso=aula_grupo');
}

include("../menu.php");
include("menu.php");
?>
<div class="container">

<div class="page-header">
<h2>Sistema de Reservas <small><?php echo $nombre_rec; ?></small></h2>
</div>

<div class="row">

<div class="col-sm-4 col-sm-offset-4">

<div class="well">
<form method="post" action="reservar/index_aulas.php">
<fieldset><legend>Selecciona Aula o Dependencia</legend>

<div class="form-group"><?php
$ocul = mysqli_query($db_con,"select * from ocultas");
if(mysqli_num_rows($ocul)>0){
	$extra_ocultas1 = "and a_aula not in (select distinct aula from ocultas)";
	$extra_ocultas2 = "and abrev not in (select distinct aula from ocultas)";
}
?>
<select class="form-control" name="servicio_aula" onchange="submit()">
	<option value=""></option>
<?php
$aula_res = "SELECT DISTINCT a_aula, n_aula FROM $db.horw WHERE a_aula NOT LIKE 'GU' AND a_aula NOT LIKE 'GUCON' AND a_aula NOT LIKE '' $extra_ocultas1 ORDER BY n_aula ASC";
$result = mysqli_query($db_con,$aula_res);
?>
<?php if(mysqli_num_rows($result)>0): ?>
  <optgroup label="Aulas del Horario">
	<?php while ($row = mysqli_fetch_array($result)):?>
	<?php $value = $row['a_aula'].' ==> '.$row['n_aula']; ?>
	<option value="<?php echo $value; ?>"><?php echo $row['n_aula']; ?></option>
	<?php endwhile; ?>
	</optgroup>
	<?php endif; ?>
<?php
$aula_res2 = "SELECT DISTINCT abrev, nombre FROM nuevas WHERE abrev NOT LIKE '' $extra_ocultas2 ORDER BY abrev ASC";
$result2 = mysqli_query($db_con,$aula_res2); ?>
<?php if(mysqli_num_rows($result2)): ?>
  <optgroup label="Aulas fuera del Horario">
	<?php while ($row2 = mysqli_fetch_array($result2)):?>
	<?php $value2 = $row2['abrev'].' ==> '.$row2['nombre']; ?>
	<option value="<?php echo $value2; ?>"><?php echo $row2['nombre']; ?></option>
	<?php endwhile; ?>
	</optgroup>
	<?php endif;?>

</select>
</div>

</fieldset>
</form>
</div>

</div>

</div>

	<?php

	if (isset($_GET['month'])) { $month = $_GET['month']; $month = preg_replace ("/[[:space:]]/", "", $month); $month = preg_replace ("/[[:punct:]]/", "", $month); $month = preg_replace ("/[[:alpha:]]/", "", $month); }
	if (isset($_GET['year'])) { $year = $_GET['year']; $year = preg_replace ("/[[:space:]]/", "", $year); $year = preg_replace ("/[[:punct:]]/", "", $year); $year = preg_replace ("/[[:alpha:]]/", "", $year); if ($year < 1990) { $year = 1990; } if ($year > 2035) { $year = 2035; } }
	if (isset($_GET['today'])) { $today = $_GET['today']; $today = preg_replace ("/[[:space:]]/", "", $today); $today = preg_replace ("/[[:punct:]]/", "", $today); $today = preg_replace ("/[[:alpha:]]/", "", $today); }


	$month = (isset($month)) ? $month : date("n",time());
	$year = (isset($year)) ? $year : date("Y",time());
	$today = (isset($today))? $today : date("j", time());
	$daylong = date("l",mktime(1,1,1,$month,$today,$year));
	$monthlong = date("F",mktime(1,1,1,$month,$today,$year));
	$dayone = date("w",mktime(1,1,1,$month,1,$year))-1;
	$numdays = date("t",mktime(1,1,1,$month,1,$year));
	$alldays = array('L','M','X','J','V','S','D');
	$next_year = $year + 1;
	$last_year = $year - 1;
	if ($daylong == "Sunday")
	{$daylong = "Domingo";}
	elseif ($daylong == "Monday")
	{$daylong = "Lunes";}
	elseif ($daylong == "Tuesday")
	{$daylong = "Martes";}
	elseif ($daylong == "Wednesday")
	{$daylong = "Miércoles";}
	elseif ($daylong == "Thursday")
	{$daylong = "Jueves";}
	elseif ($daylong == "Friday")
	{$daylong = "Viernes";}
	elseif ($daylong == "Saturday")
	{$daylong = "Sábado";}


	if ($monthlong == "January")
	{$monthlong = "Enero";}
	elseif ($monthlong == "February")
	{$monthlong = "Febrero";}
	elseif ($monthlong == "March")
	{$monthlong = "Marzo";}
	elseif ($monthlong == "April")
	{$monthlong = "Abril";}
	elseif ($monthlong == "May")
	{$monthlong = "Mayo";}
	elseif ($monthlong == "June")
	{$monthlong = "Junio";}
	elseif ($monthlong == "July")
	{$monthlong = "Julio";}
	if ($monthlong == "August")
	{$monthlong = "Agosto";}
	elseif ($monthlong == "September")
	{$monthlong = "Septiembre";}
	elseif ($monthlong == "October")
	{$monthlong = "Octubre";}
	elseif ($monthlong == "November")
	{$monthlong = "Noviembre";}
	elseif ($monthlong == "December")
	{$monthlong = "Diciembre";}
	if ($today > $numdays) { $today--; }

	// Lugares y situación
	// Comprobamos que existe la tabla del aula
	$aula_res = mysqli_query($db_con,"select distinct servicio from reservas");
	while ($a_res=mysqli_fetch_array($aula_res)) {
		$aula_reservas.="$a_res[0] ";
	}

	//$reg = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula FROM horw WHERE a_aula NOT LIKE 'G%' AND a_aula NOT LIKE '' and a_aula not in (select distinct aula from ocultas) ORDER BY n_aula ASC");
	$reg = mysqli_query($db_con, "SELECT DISTINCT a_aula, n_aula FROM horw WHERE a_aula NOT LIKE 'G%' AND a_aula NOT LIKE '' ORDER BY n_aula ASC");

	$num_aula_grupo=mysqli_num_rows($reg);
	$ci = 0;
	$primero = 0;

	while ($au_grupo = mysqli_fetch_array($reg)){

		$servicio=$au_grupo[0];
		$lugar = $au_grupo[1];

		if (strstr($aula_reservas,$servicio)==TRUE) {

		$oc = mysqli_query($db_con,"select * from ocultas where aula = '$servicio'");
		//echo "select * from ocultas where aula = '$servicio'";

		if (mysqli_num_rows($oc)<1) {

		if (stristr($servicio,"medio")==FALSE and stristr($servicio,"TIC_")==FALSE and stristr($servicio,"usuario")==FALSE and stristr($servicio,"profesores")==FALSE and stristr($servicio,"hor")==FALSE) {

			if ($ci % 3 == 0 || $ci == 0){
				echo ($primero) ? '</div> <hr>' : '';
				echo '<div class="row">';
				$primero = 1;
			}

			echo '<div class="col-sm-4">';
			?> <a name="<?php echo $servicio; ?>"></a>
<h3 class="text-center"><?php echo $lugar; ?> <br>
<small><?php echo $servicio; ?></small></h3>

<table class="table table-bordered table-centered">
	<thead>
		<tr>
			<th colspan="7">
			<h4><?php echo $monthlong; ?></h4>
			</th>
		</tr>
		<tr>
		<?php foreach ($alldays as $value): ?>
			<th><?php echo $value; ?></th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<?php
	//Días vacíos
	if ($dayone < 0) $dayone = 6;
	for ($i = 0; $i < $dayone; $i++) {
		echo "<td>&nbsp;</td>";
	}


	//Días
	for ($zz = 1; $zz <= $numdays; $zz++) {
		if ($i >= 7) {  print("</tr><tr>"); $i=0; }
		// Mirar a ver si hay alguna ctividad en el días
		$result_found = 0;
		if ($zz == $today) {
			echo "<td class=\"calendar-today\">$zz</td>";
			$result_found = 1;
		}

		if ($result_found != 1) {
			//Buscar actividad para el día y marcarla
			$sql_currentday = "$year-$month-$zz";
			$eventQuery = "SELECT event1, event2, event3, event4, event5, event6, event7 FROM `reservas` WHERE eventdate = '$sql_currentday' and servicio='$servicio'";
			//echo $eventQuery;
			$eventExec = mysqli_query($db_con, $eventQuery );
			if (mysqli_num_rows($eventExec)>0) {
				while ( $row = mysqli_fetch_array ( $eventExec ) ) {
					echo "<td class=\"calendar-orange\">$zz</td>";
					$result_found = 1;
				}
			}
			else{
				$sql_currentday = "$year-$month-$zz";
				$fest = mysqli_query($db_con, "select distinct fecha, nombre from $db.festivos WHERE fecha = '$sql_currentday'");
				if (mysqli_num_rows($fest)>0) {
					$festiv=mysqli_fetch_array($fest);
					echo "<td class=\"calendar-red\">$zz</a></td>\n";
					$result_found = 1;
				}
			}

		}

		if ($result_found != 1) {
			echo "<td>$zz</td>";
		}

		$i++; $result_found = 0;
	}

	$create_emptys = 7 - (($dayone + $numdays) % 7);
	if ($create_emptys == 7) { $create_emptys = 0; }

	if ($create_emptys != 0) {
		echo "<td colspan=\"$create_emptys\">&nbsp;</td>";
	}

	echo "</tr>";
	echo "</table>";
	?>
	<div class="well">
	<h4 class="text-info">Próximos días</h4>
	<?php
	for ($i = $today; $i <= ($today + 6); $i++) {
		$current_day = $i;
		$current_year = $year;
		$current_month = $month;
		if ($i > $numdays) {
			$current_day = ($i - $numdays);
			$current_month = $month + 1;
			if ($current_month > 12) {
				$current_month = 1; $current_year = $year + 1;
			}
		}
		$dayname = date("l",mktime(1,1,1,$current_month,$current_day,$current_year));
		if ($dayname == "Sunday")
		{$dayname = "Domingo";}
		elseif ($dayname == "Monday")
		{$dayname = "Lunes";}
		elseif ($dayname == "Tuesday")
		{$dayname = "Martes";}
		elseif ($dayname == "Wednesday")
		{$dayname = "Miércoles";}
		elseif ($dayname == "Thursday")
		{$dayname = "Jueves";}
		elseif ($dayname == "Friday")
		{$dayname = "Viernes";}
		elseif ($dayname == "Saturday")
		{$dayname = "Sábado";}

		$sql_currentday = "$current_year-$current_month-$current_day";
		$eventQuery = "SELECT event1, event2, event3, event4, event5, event6, event7 FROM `reservas` WHERE eventdate = '$sql_currentday' and servicio='$servicio'";
		$eventExec = mysqli_query($db_con, $eventQuery);
		while($row = mysqli_fetch_array($eventExec)) {
			if (mysqli_num_rows($eventExec) == 1) {
				// $this_days_title = stripslashes($row["title"]);
				$event_event1 = stripslashes($row["event1"]);
				$event_event2 = stripslashes($row["event2"]);
				$event_event3 = stripslashes($row["event3"]);
				$event_event4 = stripslashes($row["event4"]);
				$event_event5 = stripslashes($row["event5"]);
				$event_event6 = stripslashes($row["event6"]);
				$event_event7 = stripslashes($row["event7"]);
			}
		}

		echo '<p><span class="far fa-calendar fa-fw"></span> '.$dayname.' - '.$current_day.'</p>';
		echo '<a href="//'.$config['dominio'].'/intranet/reservas/reservar/index_aulas.php?year='.$current_year.'&today='.$current_day.'&month='.$current_month.'&servicio='.$servicio.'">';

		//Nombre del día
		if (mysqli_num_rows($eventExec) == 1)
		{
			if ($event_event1 !== "" && substr($event_event1,-1)!==")") {
				echo "<p>1ª hora: $event_event1</p>";
			}
			if ($event_event2 !== "" && substr($event_event2,-1)!==")") {
				echo "<p>2ª hora: $event_event2</p>";
			}
			if ($event_event3 !== "" && substr($event_event3,-1)!==")") {
				echo "<p>3ª hora: $event_event3</p>";
			}
			if ($event_event4 !== "" && substr($event_event4,-1)!==")") {
				echo "<p>4ª hora: $event_event4</p>";
			}
			if ($event_event5 !== "" && substr($event_event5,-1)!==")") {
				echo "<p>5ª hora: $event_event5</p>";
			}
			if ($event_event6 !== "" && substr($event_event6,-1)!==")") {
				echo "<p>6ª hora: $event_event6</p>";
			}
			if ($event_event7 !== "" && substr($event_event7,-1)!==")") {
				echo "<p>7ª hora: $event_event7</p>";
			}
		}

		echo "</a></p>";

		//$this_days_title = "";
		$event_event1 = "";
		$event_event2 = "";
		$event_event3 = "";
		$event_event4 = "";
		$event_event5 = "";
		$event_event6 = "";
		$event_event7 = "";
	}
	echo '<br>';
	echo '<a class="btn btn-primary btn-block" href="//'.$config['dominio'].'/intranet/reservas/reservar/index_aulas.php?servicio='.$servicio.'">Reservar...</a>';
	echo '</div>';
	echo '</div>';

	$ci+=1;
		}
	}					}
	}
	echo '</div>';
	?></div>

	<?php include("../pie.php");?>

	</body>
	</html>
