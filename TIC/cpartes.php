<?
session_start();
include("../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
$profe = $_SESSION['profi'];
?>
<?
include("../menu.php");
include("menu.php");
?>
<?
if(!$enviar)
{
?>

<div align=center>
  <div class="page-header" align="center">
  <h1>Centro TIC <small> Registro de incidencias</small></h1>
</div>
<br />
</div>
<div class="row-fluid">
  <div class="span2"></div>
  <div class="span4">
    <div class="well-2 well-large" align="left">
      <form class="form-inline" action="cpartes.php">
        <h6>Selecciona Nivel y Grupo</h6>
        <br />
        <label>NIVEL
          <select name="nivel" id="NIVEL" onchange="submit()" class="input-mini">
            <option><? echo $nivel;?></option>
            <? nivel();?>
          </select>
        </label>
        <label>GRUPO
          <select name="grupo" id="GRUPO" onchange="submit()" class="input-mini">
            <option><? echo $grupo;?></option>
            <? grupo($nivel);?>
          </select>
        </label>
        </form>
        <hr />
        <form class="form-vertical" action="cpartes.php">
        <label>ALUMNO<br />
          <select name="alumno" id="alumno" class="input-xlarge">
            <OPTION></OPTION>
            <? 

  // Datos del alumno que hace la consulta. No aparece el nombre del a&iuml;&iquest;&frac12; de la nota. Se podr&iuml;&iquest;&frac12; incluir.
  $alumnosql = mysql_query("SELECT distinct APELLIDOS, NOMBRE FROM FALUMNOS WHERE NIVEL like '$nivel%' AND GRUPO like '$grupo%' order by APELLIDOS asc");

  if ($falumno = mysql_fetch_array($alumnosql))
        {
        do {
	printf ("<OPTION>$falumno[0], $falumno[1]</OPTION>");

	} while($falumno = mysql_fetch_array($alumnosql));
        }
	?>
          </select>
        </label>
        <label> Profesor<br />
          <input type="text" value="<? echo $profe;?>" readonly class="span9"/>
          <input name="profesor" type="hidden" value="<? echo $profe;?>"  class="input-xlarge"/>
        </label>
        <label>N&ordm; Carrito<br />
          <input name="carrito" type="text" id="carrito" size="2" maxlength="2"  class="input-mini" />
        </label>
        <label>N&ordm; del Ordenador<br />
          <input name="numeroserie" type="text" id="numeroserie" size="2" maxlength="2"  class="input-mini"/>
        </label>
        <label>Fecha (<span style="color:#cc6600">*</span>)<br />
          <?
$today = date("j, n, Y");
$hoy = explode(",",$today);
$dia = $hoy[0];
$mes = $hoy[1];
$a�o = $hoy[2];
echo "<select name='fecha2' class='input-mini'>";
echo "<option>$dia</option>";
for ($i=1;$i<32;$i++){echo "<option>$i</option>";}
echo "</select>&nbsp;&nbsp;";
echo "<select name='fecha1' class='input-mini'>";
echo "<option>$mes</option>";
for ($i=1;$i<13;$i++){echo "<option>$i</option>";}
echo "</select>&nbsp;&nbsp;";
echo "<select name='fecha0' class='input-small'>";
echo "<option>$a�o</option>";
for ($i=date('Y')-1;$i<date('Y')+3;$i++){echo "<option>$i</option>";}
echo "</select> ";
$fecha = trim($a�o)."-".trim($mes)."-".trim($dia);
				?>
          <input name="fecha" type="hidden" id="fecha" value="<? echo $fecha;?>" class="input-small" />
        </label>
        <label>Hora (1, 2, etc.)<br />
          <input name="hora" type="text" id="hora" maxlength="1" size="3" class="input-mini"/>
        </label>
        <label>Descripci�n del problema <span style="color:#cc6600">(*)</span><br />
          <textarea name="descripcion" cols="46" rows="5" id="descripcion"  class="span11"></textarea>
        </label>
        <br />
        <input name="estado" type="hidden" id="enviar" value="activo" />
        <input name="enviar" type="submit" id="enviar" value="Registrar Incidencia" class="btn btn-primary" />
      </form>
    </div>
  </div>
  <div class="well-2 well-large span4">
  <h6>Instrucciones.</h6><br />
    <p>En esta p&aacute;gina se dan de alta los problemas que pod&aacute;is tener con los ordenadores, tanto port&aacute;tiles como fijos. </p>
    <p id="texto"> Los fallos pueden ser de dos tipos: bien sucede que la m&aacute;quina o alguna de sus partes presenta problemas (la m&aacute;quina no enciende, se ha fastidiado la pantalla o el teclado, etc); o bien el Sistema Operativo o alguna de sus aplicaciones no funcionan. Cualquiera de las dos clases de problemas se registran aqu&iacute;.</p>
    <p id="texto"> Los campos que es obligatorio rellenar aparecen marcados con el signo <span style="color:#cc6600">(*).</span></p>
    <p id="texto"> Si despues de haber enviado los datos quer&eacute;is modificarlos por alguna raz&oacute;n, el enlace &quot;<a href="clista.php">Listar,
      editar o eliminar los ultimos partes de incidencias</a>&quot; os permitir&aacute; seleccionar
      vuestra incidencia y editarla o eliminarla. </p>
    <p id="texto"> Y una recomendacion: si el problema ha sido causado por el mal uso de un Alumno, registrar el asunto en el modulo de <a href="admin/fechorias/infechoria.php" target="_blank">Problemas de Convivencia</a> de la Intranet. </p>
    <br />
    <a href="clista.php">
    <button class="btn btn-danger">Editar las incidencias registradas.</button>
    </a>
    </form>
  </div>
</div>
<? 
	
	}
	else
	{ 	 
	if (!$profesor or !$fecha or !$descripcion) 
	{ 
	$errorList = '<br /><div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Hay un problema en el Formulario que quieres enviar:<br>No has introducido datos en alguno de los campos obligatorios.<br> Vuelve atr�s e int�ntalo de nuevo.</div></div></p>';
	echo $errorList;
}
else
	{
 

$query="insert into partestic (nivel,grupo,carro,nserie,fecha,hora,alumno,profesor,descripcion,estado) values	('".$nivel."','".$grupo."','".$carrito."','".$numeroserie."','".$fecha."','".$hora."','".$alumno."','".$profesor."','".$descripcion."','".$estado."')";
		$prueba = mysql_query($query);
		if($prueba == "1")
		{
echo '<br /><div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Los datos de la incidencia han sido actualizados correctamente.
</div></div>';
		$direccion = "admin@$dominio";
		$tema = "Nuevo parte de incidencia";
		$texto = "Datos de la incidencia:
		Nivel --> '$nivel';
		Grupo --> '$grupo';
		Carrito --> '$carrito';
		N� de Serie --> '$numeroserie';
		Fecha --> '$fecha';
		Hora --> '$hora';
		Alumno --> '$alumno';
		Profesor --> '$profesor';
		Descripci�n --> '$descripcion';
		Estado --> '$estado';
		";
		mail($direccion, $tema, $texto); 
		}

	 }

	}

include("../pie.php");
?>
</body></html>