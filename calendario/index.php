<?php
session_start();
include("../config.php");
include_once('../config/version.php');

$GLOBALS['db_con'] = $db_con;

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


// PREPARACION BASE DE DATOS
/*
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `calendario` (
  `id` int(11) NOT NULL auto_increment,
  `categoria` int(11) NOT NULL,
  `nombre` varchar(120) collate latin1_spanish_ci NOT NULL,
  `descripcion` longtext collate latin1_spanish_ci NOT NULL,
  `fechaini` date default NULL,
  `horaini` time default NULL,
  `fechafin` date default NULL,
  `horafin` time default NULL,
  `lugar` varchar(180) collate latin1_spanish_ci NOT NULL,
  `departamento` varchar(80) collate latin1_spanish_ci default NULL,
  `unidades` varchar(120) collate latin1_spanish_ci default NULL,
  `fechareg` datetime NOT NULL,
  `profesorreg` varchar(60) collate latin1_spanish_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;");

mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `calendario_categorias` (
  `id` int(11) NOT NULL auto_increment,
  `nombre` varchar(80) collate latin1_spanish_ci NOT NULL,
  `fecha` date NOT NULL,
  `profesor` varchar(80) collate latin1_spanish_ci NOT NULL,
  `color` char(6) collate latin1_spanish_ci NOT NULL,
  `espublico` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci AUTO_INCREMENT=4;");

mysqli_query($db_con, "INSERT INTO `calendario_categorias` (`id`, `nombre`, `fecha`, `profesor`, `color`, `espublico`) VALUES
(1, 'Calendario del centro', '".date('Y-m-d')."', 'admin', 'f29b12', 1),
(2, 'Actividades extraescolares', '".date('Y-m-d')."7', 'admin', '18bc9c', 1),
(3, 'admin', '".date('Y-m-d')."', 'admin', '3498db', 0);");
*/

// CALENDARIO
$dia_actual = date('d');

$dia  = isset($_GET['dia'])  ? $_GET['dia']  : date('d');
$mes  = isset($_GET['mes'])  ? $_GET['mes']  : date('m');
$anio = isset($_GET['anio']) ? $_GET['anio'] : date('Y');

$semana = 1;

for ($i = 1; $i <= date('t', strtotime($anio.'-'.$mes)); $i++) {
	
	$dia_semana = date('N', strtotime($anio.'-'.$mes.'-'.$i));
	
	$calendario[$semana][$dia_semana] = $i;
	if ($dia_semana == 7) $semana++;
	
}


// NAVEGACION
$mes_ant = $mes - 1;
$anio_ant = $anio;

if ($mes == 1) {
	$mes_ant = 12;
	$anio_ant = $anio - 1;
}


$mes_sig = $mes + 1;
$anio_sig = $anio;

if ($mes == 12) {
	$mes_sig = 1;
	$anio_sig = $anio + 1;
}

// HTML CALENDARIO MENSUAL
function vista_mes ($calendario, $dia, $mes, $anio, $cargo) {
	
	// Correcci�n en mes
	($mes < 10) ? $mes = '0'.$mes : $mes = $mes;
	
	echo '<div class"table-responsive">';
	echo '<table id="calendar" class="table table-bordered">';
	echo '	<thead>';
	echo '		<tr>';
	echo '			<th class="text-center">Lunes</th>';
	echo '			<th class="text-center">Martes</th>';
	echo '			<th class="text-center">Mi�rcoles</th>';
	echo '			<th class="text-center">Jueves</th>';
	echo '			<th class="text-center">Viernes</th>';
	echo '			<th class="text-center">S�bado</th>';
	echo '			<th class="text-center">Domingo</th>';
	echo '		</tr>';
	echo '	</thead>';
	echo '	<tbody>';
	
	foreach ($calendario as $dias) {
		echo '		<tr>';
	
		for ($i = 1; $i <= 7; $i++) {
			
			if ($i > 5) {
				if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
					echo '			<td class="text-muted today" width="14.28%">';
				}
				else {
					echo '			<td class="text-muted" width="14.28%">';
				}
			}
			else {
				if (isset($dias[$i]) && ($mes == date('m')) && ($dias[$i] == date('d'))) {
					echo '			<td class="today" width="14.28%">';
				}
				else {
					echo '			<td width="14.28%">';
				}
			}
			
			if (isset($dias[$i])) {

				echo '				<p class="lead text-right">'.$dias[$i].'</p>';
				
				// Correcci�n en d�a
				($dias[$i] < 10) ? $dia0 = '0'.$dias[$i] : $dia0 = $dias[$i];
				
				// CALENDARIO PERSONAL
				$result = mysqli_query($GLOBALS['db_con'], "SELECT id, fecha, titulo FROM diario WHERE profesor = '".$_SESSION['profi']."'");
				while ($diario = mysqli_fetch_assoc($result)) {
					
					if ($diario['fecha'] == $anio.'-'.$mes.'-'.$dia0) {
						echo '<a href="#" data-toggle="modal" data-target="#modalEventoDiario'.$diario['id'].'" class="label label-info hidden_calendario_3 visible">'.$diario['titulo'].'</a>';
					}
				}
				mysqli_free_result($result);
				unset($diario);
				
				// CALENDARIO DEL CENTRO
				$result = mysqli_query($GLOBALS['db_con'], "SELECT id, eventdate, title FROM cal");
				while ($centro = mysqli_fetch_assoc($result)) {
					
					if ($centro['eventdate'] == $anio.'-'.$mes.'-'.$dia0) {
						if (stristr($_SESSION['cargo'],'1')) {
							echo '<a href="#" data-toggle="modal" data-target="#modalEventoCentro'.$centro['id'].'" class="label label-warning hidden_calendario_1 visible">'.$centro['title'].'</a>';
						}
						else {
							echo '<div class="label label-warning hidden_calendario_1 visible">'.$centro['title'].'</div>';
						}
					}
				}
				mysqli_free_result($result);
				unset($centro);
				
				// ACTIVIDADES EXTRAESCOLARES
				$result = mysqli_query($GLOBALS['db_con'], "SELECT id, fecha, actividad FROM actividades WHERE confirmado = 1");
				while ($actividad = mysqli_fetch_assoc($result)) {
					
					if ($actividad['fecha'] == $anio.'-'.$mes.'-'.$dia0) {
						if (stristr($_SESSION['cargo'],'1')) {
							echo '<a href="#" data-toggle="modal" data-target="#modalEventoActividad'.$actividad['id'].'" class="label label-success hidden_calendario_2 visible">'.$actividad['actividad'].'</a>';
						}
						else {
							echo '<div class="label label-success hidden_calendario_2 visible">'.$actividad['actividad'].'</div>';
						}
					}
				}
				mysqli_free_result($result);
				unset($actividad);
				
				// FESTIVOS
				$result = mysqli_query($GLOBALS['db_con'], "SELECT fecha, nombre FROM festivos");
				while ($festivo = mysqli_fetch_assoc($result)) {
					
					if ($festivo['fecha'] == $anio.'-'.$mes.'-'.$dia0) {
						echo '<div class="label label-danger hidden_calendario_festivo visible">'.$festivo['nombre'].'</div>';
					}
				}
				mysqli_free_result($result);
				unset($festivo);
				
				
			}
			else {
				echo '&nbsp;';
			}
			
			echo '			</td>';
		}
		echo '		</tr>';
	}
	
	echo '	</tbody>';
	echo '</table>';
	echo '</div>';

}

?>
<?php include("../menu.php"); ?>

	<div class="container">
		
		<style type="text/css">
		.label {
			display: block;
			white-space: normal;
			text-align: left;
			margin-top: 5px;
		}
		
		.today {
			background-color: #ecf0f1;
		}
		
		.today p.lead {
			font-weight: bold;
		}
		
		<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['profi']."' AND espublico=0"); ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php while ($row = mysqli_fetch_assoc($result)): ?>
		.hidden_calendario_<?php echo $row['id']; ?> {
		  display: none;
		}
		<?php endwhile; ?>
		<?php endif; ?>
		
		<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1"); ?>
		<?php if (mysqli_num_rows($result)): ?>
		<?php while ($row = mysqli_fetch_assoc($result)): ?>
		.hidden_calendario_<?php echo $row['id']; ?> {
		  display: none;
		}
		<?php endwhile; ?>
		<?php endif; ?>
		
		.hidden_calendario_festivo {
			display: none;
		}
		
		.visible {
			display: block;
		}
		
		@media print {
			html, body {
				width: 100%;
			}
			.container, .col-md-9 {
				width: 100%;
			}
		}
		</style>
		
		
		<?php
		// CALENDARIO PERSONAL
		$result = mysqli_query($GLOBALS['db_con'], "SELECT id, fecha, titulo FROM diario WHERE profesor = '".$_SESSION['profi']."'");
		while ($diario = mysqli_fetch_assoc($result)) {
			
			echo '<div id="modalEventoDiario'.$diario['id'].'" class="modal fade">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$diario['titulo'].'</h4>
			      </div>
			      <div class="modal-body">
			        
			        <form method="post" action="">
			        	<fieldset>
			        		<legend></legend>
			        		<div class="form-group">
			        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="T�tulo">
			        		</div>
			        		
			        	</fieldset>
			        </form>
			        
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			        <button type="button" class="btn btn-primary">Crear</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->';
		}
		mysqli_free_result($result);
		unset($diario);
		
		// CALENDARIO CENTRO
		$result = mysqli_query($GLOBALS['db_con'], "SELECT id, eventdate, title FROM cal");
		while ($centro = mysqli_fetch_assoc($result)) {
			
			echo '<div id="modalEventoCentro'.$centro['id'].'" class="modal fade">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$centro['title'].'</h4>
			      </div>
			      <div class="modal-body">
			        <p>One fine body&hellip;</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			        <button type="button" class="btn btn-primary">Crear</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->';
		}
		mysqli_free_result($result);
		unset($centro);
		
		// CALENDARIO ACTIVIDADES EXTRAESCOLARES
		$result = mysqli_query($GLOBALS['db_con'], "SELECT id, fecha, actividad FROM actividades WHERE confirmado = 1");
		while ($actividad = mysqli_fetch_assoc($result)) {
			
			echo '<div id="modalEventoActividad'.$actividad['id'].'" class="modal fade">
			  <div class="modal-dialog">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title">'.$actividad['actividad'].'</h4>
			      </div>
			      <div class="modal-body">
			        <p>One fine body&hellip;</p>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
			        <button type="button" class="btn btn-primary">Crear</button>
			      </div>
			    </div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->';
		}
		mysqli_free_result($result);
		unset($actividad);
		
		?>
		
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			
			<!-- BUTTONS -->
			<div class="col-md-9 pull-right">
				
				<h2 class="text-muted" style="display: inline;"><?php echo strftime('%B, %Y', strtotime($anio.'-'.$mes)); ?></h2>
				
				<div class="pull-right hidden-print">
					<a href="#" data-toggle="modal" data-target="#modalNuevoEvento" class="btn btn-primary">Nuevo</a>
					  
					<a href="#" onclick="javascrip:print()" class="btn btn-default">Imprimir</a>
					
					<div class="btn-group">
					  <a href="?mes=<?php echo $mes_ant; ?>&anio=<?php echo $anio_ant; ?>" class="btn btn-default">&laquo;</a>
					  <a href="?mes=<?php echo date('n'); ?>&anio=<?php echo date('Y'); ?>" class="btn btn-default">Hoy</a>
					  <a href="?mes=<?php echo $mes_sig; ?>&anio=<?php echo $anio_sig; ?>" class="btn btn-default">&raquo;</a>
					</div>
				</div>
			</div>
			
			<h2 class="hidden-print">Calendario</h2>
		</div>
		
		<!-- SCAFFOLDING -->
		<div class="row">
			
			<!-- COLUMNA IZQUIERDA -->
			<div class="col-md-3 hidden-print">
				
				<div class="row">
					<div class="col-xs-2 col-sm-3">
					  <span class="fa-stack fa-2x text-info">
					    <i class="fa fa-calendar-o fa-stack-2x"></i>
					    <strong class="fa-stack-1x" style="margin-top: .2em;"><?php echo strftime('%e', strtotime(date('Y-m-d'))); ?></strong>
					  </span>
					</div>
					<div class="col-xs-10 col-sm-9">
						<h4 style="margin-top: .2em; padding-top: 0; font-size: 1.5em;" class="text-info">
							<strong><?php echo strftime('%A', strtotime(date('Y-m-d'))); ?></strong><br>
							<?php echo strftime('%B, %Y', strtotime(date('Y-m-d'))); ?>
						</h4>
						
					</div> 
				</div>
				
				<hr>
				
				<h3>Mis calendarios</h3>
				
				<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['profi']."' AND espublico=0"); ?>
				<?php if (mysqli_num_rows($result)): ?>
				<ul class="nav nav-pills nav-stacked">
					<?php while ($row = mysqli_fetch_assoc($result)): ?>
					<li>
						<a href="#" id="toggle_calendario_<?php echo $row['id']; ?>">
							<span class="fa fa-square fa-fw fa-lg" style="color: #<?php echo $row['color']; ?>;"></span> <?php echo $row['nombre']; ?>
						</a>
					</li>
					<?php endwhile; ?>
				</ul>
				<?php endif; ?>
				
				<hr>
				
				<h3>Otros calendarios</h3>
				
				<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1"); ?>
				<?php if (mysqli_num_rows($result)): ?>
				<ul class="nav nav-pills nav-stacked">
					<?php while ($row = mysqli_fetch_assoc($result)): ?>
					<li>
						<a href="#" id="toggle_calendario_<?php echo $row['id']; ?>">
							<span class="fa fa-square fa-fw fa-lg" style="color: #<?php echo $row['color']; ?>;"></span> <?php echo $row['nombre']; ?>
						</a>
					</li>
					<?php endwhile; ?>
					<li>
						<a href="#" id="toggle_calendario_festivo">
							<span class="fa fa-square fa-fw fa-lg" style="color: #e14939;"></span> D�as festivos
						</a>
					</li>
				</ul>
				<?php endif; ?>
	
			</div><!-- /.col-md-3 -->
			
			
			<!-- COLUMNA CENTRAL -->
			<div class="col-md-9">
				
				<?php vista_mes($calendario, $dia, $mes, $anio, $_SESSION['cargo']); ?>
				
			</div><!-- /.col-md-9 -->
			
		</div><!-- /.row -->
		
	</div><!-- /.container -->
	
	<!-- MODAL NUEVO EVENTO -->
	<div id="modalNuevoEvento" class="modal fade">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Nuevo evento o actividad</h4>
	      </div>
	      <div class="modal-body">
	        
	        <form id="formNuevoEvento" method="post" action="">
	        	<fieldset>
	        		
	        		<div class="form-group">
	        			<label for="cmp_nombre" class="visible-xs">Nombre</label>
	        			<input type="text" class="form-control" id="cmp_nombre" name="cmp_nombre" placeholder="Nombre del evento o actividad" autofocus>
	        		</div>
	        		
	        		
        			<div class="row">
        				<div class="col-xs-6 col-sm-3">
        					<div class="form-group" id="datetimepicker1">
	        					<label for="cmp_fecha_ini">Fecha inicio</label>
	        					<div class="input-group">
		        					<input type="date" class="form-control" id="cmp_fecha_ini" name="cmp_fecha_ini" value="<?php echo date('d/m/Y'); ?>" data-date-format="DD/MM/YYYY">
		        					<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		        				</div>
		        			</div>
        				</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group" id="datetimepicker2">
		        				<label for="cmp_hora_ini">Hora inicio</label>
		        				<div class="input-group">
		        					<input type="date" class="form-control" id="cmp_hora_ini" name="cmp_hora_ini" value="<?php echo date('H:i'); ?>" data-date-format="HH:mm">
		        					<span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
		        				</div>
		        			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group" id="datetimepicker3">
		        				<label for="cmp_fecha_fin">Fecha fin</label>
		        				<div class="input-group">
		        					<input type="date" class="form-control" id="cmp_fecha_fin" name="cmp_fecha_fin" value="<?php echo date('d/m/Y'); ?>" data-date-format="DD/MM/YYYY">
		        					<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
		        				</div>
		        			</div>
	        			</div>
	        			<div class="col-xs-6 col-sm-3">
	        				<div class="form-group" id="datetimepicker4">
		        				<label for="cmp_hora_fin">Hora fin</label>
		        				<div class="input-group">
		        					<input type="date" class="form-control" id="cmp_hora_fin" name="cmp_hora_fin" value="<?php echo date('H:i', strtotime('+1 hour', strtotime(date('H:i')))); ?>" data-date-format="HH:mm">
		        					<span class="input-group-addon"><span class="fa fa-clock-o"></span></span>
		        				</div>
		        			</div>
	        			</div>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="cmp_descripcion">Descripci�n</label>
	        			<textarea type="text" class="form-control" id="cmp_descripcion" name="cmp_descripcion"></textarea>
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="cmp_lugar">Lugar</label>
	        			<input type="text" class="form-control" id="cmp_lugar" name="cmp_lugar">
	        		</div>
	        		
	        		<div class="form-group">
	        			<label for="cmp_calendario">Calendario</label>
	        			<select class="form-control" id="cmp_calendario" name="cmp_calendario">
	        				<optgroup label="Mis calendarios">
	        					<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['profi']."' AND espublico=0"); ?>
	        					<?php while ($row = mysqli_fetch_assoc($result)): ?>
	        					<option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
	        					<?php endwhile; ?>
	        					<?php mysqli_free_result($result); ?>
	        				</optgroup>
	        				<?php if (stristr($_SESSION['cargo'],'1')): ?>
	        				<optgroup label="Otros calendarios">
	        					<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1"); ?>
	        					<?php while ($row = mysqli_fetch_assoc($result)): ?>
	        					<option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
	        					<?php endwhile; ?>
	        					<?php mysqli_free_result($result); ?>
	        				</optgroup>
	        				<?php endif; ?>
	        			</select>
	        		</div>
	        		
	        		<div id="opciones_actividades" class="row">
	        			
	        			<div class="col-sm-6">
	        		
			        		<div class="form-group">
			        			<label for="cmp_departamento">Departamento que lo organiza</label>
			        			<select class="form-control" id="cmp_departamento" name="cmp_departamento">
			        				<option value=""></option>
			        				<?php $result = mysqli_query($db_con, "SELECT DISTINCT departamento FROM departamentos ORDER BY departamento ASC"); ?>
			        				<?php while ($row = mysqli_fetch_assoc($result)): ?>
			        				<option value="<?php echo $row['departamento']; ?>"><?php echo $row['departamento']; ?></option>
			        				<?php endwhile; ?>
			        			</select>
			        		</div>
			        		
			        		<div class="form-group">
			        			<label for="cmp_profesores">Profesores que asistir�n a la actividad</label>
			        			<select class="form-control" id="cmp_profesores" name="cmp_profesores" size="21" multiple>
			        				<?php $result = mysqli_query($db_con, "SELECT DISTINCT nombre FROM departamentos ORDER BY departamento ASC, nombre ASC"); ?>
			        				<?php while ($row = mysqli_fetch_assoc($result)): ?>
			        				<option value="<?php echo $row['nombre']; ?>"><?php echo $row['nombre']; ?></option>
			        				<?php endwhile; ?>
			        			</select>
			        		</div>
			        		
			        	</div><!-- /.col-sm-6 -->
			        	
			        	<div class="col-sm-6">
			        		
			        		<div class="form-group">
			        			<label for="">Unidades que asistir�n a la actividad</label>
				        		<?php $result = mysqli_query($db_con, "SELECT DISTINCT curso FROM alma ORDER BY curso ASC"); ?>
				        		<?php while($row = mysqli_fetch_assoc($result)): ?>
				        			<?php echo '<p class="text-info">'.$row['curso'].'</p>'; ?>
				        			<?php $result1 = mysqli_query($db_con, "SELECT DISTINCT unidad FROM alma WHERE curso = '".$row['curso']."' ORDER BY unidad ASC"); ?>
				        			<?php while($row1 = mysqli_fetch_array($result1)): ?>
				        		                 
				        			<div class="checkbox-inline"> 
				        				<label>
				        					<input name="<?php echo "grt".$row1['unidad']; ?>" type="checkbox" id="A" value="<?php echo $row1['unidad']; ?>">
				        		            <?php echo $row1['unidad']; ?>
				        		        </label>
				        		    </div>
				        		    
				        		<?php endwhile; ?>         
				        		<?php endwhile ?>
				        	</div>
			        		
			        	</div><!-- /.col-sm-6 -->
			        </div><!-- /.row -->
	        				        		
	        	</fieldset>
	        </form>
	        
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
	        <button type="button" class="btn btn-primary">Crear</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- FIN MODAL NUEVO EVENTO -->

<?php include("../pie.php"); ?>

	<script>
		$(function() {
			// MOSTRAR/OCULTAR CALENDARIOS
			<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE profesor='".$_SESSION['profi']."' AND espublico=0"); ?>
			<?php if (mysqli_num_rows($result)): ?>
			<?php while ($row = mysqli_fetch_assoc($result)): ?>
			$("#toggle_calendario_<?php echo $row['id']; ?>").click(function() {
			  $('.hidden_calendario_<?php echo $row['id']; ?>').toggleClass("visible");
			});
			<?php endwhile; ?>
			<?php endif; ?>
			
			<?php $result = mysqli_query($db_con, "SELECT id, nombre, color FROM calendario_categorias WHERE espublico=1"); ?>
			<?php if (mysqli_num_rows($result)): ?>
			<?php while ($row = mysqli_fetch_assoc($result)): ?>
			$("#toggle_calendario_<?php echo $row['id']; ?>").click(function() {
			  $('.hidden_calendario_<?php echo $row['id']; ?>').toggleClass("visible");
			});
			<?php endwhile; ?>
			<?php endif; ?>
			
			$("#toggle_calendario_festivo").click(function() {
			  $('.hidden_calendario_festivo').toggleClass("visible");
			});
			
			
			// MODAL
			$('#modalNuevoEvento').modal({
			  show: false,
			  keyboard: false,
			  backdrop: true
			})
			
			$('#opciones_actividades').hide();
			$('#cmp_calendario').change(function() {
			    if ($('#cmp_calendario').val() == 2) {
			        $('#opciones_actividades').show();
			    }
			    else {
			        $('#opciones_actividades').hide();
			    }
			});
			
			$('#modalNuevoEvento').on('hidden.bs.modal', function () {
				$('#formNuevoEvento')[0].reset();
				$('#opciones_actividades').hide();
			})
			
			// DATETIMEPICKERS
			$('#datetimepicker1').datetimepicker({
				language: 'es',
				pickTime: false
			})
			
			$('#datetimepicker2').datetimepicker({
				language: 'es',
				pickTime: true,
				pickDate: false
			})
			
			$('#datetimepicker3').datetimepicker({
				language: 'es',
				pickTime: false
			})
			
			$('#datetimepicker4').datetimepicker({
				language: 'es',
				pickTime: true,
				pickDate: false
			})
		});
	</script>
</body>
</html>
