
<?php
// include("http://$dominio/intranet/menu.php");
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
$alldays = array('Dom', 'Lun','Mar','Mie','Jue','Vie','Sab');
$next_year = $year + 1;
$last_year = $year - 1;
$result_found="";
include("nombres.php");
   
   if ($today > $numdays) { $today--; }

// Estructura de la Tabla
echo "<table class='table table-bordered table-striped'><tr><th style='text-align:center'>
	<a href='".$_SERVER['PHP_SELF']."?year=$last_year&today=$today&month=$month&profesor=$profesor'>
<i class='icon icon-arrow-left' name='calb2' style='margin-right:20px;'> </i> </a>
<h3 style='display:inline'>$year</h3>
<a href='".$_SERVER['PHP_SELF']."?year=$next_year&today=$today&month=$month&profesor=$profesor'>
<i class='icon icon-arrow-right' name='calb1' style='margin-left:20px;'> </i> </a></th></tr></table>";

   echo "<table class='table table-bordered'>
      <tr>";
	  $meses = array("1"=>"Ene" ,"2"=>"Feb" ,"3"=>"Mar" ,"4"=>"Abr" ,"5"=>"May" ,"6"=>"Jun" ,"7"=>"Jul" ,"8"=>"Ago" ,"9"=>"Sep" ,"10"=>"Oct" ,"11"=>"Nov" ,"12"=>"Dic");
	  foreach ($meses as $num_mes => $nombre_mes) {
	  	
	  	if ($num_mes==$month) {
	  		echo "<th onClick=\"window.location='" .$_SERVER['PHP_SELF']. 
		"?profesor=$profesor&year=$year&today=1&month=1';\" style='background-color:#08c'> 
		<a href=\"".$_SERVER['PHP_SELF']."?profesor=$profesor&year=$year&today=1&month=".$num_mes."\" style='color:#efefef'>".$nombre_mes."</a> </th>";
	  	}
	  	else{
	  		echo "<th onClick=\"window.location='" .$_SERVER['PHP_SELF']. 
		"?profesor=$profesor&year=$year&today=1&month=1';\" > 
		<a href=\"".$_SERVER['PHP_SELF']."?profesor=$profesor&year=$year&today=1&month=".$num_mes."\">".$nombre_mes."</a> </th>";
	  	}
	  if ($num_mes=='6') {
	  		echo "</tr><tr>";
	  	}
	  }
	  echo "</tr>
    </table>";
   
//Nombre del Mes
echo "<table class='table table-bordered'><tr>";
echo "<td colspan=\"7\" valign=\"middle\" align=\"center\"><h6 align='center'>" . $monthlong . 
"</h6></td>";
echo "</tr><tr>";


//Nombre de D�as
foreach($alldays as $value) {
  echo "<th  style='background-color:#eee'>
  $value</th>";
}
echo "</tr><tr>";


//D�as vac�os
for ($i = 0; $i < $dayone; $i++) {
  echo "<td>&nbsp;</td>";
}

//D�as
for ($zz = 1; $zz <= $numdays; $zz++) {
  if ($i >= 7) {  print("</tr><tr>"); $i=0; }
  // Mirar a ver si hay alguna ctividad en el d�as
  $result_found = 0;
  if ($zz == $today) { 
    echo "<td onClick=\"window.location='" 
	.$_SERVER['PHP_SELF']. "?profesor=$profesor&year=$year&today=$zz&month=$month';\" style='background-color:#08c;color:#fff;cursor:pointer;'>$zz</td>";
    $result_found = 1;
  }

  if ($result_found != 1) {
    echo "<td onClick=\"window.location='" .$_SERVER['PHP_SELF']. "?profesor=$profesor&year=$year&today=$zz&month=$month';\" style='cursor:pointer;'><a href='".$_SERVER['PHP_SELF']."?profesor=$profesor&year=$year&today=$zz&month=$month'>$zz</td>";
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
