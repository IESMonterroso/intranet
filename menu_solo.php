<?
include ("funciones.php");
$idea = $_SESSION ['ide'];
$activo1="";
$activo2="";
$activo3="";
if (strstr($_SERVER['REQUEST_URI'],'index.php')==TRUE) {$activo1 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'mensajes')==TRUE){ $activo2 = ' class="active" ';}
if (strstr($_SERVER['REQUEST_URI'],'upload')==TRUE){ $activo3 = ' class="active" ';}
?>

	<nav class="navbar navbar-default navbar-fixed-top hidden-print" role="navigation">
		<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
		    <span class="sr-only">Cambiar navegación</span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		    <span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="http://<?php echo $dominio; ?>/intranet/"><?php echo $nombre_del_centro; ?></a>
		</div>
		
		 <!-- Collect the nav links, forms, and other content for toggling -->
		 <div class="collapse navbar-collapse" id="navbar">
		 	<ul class="nav navbar-nav">
		 		<li <? echo $activo1;?>><a href="http://<? echo $dominio;?>/intranet/index.php">Inicio</a></li>
		 		<li<? echo $activo3;?>><a href="http://<? echo $dominio;	?>/intranet/upload/">Documentos</a></li>
		 		<li><a href="https://www.juntadeandalucia.es/educacion/portalseneca/web/seneca/inicio" target="_blank">Séneca</a></li>
		 	</ul>
		 	
		 	<div class="navbar-right">
			 	<ul class="nav navbar-nav">
				 	<?php
				 	// Comprobamos mensajes sin leer
				 	$result_mensajes = mysql_query("SELECT ahora, asunto, texto, profesor, id_profe, origen FROM mens_profes, mens_texto WHERE mens_texto.id = mens_profes.id_texto AND profesor='".$_SESSION['profi']."' AND recibidoprofe=0");
				 	$mensajes_sin_leer = mysql_num_rows($result_mensajes);
				 	mysql_free_result($result_mensajes);
				 	?>
			 		<li class="dropdown">
			 			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			 				<span class="fa fa-envelope <?php echo ($mensajes_sin_leer > 0) ? 'text-warning' : ''; ?>"></span> <b class="caret"></b>
			 			</a>
			 			<ul class="dropdown-menu dropdown-messages">
			 				<?php $result_mensajes = mysql_query("SELECT ahora, asunto, id, recibidoprofe, texto, origen from mens_profes, mens_texto where mens_texto.id = mens_profes.id_texto and profesor = '".$_SESSION['profi']."' ORDER BY ahora DESC LIMIT 0, 5"); ?>
			 				<?php if(mysql_num_rows($result_mensajes)): ?>
			 				<?php while ($row = mysql_fetch_array($result_mensajes)): ?>
			 				<?php $fecha = date_create($row['ahora']); ?>
			 				<li>
			 					<a href="http://<?php echo $dominio; ?>/intranet/admin/mensajes/mensaje.php?id=<?php echo $row['id']; ?>">
			 						<div>
			 							<span class="pull-right text-muted"><em><?php echo date_format($fecha, 'd M') ?></em></span>
			 							<strong><?php echo $row['origen']; ?></strong>
			 						</div>
			 						<?php echo $row['asunto']; ?>
			 					</a>
			 				</li>
			 				<li class="divider"></li>
			 				<?php endwhile; ?>
			 				<?php mysql_free_result($result_mensajes); ?>
			 				<?php endif; ?>
			 				<li><a class="text-center" href="http://<?php echo $dominio; ?>/intranet/admin/mensajes/"><strong>Ver todos los mensajes <span class="fa fa-angle-right"></span></strong></a></li>
			 			</ul>
			 		</li>
			 		
			 		<li class="dropdown">
			 			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			 				<span class="fa fa-user fa-fw"></span>
			 				<? echo $idea; ?> <b class="caret"></b>
			 			</a>
			 			<ul class="dropdown-menu">
			 				<li><a href="http://<? echo $dominio; ?>/intranet/clave.php"><i class="fa fa-key fa-fw"></i> Cambiar contraseña</a></li>
			 				<li><a href="http://<? echo $dominio; ?>/intranet/admin/fotos/fotos_profes.php"><i class="fa fa-camera fa-fw"></i> Cambiar fotografía</a></li>
			 				<li><a href="http://<? echo $dominio;?>/intranet/salir.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a></li>
			 			</ul>
			 		</li>
			   </ul>
			   <p class="navbar-text" style="margin-top: 7px; margin-bottom: 7px; color: #ffffff;">
			     <small><i class="fa fa-clock-o fa-lg"></i> Última conexión:<br class="hidden-xs">
			     <?php
			     $time = mysql_query("select fecha from reg_intranet where profesor = '".$_SESSION['profi']."' order by fecha desc limit 2");
			     
			     while($last = mysql_fetch_array($time)) {
			     	$num+=1;
			     	
			     	if($num == 2) {
			     		$t_r0 = explode(" ",$last[0]);
			     		$dia_hora = cambia_fecha($t_r0[0]);
			     		echo "$dia_hora &nbsp; $t_r0[1]";
			     	}
			     }
			     ?>
			     </small>
			   </p>
			  </div>
		   
		  </div><!-- /.navbar-collapse -->
		 </div><!-- /.container-fluid -->
	</nav>
