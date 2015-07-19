<?
if (isset($_GET['recurso'])) {$recurso = $_GET['recurso'];}elseif (isset($_POST['recurso'])) {$recurso = $_POST['recurso'];}else{$recurso="";}
if (isset($_GET['servicio'])) {$servicio = $_GET['servicio'];}elseif (isset($_POST['servicio'])) {$servicio = $_POST['servicio'];}else{$servicio="";}
if (isset($_GET['mens'])) {$mens = $_GET['mens'];}elseif (isset($_POST['mens'])) {$mens = $_POST['mens'];}else{$mens="";}
if (isset($_GET['servicio_aula'])) {$servicio_aula = $_GET['servicio_aula'];}elseif (isset($_POST['servicio_aula'])) {$servicio_aula = $_POST['servicio_aula'];}else{$servicio_aula="";}
?>

	<div class="container">
		
		<ul class="nav nav-tabs">
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
<h4 class="modal-title" id="myModalLabel">Informaci�n sobre el m�dulo de Reservas</h4>
</div>
<div class="modal-body">
<p>
El sistema de reservas permite controlar el uso de los medios del Centro (Dependencias, Recursos Audiovisuales, etc.). Hay tres categor�as por defecto en el sistema: Aulas, Ordenadores y Medios. Se configura en la p�gina de Administraci�n de la Intranet donde se define el n�mero, nombre, etc. de los recursos que se integran en la reserva.<br><br>
La reserva de Aulas y Dependencias del Centro est� integrada con el m�dulo de Horarios. Puede funcionar sin la importaci�n de los horarios si creamos las Aulas desde la opci�n del men� 'Crear/Ocultar/Eliminar Aulas/Dependencias', pero est� pensado para tomar la lista de aulas desde el horario que hemos importado. Por defecto, todas las aulas del Centro aparecen en la lista como reservables. Si deseamos ocultar aulas del sistema utilizamos la opci�n mencionada del men�; tambi�n podemos crear aulas que no aparecen en el horario.<br>
El funcionamiento es sencillo: elegimos el aula (Recurso TIC o Medio), fecha y hora; comprobamos que no ha sido reservada anteriormente por otro profesor y procedemos a registrarla. El Aula Magna (Sal�n de Usos M�ltiples) s�lo puede ser reservado por el Equipo Directivo (si necesitamos hacerlo, debemos pedir autorizaci�n a los miembros del mismo). El resto de las aulas s�lo permiten la reserva cuando en la hora correspondiente el aula no est� asignada en el horario a alg�n profesor en tareas lectivas. Si nos hemos equivocado o queremos eliminar una reserva, seleccionamos el d�a de la misma y borramos nuestro nombre el d�a y la hora elegidos. Una vez borrado, enviamos los datos del formulario con el bot�n <em><b>Reservar</b></em> y desaparecer� de la lista.
<? if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
<br>El Equipo directivo puede asignar el horario de las dependencias del Centro de 
varias maneras. Si el aula aparece en el Horario y este se ha importado en la 
base de datos, se presentar�n las horas del aula con la asignatura que se 
imparte en ese momento con la expresi�n <strong>Asignada por Horario</strong>. 
El Equipo directivo puede asignar el aula a otro profesor por encima del Horario 
si por alguna raz�n lo considera necesario. La hora aparecer� entonces en el 
formulario con la expresi�n <strong>Asignada por Direcci�n.</strong><br>
Si el aula est� vac�a en ese momento cualquier profesor puede seleccionar esa 
hora para utilizar el aula.<br>
Si queremos asignar una hora a un profesor durante todo el curso escolar, 
seleccionamos al mismo en el formulario y pulsamos sobre el bot�n rojo 
<strong>Reservar todo el Curso</strong>. Es importante elegir un d�a sin reserva 
alguna de profesores para realizar la asignaci�n permanente porque ls profesores 
que aparezcan en el formulario quedar�n fijados para todo el curso escolar. Los 
campos en los que aparezca la palabra "Asignaci�n" (bien por Horario o bien por 
Direcci�n) no se registran en la reserva permanete. A partir de ese momento los 
profesores ver�n en ese campo la expresi�n <strong>Asignada por 
Direcci�n</strong></strong> y no podr�n elegir el aula en esa hora. Este 
procedimiento se puede aplicar tantas veces como queramos para aquellas 
dependencias o aulas que aparezcan en el horario.
<?php endif; ?>	
 <br><br>
Si hemos marcado la opci�n 'Centro TIC' en la instalaci�n de la aplicaci�n, aparecer� una entrada en el men� para los carritos de ordenadores o aulas TIC. Si utilizamos los carros de ordenadores mediante el sistema de reservas podemos acceder a las estad�sticas de uso de los mismos dentro del men� de la p�gina de iicio de la Intranet (Trabajo --> Centro TIC --> Estad�sticas).<br><br> 
</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>
			<? if ($mod_horario=="1"): ?>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index_aula')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $dominio; ?>/intranet/reservas/index_aula.php?recurso=aula_grupo">Aulas y Dependencias del Centro</a></li>
			<?php endif; ?>
			<? if ($mod_tic=="1"): ?>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php?recurso=TIC_')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $dominio; ?>/intranet/reservas/index.php?recurso=TIC_">Ordenadores TIC</a></li>
			<?php endif; ?>	
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php?recurso=medio')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $dominio; ?>/intranet/reservas/index.php?recurso=medio">Medios audiovisuales</a></li>
			<? if ($mod_horario=="1"): ?>
			<? if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'ocultar.php')==TRUE) ? ' class="active"' : ''; ?>><a href="//<?php echo $dominio; ?>/intranet/reservas/ocultar.php">Crear / Ocultar / Eliminar Aulas/Dependencias</a></li>			
			<?php endif; ?>		
			<?php endif; ?>		
		</ul>
		
	</div>
