<?
session_start();
include("../../config.php");
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}

if($_SESSION['cambiar_clave']) {
	header('Location:'.'http://'.$dominio.'/intranet/clave.php');
}

registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);



$pr = $_SESSION['profi'];
$cargo = $_SESSION['cargo'];
?>

  <?php
 include("../../menu.php");
 include("menu.php"); 
  $tut = mysql_query("select unidad from FTUTORES where tutor = '$pr'");
  //echo "select unidad, grupo from FTUTORES where tutor = '$pr'";
  if (mysql_num_rows($tut) > 0) {
  $tuto = mysql_fetch_array($tut);
  $unidad = $tuto[0];
  }

?>
  
<div align="center">      
<div class="page-header">
  <h2>Informes de Tareas <small> Buscar Informes</small></h2>
</div>
<br />
    
<div class="well well-large" style="width:360px" align="left">
<form action="buscar.php" method="post">  
  
    <label>Apellidos<br />
    <input name="apellidos" type="text" class="input-xlarge" alt="Alumno" />
  </label>
    
   <label> Nombre<br />
    <input name="nombre" type="text" class="input-large" alt="nombre" />
  </label>
  
   <label> Grupo<br />
<SELECT name="unidad" class="input-small">
      <OPTION><? echo $unidad;?></OPTION>
      <? unidad();?>
    </SELECT>
        </label>   

  
    <br />
      <input type="submit" name="submit1" value="Buscar Informes" class="btn btn-primary">
    <br /><br />
    <div class="well"><p class="help-block"><strong><u>Nota</u></strong>: No es necesario escribir el Nombre o Apellidos completos del Alumno. Es preferible introducir pocos datos aunque el resultado sea m&aacute;s amplio. As&iacute;, si escribo "gar" el resultado incluir&aacute; alumnos con Apellidos como "<span class="Estilo1">Gar</span>c�a", "Esti<span class="Estilo1">gar</span>ribia", "Mel<span class="Estilo1">gar</span>", etc.</p></div>
  
</form>		
</div>
</div>
<?php
	include("../../pie.php");
?>		
</body>
</html>
