<? include("../conf_principal.php");?>
<? include "../cabecera.php"; ?>
<? include "../menu.php"; ?>

<div class="span9">	
<h3 align='center'>Consultas en los Fondos de la Biblioteca del Centro<br /></h3>  <hr />
<br>
  <div class="well well-large" style="width:60%;margin:auto;">
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
  <p class="text-info">
    La Biblioteca del Centro ha registrado m�s de 10.000 vol�menes en el Fondo general, y el trabajo contin�a. En estas p�ginas puedes buscar textos u otros materiales en nuestra base de datos.</p>
    <p class="text-info">La consulta de materiales en el Fondo es abierta, por lo que no es necesario escribir autor, t�tulo o editorial <em>exactos</em>. Si, por ejemplo, introduzco la expresi�n
  "Cer" en el campo "Autor", la consulta me devolver� libros de "<b>Cer</b>nuda" y de "<b>Cer</b>vantes".
   </p>
  </div>
</div>
<? include("../pie.php");?>

