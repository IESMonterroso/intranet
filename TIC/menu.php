	<div class="container hidden-print">
	<!-- Button trigger modal --> 
	<a href="#"
	class="btn btn-default btn-sm pull-right" data-toggle="modal"
	data-target="#myModal" style="display:inline;"> <span class="fa fa-question fa-lg"></span> </a>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal"><span
	aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<h4 class="modal-title" id="myModalLabel">Informaci�n sobre el m�dulo TIC</h4>
</div>
<div class="modal-body">
<p>
Este m�dulo incluye varias funciones propias de un Centro con proyecto TIC. La primera de todas es la presentaci�n de un formulario para registrar incidencias en el uso de las TIC (conexi�n, aver�a de m�quinas, problemas de software, etc.). Las incidencias pueden ser vistas y filtradas, y tambi�n editadas por parte del autor de la incidencia y el Coordinador TIC.<br><br>
Los perfiles de profesores y alumnos nos ofrecen los datos (Usuario y Contrase�a) para el acceso a la red Andared, la Plataforma Educativa o Moodle. La aplicaci�n crea una tabla en la Base de Datos con los nombres de usuario y contrase�a de alumnos y profesores al importar y actualizar los datos de ambos, y crea un par de archivos de texto preparados para la importaci�n masiva de los mismos en Gesuser, Moodle, etc. <br>El Perfil del Profesor nos presenta nuestro nombre de usuario y clave de acceso en Andared y sus servicios. <br>El Perfil del Alumno nos presenta los mismos datos para los alumnos de nuestros grupos, de tal manera que podamos ofrec�rselo, por ejemplo, para el aceso a Moodle. Un formulario nos presenta nuestros Grupos y Asignaturas en el Curso Escolar para seleccionar. Si un alumno al que damos clase no aparece en el listado, seguramente el problema se encuentra en un error de matriculaci�n del mismo (no se ha matriculado en nuestra asignatura). Es conveniente comunicar este dato a la Administracci�n del Centro.<br><br>
Las Estad�sticas TIC nos ofrecen los datos de uso de ordenadores y profesores tal como queda reflejado en el sistema de reservas de los mismos. Los ordenadores o carros con port�tiles deben haber sido registrados durante la instalaci�n, o a trav�s de Administracci�n de la Intranet  --> Configuraci�n de la aplicaci�n.
</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>
</div>
</div>
</div>
		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Nueva incidencia</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'incidencias.php')==TRUE) ? ' class="active"' : ''; ?>><a href="incidencias.php">Listado de incidencias</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'perfiles_alumnos.php')==TRUE) ? ' class="active"' : ''; ?>><a href="perfiles_alumnos.php">Perfiles alumnos</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'perfiles_profesores.php')==TRUE) ? ' class="active"' : ''; ?>><a href="perfiles_profesores.php">Perfiles profesores</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'protocolo.php')==TRUE) ? ' class="active"' : ''; ?>><a href="protocolo.php">Protocolo de uso</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'estadisticas.php')==TRUE) ? ' class="active"' : ''; ?>><a href="estadisticas.php">Estad�sticas TIC</a></li>
		</ul>
		
	</div>
