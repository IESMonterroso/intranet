<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);


?>
<script>
function confirmacion() {
	var answer = confirm("ATENCI�N:\n �Est�s seguro de que quieres borrar los datos? Esta acci�n es irreversible. Para borrarlo, pulsa Aceptar; de lo contrario, pulsa Cancelar.")
	if (answer){
return true;
	}
	else{
return false;
	}
}
</script>
<?php
include("../../menu.php");
include("menu.php");

$id_alumno=$_POST['ident'];
$asignatura=$_POST['asignatura'];
$informe=$_POST['informe'];
$profesor =$_POST['profesor'];
?>
<div align="center">
<div class="page-header">
  <h2>Informes de Tareas <small> Redactar Informe</small></h2>
</div>
<br />
<?
if (empty($informe) or empty($asignatura) or empty($id_alumno)) {
	echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Los datos no est�n completos.<br>Debes seleccionar Asignatura y rellenar el Informe de Tareas.<br>Vuelve a la p�gina anterior y rellena todos los datos.
<br /><br /><input type="button" onClick="history.back(1)" value="Volver" class="btn btn-danger">
		</div></div>';
	exit;

}
else
{
$ya_hay=mysql_query("select tarea from tareas_profesor where asignatura = '$asignatura' and id_alumno = '$id'");
$ya_hay1=mysql_fetch_row($ya_hay);
if (strlen($ya_hay1[0]) > '0') {
	mysql_query("update tareas_profesor set tarea = '$informe' where id_alumno = '$id' and asignatura = '$asignatura'") or die("<br><center><p>El Informe no ha podido ser actualizado. Busca ayuda. </p></center>");
	echo '<br /><div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido actualizado correctamente. Puedes comprobar los datos m�s abajo. 
		</div></div>';
}
else{
	mysql_query("insert into tareas_profesor (id_alumno,profesor,asignatura,tarea) values ('$id_alumno','$profesor','$asignatura','$informe')") or die("<br><center><p>El Informe no ha podido ser registrado. Busca ayuda. </p></center>");
echo '<br /><div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
El Informe ha sido guardado correctamente. Puedes comprobar los datos m�s abajo. 
		</div></div>';	}	
}
	
	


echo "<div align='center'>";
$alumno=mysql_query("SELECT APELLIDOS,NOMBRE,tareas_alumnos.unidad,tareas_alumnos.id,tutor, FECHA, duracion FROM tareas_alumnos, FTUTORES WHERE FTUTORES.unidad = tareas_alumnos.unidad and ID='$id_alumno'");
$dalumno = mysql_fetch_array($alumno);
echo "<br /><h4>$dalumno[1] $dalumno[0] <span>($dalumno[2])</span><br> <span>Fecha de Expulsi�n:</span> $dalumno[5] ($dalumno[6] d�as)<br><span>Tutor:</span> $dalumno[4]</h4><br />";
$datos=mysql_query("SELECT asignatura, tarea, id FROM tareas_profesor WHERE id_alumno='$id_alumno'");
// echo "SELECT asignatura, tarea FROM tareas_profesor WHERE id_alumno='$id'";
if(mysql_num_rows($datos) > 0)
{
echo "<table class='table' align='center' style='width:800px;'>";
	while($informe = mysql_fetch_array($datos))
{
$fondo = "";
if($informe[0] == $asignatura){$fondo="background-color:#ffc40d;";}
	echo "<tr><td id='filasecundaria' style='color:black;$fondo;' nowrap >$informe[0]</td>
		  <td style='$fondo'>$informe[1]</td>";
	echo "<td><a href='borrar.php?del=1&id_del=$informe[2]&id_alumno=$id_alumno&asignatura=$asignatura&profesor=$profesor'><i class='fa fa-trash-o' title='Borrar' onClick='return confirmacion();'> </i> </a></td>";
	echo"</tr>";
}
echo"</table>";
}
?>
<? include("../../pie.php");?>		

</body>
</html>
