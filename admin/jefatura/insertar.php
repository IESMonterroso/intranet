<?php
// Control de errores
if (!$fecha or !$observaciones or !$causa or !$accion)
{
	echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
No has introducido datos en alguno de los campos , y <strong> todos son obligatorios</strong>.<br> Vuelve atr�s e int�ntalo de nuevo.
</div></div><br />';
exit();
}

if ($alumno == "Todos los Alumnos") {
$todos0 = mysql_query("select distinct claveal, apellidos, nombre from FALUMNOS where unidad = '$unidad'");
while ($todos = mysql_fetch_array($todos0)) {
$clave=$todos[0];	
if (empty($prohibido)){$prohibido = "0";}
$tutor = "Jefatura de Estudios";
$apellidos = $todos[1];
$nombre = $todos[2];
$dia = explode("-",$fecha);
$fecha2 = "$dia[2]-$dia[1]-$dia[0]";
		$query="insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, jefatura, prohibido, claveal) values 
		('".$apellidos."','".$nombre."','".$tutor."','".$unidad."','".$observaciones."','".$causa."','".$accion."','".$fecha2."','1','".$prohibido."','".$clave."')";
		 // echo $query;
mysql_query($query);
echo $query."<br>";
}
echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han introducido correctamente.
</div></div><br />';
}
else{
if (empty($prohibido)){$prohibido = "0";}
$tutor = "Jefatura de Estudios";
$tr = explode(" --> ",$alumno);
$al = $tr[0];
$clave = $tr[1];
$trozos = explode (", ", $al);
$apellidos = $trozos[0];
$nombre = $trozos[1];
$dia = explode("-",$fecha);
$fecha2 = "$dia[2]-$dia[1]-$dia[0]";
		$query="insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, jefatura, prohibido, claveal) values 
		('".$apellidos."','".$nombre."','".$tutor."','".$unidad."','".$observaciones."','".$causa."','".$accion."','".$fecha2."','1','".$prohibido."','".$clave."')";
		 // echo $query;
mysql_query($query);
echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos se han introducido correctamente.
</div></div><br />';
}
  $al0 = mysql_query("select distinct id, FALUMNOS.claveal, tutoria.claveal from tutoria, FALUMNOS where tutoria.apellidos=FALUMNOS.apellidos and tutoria.nombre=FALUMNOS.nombre and tutoria.unidad=FALUMNOS.unidad order by id");
  while($al1 = mysql_fetch_array($al0))
  {
 $claveal = $al1[1];
 $clave_tut = $al1[2];
 $id = $al1[0];
 if (empty($clave_tut)) {
 	mysql_query("update tutoria set claveal='$claveal' where id='$id'");
 }
}
?>
