	<div class="container hidden-print">
		
		<?php if (strstr($_SESSION['cargo'],'1') == TRUE || strstr($_SESSION['cargo'],'8') == TRUE): ?>
		<form method="post" action="">
			<div class="pull-right">
			  <?php $result = mysql_query("SELECT DISTINCT unidad, tutor FROM FTUTORES ORDER BY unidad ASC"); ?>
			  <?php if(mysql_num_rows($result)): ?>
			  <select class="form-control input-sm" id="tutor" name="tutor" onchange="submit()">
			  	<?php while($row = mysql_fetch_array($result)): ?>
			  	<option value="<?php echo $row['tutor'].' ==> '.$row['unidad']; ?>" <?php echo ($_SESSION['mod_tutoria']['tutor'].' ==> '.$_SESSION['mod_tutoria']['unidad'] == $row['tutor'].' ==> '.$row['unidad']) ? 'selected' : ''; ?>><?php echo $row['unidad'].' - '.$row['tutor']; ?></option>
			  	<?php endwhile; ?>
			  </select>
			  <?php else: ?>
			  <select class="form-control" id="tutor" name="tutor" disabled>
			  	<option value=""></option> 
			  </select>
			  <?php endif; ?>
			  <?php mysql_free_result($result); ?>
			</div>
		</form>
		<?php endif; ?>
		
		<ul class="nav nav-tabs">
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Resumen global</a></li>
			<li<?php echo (strstr($_SERVER['REQUEST_URI'],'intervencion.php')==TRUE) ? ' class="active"' : ''; ?>><a href="intervencion.php">Intervenciones tutoriales</a></li>
			<?php if (isset($mod_sms) && $mod_sms): ?>
			<li><a href="../../sms/index.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad'];?>">Enviar SMS</a></li>
			<?php endif; ?>
			<li class="dropdown<?php echo (strstr($_SERVER['REQUEST_URI'],'consulta_')==TRUE) ? ' active' : ''; ?>">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      Consultas <span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
		    	<li><a href="../datos/datos.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad'] ?>">Datos de alumnos/as</a></li>
		      <li><a href="../cursos/ccursos.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>&submit1=1" target="_blank">Listado de alumnos/as</a></li>
		      <li><a href="consulta_fotografias.php">Fotograf�as de alumnos/as</a></li>
		      <li><a href="consulta_mesas.php">Asignaci�n de mesas</a></li>
		      <li><a href="consulta_credenciales.php">Credenciales de alumnos</a></li>
		      <li><a href="consulta_absentimo.php">Alumnos absentistas</a></li>
		      <li class="divider"></li>
		      <li><a href="../../upload/index.php?index=publico&direction=0&order=&directory=programaciones/Orientacion" target="_blank">Material de orientaci�n</a></li>
		    </ul>
		  </li>
		  <li class="dropdown">
		    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
		      Informes <span class="caret"></span>
		    </a>
		    <ul class="dropdown-menu" role="menu">
		    	<li><a href="../informes/cinforme.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>">Informe de un alumno/a</a></li>
		      <li><a href="../infotutoria/index.php">Informes de tutor�a</a></li>
		      <li><a href="../tareas/index.php">Informes de tareas</a></li>
		      <li class="divider"></li>
		      <li><a href="memoria.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>">Memoria de tutor�a</a></li>
		    </ul>
		  </li>
		  <?php if(substr($_SESSION['mod_tutoria']['unidad'],1,1) == "E"): ?>
		  <li><a href="../libros/libros.php?unidad=<?php echo $_SESSION['mod_tutoria']['unidad']; ?>&tutor=1">Libros de Texto</a></li>
		  <?php endif; ?>
		</ul>
		
	</div>
