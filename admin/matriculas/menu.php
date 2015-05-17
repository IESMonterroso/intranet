<?php
if (isset($_GET['curso'])) {$curso = $_GET['curso'];}elseif (isset($_POST['curso'])) {$curso = $_POST['curso'];}else{$curso="";}
if (isset($_GET['dni'])) {$dni = $_GET['dni'];}elseif (isset($_POST['dni'])) {$dni = $_POST['dni'];}else{$dni="";}
if (isset($_GET['enviar'])) {$enviar = $_GET['enviar'];}elseif (isset($_POST['enviar'])) {$enviar = $_POST['enviar'];}else{$enviar="";}
if (isset($_GET['id'])) {$id = $_GET['id'];}elseif (isset($_POST['id'])) {$id = $_POST['id'];}else{$id="";}
if (isset($_GET['listados'])) {$listados = $_GET['listados'];}elseif (isset($_POST['listados'])) {$listados = $_POST['listados'];}else{$listados="";}
if (isset($_GET['listado_total'])) {$listado_total = $_GET['listado_total'];}elseif (isset($_POST['listado_total'])) {$listado_total = $_POST['listado_total'];}else{$listado_total="";}
if (isset($_GET['imprimir'])) {$imprimir = $_GET['imprimir'];}elseif (isset($_POST['imprimir'])) {$imprimir = $_POST['imprimir'];}else{$imprimir="";}
if (isset($_GET['cambios'])) {$cambios = $_GET['cambios'];}elseif (isset($_POST['cambios'])) {$cambios = $_POST['cambios'];}else{$cambios="";}
if (isset($_GET['sin_matricula'])) {$sin_matricula = $_GET['sin_matricula'];}elseif (isset($_POST['sin_matricula'])) {$sin_matricula = $_POST['sin_matricula'];}else{$sin_matricula="";}
?>
	
	<div class="container">
		
		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'previsiones.php')==TRUE) ? ' class="active"' : ''; ?>><a href="previsiones.php">Previsiones de matr�cula</a></li>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'consultas')==TRUE) ? ' active' : ''; ?>">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			    Consultas <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="consultas.php">Matriculas de ESO</a></li>
			    <li><a href="consultas_bach.php">Matriculas de Bachillerato</a></li>
			  </ul>
			</li>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'index')==TRUE) ? ' active' : ''; ?>">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			    Matriculaci�n <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="index.php">Matricular en ESO</a></li>
			    <li><a href="index_bach.php">Matricular en Bachillerato</a></li>
			  </ul>
			</li>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'importar')==TRUE) ? ' active' : ''; ?>">
			  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
			    Herramientas <span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu" role="menu">
			  	<li><a href="index_primaria.php">Importar Alumnado de Primaria</a></li>
			  	<li><a href="index_secundaria.php">Importar Alumnado de ESO</a></li>
			  	<li><a href="activar_matriculas.php?activar=1">Activar matriculaci�n</a></li>
			  	<li><a href="activar_matriculas.php?activar=2">Desactivar matriculaci�n</a></li>
			  </ul>
			</li>
			<li><a href="consulta_transito.php">Informes de Tr�nsito</a></li>
			<li>
			<!-- Button trigger modal -->
<a href="#" data-toggle="modal" data-target="#myModal">
 <span class="fa fa-question-circle fa-lg"></span>
</a>

 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Instrucciones de uso.</h4>
      </div>
      <div class="modal-body">
		<p class="help-block">
		El m�dulo de Matriculaci�n de Alumnos permite la matriculaci�n de los alumnos en ESO o Bachillerato a trav�s de la P�gina del Centro (o bien de la Intranet). La P�gina p�blica del Centro contiene, dentro de la secci�n privada <b>"Acceso para los Alumnos"</b>, un formulario de matriculaci�n que se activa en el mes de Junio. Los padres o los alumnos (en una sesi�n de Tutor�a y acompa�ados por el Tutor) registran en primer lugar los datos de la matr�cula. A continuaci�n, el Centro imprime los papeles de la matr�cula y se le entregan al alumno para que sus padres los firmen y presenten posteriormente en la Administraci�n dentro de las fechas elegidas por el Centro. </p>
		<p class="help-block"> 
		La secci�n <b>"Matriculaci�n"</b> presenta un formulario semejante al que hay en la P�gina del Centro. Permite matricular a los alumnos desde la Intranet.</p>
		<p class="help-block">
		La secci�n <b>"Consultas"</b> presenta los datos de los alumnos matriculados de forma estucturada en una tabla. Los datos pueden ser filtrados y ordenados de m�ltiples maneras, en funci�n de las opciones propias de cada nivel. El primer campo de verificaci�n que nos encontramos en la tabla debe ser marcado por el personal de Administraci�n o Equipo directivo cuando el alumno entrega la matr�cula, para as� diferenciar entre los alumnos que se han matyriculado y los que han entregado efectivamente la matr�cula. El campo "GR2" permite asigna la letra del grupo al alumno para generar posteriormente los listados de grupos de una forma f�cil. Las casillas "Bil" y "Div" se utilizan para seleccionar a los alumnos Bilingues y de Diversificaci�n.<br> 
		Los botones de radio asociados al Promoci�n (SI/PIL/NO en ESO; SI/NO/3-4 en Bachillerato) son el elmento decisivo de la consulta. Cuando se han subido las calificaciones de la Evaluaci�n Ordinaria, la aplicaci�n aplica los criterios de promoci�n habitulaes en cada nivel y marca el boton de radio correspondiente. Es entonces cuando se pueden "Enviar lo datos" con el bot�n que hay al final de la tabla de consultas. Sin embargo, hay que tener en cuenta que hay alumnos que pueden promocionar por imperativo legal, o con m�s de 2 asignaturas suspensas, etc. Estos alumnos an�malos deben ser seleccionados antes de enviar los datos para que la aplicaci�n no seleccione la opci�n equivocada. Hay que tener en cuenta que en Junio s�lo paracer�n seleccionados los alumnos que promocionan. Los alumnos que tengan materias pendientes para Septiembre aparecen sin ninguna opci�n de promoci�n marcada hasta que las calificaciones de la Evaluaci�n Extraordinaria se importan en la Intranet, cuando habr� que enviar los datos una vez contempladas las anomal�as mencionadas antes.<br>
		Al final de la tabla aparece un conjunto de botones con funciones espec�ficas. El bot�n <b>"Imprimir"</b> manda a la impresora el documento PDF de todos los alumnos del nivel que estamos consultando, la misma funci�n asignada al bot�n <b>"Car�tulas"</b> (para pegar en la portada del sobre o carpeta donde se guardan los documentos impresos de la matr�cula). El bot�n <b>"Ver cambios en datos"</b> facilita al personal de Administraci�n la tarea de saber qu� alumnos han modificado datos importantes de la matr�cula respecto a la del curso anterior (Direcci�n, Tel�fonos, etc.). El bot�n <b>"Alumnos sin matricular"</b> nos informa sobre los alumnos de Centro que no han registrado los datos en el formulario. El �ltimo bot�n genera un PDF con las listas de alumnos por grupo asignado, y s�lo presenta los datos de los alumnos cunado estos han sido asignados a un grupo en el campo "GR2".<br>
		La Consulta contiene al final una tabla con estad�sticas de la matriculaci�n en diferentes campos dependiendo del nivel.
		</p>
		<p class="help-block">
		La secci�n <b>"Herramientas"</b> del men� permite <em>importar los datos de los alumnos pertenecientes a Centros adscritos</em>, tanto de Primaria como de Secundaria. La p�gina de importaci�n da informaci�n concreta sobre el proceso a seguir para realizarla, tanto en Primaria como en Secundaria (si procede). Esto permite extender los beneficios de la matriculaci�n a los alumnos de los mismos. Estos acceden al formulario de matriculaci�n del mismo modo que los propios alumnos del Centro, a trav�s del enlace <b>"Acceso para Alumnos"</b> en la P�gina del Centro. Se identifican con el DNI o NIF del Tutor legal en los campos "Clave del Centro" y "Clave del Alumno".<br>
		Los alumnos del Centro se identifican mediante el NIE de S�neca en los campos <em>"Clave del Centro" y "Clave del Alumno"</em>. El Tutor les entrega normalmente el NIE en la sesi�n de Tutor�a dedicada a la matriculaci�n. Para poder matricularse, es necesario <b>"Activar la matriculaci�n"</b>. Al hacerlo, se modifica la tabla de contrase�as que los Padres utilizan para acceder a las p�ginas privadas del alumno en la P�gina p�blica del Centro, de tal modo que el acceso se haga con el NIE. Una vez terminado el proceso, hay que <b>"Desactivar la matriculaci�n"</b> para restaurar las contrase�as de los Padres y estos puedan volver a entrar normalmente.</p>      
		</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
			</li>
		</ul>
		
	</div>
	