<? include("../conf_principal.php");?>
<? include "../cabecera.php"; ?>
<? include('../menu.php'); ?>
<div class="span9">	
<h3 align='center'>Actividades Extraescolares en este Curso Escolar</h3>
<hr />
<div class="span10 offset1">
 <p class="muted" align="center"><small>(Las Fechas son aproximadas. Pueden variar en funcion de la Actividad, o bien si la misma se realiza fuera del Centro.)</small></p>
<?
	mysql_connect ($host, $user, $pass);
	mysql_select_db ($db);
	
	$meses = "select distinct month(fecha) from actividades order by fecha";
$meses0 = mysql_query($meses);
while ($mes = mysql_fetch_array($meses0))
{
$mes1 = $mes[0];
  echo "<div align=center><strong>";
  if($mes1 ==  "01") $mes2 = "Enero";
  if($mes1 ==  "02") $mes2 = "Febrero";
  if($mes1 ==  "03") $mes2 = "Marzo";
  if($mes1 ==  "04") $mes2 = "Abril";
  if($mes1 ==  "05") $mes2 = "Mayo";
  if($mes1 ==  "06") $mes2 = "Junio";
  if($mes1 ==  "09") $mes2 = "Septiembre";
  if($mes1 ==  "10") $mes2 = "Octubre";
  if($mes1 ==  "11") $mes2 = "Noviembre";
  if($mes1 ==  "12") $mes2 = "Diciembre";
  
  echo "<br><legend class='text-warning'>$mes2</legend>";
	
  $datos0 = "select unidades, descripcion, departamento, profesores, concat (horaini,' - ', horafin), concat(fechaini,' - ',fechafin), profesorreg, nombre from calendario where month(fechaini)='$mes1' and categoria = '2' order by id";
  $datos1 = mysql_query($datos0);
  if(mysql_num_rows($datos1)>0){
  while($datos = mysql_fetch_array($datos1))
  {
  if(strlen($datos[0]) > 96){
$gr1 = substr($datos[0],0,48)."<br>";
$gr2 = substr($datos[0],48,48)."<br>";
$gr3 = substr($datos[0],96)."<br>";
$grupos = $gr1.$gr2.$gr3;
$grupos0 =  substr($grupos,0,strlen($grupos)-5);
}
elseif(strlen($datos[1]) > 48 and strlen($datos[1]) < 96){
$gr1 = substr($datos[1],0,48)."<br>";
$gr2 = substr($datos[1],48,96)."<br>";
$grupos = $gr1.$gr2;
$grupos0 =  substr($grupos,0,strlen($grupos)-5);
}
elseif(strlen($datos[1]) < 50){
$gr1 = substr($datos[1],0,50)."<br>";
$grupos = $gr1;
$grupos0 =  substr($grupos,0,strlen($grupos)-5);
}
if ($datos[6]=='admin') {
	$profe_reg = "Departamento de Actividades Extraescolares";
}
else{
	$profe_reg = $datos[6];
}
  ?>
      <div class="well well-large span10" align='left'>
          <h4 class="text-success"><? echo $datos[7];?></h4>
          <hr>
      <dl class="dl-horizontal">

        <dt class="text-info">Grupos</dt><dd><? echo $grupos0;?></dd>

        <dt class="text-info">Descripci�n</dt><dd><? echo $datos[1];?></dd>

        <dt class="text-info">Departamento</dt><dd><? echo $datos[2];?></dd>

        <dt class="text-info">Profesor</dt><dd><? echo $datos[3];?></dd>

        <dt class="text-info">Horario</dt><dd><? echo $datos[4];?></dd>

        <dt class="text-info">Fecha</dt><dd><? echo $datos[5];?></dd>

        <dt class="text-info">Informaci�n</dt><dd><? echo $profe_reg;?></dd>
      
      </dl>
      </div>
    <?
 }
  }
  
  else{
echo "<div class='alert alert-warning' style='max-width:450px;margin:auto'>El 
grupo $unidad no tiene programada ninguna Actividad Complementaria durante este 
curso a d�a de hoy.</div></div>";	  
  }
}
?>


	</div>

<? include("../pie.php");?>

<? include("../pie.php");?>
