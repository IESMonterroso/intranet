<?
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
if (isset($_GET['edicion'])) {$edicion = $_GET['edicion'];}elseif (isset($_POST['edicion'])) {$edicion = $_POST['edicion'];}else{$edicion="";}
if (isset($_GET['submit'])) {$submit = $_GET['submit'];}elseif (isset($_POST['submit'])) {$submit = $_POST['submit'];}else{$submit="";}
if (isset($_GET['borrar'])) {$borrar = $_GET['borrar'];}elseif (isset($_POST['borrar'])) {$borrar = $_POST['borrar'];}else{$borrar="";}
if (isset($_GET['departamento'])) {$departamento = $_GET['departamento'];}elseif (isset($_POST['departamento'])) {$departamento = $_POST['departamento'];}else{$departamento="";}
if (isset($_GET['contenido'])) {$contenido = $_GET['contenido'];}elseif (isset($_POST['contenido'])) {$contenido = $_POST['contenido'];}else{$contenido="";}
if (isset($_GET['fecha'])) {$fecha = $_GET['fecha'];}elseif (isset($_POST['fecha'])) {$fecha = $_POST['fecha'];}else{$fecha="";}
if (isset($_GET['actualiza'])) {$actualiza = $_GET['actualiza'];}elseif (isset($_POST['actualiza'])) {$actualiza = $_POST['actualiza'];}else{$actualiza="";}
if (isset($_GET['numero'])) {$numero = $_GET['numero'];}elseif (isset($_POST['numero'])) {$numero = $_POST['numero'];}else{$numero="";}
if (isset($_GET['jefedep'])) {$jefedep = $_GET['jefedep'];}elseif (isset($_POST['jefedep'])) {$jefedep = $_POST['jefedep'];}else{$jefedep="";}
if (isset($_GET['pag'])) {$pag = $_GET['pag'];}elseif (isset($_POST['pag'])) {$pag = $_POST['pag'];}else{$pag="";}
if (isset($_GET['q'])) {$expresion = $_GET['q'];}elseif (isset($_POST['q'])) {$expresion = $_POST['q'];}else{$expresion="";}

$activo1="";
$activo2="";
if (strstr($_SERVER['REQUEST_URI'],'add.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'index_admin.php')==TRUE) {$activo2 = ' class="active" ';}
?>
 <div class="container">   
		
	<!-- Button trigger modal --> <a href="#"
	class="btn btn-default btn-sm pull-right" data-toggle="modal"
	data-target="#myModal"> <span class="fa fa-question fa-lg"></span> </a>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span
	aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" id="myModalLabel">Informaci�n sobre el registro de Actas del Departamento.</h4>
</div>
<div class="modal-body">
<p class="help-block">
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
 	<form method="get" action="buscar.php">
<div class="navbar-search pull-right col-sm-3">
 	 		   <div class="input-group">
 		     <input type="text" class="form-control input-sm" id="q" name="q" maxlength="60" value="<?php echo (isset($_GET['q'])) ? $_GET['q'] : '' ; ?>" placeholder="Buscar...">
 		     <span class="input-group-btn">
 		       <button class="btn btn-default btn-sm" type="submit"><span class="fa fa-search fa-lg"></span></button>
 		     </span>
 		   </div><!-- /input-group -->
 	 		 </div><!-- /.col-lg-3--> 		 
 	</form>  
 	
  	 			<ul class="nav nav-tabs">
 <li <? echo $activo1;?>><a href="add.php">Nueva Acta / Lista de Actas</a></li>                 		
 <?
          if (strstr($_SESSION['cargo'],"1") == TRUE) {
          	?>
          	<li <? echo $activo2;?>><a href="index_admin.php">Todas las Actas</a></li>
          	<?
          }
          ?>
      
    </ul>
        </div>
        </div>
