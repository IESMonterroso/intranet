<?
if (isset($_GET['month'])) { $month = $_GET['month']; $month = preg_replace ("/[[:space:]]/", "", $month); $month = preg_replace ("/[[:punct:]]/", "", $month); $month = preg_replace ("/[[:alpha:]]/", "", $month); }
if (isset($_GET['year'])) { $year = $_GET['year']; $year = preg_replace ("/[[:space:]]/", "", $year); $year = preg_replace ("/[[:punct:]]/", "", $year); $year = preg_replace ("/[[:alpha:]]/", "", $year); if ($year < 1990) { $year = 1990; } if ($year > 2035) { $year = 2035; } }
if (isset($_GET['today'])) { $today = $_GET['today']; $today = preg_replace ("/[[:space:]]/", "", $today); $today = preg_replace ("/[[:punct:]]/", "", $today); $today = preg_replace ("/[[:alpha:]]/", "", $today); }

$month = (isset($month)) ? $month : date("n",time());
$year = (isset($year)) ? $year : date("Y",time());
$today = (isset($today))? $today : date("j", time());
$daylong = date("l",mktime(1,1,1,$month,$today,$year));
$monthlong = date("F",mktime(1,1,1,$month,$today,$year));
$dayone = date("w",mktime(1,1,1,$month,1,$year));
$numdays = date("t",mktime(1,1,1,$month,1,$year));
$alldays = array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');
$next_year = $year + 1;
$last_year = $year - 1;
    if ($daylong == "Sunday")
	{$daylong = "Domingo";}
    elseif ($daylong == "Monday")
	{$daylong = "Lunes";}
    elseif ($daylong == "Tuesday")
	{$daylong = "Martes";}
    elseif ($daylong == "Wednesday")
	{$daylong = "M�rcoles";}
    elseif ($daylong == "Thursday")
	{$daylong = "Jueves";}
    elseif ($daylong == "Friday")
	{$daylong = "Viernes";}
    elseif ($daylong == "Saturday")
	{$daylong = "S�bado";}
    

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

// Estructura de la Tabla
?>
<br />
<div class="row-fluid">
<div class=span6>
<legend class='text-warning' align='center'><br />Instrucciones de uso</legend>
<p class="help-block text-info" style="text-align:justify">
A trav�s de esta p�gina puedes registrar las pruebas, controles o actividades de distinto tipo para los alumnos de los Grupos a los que das clase, o bien puedes utilizar esta p�gina para registrar entradas p�rsonales como si fuera un calendario. <em>Los registros que est�n relacionados con tus Grupos y Asignaturas aparecer�n en el Calendario de la Intranet, pero tambi�n en la p�gina personal del alumno en la P�gina del Centro</em>, de tal modo que Padres y Alumnos puedan ver en todo momento las fechas de las pruebas; <em>si la actividad no contiene Grupo alguno aparecer� s�lo en el Calendario de la Intranet a modo de recordatorio</em>. La fecha y el T�ulo de la actividad son los �nicos campos obligatorios. Puedes editar, ver y borrar los registros mediante los iconos de la tabla que presenta todas tus actividades.
</p>
</div>
<div class=span6>
    <?
	echo "<legend class='text-warning' align='center'><br />$daylong $today, $year</legend>";	
$sql_date = "$year-$month-$today";
$semana = date( mktime(0, 0, 0, $month, $today, $year));
$hoy = getdate($semana);
$numero_dia = $hoy['wday'];

//Nombre del Mes
echo "<table class='table table-bordered table-striped' style='' align='center'><thead>";
echo "<th colspan=\"7\" align=\"center\"><div align='center'>" . $monthlong . 
"</div></th>";
echo "</thead><tr>";


//Nombre de Días
foreach($alldays as $value) {
  echo "<th style=''>
  $value</th>";
}
echo "</tr><tr>";


//Días vacíos
for ($i = 0; $i < $dayone; $i++) {
  echo "<td>&nbsp;</td>";
}


//Días
for ($zz = 1; $zz <= $numdays; $zz++) {
  if ($i >= 7) {  print("</tr><tr>"); $i=0; }
  // Mirar a ver si hay alguna ctividad en el días
  $result_found = 0;
  if ($zz == $today) { 
    echo "<td onClick=\"window.location='" 
	.$_SERVER['PHP_SELF']. "?year=$year&today=$zz&month=$month';\" style='background-color:#08c;color:#fff;cursor:pointer;'>$zz</td>";
    $result_found = 1;
  }
  if ($result_found != 1) {//Buscar actividad  y marcarla.
    $sql_currentday = "$year-$month-$zz";
    $eventQuery = "SELECT distinct fecha FROM diario WHERE profesor = '$profe' and fecha = '$sql_currentday';";
    //echo $eventQuery;
    $eventExec = mysql_query($eventQuery);
    while($row = mysql_fetch_array($eventExec)) {
      if (mysql_num_rows($eventExec) == 1) {
echo "<td onClick=\"window.location='" .$_SERVER['PHP_SELF']. "?year=$year&today=$zz&month=$month';\" style='background-color:#f89406;color:#fff;cursor:pointer;'>$zz</td>";
        $result_found = 1;
      }
    }
  }

  if ($result_found != 1) {
    echo "<td onClick=\"window.location='" .$_SERVER['PHP_SELF']. "?year=$year&today=$zz&month=$month';\" style='cursor:pointer;'><a href='".$_SERVER['PHP_SELF']."?year=$year&today=$zz&month=$month'>$zz</a></td>";
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
echo "</div>";
?>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               