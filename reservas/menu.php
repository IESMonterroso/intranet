<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

if (isset($_GET['recurso'])) {$recurso = preg_replace('([^A-Za-z0-9 ])', '', $_GET['recurso']);}elseif (isset($_POST['recurso'])) {$recurso = $_POST['recurso'];}else{$recurso="";}
if (isset($_GET['servicio'])) {$servicio = $_GET['servicio'];}elseif (isset($_POST['servicio'])) {$servicio = $_POST['servicio'];}else{$servicio="";}
if (isset($_GET['mens'])) {$mens = $_GET['mens'];}elseif (isset($_POST['mens'])) {$mens = $_POST['mens'];}else{$mens="";}
if (isset($_GET['servicio_aula'])) {$servicio_aula = $_GET['servicio_aula'];}elseif (isset($_POST['servicio_aula'])) {$servicio_aula = $_POST['servicio_aula'];}else{$servicio_aula="";}
?>

<div class="container">

	<!-- Button trigger modal -->
	<a href="#" class="btn btn-default btn-sm pull-right hidden-print" data-toggle="modal" data-target="#modalAyuda">
		<span class="fas fa-question fa-lg"></span>
	</a>

	<!-- Modal -->
	<div class="modal fade" id="modalAyuda" tabindex="-1" role="dialog" aria-labelledby="modal_ayuda_titulo" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
					<h4 class="modal-title" id="modal_ayuda_titulo">Instrucciones de uso</h4>
				</div>
				<div class="modal-body">
					<?php if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
					<p>El sistema de reservas permite asignar y controlar el uso de los medios del
					Centro (Dependencias, Medios Audiovisuales, Ordenadores, etc.). Hay tres
					Tipos por defecto en el sistema: Aulas, Ordenadores TIC y Medios, pero puedes
					añadir o quitar categorías (Tipos) a tu gusto.</p>

					<p>El módulo se configura bien desde el menú en la página de Administración de
					la Intranet (<em><strong>Configuración --> Gestión de Reservas</strong></em>) o
					bien en la propia página de las Reservas (<em><strong>Gestión de Reservas -->
					Administración de recursos</strong></em>). Defines en primer lugar los <em>tipos
					de recursos</em> que vas abrir al sistema (Ordenadores TIC y Medios Audiovisuales
					aparecen por defecto). Una vez definidos los tipos de recursos, crea los
					<em>elementos</em> (Carrito nº 1, Videoproyector nº 1, etc.) dentro de cada tipo.
					Puedes añadir una descripción o información más precisa en el campo
					<em><strong>Observaciones</strong></em>, y aparecerá bajo el nombre del recurso
					en la página de entrada del módulo de reservas.</p>

					<?php endif; ?>
					<p>La reserva de Aulas y Dependencias del Centro está integrada con el módulo de
					Horarios. Puede funcionar sin la importación de los horarios si creamos las Aulas
					desde la opción del menú 'Administrar Aulas y Dependencias', pero está
					pensado para tomar la lista de aulas desde el horario que hemos importado. Por
					defecto, todas las aulas del Centro aparecen en la lista como reservables. Si
					deseamos ocultar aulas del sistema utilizamos la opción mencionada del menú;
					también podemos crear aulas que no aparecen en el horario.</p>

					<p>El funcionamiento es sencillo: elegimos el aula (Recurso TIC o Medio), fecha y
					hora; comprobamos que no ha sido reservada anteriormente por otro profesor y
					procedemos a registrarla. El Aula Magna (Salón de Usos Múltiples) sólo puede ser
					reservado por el Equipo Directivo (si necesitamos hacerlo, debemos pedir
					autorización a los miembros del mismo). El resto de las aulas sólo permiten la
					reserva cuando en la hora correspondiente el aula no está asignada en el horario
					a algún profesor en tareas lectivas. Si nos hemos equivocado o queremos eliminar
					una reserva, seleccionamos el día de la misma y borramos nuestro nombre el día y
					la hora elegidos. Una vez borrado, enviamos los datos del formulario con el botón
					<em><strong>Reservar</strong></em> y desaparecerá de la lista.</p>

					<?php if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
					<p>El Equipo directivo puede asignar el horario de las dependencias del Centro de
					varias maneras. Si el aula aparece en el Horario y este se ha importado en la base
					de datos, se presentarán las horas del aula con la asignatura que se imparte en
					ese momento con la expresión <strong>Asignada por Horario</strong>. El Equipo
					directivo puede asignar el aula a otro profesor por encima del Horario si por
					alguna razón lo considera necesario. La hora aparecerá entonces en el formulario
					con la expresión <strong>Asignada por Dirección.</strong></p>

					<p>Si el aula está vacía en ese momento cualquier profesor puede seleccionar esa
					hora para utilizar el aula.</p>

					<p>Si queremos asignar una hora a un profesor durante todo el curso escolar,
					seleccionamos al mismo en el formulario y pulsamos sobre el botón rojo
					<strong>Reservar todo el Curso</strong>. Es importante elegir un día sin reserva
					alguna de profesores para realizar la asignación permanente porque ls profesores
					que aparezcan en el formulario quedarán fijados para todo el curso escolar. Los
					campos en los que aparezca la palabra "Asignación" (bien por Horario o bien por
					Dirección) no se registran en la reserva permanente. A partir de ese momento los
					profesores verán en ese campo la expresión <strong>Asignada por Dirección</strong>
					y no podrán elegir el aula en esa hora. Este procedimiento se puede aplicar tantas
					veces como queramos para aquellas dependencias o aulas que aparezcan en el horario.</p>
					<?php endif; ?>

					<p>Si hemos marcado la opción 'Centro TIC' en la instalación de la aplicación,
					aparecerá una entrada en el menú para los carritos de ordenadores o aulas TIC.
					Si utilizamos los carros de ordenadores mediante el sistema de reservas podemos
					acceder a las estadísticas de uso de los mismos dentro del menú de la página de
					inicio de la Intranet (Trabajo --> Centro TIC --> Estadísticas).</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Entendido</button>
				</div>
			</div>
		</div>
	</div>


	<ul class="nav nav-tabs">
	<?php if ($config['mod_horarios']): ?>
	<li
	<?php echo (strstr($_SERVER['REQUEST_URI'],'index_aula')==TRUE) ? ' class="active"' : ''; ?>><a
		href="//<?php echo $config['dominio']; ?>/intranet/reservas/index_aula.php?recurso=aula_grupo">Aulas
	y Dependencias del Centro</a></li>
	<?php endif; ?>
	<?php
	$rc = mysqli_query($db_con, "select tipo from reservas_tipos");
	while ($srv = mysqli_fetch_array($rc)) {
	?>
	<li
	<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php?recurso='.urlencode($srv[0]))==TRUE) ? ' class="active"' : ''; ?>><a
		href="//<?php echo $config['dominio']; ?>/intranet/reservas/index.php?recurso=<?php echo urlencode($srv[0]);?>"><?php echo $srv[0];?></a></li>
	<?php
	}
	?>
	<?php if (strstr($_SESSION['cargo'],"1")==TRUE): ?>
	<li	class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'gestion')==TRUE OR strstr($_SERVER['REQUEST_URI'],'ocultar')==TRUE) ? ' active' : ''; ?>">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"> Gestión de reservas<span
		class="caret"></span> </a>
	<ul class="dropdown-menu" role="menu">
	<?php if ($config['mod_horarios']): ?>

	<li
	<?php echo (strstr($_SERVER['REQUEST_URI'],'ocultar.php')==TRUE) ? ' class="active"' : ''; ?>><a
		href="//<?php echo $config['dominio']; ?>/intranet/reservas/ocultar.php">Administración de aulas y dependencias</a></li>
	<?php endif; ?>
	<li><a href="//<?php echo $config['dominio']; ?>/intranet/reservas/gestion_tipo.php">Administración de recursos</a></li>
	</ul>
	</li>
	<?php endif; ?>
</ul>

</div>
