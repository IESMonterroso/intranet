	<div class="container">
		
		<ul class="nav nav-tabs">
			<li <?php echo (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) ? ' class="active"' : ''; ?>><a href="index.php">Intervenci�n sobre Alumno</a></li>
			<li <?php echo (strstr($_SERVER['REQUEST_URI'],'profesores.php')==TRUE) ? ' class="active"' : ''; ?>><a href="profesores.php">Intervenci�n sobre Profesor</a></li>
		</ul>
		
	</div>
	