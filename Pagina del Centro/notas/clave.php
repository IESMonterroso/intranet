<?
session_start();		
if (isset($_POST['usuario'])) {$usuario = $_POST['usuario'];}else{$usuario="";}
if (isset($_POST['codigo2'])) {$codigo2 = $_POST['codigo2'];}else{$codigo2="";}
if (isset($_POST['codigo3'])) {$codigo3 = $_POST['codigo3'];}else{$codigo3="";}
if (isset($_POST['control'])) {$control = $_POST['control'];}else{$control="";}
	
?>
<?  include("../conf_principal.php"); ?>
<?  include("../cabecera.php"); ?>
<?  include("../menu.php"); ?>
<?  //include("../funciones.php"); ?>
<div class="span9">
<div class="span8 offset2">
<div align="center">
<h3>Registro de la Clave de Acceso</h3>
<hr />
<?

if(isset($_POST['enviar_clave']))	{

	include("../conf_principal.php");
	include("../funciones.php");
	mysql_connect ($host, $user, $pass);
	mysql_select_db ($db);
	
if ($codigo2 === $codigo3 and strlen($codigo2)>7) 
{	
	$cod_sha = sha1($codigo2);
	$alu0 = mysql_query("SELECT claveal, pass from control WHERE claveal = '".$_SESSION['clave_al']."'");
	// No se ha registrado
	if ($alu0) {
		$contra = "insert into control VALUES('','".$_SESSION['clave_al']."','$cod_sha','".$_POST['correo']."') ";
	}
	else{
		$contra = "update control set pass = '$cod_sha', correo='".$_POST['correo']."' where claveal = '".$_SESSION['clave_al']."' ";
	}
mysql_query($contra2);
mysql_query($contra) or die('
    <div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:450px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>ATENCI�N:</h4>
Se ha producido un error:<br />No se ha podido cambiar la contrase�a en la Base de Datos, as� que mejor te pongas en contacto con quien pueda arreglar el asunto.
			</div>
          </div>');
echo '
    <div align="center"><div class="alert alert-success alert-block fade in" style="max-width:450px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
La Contrase�a se ha modificado en la Base de Datos. <br />La nueva clave para entrar es <span class="badge badge-warning">'.$codigo2.'</span>.
Puedes volver a cambiarla en cualquier momento. Y si te olvidas de la misma, ponte en contacto para volver al principio.
			</div>
          </div>';
		  echo '<br><center><form><input name="volver" type="button" onClick="location.href=\'http://'.$dominio.'/notas/datos.php\'" value="Ir a la P�gina Principal" class="btn btn-primary"></form></center></div></div></div></div>';
}
else {
if(!($codigo2 === $codigo3)){
echo '
    <div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:450px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>ATENCI�N:</h4>
Las claves que has elegido no coinciden. Vuelve atr�s e int�ntalo de nuevo. Y no olvides que hay que respetar la diferencia entre may�sculas y min�sculas.        
			</div>
          </div> ';
   }
elseif (strlen($codigo2)<8) {
echo '
    <div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:450px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h4>ATENCI�N:</h4>
Parece que has elegido una clave de acceso con menos de 8 caracteres, pero s�lo se admiten claves de 8 � m�s caracteres. Vuelve atr�s e int�ntalo de nuevo.
			</div>
          </div>   </div>
</div>
';
}	
}
  }
  else
  {
?>
</div>
<div align="center" class="well well-large" style="width:540px;margin:auto">
<form name="entrada" action="clave.php" method="post">
<table cellpadding="4">
<tr><th style="text-align:left">Usuario</th><td><input class="input-xlarge" type="text" name="usuario" disabled="disabled" 
value="<? echo $_SESSION['todosdatos'];?>" size=40></td></tr>
<tr><th style="text-align:left">Nueva Contrase�a&nbsp;&nbsp;</th><td><input type="password" name="codigo2" value="" size=20 required /></td></tr>
<tr><th style="text-align:left">Rep�tela otra vez</th><td><input type="password" name="codigo3" value="" size=20 required /></td></tr>
<tr><th style="text-align:left">Correo</th><td><input class="input-xlarge" type="text" name="correo" value="<? echo $_SESSION['correo'];?>" size=40 required></td></tr>
</table>
<br /><div align="center"><input class="btn btn-primary" type='submit' name="enviar_clave" value="Cambiar clave" /></div>
</form> 
  <br />
  <div class="well well-large" style='text-align:left;'>
  <p>
  La clave debe contener al menos <b>8 caracteres</b>. Una vez registrada tendr�s que usar tu nueva clave para acceder a estas p�ginas.
La direcci�n de correo electr�nico se usar� para las notificaciones y para reiniciar la contrase�a
en caso de olvido.</p>
</div>
 </div>
 </div>
<?
  }
  include("../pie.php");
  ?>