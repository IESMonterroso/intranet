<? 
session_start();
if($_SESSION['aut']<>1)
{
header("location:http://iesmonterroso.org");
exit;	
}
	$clave_al = $_SESSION['clave_al'];
	$claveal = $_SESSION['clave_al'];
	$todosdatos = $_SESSION['todosdatos'];
	$unidad = $_SESSION['unidad'];
	$curso = $unidad;
	include("../conf_principal.php");
	include("../funciones.php");
	mysql_connect ($host, $user, $pass);
	mysql_select_db ($db);
?>
<? include "../cabecera.php"; ?>
<? include('consultas.php'); ?>
<div class="span9">	
<?
   echo "<h3 align='center'>$todosdatos<br /></h3>";   
   echo "<p class='lead muted' align='center'><i class='icon icon-book'> </i> Calendario de Ex�menes y Actividades del Grupo $unidad</p><hr /><br />";


?>
<div class="row-fluid">
<div class="span8">

<?
$eventQuery = mysql_query("SELECT id, fecha, grupo, materia, tipo, titulo FROM diario WHERE grupo like '%".$_SESSION['unidad']."%' and date(fecha)>'$inicio_curso'");
echo "<table class='table table-bordered table-striped' align='center'><tr class='text-info'><th>FECHA</th><th>GRUPO</th><th>MATERIA</th><th>T�TULO</th></tr><tbody>";
while ($reg=mysql_fetch_array($eventQuery)) {
	echo "<tr>
	<td nowrap>$reg[1]</td>
	<td>$reg[2]</td>
	<td>$reg[3]</td>
	<td>$reg[5]</td>
	</tr>";
}
echo "</tbody></table>";
?>
</div>
<div class="span4">
<?
include("calendario_grupos.php");
?>
</div>
</div>
</div>

<?
include("../pie.php");
?>
</BODY>
</HTML>
