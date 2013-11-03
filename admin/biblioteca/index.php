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
if(!(stristr($_SESSION['cargo'],'1') == TRUE) and !(stristr($_SESSION['cargo'],'4') == TRUE) and !(stristr($_SESSION['cargo'],'5') == TRUE) and !(stristr($_SESSION['cargo'],'8') == TRUE))
{
header("location:http://$dominio/intranet/salir.php");
exit;	
}  
?>
<?php
 include("../../menu.php");
?>
<br />
<div align="center">
<div class="page-header">
  <h2>Biblioteca del Centro <small> Consultas en los Fondos de la Biblioteca</small></h2>
</div>
</div>
<br />
<div class="container-fluid">
<div class="row-fluid">

<div class='well well-large span6 offset3'>  

  <p>
    La Biblioteca del Centro ha registrado m�s de 10.000 vol�menes en el Fondo general, y el trabajo contin�a. En estas p�ginas puedes buscar textos u otros materiales en nuestra base de datos.</p>
    <p>La consulta de materiales en el Fondo es abierta, por lo que no es necesario escribir autor, t�tulo o editorial <em>exactos</em>. Si, por ejemplo, introduzco la expresi�n
  "Cer" en el campo "Autor", la consulta me devolver� libros de "<b>Cer</b>nuda" y de "<b>Cer</b>vantes".
   </p>

  <hr />
  <form action="biblioteca.php" method="post" name="libros" id="libros" class="form-horizontal">
  <div class="control-group">
        <label class="control-label" for="autor">Autor</label>
        <div class="controls">
                  <input type="text" name="autor" id="autor" class="input-xlarge" /> 
                  </div> 
        <br /><label class="control-label" for="titulo0">T&iacute;tulo </label>
        <div class="controls">
                 <input type="text" name="titulo0" id="titulo0" class="input-xlarge" /> 
                 </div>      
        <br /><label class="control-label" for="editorial">Editorial</label>
        <div class="controls">
                  <input type="text" name="editorial" id="editorial" class="input-xlarge" />
                  </div>
                  <hr />
  <input type="submit" name="enviar" value="Buscar Libros" class="btn btn-block btn-primary" />   
  </form>
  </div>
  </div>
</div>
  </div>
<? include("../../pie.php");?>