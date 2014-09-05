<?php
// COMPROBAMOS LA VERSI�N DE PHP
if (version_compare(phpversion(), '5.3.0', '<')) die ("<h1>Versi�n de PHP incompatible</h1>\n<p>Necesita PHP 5.3.0 o superior para poder utilizar esta aplicaci�n.</p>");

session_start();

// Comprobamos estado del archvo de configuraci�n.
$f_config = file_get_contents('config.php');

$tam_fichero = strlen($f_config);
if (file_exists ( "config.php" ) and $tam_fichero > '10') {
}
else{
// Compatibilidad con versiones anteriores: se mueve el archivo de configuraci�n al directorio ra�z.
// Archivo de configuraci�n en antiguo directorio se mueve al raiz de la intranet
if (file_exists ("/opt/e-smith/config.php")) 
{
	$texto = fopen("config.php","w+");
	if ($texto==FALSE) {
		echo "<script>alert('Parece que tenemos un problema serio para continuar: NO es posible escribir en el directorio de la Intranet. Debes asegurarte de que sea posible escribir en ese directorio, porque la aplicaci�n necesita modificar datos y crear archivos dentro del mismo. Utiliza un Administrador de archvos para conceder permiso de escritura en el directorio donde se encuentra la intranet. Hasta entonces me temo que no podemos continuar.')</script>";
		fclose($texto);
		exit();
	}
	else{
$lines = file('/opt/e-smith/config.php');
$Definitivo="";
foreach ($lines as $line_num => $line) {
$Definitivo.=$line;
}
$pepito=fwrite($texto,$Definitivo) or die("<script>alert('Parece que tenemos un problema serio para continuar: NO es posible escribir en el archivo de configuraci�n de la Intranet ( config.php ). Debes asegurarte de que sea posible escribir en ese directorio, porque la aplicaci�n necesita modificar datos y crear archivos dentro del mismo. Utiliza un Administrador de archvos para conceder permiso de escritura en el directorio donde se encuentra la intranet. Hasta entonces me temo que no podemos continuar.')</script>");
fclose ($texto);
}
}
else{
	header("location:config/index.php");
	exit();
}
}
// Archivo de configuraci�n cargado
include_once("config.php");

// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	header('Location:'.'http://'.$dominio.'/intranet/salir.php');	
	exit();
}

registraPagina ( $_SERVER ['REQUEST_URI'], $db_host, $db_user, $db_pass, $db );
$pr = $_SESSION ['profi'];
// Comprobamos si da clase a alg&uacute;n grupo
$cur0 = mysql_query ( "SELECT distinct prof FROM horw where prof = '$pr'" );
$cur1 = mysql_num_rows ( $cur0 );
$_SESSION ['n_cursos'] = $cur1;
$n_curso = $_SESSION ['n_cursos'];
// Variable del cargo del Profesor
$cargo0 = mysql_query ( "select cargo, departamento, idea from departamentos where nombre = '$pr'" );
$cargo1 = mysql_fetch_array ( $cargo0 );
$_SESSION ['cargo'] = $cargo1 [0];
$carg = $_SESSION ['cargo'];
$_SESSION ['dpt'] = $cargo1 [1];
$dpto = $_SESSION ['dpt'];
if (isset($_POST['idea'])) {}
else{
$_SESSION ['ide'] = $cargo1 [2];
$idea = $_SESSION ['ide'];
}
if (stristr ( $carg, '2' ) == TRUE) {
	$result = mysql_query ( "select distinct unidad from FTUTORES where tutor = '$pr'" );
	$row = mysql_fetch_array ( $result );
	$_SESSION ['tut'] = $pr;
	$_SESSION ['s_unidad'] = $row [0];
}
?>
<? include("menu.php");?>

	<div class="container-fluid" style="padding-top: 15px;">
		
		<div class="row">
			
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-sm-3">
				
				<div id="bs-tour-menulateral">
				<?php include("menu_lateral.php"); ?>
				</div>
				
				<div id="bs-tour-ausencias" class="hidden-xs">
				<?php include("admin/ausencias/widget_ausencias.php"); ?>
				</div>
				
				<div id="bs-tour-destacadas" class="hidden-xs">
				<?php include ("admin/noticias/widget_destacadas.php"); ?>
				</div>
	
			</div><!-- /.col-sm-3 -->
			
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-5">
				
				<?php 
				if (stristr($carg, '2' )==TRUE) {
					$_SESSION['mod_tutoria']['tutor']  = $_SESSION['tut'];
					$_SESSION['mod_tutoria']['unidad'] = $_SESSION['s_unidad'];
					
					define('INC_TUTORIA', 1);
					include("admin/tutoria/inc_pendientes.php");
				}
				?>
				<div id="bs-tour-pendientes">
				<?php include ("pendientes.php"); ?>
				</div>
				          
        <div class="bs-module">
        <?php include("admin/noticias/widget_noticias.php"); ?>
        </div>
        
        <br>
				
			</div><!-- /.col-sm-5 -->
			
			
			
			<!-- COLUMNA DERECHA -->
			<div class="col-sm-4">
				
				<div id="bs-tour-buscar">
				<?php include("buscar.php"); ?>
				</div>
				
				<br><br>
				
				<div id="bs-tour-calendario">
				<?php include("admin/calendario/index.php"); ?>
				</div>
				
				<br><br>
				
				<?php if($mod_horario and ($n_curso > 0)): ?>
				<div id="bs-tour-horario">
				<?php include("horario.php"); ?>
				</div>
				<?php endif; ?>
				
			</div><!-- /.col-sm-4 -->
			
		</div><!-- /.row -->
		
	</div><!-- /.container-fluid -->

<?php include("pie.php"); ?>
	
	<?php if (isset($_GET['tour']) && $_GET['tour']): ?>
	<script>
	// Instance the tour
	var tour = new Tour({
		
		onEnd: function() {
		  return window.location.href = 'http://<?php echo $dominio; ?>/intranet/index.php';
		},
		
		keyboard: true,
		template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-default btn-sm' data-role='prev'>� Anterior</button>&nbsp;<button class='btn btn-default btn-sm' data-role='next'>Siguiente �</button><button class='btn btn-default btn-sm' data-role='end'>Terminar</button></div></nav></div>",
		
	  steps: [
	  {
	    title: "<h1>Bienvenido a la Intranet</h1>",
	    content: "<p class='lead'>Antes de comenzar, realice un tour por la portada de la Intranet para conocer las caracter�sticas de los m�dulos que la componen y la informaci�n de la que dispone.</p><p>Haga click en <strong>Siguiente</strong> para continuar o haga click en <strong>Omitir</strong> para saltarse el tour.",
	    container: "body",
	    template: "<div class='popover tour' style='max-width: 600px !important;'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-default btn-sm' data-role='next'>Siguiente �</button><button class='btn btn-default btn-sm' data-role='end'>Omitir</button></div></nav></div>",
	    orphan: true,
	    backdrop: true,
	  },
	  {
	    element: "#bs-tour-usermenu",
	    title: "Men� de usuario",
	    content: "Desde este men� podr�s volver a cambiar la contrase�a, correo electr�nico y la fotograf�a.",
	    container: "body",
	    placement: "bottom",
	    backdrop: false,
	  },
	  {
	    element: "#bs-tour-consejeria",
	    title: "Novedades de la Consejer�a",
	    content: "Consulta las �ltimas novedades de la Consejer�a de Educaci�n, Cultura y Deporte de la Junta de Andaluc�a. Este icono solo ser� visible desde la portada de la Intranet.",
	    container: "body",
	    placement: "bottom",
	    backdrop: false,
	  },
	  {
	    element: "#bs-tour-mensajes",
	    title: "Mensajes",
	    content: "Consulta los �ltimos mensajes recibidos. Cuando recibas un mensaje, el icono cambiar� de color para avisarte. Para leer el mensaje haz click en este icono o dir�gete a la portada de la Intranet para ver todos los avisos.",
	    container: "body",
	    placement: "bottom",
	    backdrop: false,
	  },
	  {
	    element: "#bs-tour-menulateral",
	    title: "Men� de opciones",
	    content: "Seg�n tu perfil de trabajo tendr�s un men� con las opciones que necesitas en tu d�a a d�a.<br>Desde el men� <strong>Consultas</strong> tendr�s acceso a la informaci�n de los alumnos, horarios, estad�sticas y fondos de la Biblioteca del centro.<br>El men� <strong>Trabajo</strong> contiene las acciones de registro de Problemas de Convivencia, Faltas de Asistencia, Reservas de aulas y medios audiovisuales, Actividades evaluables, etc.<br>El men� <strong>Departamento</strong> contiene las opciones necesarias para la gesti�n de tu departamento.<br>Y por �ltimo, <strong>P�ginas de interes</strong> contiene enlaces a p�ginas externas de informaci�n y recursos educativos.",
	    container: "body",
	    placement: "right",
	    backdrop: true,
	  },
	  {
	    element: "#bs-tour-ausencias",
	    title: "Profesores de baja",
	    content: "Este m�dulo ofrece informaci�n sobre los profesores que est�n de baja en el d�a. Si el profesor ha indicado tareas para los alumnos aparecer� marcado con el icono chequeado. Para registrar una ausencia debe dirigirse al men� <strong>Trabajo</strong>, <strong>Profesores ausentes</strong>.",
	    container: "body",
	    placement: "right",
	    backdrop: true,
	  },
	  {
	    element: "#bs-tour-destacadas",
	    title: "Noticias destacadas",
	    content: "Este m�dulo ofrece un listado de las noticias mas importantes. Puede aparecer durante varios d�as, seg�n lo establezca el Equipo directivo.",
	    container: "body",
	    placement: "right",
	    backdrop: true,
	  },
	  {
	    element: "#bs-tour-pendientes",
	    title: "Tareas pendientes",
	    content: "Aqu� aparecer�n los avisos de tareas pendientes que tienes pendientes por realizar.",
	    container: "body",
	    placement: "bottom",
	    backdrop: true,
	  },
	  {
	    element: "#bs-tour-buscar",
	    title: "Buscar alumnos y noticias",
	    content: "Este buscador te permite buscar alumnos para consultar su expediente o realizar alguna acci�n como registrar un Problema de Convivencia o Intervenci�n. Puedes buscar tanto por nombre como apellidos. <br>Si presionas la tecla <kbd>Intro</kbd> buscar� coincidencias en las noticias publicadas.",
	    container: "body",
	    placement: "left",
	    backdrop: true,
	  },
	  {
	    element: "#bs-tour-calendario",
	    title: "Calendario del centro y Calendario personal",
	    content: "En la parte inferior del calendario aparecer� las actividades de los pr�ximos 7 d�as. Si el texto est� marcado en color naranja quiere decir que dicha actividad afecta a su horario. Tambi�n aparecer� su <em>Calendario personal</em> con aquellas actividades evaluables que haya registrado desde el men� <strong>Trabajo</strong>, <strong>Actividades evaluables</strong>.",
	    container: "body",
	    placement: "left",
	    backdrop: true,
	  }],
	 	});
	
	// Initialize the tour
	tour.init();
	
	// Start the tour
	tour.start(true);
	</script>
	<?php endif; ?>

</body>
</html>
