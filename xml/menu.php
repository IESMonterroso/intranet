<ul class="nav nav-pills nav-stacked">
	<li class="nav-header">Configuraci�n</li> 
	<li><a href="../config/config.php">Configuraci�n general y m�dulos</a></li>
	<li><a href="../reservas/gestion_tipo.php">Gesti�n de reservas</a></li> 
	
	<li class="nav-header">A principio de curso...</li>
	<li><a href="jefe/index2.php">Importar Alumnos</a></li>
	<li><a href="jefe/asignaturas.php">Importar Asignaturas</a></li>
	<li><a href="jefe/index_xml.php">Importar datos del Centro</a></li>
	<li><a href="jefe/index_departamentos.php">Importar Departamentos</a></li>
	<li><a href="jefe/index_profesores.php">Importar Profesores</a></li>
	<li><a href="jefe/index_pas.php">Importar Personal no docente</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/horario.php">Importar/Preparar Horarios </a></li>
	<li><a href="jefe/index_limpia.php">Limpiar Horarios</a></li>
	<?php endif; ?>
	<li><a href="jefe/index_festivos.php">Importar d�as festivos</a></li>
	<li><a href="jefe/index_jornada.php">Importar Jornada</a></li> 
	<li><a href="jefe/rof/index.php">Modificar ROF</a></li>
	
	<li class="nav-header">Actualizaci�n</li>
	<li><a href="jefe/index.php">Actualizar Alumnos</a></li>
	<li><a href="jefe/asignaturas.php?actualiza=1">Actualizar Asignaturas</a></li>
	<li><a href="jefe/horario.php">Actualizar Horarios</a></li>
	<li><a href="jefe/index_profesores.php">Actualizar Profesores</a></li>
	<li><a href="jefe/index_pas.php">Actualizar Personal no docente</a></li>
	<li><a href="jefe/index_departamentos2.php">Actualizar Departamentos</a></li>

	<li class="nav-header">Personal del Centro</li> 
	<li><a href="../config/cargos.php">Perfiles de los profesores</a></li>
	<li><a href="jefe/gest_dep.php">Gesti�n de los Departamentos</a></li>
	<li><a href="jefe/reset_password.php">Restablecer contrase�as</a></li>
	<?php if(isset($config['mod_horarios']) && $config['mod_horarios']): ?>
	<li><a href="jefe/horarios/index.php">Crear / Modificar horarios</a></li>
	<?php endif; ?>
	<li><a href="jefe/index_hor.php">Sustituci�n de profesores</a></li>
	<li><a href="jefe/index_fotos_profes.php">Subir fotos de profesores</a></li>
	<li><a href="jefe/informes/accesos.php">Informe de accesos</a></li>

	<li class="nav-header">Alumnos</li>
	<li><a href="../admin/cursos/listatotal.php">Listas de todos los Grupos</a></li>
	<li><a href="jefe/form_carnet.php">Carnet de los alumnos</a></li>
	<li><a href="jefe/index_fotos.php">Subir fotos de alumnos</a></li>
	</a></li>
	<li><a href="../admin/libros/indextextos.php">Libros de Texto Gratuitos
	</a></li>
	<?php if(isset($config['mod_matriculacion']) && $config['mod_matriculacion']): ?>
	<li><a href="../admin/matriculas/index.php">Matriculaci�n de alumnos
	</a></li>
	<li><a href="./jefe/index_mayores.php">Alumnos mayores de 18 a�os
	</a></li>	
	<?php endif; ?>
	<li><a href="jefe/informes/accesos_alumnos.php">Informe de accesos</a></li>

	<li class="nav-header">Notas de evaluaci�n</li>
	<li>
	<a href="jefe/index_notas.php">Importar Calificaciones</a></li>
	
	<li class="nav-header">Bases de datos</li>
	<li><a href="jefe/copia_db/index.php">Copias de seguridad</a></li>
	</a></li>
</ul>
