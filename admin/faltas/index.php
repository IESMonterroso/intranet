<?
if (isset($_GET['unidad'])) {$unidad = $_GET['unidad'];}elseif (isset($_POST['unidad'])) {$unidad = $_POST['unidad'];}else{$unidad="";}
if (isset($_GET['unidad1'])) {$unidad1 = $_GET['unidad1'];}elseif (isset($_POST['unidad1'])) {$unidad1 = $_POST['unidad1'];}else{$unidad1="";}
if (isset($_GET['mes'])) {$mes = $_GET['mes'];}elseif (isset($_POST['mes'])) {$mes = $_POST['mes'];}else{$mes="";}
if (isset($_GET['FALTA'])) {$FALTA = $_GET['FALTA'];}elseif (isset($_POST['FALTA'])) {$FALTA = $_POST['FALTA'];}else{$FALTA="";}
if (isset($_GET['numero2'])) {$numero2 = $_GET['numero2'];}elseif (isset($_POST['numero2'])) {$numero2 = $_POST['numero2'];}else{$numero2="";}
if (isset($_GET['submit1'])) {$submit1 = $_GET['submit1'];}elseif (isset($_POST['submit1'])) {$submit1 = $_POST['submit1'];}else{$submit1="";}
if (isset($_GET['nombre'])) {$nombre = $_GET['nombre'];}elseif (isset($_POST['nombre'])) {$nombre = $_POST['nombre'];}else{$nombre="";}
if (isset($_GET['fecha4'])) {$fecha4 = $_GET['fecha4'];}elseif (isset($_POST['fecha4'])) {$fecha4 = $_POST['fecha4'];}else{$fecha4="";}
if (isset($_GET['fecha3'])) {$fecha3 = $_GET['fecha3'];}elseif (isset($_POST['fecha3'])) {$fecha3 = $_POST['fecha3'];}else{$fecha3="";}
if (isset($_GET['submit2'])) {$submit2 = $_GET['submit2'];}elseif (isset($_POST['submit2'])) {$submit2 = $_POST['submit2'];}else{$submit2="";}
if (isset($_GET['numero'])) {$numero = $_GET['numero'];}elseif (isset($_POST['numero'])) {$numero = $_POST['numero'];}else{$numero="";}
if (isset($_GET['fecha10'])) {$fecha10 = $_GET['fecha10'];}elseif (isset($_POST['fecha10'])) {$fecha10 = $_POST['fecha10'];}else{$fecha10="";}
if (isset($_GET['fecha20'])) {$fecha20 = $_GET['fecha20'];}elseif (isset($_POST['fecha20'])) {$fecha20 = $_POST['fecha20'];}else{$fecha20="";}
if (isset($_GET['submit4'])) {$submit4 = $_GET['submit4'];}elseif (isset($_POST['submit4'])) {$submit4 = $_POST['submit4'];}else{$submit4="";}
if (isset($_GET['submit3'])) {$submit3 = $_GET['submit3'];}elseif (isset($_POST['submit3'])) {$submit3 = $_POST['submit3'];}else{$submit3="";}
if (isset($_GET['profe'])) {$profe = $_GET['profe'];}elseif (isset($_POST['profe'])) {$profe = $_POST['profe'];}else{$profe="";}
if (isset($_GET['materia'])) {$materia = $_GET['materia'];}elseif (isset($_POST['materia'])) {$materia = $_POST['materia'];}else{$materia="";}

if ($submit1)
{
	include("faltas.php");
}
elseif ($submit2)
{
	include("informes.php");
}
elseif ($submit3)
{
	include("asignaturas.php");
}
elseif ($submit4)
{
	include("faltasdias.php");
}
else
{
	require('../../bootstrap.php');
	
	include("../../menu.php");
	include("../../faltas/menu.php");
	?>

<div class="container">
<div class="page-header">
<h2 style="display:inline">Faltas de Asistencia <small> Consultas</small></h2>
	<!-- Button trigger modal --> <a href="#"
	class="btn btn-default btn-sm pull-right" data-toggle="modal"
	data-target="#myModal"  style="display:inline"> <span class="fa fa-question fa-lg"></span> </a>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span
	aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" id="myModalLabel">Instrucciones de uso.</h4>
</div>
<div class="modal-body">
<p class="help-block"><b>Informaci�n sobre el registro de Actas del Departamento</b><br><br>
Este m�dulo permite a los Jefes de Departamento crear un documento digital para las Reuniones del mismo, visible tanto por los miembros del Departamento como por el Equipo directivo. Sustituye al m�todo tradicional del Libro de Actas, y puede ser imprimido en caso de necesida por el Departamento o la Direcci�n.
<br><br>
Seleccionamos en primer lugar la fecha de la reuni�n. Las Actas se numeran autom�ticamente por lo que no es necesario intervenir manualmente en ese campo. El formulario contiene un texto prefijado con el esquema de cualquier Acta: Departamento, Curso escolar, N� de Acta, Asistentes etc. El texto comienza con el Orden del d�a, y contin�a con la descripci�n de los contenidos tratados en la reuni�n. No es necesario escribir la fecha de la misma (l�nea seguida vac�a) puesto que se coloca posteriormente con la fecha elegida.<br><br>
A la derecha del formulario van apareciendo en su orden las Actas, visibles para todos los miembros del Departamento. El Jefe del Departamento puede editar las Actas <b>hasta el momento en que se impriman</b> para entregar al Director: en ese momento el Acta queda bloqueada y s�lo puede ser visualizada o imprimida. Al ser imprimida aparece un icono de verificaci�n sustituyendo al icono de edici�n en la lis ta de actas. Por esta raz�n, hay que se muy cuidadoso e imprimir el Acta s�lo cuando la misma est� completada.<br><br>
Los Administradores de la Intranet (Equipo Directivo, por ejemplo) tiene acceso a una opci�n, 'Todas las Actas', que les abre una p�gina con todas las Actas de todos los Departamentos. La edici�n est� prohibida, pero pueden verlas e imprimirlas.
</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>
</div>
<br />

<div class="row">

<div class="col-sm-6">

<div class="well well-large">

<form action='index.php' method='post' name='f1' class='form' role='form'>

<fieldset>

<legend>Faltas de un alumno</legend> 

<div class="form-group col-md-3">
<label for="grupo" class="control-label"> Grupo </label> 
<SELECT id="grupo"
	name="unidad1" onChange="submit()" class="form-control">
	<OPTION><? echo $unidad1;?></OPTION>
	<? unidad($db_con);?>
</SELECT>
</div>

<div class="form-group col-md-9">
<label for="al" class="control-label"> Alumno </label> 
<select id="al"
	name='nombre' class="form-control">
	<?
	printf ("<OPTION></OPTION>");

	// Datos del alumno que hace la consulta. No aparece el nombre del a&iuml;&iquest;&frac12; de la nota. Se podr&iuml;&iquest;&frac12; incluir.
	$alumnosql = mysqli_query($db_con, "SELECT distinct APELLIDOS, NOMBRE, CLAVEAL FROM FALUMNOS WHERE unidad like '$unidad1%' order by APELLIDOS asc");

	if ($falumno = mysqli_fetch_array($alumnosql))
	{

		do {
			$claveal = $falumno[2];
			global $claveal;
			$opcion = printf ("<OPTION>$falumno[0], $falumno[1] --> $falumno[2]</OPTION>");
			echo "$opcion";

		} while($falumno = mysqli_fetch_array($alumnosql));
	}
	$fecha = (date("d").-date("m").-date("Y"));
	$comienzo=explode("-",$inicio_curso);
	$comienzo_curso=$comienzo[2]."-".$comienzo[1]."-".$comienzo[0];
	$fecha2 = date("m");
	?>
</select>
</div>

<legend><small>Rango de fechas...</small></legend>

<div class="form-group col-md-6"  id="datetimepicker1" style="display: inline;">
<label class="control-label"
	for="fecha4">Inicio </label>
<div class="input-group">
<input name="fecha4" type="text"
	class="form-control" value="" data-date-format="DD-MM-YYYY" id="fecha4"
	required> <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
</div>
</div>

<div class="form-group col-md-6"   id="datetimepicker2" style="display: inline;">
<label class="control-label"
	for="fecha3">Fin </label>
<div class="input-group">
<input name="fecha3" type="text"
	class="form-control" value="" data-date-format="DD-MM-YYYY" id="fecha3"
	required> <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
</div>
</div>

<div class="form-group col-md-4">
<input name=submit2 type=submit id="submit2"
	value='Lista detallada de Faltas' class="btn btn-primary"> 
</div>

</fieldset>

</form>

</div>

<br>

<div class="well well-large">

<legend>Faltas de un Grupo.</legend>

<form action='index.php' method='post' name='f1' class='form' role='form'>

<fieldset>

<div class="form-group col-md-6">
<label class="control-label" for="unidad"> Grupo </label> 
<SELECT
	id="unidad" name="unidad" onChange="submit()" class="form-control">
	<OPTION><? echo $_POST['unidad'];?></OPTION>
	<? unidad($db_con);?>
</SELECT>
</div>

<div class="form-group col-md-6">
<label class="control-label" for="mes"> Mes </label> 
<SELECT name='mes' id='mes'
	class="form-control">
	<OPTION></OPTION>
	<?
	for($i=1;$i<13;$i++){
		echo "<OPTION>$i</OPTION>";
	}
	?>
</SELECT> 
</div>

<div class="form-group col-md-6">
<label class="control-label" for="falta">Falta:</label> 
<SELECT id='falta'  name='FALTA' class="form-control">
	<OPTION>F</OPTION>
	<OPTION>J</OPTION>
</SELECT>
</div>

<div class="form-group col-md-6">
<label class="control-label" for='numero2'> N&uacute;mero m&iacute;nimo de Faltas</label> 
<INPUT id='numero2' name="numero2" type="text" class="form-control" maxlength="3" alt="Mes" value="1">
</div>

<div class="form-group col-md-4">
<input name="submit1" type="submit" id="submit1" value="Enviar Datos"
	class="btn btn-primary">
</div>

</fieldset>

</form>

</div>



</div>


<div class="col-md-6">

<div class="well well-large">

<legend>Faltas por Asignatura</legend>

<form action='index.php' method='post' name='f1' class='form' role='form'>

<fieldset>

<div class="form-group col-md-12">
<label class="control-label" for="profe"> Profesor </label> 
<SELECT
	id="profe" name="profe" onChange="submit()" class="form-control" required>
<?
if (isset($_POST['profe'])) {
	$profe = $_POST['profe'];
}
	printf ("<OPTION>$profe</OPTION>");

	$profesql = mysqli_query($db_con, "SELECT distinct profesor FROM profesores order by profesor asc");

	if ($fprofe = mysqli_fetch_array($profesql))
	{

		do {
			$opcion = printf ("<OPTION>$fprofe[0]</OPTION>");
			echo "$opcion";

		} while($fprofe = mysqli_fetch_array($profesql));
	}
?>
</SELECT>
</div>

<div class="form-group col-md-12">
<label class="control-label" for="materia"> Asignatura </label> 
<SELECT name='materia' id='materia'
	class="form-control" required>
	<OPTION></OPTION>
<?
	$asig0 = mysqli_query($db_con, "SELECT distinct materia, grupo, nivel FROM profesores WHERE profesor = '$profe' order by grupo, nivel, materia asc");

	if ($asig = mysqli_fetch_array($asig0))
	{

		do {
			$opcion = printf ("<OPTION>$asig[0] -> $asig[1] -> $asig[2]</OPTION>");
			echo "$opcion";

		} while($asig = mysqli_fetch_array($asig0));
	}
?>
</SELECT> 
</div>

<div class="form-group col-md-4">
<input name="submit3" type="submit" id="submit1" value="Enviar Datos"
	class="btn btn-primary">
</div>

</fieldset>

</form>

</div>

<br>

<div class="well well-large">

<form action='index.php' method='post' name='f1' class='form' role='form'>

<fieldset>

<legend> Faltas y d�as sin justificar</legend>

<span class="help-block">( Alumnos que
tienen un n�mero m�nimo de faltas entre el rango de fechas
seleccionadas. )</span> 

<div class="form-group col-md-6">
<label class="control-label" for='numero'> N�mero m�nimo de Faltas</label> 
<INPUT
	name="numero" type="text" id="numero" class="form-control"
	maxlength="3" value="1">
</div>
<legend><small>Rango de fechas...</small></legend>

<div class="form-group col-md-6" id="datetimepicker3" style="display: inline;">
<label class="control-label" for="fecha10">Inicio </label>
<div class="input-group">
<input name="fecha10" type="text"
	class="form-control" value="" data-date-format="DD-MM-YYYY"
	id="fecha10" required> <span class="input-group-addon"><i
	class="fa fa-calendar"></i></span>
</div>
</div>

<div class="form-group col-md-6" id="datetimepicker4" style="display: inline;">
<label
	for="fecha20" class="control-label">Fin </label>
<div class="input-group"><input name="fecha20" type="text"
	class="form-control" value="" data-date-format="DD-MM-YYYY"
	id="fecha20" required> <span class="input-group-addon"><i
	class="fa fa-calendar"></i></span>
</div>
</div>

<br>

<div class="form-group col-md-4">
<INPUT name="submit4" type="submit" value="Enviar Datos" id="submit4"
	class="btn btn-primary">
</div>

</fieldset>

</form>

</div>
</div>

	<? }?>

</div>
</div>
	<? include("../../pie.php");?>

<script>  
$(function ()  
{ 
	$('#datetimepicker1').datetimepicker({
		language: 'es',
		pickTime: false
	});
	
	$('#datetimepicker2').datetimepicker({
		language: 'es',
		pickTime: false
	});
	
	$('#datetimepicker3').datetimepicker({
		language: 'es',
		pickTime: false
	});
	
	$('#datetimepicker4').datetimepicker({
		language: 'es',
		pickTime: false
	});
});  
</script>
</body>
</html>
