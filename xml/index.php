<?
session_start();
include("../config.php");
include_once('../config/version.php');
// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/salir.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/salir.php');
		exit();
	}
}

if($_SESSION['cambiar_clave']) {
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/clave.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/clave.php');
		exit();
	}
}


registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);



if(!(stristr($_SESSION['cargo'],'1') == TRUE))
{
header('Location:'.'http://'.$dominio.'/intranet/salir.php');
exit;	
}
?>
<?
include("../menu.php");
?>

<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
		<h2>Administraci�n <small>Funciones, configuraci�n, importaci�n de datos,...</small></h2>
	</div>
	
	
	<div class="row">
		
		<!-- COLUMNA IZQUIERDA -->
		<div class="col-sm-4">
		
			<div class="well">
			<?php include("menu.php");?>
			</div>
			
		</div><!-- /.col-sm-4 -->
		
		
		<!-- COLUMNA DERECHA -->
		<div class="col-sm-8">
			
			<h3>Descripci�n de los m�dulos e instrucciones.</h3>
			
			<div class="text-justify">
			<p>	
			Esta es la pagina de Administraci�n de la Intranet y de las Bases de Datos de la misma. A continuaci�n siguen algunas explicaciones sobre la mayor�a de los m�dulos que componen la aplicaci�n.</p>
			<hr>
			<p>La <strong>primera opci�n (<span class="text-info">Cambiar la Configuraci�n</span>)</strong> permite editar y modificar los datos de la configuraci�n que se crearon cuando se instal� la Intranet.</p> 
			
			<hr>
			<p>	
			El <strong>segundo grupo de opciones (<span class="text-info">A Principio de curso...</span>)</strong> crea las tablas principales: Alumnos, Profesores, Asignaturas, Calificaciones y Horarios. Hay que tener a mano varios archivos que descargamos de Seneca y Horw: </p>
			<ul>
			
			<li>Los Alumnos, Asignaturas y Sistemas de Calificaciones se crean una sola vez a comienzo de curso, aunque luego podemos actualizarlos cuando queramos. En este proceso se crean las tablas de Alumnos y se les asigna un n�mero de aula. Tambi�n se generan dos archivos preparados para el Alta masiva de Alumnos y Profesores en Gesuser (los coloca en intranet/xml/jefe/TIC/). Necesitamos dos archivos de S�neca: 
			<ul>
			  <li>el de los alumnos. Lo descargamos desde S�neca. Alumnado --&gt; Alumnado --&gt; Alumnado del Centro --&gt; Aceptar (arriba a la derecha) --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano. El archivo que se descarga se llama RelPerCen.txt</li>
			  <li>el de las evaluaciones. Se descarga de Seneca desde &quot;Intercambio de Informaci�n --&gt; Exportaci�n desde Seneca --&gt; Exportaci�n de Calificaciones&quot;. Arriba a la derecha hay un icono para crear un nuevo documento con los datos de las evaluaciones; seleccionar todos los grupos del Centro para una evaluaci�n (la primera vale, por ejemplo) y a�adirlos a la lista. Cuando hay�is terminado, haced click en el icono de confirmaci�n y al cabo de un minuto volved a la p�gina de exportaci�n de calificaciones y ver�is que se ha generado un archivo comprimido que pod�is descargaros. </li>
			</ul>
			</li>
			<li>Los datos generales del Centro. Este m�dulo se encarga de importar la relaci�n de <strong>cursos</strong> y <strong>unidades</strong> del Centro registrados en S�neca, as� como la relaci�n de <strong>materias</strong> que se imparten y <strong>actividades</strong> del personal docente. Se importar� tambi�n la relaci�n de <strong>dependencias</strong>, que se utilizar� para realizar reservas de aulas o consultar el horario de aulas.</li>
			  <li>Los profesores. Se descarga desde S�neca --&gt; Personal --&gt; Personal del centro --&gt; Unidades y Materias  --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano.</li>
			  <li>Los Horarios (que generamos con Horw). Requiere el archivo con extensi�n XML que se genera con el programa generador de horarios para subir los datos del Horario a S�neca. Este m�dulo tambi�n se encarga de preparar el archivo para exportar a S�neca que crean los programas de Horarios (Horw, etc.), evitando tener que registrar manualmente los horarios de cada profesor. La adaptaci�n que realiza este m�dulo es conveniente, ya que la compatibilidad con S�neca de los generadores de horarios tiene limitaciones (C�digo �nico de las asignaturas de Bachillerato, Diversificaci�n, etc.). Es necesario tener a mano el archivo en formato XML que se exporta desde Horw o cualquier otro generador de Horarios. 
Es posible importar los horarios con el archivo de tipo DL que se genera con Horwin, pero esta opci�n s�lo debe utilizarse excepcionalmente. La opci�n preferida y m�s completa es el archivo XML.</li>
			  <li>Los Departamentos. Se descarga desde S�neca --&gt; Personal --&gt; Personal del centro  --&gt; Exportar (arriba a la izquierda) --&gt; Exportar datos al formato: Texto plano.</li>
			  <li>Los Horarios (que generamos con Horw). Solo se genera una vez a principio de Curso. Las instrucciones de la descarga est�n en el formulario correspondiente, al pinchar en el enlace.</li>
			 <li>La importaci�n de alumnos pendientes permite crear una tabla con los alumnos con asignaturas pendientes de cursos anteriores, que posteriormente puede consultarse en Consultas --> Listas de alumnos. </li>
			</ul>
			</p>
			<hr>
			<p>
			El <strong>tercer grupo de opciones</strong> afecta a los <strong><span class="text-info">Profesores</span></strong>. 
			 Una vez se han creado los Departamentos y Profesores, es necesario seleccionar los <span class="text-info">Perfiles de los Profesores</span> para que la aplicaci�n se ajuste a las funciones propias de cada profesor ( Tutor, Direcci�n, Jefe de Departamento, etc. ). Tambi�n desde aqu� se puede <span class="text-info">Restablecer las contrase�as</span> de los profesores que se hayan olvidado de la misma. Al restablecer, el profesor deber� entrar de nuevo con el DNI como contrase�a, lo que le llevar� a la p�gina desde la que tendr� que cambiarla con los criterios de S�neca. La �ltima opci�n, <span class="text-info">Copiar datos de un profesor a otro</span> cambia el horario de un profesor que ha sido sustituido al profesor que lo sustituye, de tal manera que el nuevo profesor pueda entrar en la Intranet con su usuario IdEA normalmente.
			 </p>
			 <hr>
			<p>
			    El <strong>cuarto grupo (<span class="text-info">Actualizaci�n</span>)</strong> permite actualizar los datos de Alumnos, Profesores y Departamentos del Centro. Esta pensado para la actualizaci�n de los alumnos que se van matriculando a lo largo del Curso, as� como para la puesta al d�a de la lista de Profesores y Departamentos. Necesita el archivo de alumnos y el de la evaluaci�n correspondiente como en la primera opci�n, ambos actualizados. Los m�dulos de Profesores y Departamentos, as� como el de Horarios, requieren de sus respectivos archivos, especificados en su propia p�gina. Es importante tener en cuenta que despu�s de actualizar los horarios deben actualizarse los profesores para garantizar la compatibilidad de los datos de Horw con S�neca. La �ltima opci�n, Limpiar Horarios, se debe ejecutar cuando los cambios en los horarios se han dado por terminados y se encuentran en perfecto estado en S�neca. Supone que los profesores se encuentran actualizados.</p>
			    <hr>
			<p>	
			El <strong>quinto grupo (<span class="text-info">Notas de Evaluaci�n</span>)</strong> crea y actualiza la tabla de las Notas de Evaluaci�n que aparecen en los Informes de la Intranet, pero tambi�n presenta las Calificaciones del alumno en la pagina principal. Los archivos necesarios se descargan de S�neca desde &quot;Intercambio de Informaci�n --&gt; Exportaci�n desde Seneca --&gt; Exportaci�n de Calificaciones&quot;.</p>
			<hr>
			<p>El <strong>sexto grupo <span class="text-info">(Faltas de asistencia</span>)</strong> contiene un grupo de funciones que aparecen si el m�dulo de Faltas de asistencia ha sido activado en la p�gina de Configuraci�n de la intranet.</p>
			<ul>
			  <li>&quot;Alumnos Absentistas&quot; da entrada al m�dulo de Absentismo, desde el cual se registran los alumnos y permite al Tutor, Direcci�n y Orientaci�n informar sobre los mismos. </li>
			  <li>El &quot;Parte de Faltas completo&quot; genera todos los partes de aula de Faltas de Asistencia para todas las clases,  todos los d�as de la semana. </li>
			  <li>El &quot;Informe de Faltas para Padres&quot; presenta las cartas que se env�an a los pap�s con las faltas del nene,
			    preparadas para imprimir, pero solo en aquellos casos para los que no se env�en SMS con la siguiente opci�n, &quot;SMS de Faltas para Padres&quot;. </li>
			  <li>&quot;Horario de faltas para profesores&quot; crea los horarios que los profesores necesitan para registrar las faltas de asistencia de los alumnos. Cada profesor marca en su horario el n�mero de clase de los alumnos ausentes en una semana, y, o bien las registra posteriormente en el m�dulo de &quot;Poner faltas&quot;, o bien lo entrega para que los Tutores de Faltas lo hagan.</li>
			  <li>&quot;SMS de faltas para los Padres&quot; permite enviar regularmente un SMS de faltas a los padres de modo masivo. Se env�an SMS a los padres de todos los alumnos que tengan un m�nimo de una falta de asistencia en el plazo de tiempo seleccionado.</li>
			</ul>
			<hr>
			<p>El <strong>s�ptimo grupo <span class="text-info">(Alumnos)</span></strong> toca asuntos varios relacionados con los mismos. </p>
			<ul>
			 <li>Las Listas de Grupos. Supone que se han realizado todas las tareas anteriores (Horario, Profesores, Alumnos, etc.). Presenta la lista de todos los Grupos del Centro preparada para ser imprimida y entregada a los Profesores a principios de Curso. </li>
			<li>"Carnet de los Alumnos" permite generar los carnet de los alumnos del Centro preparados para ser imprimidos. Este m�dulo supone que se han subido las fotos de los alumnos a la intranet utilizando el enlace "Subir fotos de alumnos", a continuaci�n.</li>
			<li>"Subir fotos de alumnos" permite hacer una subida en bloque de todas las fotos de los alumnos para se utilizadas por los distintos m�dulos de la Intranet. Para ello, necesitaremos crear un archivo comprimido ( .zip ) con todos los archivos de fotos de los alumnos. Cada archivo de foto tiene como nombre el NIE de S�neca (el N�mero de Identificaci�n que S�neca asigna a cada alumno ) seguido de la extensi�n .jpg o .jpeg. El nombre t�pico de un archivo de foto quedar�a por ejemplo as�: 1526530.jpg. Las fotos de los profesores se suben del mismo modo, pero el nombre se construye a partir del usuario IdEA ( mgargon732.jpg, por ejemplo).</li>
			  <li>&quot;Libros de Texto gratuitos&quot; es un conjunto de p�ginas pensadas para registrar el estado de los libros de cada alumno dentro del Programa de Ayudas al Estudio de la Junta, e imprimir los certificados correspondientes (incluidas las facturas en caso de mal estado o p�rdida del material).</li>
			    <li>&quot;Matriculaci�n de alumnos&quot; es un m�dulo que permite matricular a los alumnos a trav�s de la intranet o, en su caso, a trav�s de internet (si el m�dulo se ha incorporado a la p�gina principal del Centro). Los tutores, a final de curso, ayudan a los alumnos a matricularse en una sesi�n de tutor�a. Posteriormente el Centro imprime los formularios de la matr�cula y se los entregan a los alumnos para ser firmados por sus padres y entregados de nuevo en el IES. El Equipo directivo cuenta entonces con la ventaja de poder administrar los datos f�cilmente para formar los grupos de acuerdo a una gran variedad de criterios. El m�dulo incluye una p�gina que realiza previsiones de matriculaci�n de alumnos en las distintas evaluaciones.</li> 
			    </ul>
			<hr>
			<p>El <strong>�ltimo grupo <span class="text-info">(Base de datos)</span></strong> permite realizar copias de seguridad de las bases de datos que contienen los datos esenciales de la Intranet. La copia de seguridad crea un archivo, comprimido o en formato texto (SQL), en un directorio de la aplicaci�n ( /intranet/xml/jefe/copia_db/ ). Esta copia puede ser descargada una vez creada. Tambi�n podemos restaurar la copia de seguridad seleccionando el archivo que hemos creado anteriormente. </p>
			
			    
			  <!--   <li>&quot;Registro de Fotocopias&quot; es un m�dulo que permite a los Conserjes y Direcci�n registrar las fotocopias que se hacen en el centro. La Direcci�n tambi�n puede ver estad�sticas por profesor y departamento.</li>
			    
			<li>&quot;Importar fotos de alumnos&quot; permite insertar o reemplazar fotos de alumnos en la tabla <em>Fotos</em> de la Base de datos general. El directorio donde se encuentran las fotos debe ser registrado en la p�gina de Administraci�n de la intranet, y es conveniente que se encuentre dentro del alcance de PHP. El nombre de los archivos de las fotos debe contener como primera parte el identificador Personal del Alumno en S�neca (<strong>claveal</strong> en la tabla <strong>Alma</strong>, por ejemplo), seguido del formato de imagen (<em>.jpeg</em> o <em>.jpg</em>). Una vez insertadas o actualizadas las fotos, pueden ser consultadas desde varios m�dulos de la Intranet. </li>-->
			
		</div><!-- /.col-sm-8 -->
		
	</div><!-- /.row -->

</div><!-- /.container -->


<? include("../pie.php");?>  
</body>
</html>
