<?
if (isset($_GET['nivel'])) {$nivel = $_GET['nivel'];}elseif (isset($_POST['nivel'])) {$nivel = $_POST['nivel'];}else{$nivel="";}
if (isset($_GET['grupo'])) {$grupo = $_GET['grupo'];}elseif (isset($_POST['grupo'])) {$grupo = $_POST['grupo'];}else{$grupo="";}
if (isset($_GET['mes'])) {$mes = $_GET['mes'];}elseif (isset($_POST['mes'])) {$mes = $_POST['mes'];}else{$mes="";}
if (isset($_GET['FALTA'])) {$FALTA = $_GET['FALTA'];}elseif (isset($_POST['FALTA'])) {$FALTA = $_POST['FALTA'];}else{$FALTA="";}
if (isset($_GET['numero2'])) {$numero2 = $_GET['numero2'];}elseif (isset($_POST['numero2'])) {$numero2 = $_POST['numero2'];}else{$numero2="";}
if (isset($_GET['submit1'])) {$submit1 = $_GET['submit1'];}elseif (isset($_POST['submit1'])) {$submit1 = $_POST['submit1'];}else{$submit1="";}
if (isset($_GET['nivel1'])) {$nivel1 = $_GET['nivel1'];}elseif (isset($_POST['nivel1'])) {$nivel1 = $_POST['nivel1'];}else{$nivel1="";}
if (isset($_GET['grupo1'])) {$grupo1 = $_GET['grupo1'];}elseif (isset($_POST['grupo1'])) {$grupo1 = $_POST['grupo1'];}else{$grupo1="";}
if (isset($_GET['nombre'])) {$nombre = $_GET['nombre'];}elseif (isset($_POST['nombre'])) {$nombre = $_POST['nombre'];}else{$nombre="";}
if (isset($_GET['fecha4'])) {$fecha4 = $_GET['fecha4'];}elseif (isset($_POST['fecha4'])) {$fecha4 = $_POST['fecha4'];}else{$fecha4="";}
if (isset($_GET['fecha3'])) {$fecha3 = $_GET['fecha3'];}elseif (isset($_POST['fecha3'])) {$fecha3 = $_POST['fecha3'];}else{$fecha3="";}
if (isset($_GET['submit2'])) {$submit2 = $_GET['submit2'];}elseif (isset($_POST['submit2'])) {$submit2 = $_POST['submit2'];}else{$submit2="";}
if (isset($_GET['numero'])) {$numero = $_GET['numero'];}elseif (isset($_POST['numero'])) {$numero = $_POST['numero'];}else{$numero="";}
if (isset($_GET['fecha10'])) {$fecha10 = $_GET['fecha10'];}elseif (isset($_POST['fecha10'])) {$fecha10 = $_POST['fecha10'];}else{$fecha10="";}
if (isset($_GET['fecha20'])) {$fecha20 = $_GET['fecha20'];}elseif (isset($_POST['fecha20'])) {$fecha20 = $_POST['fecha20'];}else{$fecha20="";}
if (isset($_GET['submit4'])) {$submit4 = $_GET['submit4'];}elseif (isset($_POST['submit4'])) {$submit4 = $_POST['submit4'];}else{$submit4="";}

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
include("informes.php");
}
elseif ($submit4)
{
include("faltasdias.php");
}
else
{
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
?>
<?php
include("../../menu.php");
include("../../faltas/menu.php");
?>
<div class="page-header" align="center">
  <h2>Faltas de Asistencia <small> Consultas</small></h2>
  </div>
<br />

<form action='index.php' method='post' name='f1' class="form-inline">
<div class="row-fluid">
  <div class="span4">
  <div class="well well-large pull-right"  style="width:340px;">
  <legend>Resumen de faltas de un Grupo.</legend>
<br />
  <h6>Selecciona Nivel y Grupo</h6>
    <label> Nivel:
    <SELECT name="nivel" onChange="submit()" class="input-mini"  style="display:inline">
      <OPTION><? echo $nivel;?></OPTION>
      <? nivel();?>
    </SELECT>
  </label>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <label style="display:inline">
  Grupo:
  <SELECT name="grupo" onChange="submit()" class="input-mini"  style="display:inline">
    <OPTION><? echo $grupo;?></OPTION>
    <? grupo($nivel);?>
  </SELECT>
  </label>
  <hr>
   <label>
  Mes:&nbsp;
  <SELECT name='mes' class="input-mini">
    <OPTION><? echo date(m); ?></OPTION>
    <?
	for($i=1;$i<13;$i++){
	echo "<OPTION>$i</OPTION>";	
	}
	?>    
  </SELECT>
  <!-- <INPUT name="mes" type="text" value="<? echo date(m); ?>" class="input-mini" maxlength="2" >-->
  </label>
  <br />
  <label>
  Falta:
  <SELECT name='FALTA' class="input-mini">
    <OPTION>F</OPTION>
    <OPTION>J</OPTION>
  </SELECT>
  </label>
  <br />
  <label>
  N&uacute;mero m&iacute;nimo de
  Faltas
  <INPUT name="numero2" type="text" class="input-mini" maxlength="3" alt="Mes" value="1">
  </label>
  <br /><br />
  <input name="submit1" type="submit" id="submit1" value="Enviar Datos" class="btn btn-primary">
  </div>
  </div>
  
  
  
   <div class="span4">
  <div class="well well-large">
  <legend>Resumen de faltas de un alumno</legend>
  <br />
  <h6>Selecciona Nivel y Grupo</h6>
    <label> Nivel:
    <SELECT name="nivel1" onChange="submit()" class="input-mini"  style="display:inline">
      <OPTION><? echo $nivel1;?></OPTION>
      <? nivel();?>
    </SELECT>
  </label>
  &nbsp;&nbsp;&nbsp;&nbsp;
  <label style="display:inline">
  Grupo:
  <SELECT name="grupo1" onChange="submit()" class="input-mini"  style="display:inline">
    <OPTION><? echo $grupo1;?></OPTION>
    <? grupo($nivel1);?>
  </SELECT>
  </label>
  <hr>
  <h6>Selecciona el alumno</h6>
  <label>
  Alumno:
  <select name='nombre' class="input-xlarge">
    <?
printf ("<OPTION></OPTION>"); 
  
  // Datos del alumno que hace la consulta. No aparece el nombre del a&iuml;&iquest;&frac12; de la nota. Se podr&iuml;&iquest;&frac12; incluir.
  $alumnosql = mysql_query("SELECT distinct APELLIDOS, NOMBRE, CLAVEAL FROM FALUMNOS WHERE NIVEL like '$nivel1%' AND GRUPO like '$grupo1%' order by APELLIDOS asc");

  if ($falumno = mysql_fetch_array($alumnosql))
        {

        do {
		$claveal = $falumno[2];
		global $claveal;
	      $opcion = printf ("<OPTION>$falumno[0], $falumno[1] --> $falumno[2]</OPTION>");
	      echo "$opcion";

	} while($falumno = mysql_fetch_array($alumnosql));
        }
	$fecha = (date("d").-date("m").-date("Y"));
	$comienzo=explode("-",$inicio_curso);
	$comienzo_curso=$comienzo[2]."-".$comienzo[1]."-".$comienzo[0];
	$fecha2 = date("m");
	?>
  </select>
  </label>
  <hr>
    <h6>Rango de fechas...</h6>
         <label>Inicio<br />
 <div class="input-append" style="display:inline;" >
            <input name="fecha4" type="text" class="input input-small" value="" data-date-format="dd-mm-yyyy" id="fecha4" >
  <span class="add-on"><i class="icon-calendar"></i></span>
</div> 

</label>
  &nbsp;&nbsp;&nbsp;&nbsp;
<label>Fin<br />
 <div class="input-append" style="display:inline;" >
            <input name="fecha3" type="text" class="input input-small" value="" data-date-format="dd-mm-yyyy" id="fecha3" >
  <span class="add-on"><i class="icon-calendar"></i></span>
</div> 
    </label> 

  <br />
  <br />
  <input name=submit2 type=submit id="submit2" value='Lista detallada de Faltas' class="btn btn-primary">
  <br>
</div>
</div>



<div class="span4">
<div class="well well-large pull-left" style="width:340px;">
  <legend> Faltas y d�as sin justificar</legend>
  <br />
  <span class="help-block">( Alumnos que tienen un n�mero m�nimo de faltas entre el rango de fechas seleccionadas. )</span>
  <label>
  N�mero m�nimo de Faltas <INPUT name="numero" type="text" id="numero" class="input-mini" maxlength="3" value="1">
  </label>
 <hr>
   <h6>Rango de fechas...</h6>
         <label>Inicio<br />
 <div class="input-append" style="display:inline;" >
            <input name="fecha10" type="text" class="input input-small" value="" data-date-format="dd-mm-yyyy" id="fecha10" >
  <span class="add-on"><i class="icon-calendar"></i></span>
</div> 

</label>
  &nbsp;&nbsp;&nbsp;&nbsp;
<label>Fin<br />
 <div class="input-append" style="display:inline;">
            <input name="fecha20" type="text" class="input input-small" value="" data-date-format="dd-mm-yyyy" id="fecha20" >
  <span class="add-on"><i class="icon-calendar"></i></span>
</div> 

    </label> <br />
  <INPUT name="submit4" type="submit" value="Enviar Datos" id="submit4" class="btn btn-primary">
  </div>
  </div>
  <? }?>
</div>  
</form>
<? include("../../pie.php");?>

<script>  
	$(function ()  
	{ 
		$('#fecha4').datepicker()
		.on('changeDate', function(ev){
			$('#fecha4').datepicker('hide');
		});
		});  
	</script>
<script>  
	$(function ()  
	{ 
		$('#fecha3').datepicker()
		.on('changeDate', function(ev){
			$('#fecha3').datepicker('hide');
		});
		});  
	</script>	
	
<script>  
	$(function ()  
	{ 
		$('#fecha10').datepicker()
		.on('changeDate', function(ev){
			$('#fecha10').datepicker('hide');
		});
		});  
	</script>
<script>  
	$(function ()  
	{ 
		$('#fecha20').datepicker()
		.on('changeDate', function(ev){
			$('#fecha20').datepicker('hide');
		});
		});  
	</script>
</body>
</html>
