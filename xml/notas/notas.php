<?
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
session_destroy();
header("location:http://$dominio/intranet/salir.php");	
exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}
?>
<?
include("../../menu.php");
?>
<br />
<div align="center">
<div class="page-header">
  <h2>Administraci�n <small> Importaci�n de calificaciones por Evaluaci�n</small></h2>
</div>
<br />
<div class="well well-large" style="width:700px;margin:auto;text-align:left">
<?
$directorio = $_GET['directorio'];
//echo $directorio."<br>";
if ($directorio=="../exporta1") {
	mysql_query("TRUNCATE TABLE notas");
}

// Recorremos directorio donde se encuentran los ficheros y aplicamos la plantilla.
if ($handle = opendir($directorio)) {
   while (false !== ($file = readdir($handle))) {   	
      if ($file != "." && $file != ".."&& $file != ".xml") {
       	
$doc = new DOMDocument('1.0', 'utf-8');

$doc->load( $directorio.'/'.$file );

$claves = $doc->getElementsByTagName( "ALUMNO" );
 
/*Al ser $materias una lista de nodos
lo puedo recorrer y obtener todo
su contenido*/
foreach( $claves as $clave )
{	
$clave2 = $clave->getElementsByTagName( "X_MATRICULA" );
$clave3 = $clave2->item(0)->nodeValue;
//$codigo = "";
$materias = $clave->getElementsByTagName( "MATERIA_ALUMNO" );
if ($directorio=="../exporta1") {
$cod = "INSERT INTO notas VALUES ('$clave3', '";
}
if ($directorio=="../exporta2") {
	$cod = "update notas set notas2 = '";
}
if ($directorio=="../exportaO") {
	$cod = "update notas set notas3 = '";
}
if ($directorio=="../exportaE") {
	$cod = "update notas set notas4 = '";
}
foreach( $materias as $materia )
{		
$codigos = $materia->getElementsByTagName( "X_MATERIAOMG" );
$codigo = $codigos->item(0)->nodeValue;
$notas = $materia->getElementsByTagName( "X_CALIFICA" );
$nota = $notas->item(0)->nodeValue;
$codigo.=":";
$nota.=";";
$cod.=$codigo.$nota;
}
if ($directorio=="../exporta1") {
$cod.="', '', '', '')";
	}
	else{
$cod.="' where claveal = '$clave3'";
	}
// echo $cod."<br>";
mysql_query($cod);
}   	       
  }
   }
   closedir($handle);
   echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
Las Notas de Evaluaci�n se han importado correctamente en la base de datos.
</div></div><br />';
}  
else
{
	echo '<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
Parece que no hay archivos en el directorio correspondiente.<br> O bien no has enviado el archivo correcto descargado de S�neca o bien el archivo est� corrompido.
</div></div><br />';
exit;
}

?>
<div align="center">
<input type="button" value="Volver atr�s" name="boton" onclick="history.back(2)" class="btn btn-inverse" />
</div>
</div>
</div>
<? include("../../pie.php");?>
</body>
</html>
