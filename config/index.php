<?php 
setlocale(LC_CTYPE, 'es_ES');
define('CONFIG_FILE', '../config.php');
if (file_exists(CONFIG_FILE) && !(isset($_POST['instalar']))) header('Location:'.'../index.php');

include('version.php');


// TITULO DE LA PAGINA
$page_header = 'Instalaci�n de la Intranet';


// FUNCIONES Y VARIABLES NECESARIAS PARA LA INSTALACI�N
$config_existe = 0;
$doc_dir = substr(__DIR__, 0, -6).'varios/publico/';

$provincias = array('Almer�a', 'C�diz', 'C�rdoba', 'Granada', 'Huelva', 'Ja�n', 'M�laga', 'Sevilla');

function crear_archivo($filename)
{
	if($file = fopen($filename, 'w+'))
	{
		fclose($file);
		unlink($filename);
		return 1;
	}
	else
	{
		return 0;
	}
}

function crear_directorio($dirname)
{
	mkdir($dirname);
	if(! file_exists($dirname))
	{
		return 0;
	}
	else
	{
		rmdir($dirname);
		return 1;
	}
}

function generador_password($long)
{
	$alfabeto = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array();
    $long_alfabeto = strlen($alfabeto) - 1;
    for ($i = 0; $i < $long; $i++) {
        $p = rand(0, $long_alfabeto);
        $pass[] = $alfabeto[$p];
    }
    return implode($pass);
}

function limpiar_string($string)
{
	return trim(htmlspecialchars($string, ENT_QUOTES,'ISO-8859-1'));
}


// PROCESAMOS EL FORMULARIO
if (isset($_POST['instalar']))
{
	
	// LIMPIAMOS CARACTERES
	$dominio_centro		= limpiar_string($_POST['dominio_centro']);
	(isset($_POST['forzar_ssl'])) ? $forzar_ssl = 1 : $forzar_ssl = 0;
	
	$nombre_centro		= limpiar_string($_POST['nombre_centro']);
	$codigo_centro		= limpiar_string($_POST['codigo_centro']);
	$email_centro		= limpiar_string($_POST['email_centro']);
	$direccion_centro	= limpiar_string($_POST['direccion_centro']);
	$codpostal_centro	= limpiar_string($_POST['codpostal_centro']);
	$localidad_centro	= limpiar_string($_POST['localidad_centro']);
	$provincia_centro	= limpiar_string($_POST['provincia_centro']);
	$telefono_centro	= limpiar_string($_POST['telefono_centro']);
	$fax_centro			= limpiar_string($_POST['fax_centro']);
	
	$direccion_director			= limpiar_string($_POST['direccion_director']);
	$direccion_jefe_estudios	= limpiar_string($_POST['direccion_jefe_estudios']);
	$direccion_secretaria		= limpiar_string($_POST['direccion_secretaria']);
	
	$db_host	= limpiar_string($_POST['db_host']);
	$db_name	= limpiar_string($_POST['db_name']);
	$db_user	= limpiar_string($_POST['db_user']);
	$db_pass	= limpiar_string($_POST['db_pass']);
	
	$curso_escolar	= limpiar_string($_POST['curso_escolar']);
	$fecha_inicio	= limpiar_string($_POST['fecha_inicio']);
	$fecha_final	= limpiar_string($_POST['fecha_final']);
	
	(isset($_POST['mod_biblioteca'])) ? $modulo_biblioteca = 1 : $modulo_biblioteca = 0;
	$modulo_biblioteca_web	= limpiar_string($_POST['mod_biblioteca_web']);
	
	(isset($_POST['mod_bilingue'])) ? $modulo_bilingue = 1 : $modulo_bilingue = 0;
	
	(isset($_POST['mod_centrotic'])) ? $modulo_centrotic = 1 : $modulo_centrotic = 0;
	
	(isset($_POST['mod_documentos'])) ? $modulo_documentos = 1 : $modulo_documentos = 0;
	$modulo_documentos_dir	= limpiar_string($_POST['mod_documentos_dir']);
	(isset($_POST['mod_documentos_biblioteca'])) ? $mod_documentos_biblioteca = 1 : $mod_documentos_biblioteca = 0;
	(isset($_POST['mod_documentos_recursos'])) ? $mod_documentos_recursos = 1 : $mod_documentos_recursos = 0;
	(isset($_POST['mod_documentos_departamentos'])) ? $mod_documentos_departamentos = 1 : $mod_documentos_departamentos = 0;
	
	(isset($_POST['mod_sms'])) ? $modulo_sms = 1 : $modulo_sms = 0;
	$modulo_sms_id		= limpiar_string($_POST['mod_sms_id']);
	$modulo_sms_user	= limpiar_string($_POST['mod_sms_user']);
	$modulo_sms_pass	= limpiar_string($_POST['mod_sms_pass']);
	
	(isset($_POST['mod_asistencia'])) ? $modulo_asistencia = 1 : $modulo_asistencia = 0;
	
	(isset($_POST['mod_horarios'])) ? $modulo_horarios = 1 : $modulo_horarios = 0;
	
	(isset($_POST['mod_matriculacion'])) ? $modulo_matriculacion = 1 : $modulo_matriculacion = 0;
	(isset($_POST['mod_transporte_escolar'])) ? $modulo_transporte_escolar = 1 : $modulo_transporte_escolar = 0;
	
	
	// CREACI�N DEL ARCHIVO DE CONFIGURACI�N
	if($file = fopen(CONFIG_FILE, 'w+'))
	{
		fwrite($file, "<?php \r\n");
		
		fwrite($file, "\r\n// CONFIGURACI�N INTRANET\r\n");
		fwrite($file, "\$config['dominio']\t\t\t= '$dominio_centro';\r\n");
		fwrite($file, "\$config['forzar_ssl']\t\t= $forzar_ssl;\r\n");
		fwrite($file, "\$config['mantenimiento']\t= 0;\r\n");
		
		fwrite($file, "\r\n// INFORMACI�N DEL CENTRO\r\n");
		fwrite($file, "\$config['centro_denominacion']\t= '$nombre_centro';\r\n");
		fwrite($file, "\$config['centro_codigo']\t\t= '$codigo_centro';\r\n");
		fwrite($file, "\$config['centro_email']\t\t\t= '$email_centro';\r\n");
		fwrite($file, "\$config['centro_direccion']\t\t= '$direccion_centro';\r\n");
		fwrite($file, "\$config['centro_codpostal']\t\t= '$codpostal_centro';\r\n");
		fwrite($file, "\$config['centro_localidad']\t\t= '$localidad_centro';\r\n");
		fwrite($file, "\$config['centro_provincia']\t\t= '$provincia_centro';\r\n");
		fwrite($file, "\$config['centro_telefono']\t\t= '$telefono_centro';\r\n");
		fwrite($file, "\$config['centro_fax']\t\t\t= '$fax_centro';\r\n");
		
		fwrite($file, "\r\n// EQUIPO DIRECTIVO\r\n");
		fwrite($file, "\$config['directivo_direccion']\t= '$direccion_director';\r\n");
		fwrite($file, "\$config['directivo_jefatura']\t= '$direccion_jefe_estudios';\r\n");
		fwrite($file, "\$config['directivo_secretaria']\t= '$direccion_secretaria';\r\n");
		
		fwrite($file, "\r\n// BASE DE DATOS\r\n");
		fwrite($file, "\$config['db_host']\t= '$db_host';\r\n");
		fwrite($file, "\$config['db_name']\t= '$db_name';\r\n");
		fwrite($file, "\$config['db_user']\t= '$db_user';\r\n");
		fwrite($file, "\$config['db_pass']\t= '$db_pass';\r\n");
		
		fwrite($file, "\r\n// CURSO ESCOLAR\r\n");
		fwrite($file, "\$config['curso_actual']\t= '$curso_escolar';\r\n");
		fwrite($file, "\$config['curso_inicio']\t= '$fecha_inicio';\r\n");
		fwrite($file, "\$config['curso_fin']\t= '$fecha_final';\r\n");
		
		fwrite($file, "\r\n// M�DULO: BIBLIOTECA\r\n");
		fwrite($file, "\$config['mod_biblioteca']\t\t= $modulo_biblioteca;\r\n");
		fwrite($file, "\$config['mod_biblioteca_web']\t= '$modulo_biblioteca_web';\r\n");
		
		fwrite($file, "\r\n// M�DULO: BILING�E\r\n");
		fwrite($file, "\$config['mod_bilingue']\t\t\t= $modulo_bilingue;\r\n");
		
		fwrite($file, "\r\n// M�DULO: CENTRO TIC\r\n");
		fwrite($file, "\$config['mod_centrotic']\t\t= $modulo_centrotic;\r\n");
		
		fwrite($file, "\r\n// M�DULO: DOCUMENTOS\r\n");
		fwrite($file, "\$config['mod_documentos']\t\t= $modulo_documentos;\r\n");
		fwrite($file, "\$config['mod_documentos_dir']\t= '$modulo_documentos_dir';\r\n");
		fwrite($file, "\$config['mod_documentos_biblioteca']\t= '$mod_documentos_biblioteca';\r\n");
		fwrite($file, "\$config['mod_documentos_recursos']\t= '$mod_documentos_recursos';\r\n");
		fwrite($file, "\$config['mod_documentos_departamentos']\t= '$mod_documentos_departamentos';\r\n");
		
		fwrite($file, "\r\n// M�DULO: SMS\r\n");
		fwrite($file, "\$config['mod_sms']\t\t\t\t= $modulo_sms;\r\n");
		fwrite($file, "\$config['mod_sms_id']\t\t\t= '$modulo_sms_id';\r\n");
		fwrite($file, "\$config['mod_sms_user']\t\t\t= '$modulo_sms_user';\r\n");
		fwrite($file, "\$config['mod_sms_pass']\t\t\t= '$modulo_sms_pass';\r\n");
		
		fwrite($file, "\r\n// M�DULO: FALTAS DE ASISTENCIA\r\n");
		fwrite($file, "\$config['mod_asistencia']\t\t= $modulo_asistencia;\r\n");
		
		fwrite($file, "\r\n// M�DULO: HORARIOS\r\n");
		fwrite($file, "\$config['mod_horarios']\t\t\t= $modulo_horarios;\r\n");
		
		fwrite($file, "\r\n// M�DULO: MATRICULACI�N\r\n");
		fwrite($file, "\$config['mod_matriculacion']\t\t= $modulo_matriculacion;\r\n");
		fwrite($file, "\$config['mod_transporte_escolar']\t= $modulo_transporte_escolar;\r\n");
		
		fwrite($file, "\r\n\r\n// Fin del archivo de configuraci�n");
		
		$config_existe = 1;
		fclose($file);
	}
	
	// FORZAR USO DE HTTPS
	if($forzar_ssl)
	{
		if($file = fopen('../.htaccess', 'w+'))
		{
			fwrite($file, "Options +FollowSymLinks\r\n");
			fwrite($file, "RewriteEngine On\r\n");
			fwrite($file, "RewriteCond %{SERVER_PORT} 80\r\n");
			fwrite($file, "RewriteCond %{REQUEST_URI} intranet\r\n");
			fwrite($file, "RewriteRule ^(.*)$ https://".$dominio_centro."/intranet/$1 [R,L]\r\n");
		}
		fclose($fp);
	}
	
	
	if ($config_existe)
	{
		include(CONFIG_FILE);
		
		$db_con = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass']);
		
		if (mysqli_connect_errno())
		{
			$esError = 1;
			$details_error = mysqli_connect_error();
			
			unlink(CONFIG_FILE);
		}
		else
		{
			$pass_admin = generador_password(9);
			$pass_sha1	= sha1($pass_admin);
			
			// COMPROBAMOS SI SE TRATA DE UNA ACTUALIZACI�N DE LA APLICACI�N
			if(isset($_GET['update']) && $_GET['update']) {
				mysqli_select_db($db_con, $db_name);
				
				mysqli_query($db_con, "UPDATE c_profes SET pass='$pass_sha1', PROFESOR='Administrador', dni='$pass_admin' WHERE idea='admin' LIMIT 1");
				mysqli_query($db_con, "UPDATE departamentos SET NOMBRE='Administrador', DNI='$pass_admin', DEPARTAMENTO='Admin', CARGO='1' WHERE idea='admin' LIMIT 1");
				mysqli_query($db_con, "UPDATE calendario_categorias SET nombre='Administrador', fecha='".date('Y-m-d')."' WHERE nombre='admin' AND profesor='admin' LIMIT 1");
				mysqli_query($db_con, "UPDATE noticias SET contact='Administrador' WHERE contact='admin'");
				mysqli_query($db_con, "UPDATE mens_texto SET origen='Administrador' WHERE origen='admin'");
				mysqli_query($db_con, "UPDATE mens_profes SET profesor='Administrador' WHERE profesor='admin'");
				mysqli_query($db_con, "UPDATE reg_intranet SET profesor='Administrador' WHERE profesor='admin'");
			}
			else {
				// CREACI�N DE LA BASE DE DATOS
				mysqli_query($db_con, "CREATE DATABASE IF NOT EXISTS `$db_name` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci");
				mysqli_select_db($db_con, $db_name);
				
				// IMPORTACI�N DE TABLAS
				$sql = file_get_contents('tablas.sql');
				mysqli_multi_query($db_con, $sql);
				while (mysqli_next_result($db_con));
				
				// A�ADIENDO USUARIO ADMINISTRADOR
				mysqli_query($db_con, "INSERT INTO `c_profes` (`id`, `pass`, `PROFESOR`, `dni`, `idea`, `correo`, `estado`) VALUES 
				(1, '$pass_sha1', 'Administrador', '$pass_admin', 'admin', NULL, 0)");
				
				mysqli_query($db_con, "INSERT INTO `departamentos` (`NOMBRE`, `DNI`, `DEPARTAMENTO`, `CARGO`, `idea`) VALUES 
				('Administrador', '$pass_admin', 'Admin', '01', 'admin')");
				
				mysqli_query($db_con, "INSERT INTO `calendario_categorias` (`id`, `nombre`, `fecha`, `profesor`, `color`, `espublico`) VALUES
				(1, 'Calendario del centro', '".date('Y-m-d')."', 'admin', '#f29b12', 1),
				(2, 'Actividades extraescolares', '".date('Y-m-d')."', 'admin', '#18bc9c', 1),
				(3, 'Administrador', '".date('Y-m-d')."', 'admin', '#3498db', 0)");
			}
						
			mysqli_close($db_con);
			
			$esError = 0;
		}
		
	}
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="iso-8859-1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">
	<meta name="robots" content="noindex, nofollow">
	
	<title><?php echo $page_header; ?></title>
	
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/animate.css" rel="stylesheet">
	<link href="../css/otros.css" rel="stylesheet">
	<link href="../css/font-awesome.min.css" rel="stylesheet">
	
	<style type="text/css">
	.dl-horizontal dt
	{
		width: 230px;
	}
	
	.dl-horizontal dd
	{
		margin-left: 250px;
	}
	</style>
</head>

<body style="padding-top: 0;">

	<!--[if lte IE 9 ]>
	<div id="old-ie" class="modal">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-body">
	      	<br>
	        <p class="lead text-center">Est�s utilizando una versi�n de Internet Explorer demasiado antigua. <br>Actualiza tu navegador o c�mbiate a <a href="http://www.google.com/chrome/">Chrome</a> o <a href="https://www.mozilla.org/es-ES/firefox/new/">Firefox</a>.</p>
	        <br>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<![endif]-->
	
	<div class="container">
		
		<div class="page-header">
			<h1 class="text-center">
				<span class="fa fa-dashboard fa-2x"></span><br>
				<?php echo $page_header; ?>
			</h1>
		</div>
		
		<form id="form-instalacion" class="form-horizontal" data-toggle="validator" action="" method="post" role="form" autocomplete="off">
		
		<div class="row">
			
			<div class="col-sm-offset-2 col-sm-8">
				
				<div id="tabs-instalacion" class="tab-content">
					<?php if(! $config_existe): ?>
					<!-- TERMINOS Y CONDICIONES DE USO -->
				    <div role="tabpanel" class="tab-pane active" id="terminos">
				    
				    	<div class="well">
				    		<h3>T�rminos y condiciones de uso</h3>
				    		<br>
				    		<object type="text/html" data="../LICENSE.md" style="width: 100%; min-height: 300px; border: 1px solid #dedede; background-color: #fff;"></object>
				    		
				    		<div class="checkbox">
				    			<label for="terms-accept">
				    				<input type="checkbox" name="terms-accept" id="terms-accept" value="YES">
				    				Acepto los t�rminos y condiciones de uso de esta aplicaci�n.
				    			</label>
				    		</div>
				    		
				    		<br>
				    		
				    		<div class="pull-right">
				    			<a href="#php-config" aria-controls="php-config" data-toggle="tab" class="btn btn-primary disabled">Continuar <span class="fa fa-chevron-right fa-fw"></span></a>
				    		</div>
				    		<div class="clearfix"></div>
				    	</div>
				    
				    </div>
				    
				    <!-- COMPROBACI�N CONFIGURACI�N PHP -->
				    <div role="tabpanel" class="tab-pane" id="php-config">
				    
				    	<div class="well">
				    		<h3>Configuraci�n de PHP</h3>
				    		<br>
				    		
				    		<p class="text-center text-info">Para una mejor experiencia en el uso de la Intranet, es recomendable que las variables de configuraci�n de PHP est�n marcadas en verde. En el caso de que aparezca marcada en rojo, modifique la configuraci�n en <em>php.ini</em> o p�ngase en contacto con su proveedor de alojamiento Web y vuelva a iniciar esta instalaci�n.</p>
				    		<br>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Versi�n de PHP</dt>
				    		  <dd><?php echo (phpversion() < '5.3.0') ? '<span class="text-danger">Versi�n actual: '.phpversion().'. Actualice a la versi�n 5.3.0 o superior</span>' : '<span class="text-success">'.phpversion().'</span>'; ?></dd>
				    		</dl>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Display errors</dt>
				    		  <dd><?php echo (ini_get('display_errors') == 0) ? '<span class="text-success">Deshabilitado</span>' : '<span class="text-danger">Valor actual: Habilitado. Por seguridad, deshabilite la variable <em>display_errors</em></span>'; ?></dd>
				    		</dl>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Register globals</dt>
				    		  <dd><?php echo (ini_get('register_globals') == 0) ? '<span class="text-success">Deshabilitado</span>' : '<span class="text-danger">Valor actual: Habilitado. Por seguridad, deshabilite la variable <em>register_globals</em></span>'; ?></dd>
				    		</dl>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Upload max filesize</dt>
				    		  <dd><?php echo (substr(ini_get('upload_max_filesize'),0,-1) < '16') ? '<span class="text-danger">Valor actual: '.ini_get('upload_max_filesize').'B. Aumente el tama�o m�ximo de archivos a 16 MB o superior.</span>' : '<span class="text-success">'.ini_get('upload_max_filesize').'B</span>'; ?></dd>
				    		</dl>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Memory limit</dt>
				    		  <dd><?php echo (substr(ini_get('memory_limit'),0,-1) < '32') ? '<span class="text-danger">Valor actual: '.ini_get('memory_limit').'B. Aumente el tama�o de memoria a 32 MB o superior.</span>' : '<span class="text-success">'.ini_get('memory_limit').'B</span>'; ?></dd>
				    		</dl>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Escritura/lectura de archivos</dt>
				    		  <dd><?php echo (crear_archivo('install_tmp.txt')) ? '<span class="text-success">Habilitado</span>' : '<span class="text-danger">Valor actual: Deshabilitado. Debe crear el archivo <em>configuracion.php</em> en el directorio principal de la Intranet con permisos de escritura y lectura.</span>'; ?></dd>
				    		</dl>
				    		
				    		<dl class="dl-horizontal">
				    		  <dt>Escritura/lectura de directorios</dt>
				    		  <dd><?php echo (crear_directorio('install_tmp')) ? '<span class="text-success">Habilitado</span>' : '<span class="text-danger">Valor actual: Deshabilitado. Debe dar permisos de escritura y lectura a todos los directorios de la Intranet.</span>'; ?></dd>
				    		</dl>
				    		
				    		<br>
				    		
				    		<div class="pull-left">
				    			<a href="#terminos" aria-controls="terminos" data-toggle="tab" class="btn btn-default"><span class="fa fa-chevron-left fa-fw"></span> Anterior</a>
				    		</div>
				    		<div class="pull-right">
				    			<a href="#informacion" aria-controls="informacion" data-toggle="tab" class="btn btn-primary disabled">Continuar <span class="fa fa-chevron-right fa-fw"></span></a>
				    		</div>
				    		<div class="clearfix"></div>
				    	</div>
				    
				    </div>
				    
				    
				    <!-- INFORMACI�N CENTRO EDUCATIVO -->
				    <div role="tabpanel" class="tab-pane" id="informacion">
				    	
				    	<div class="well">
				    		<h3>Informaci�n de su centro educativo</h3>
				    		<br>
				    		
				    		<?php $tam_label = 3; ?>
				    		<?php $tam_control = 8; ?>
				    			
				    		  <input type="hidden" name="dominio_centro" value="<?php echo ($_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) ? $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'] : $_SERVER['SERVER_NAME']; ?>">
				    		  
				    		  <?php $ssl = ($_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) ? 'https://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'] : 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>
				    		  <?php if(file_get_contents($ssl, NULL, NULL, 0, 5)): ?>
				    		  <input type="hidden" name="forzar_ssl" value="1">
				    		  <?php endif; ?>
				    		  
				    		  <div class="form-group">
				    		    <label for="nombre_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Denominaci�n</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="nombre_centro" name="nombre_centro" placeholder="I.E.S. Monterroso" data-error="La denominaci�n del centro no es v�lida" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="codigo_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Centro c�digo</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="codigo_centro" name="codigo_centro" placeholder="29002885" maxlength="8" data-minlength="8" data-error="El c�digo del centro no es v�lido" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="email_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Correo electr�nico</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="email" class="form-control" id="email_centro" name="email_centro" placeholder="29002885.edu@juntadeandalucia.es" data-error="La direcci�n de correo electr�nico no es v�lida" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="direccion_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Direcci�n postal</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="direccion_centro" name="direccion_centro" placeholder="Calle Santo Tom�s de Aquino, s/n" data-error="La direcci�n postal no es v�lida" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="codpostal_centro" class="col-sm-<?php echo $tam_label; ?> control-label">C�digo postal</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="codpostal_centro" name="codpostal_centro" placeholder="29680" maxlength="5" data-minlength="5" data-error="El c�digo postal no es v�lido" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="localidad_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Localidad</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="localidad_centro" name="localidad_centro" placeholder="Estepona" data-error="La localidad no es v�lida" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="provincia_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Provincia</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <select class="form-control" id="provincia_centro" name="provincia_centro" data-error="La provincia no es v�lida" required>
				    		      	<option value=""></option>
				    		      	<?php foreach($provincias as $provincia): ?>
				    		      	<option value="<?php echo $provincia; ?>"><?php echo $provincia; ?></option>
				    		      	<?php endforeach; ?>
				    		      </select>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="telefono_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Tel�fono</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="tel" class="form-control" id="telefono_centro" name="telefono_centro" placeholder="952795802" maxlength="9" data-minlength="9" data-error="El t�lefono no es v�lido" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="fax_centro" class="col-sm-<?php echo $tam_label; ?> control-label">Fax</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="tel" class="form-control" id="fax_centro" name="fax_centro" placeholder="952795802" maxlength="9" data-minlength="9" data-error="El fax no es v�lido">
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="direccion_director" class="col-sm-<?php echo $tam_label; ?> control-label">Director/a</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="direccion_director" name="direccion_director" placeholder="Nombre y apellidos" maxlength="60" data-error="Este campo es obligatorio" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="direccion_jefe_estudios" class="col-sm-<?php echo $tam_label; ?> control-label">Jefe/a de Estudios</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="direccion_jefe_estudios" name="direccion_jefe_estudios" placeholder="Nombre y apellidos" maxlength="60" data-error="Este campo es obligatorio" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="direccion_secretaria" class="col-sm-<?php echo $tam_label; ?> control-label">Secretario/a</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="direccion_secretaria" name="direccion_secretaria" placeholder="Nombre y apellidos" maxlength="60" data-error="Este campo es obligatorio" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		
				    		<br>
				    		
				    		<div class="pull-left">
				    			<a href="#php-config" aria-controls="php-config" data-toggle="tab" class="btn btn-default"><span class="fa fa-chevron-left fa-fw"></span> Anterior</a>
				    		</div>
				    		<div class="pull-right">
				    			<a href="#base-datos" aria-controls="base-datos" data-toggle="tab" class="btn btn-primary disabled">Continuar <span class="fa fa-chevron-right fa-fw"></span></a>
				    		</div>
				    		<div class="clearfix"></div>
				    	</div>
				    	
				    </div>
				    
				    
				    <!-- CONFIGURACI�N DE LA BASE DE DATOS -->
				    <div role="tabpanel" class="tab-pane" id="base-datos">
				    	
				    	<div class="well">
				    		<h3>Configuraci�n de la base de datos</h3>
				    		<br>
				    		  
				    		  <?php $tam_label = 3; ?>
				    		  <?php $tam_control = 8; ?>
				    		  
				    		  <div class="form-group">
				    		    <label for="db_host" class="col-sm-<?php echo $tam_label; ?> control-label">Servidor</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="db_host" name="db_host" placeholder="localhost" data-error="La direcci�n servidor de base de datos no es v�lida" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="db_name" class="col-sm-<?php echo $tam_label; ?> control-label">Base de datos</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="db_name" name="db_name" placeholder="intranet" data-error="El nombre de la base de datos no es v�lido" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="db_user" class="col-sm-<?php echo $tam_label; ?> control-label">Usuario</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="db_user" name="db_user" data-error="El nombre de usuario de la base de datos no es v�lido" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="db_pass" class="col-sm-<?php echo $tam_label; ?> control-label">Contrase�a</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="password" class="form-control" id="db_pass" name="db_pass" data-error="La contrase�a de la base de datos no es v�lido" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		
				    		<br>
				    		
				    		<div class="pull-left">
				    			<a href="#informacion" aria-controls="informacion" data-toggle="tab" class="btn btn-default"><span class="fa fa-chevron-left fa-fw"></span> Anterior</a>
				    		</div>
				    		<div class="pull-right">
				    			<a href="#curso-escolar" aria-controls="curso-escolar" data-toggle="tab" class="btn btn-primary disabled">Continuar <span class="fa fa-chevron-right fa-fw"></span></a>
				    		</div>
				    		<div class="clearfix"></div>
				    	</div>
				    	
				    </div>
				    
				    
				    <!-- INFORMACI�N CURSO ESCOLAR -->
				    <div role="tabpanel" class="tab-pane" id="curso-escolar">
				    	
				    	<div class="well">
				    		<h3>Informaci�n del curso escolar</h3>
				    		<br>
				    		  
				    		  <?php $tam_label = 3; ?>
				    		  <?php $tam_control = 8; ?>
				    		  
				    		  <div class="form-group">
				    		    <label for="curso_escolar" class="col-sm-<?php echo $tam_label; ?> control-label">Curso escolar</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="curso_escolar" name="curso_escolar" value="<?php echo (date('n') > 6) ?  date('Y').'/'.(date('y')+1) : (date('Y')-1).'/'.date('y'); ?>" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="fecha_inicio" class="col-sm-<?php echo $tam_label; ?> control-label">Fecha de inicio</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo (date('n') > 6) ?  date('Y').'-09-15' : (date('Y')-1).'-09-15'; ?>" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		  
				    		  <div class="form-group">
				    		    <label for="fecha_final" class="col-sm-<?php echo $tam_label; ?> control-label">Fecha final</label>
				    		    <div class="col-sm-<?php echo $tam_control; ?>">
				    		      <input type="text" class="form-control" id="fecha_final" name="fecha_final" value="<?php echo (date('n') > 6) ?  (date('Y')+1).'-06-23' : date('Y').'-06-23'; ?>" required>
				    		      <div class="help-block with-errors"></div>
				    		    </div>
				    		  </div>
				    		
				    		<br>
				    		
				    		<div class="pull-left">
				    			<a href="#base-datos" aria-controls="base-datos" data-toggle="tab" class="btn btn-default"><span class="fa fa-chevron-left fa-fw"></span> Anterior</a>
				    		</div>
				    		<div class="pull-right">
				    			<a href="#modulos" aria-controls="modulos" data-toggle="tab" class="btn btn-primary disabled">Continuar <span class="fa fa-chevron-right fa-fw"></span></a>
				    		</div>
				    		<div class="clearfix"></div>
				    	</div>
				    	
				    </div>
				    
				    <!-- SELECCI�N DE M�DULOS -->
				    <div role="tabpanel" class="tab-pane" id="modulos">
				    	
				    	<div class="well">
				    		<h3>Configuraci�n de m�dulos</h3>
							<br>
	    		            
				    		<div class="row">
				    			<div class="col-sm-4" style="border-right: 3px solid #dce4ec; margin-right: 25px;">
									<ul class="nav nav-pills nav-stacked" role="tablist">
										<li class="active"><a href="#mod_biblioteca" aria-controls="mod_biblioteca" role="tab" data-toggle="tab">Biblioteca</a></li>
										<li><a href="#mod_bilingue" aria-controls="mod_bilingue" role="tab" data-toggle="tab">Centro Biling�e</a></li>
										<li><a href="#mod_centrotic" aria-controls="mod_centrotic" role="tab" data-toggle="tab">Centro TIC</a></li>
										<li><a href="#mod_documentos" aria-controls="mod_documentos" role="tab" data-toggle="tab">Documentos</a></li>
										<li><a href="#mod_sms" aria-controls="mod_sms" role="tab" data-toggle="tab">Env�o SMS</a></li>
										<li><a href="#mod_asistencia" aria-controls="mod_asistencia" role="tab" data-toggle="tab">Faltas de Asistencia</a></li>
										<li><a href="#mod_horarios" aria-controls="mod_horarios" role="tab" data-toggle="tab">Horarios</a></li>
										<li><a href="#mod_matriculacion" aria-controls="mod_matriculacion" role="tab" data-toggle="tab">Matriculaci�n</a></li>
									</ul>
								</div>
								
				    			<div class="tab-content col-sm-7">
				    				
				    				<!-- M�DULO: BIBLIOTECA -->
				    			    <div role="tabpanel" class="tab-pane active" id="mod_biblioteca">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
						    			    		<input type="checkbox" name="mod_biblioteca" value="1">
						    			    		<strong>Biblioteca</strong>
						    			    		<p class="help-block">Si el Centro dispone de Biblioteca que funciona con Abies, y cuenta con un equipo de profesores dedicados a su mantenimiento, puedes activar este m�dulo. Permite consultar e importar los fondos, lectores y pr�stamos, as� como hacer un seguimiento de los alumnos morosos.</p>
						    			    	</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    	<br>
				    			    	
				    			    	<div class="form-group">
				    			    		<label for="mod_biblioteca_web">P�gina web de la Biblioteca</label>
				    			    		<div class="input-group">
			    			    		      <div class="input-group-addon">http://</div>
			    			    		      <input type="text" class="form-control" id="mod_biblioteca_web" name="mod_biblioteca_web" placeholder="iesmonterroso.org/biblioteca/">
			    			    		    </div>
				    			    	</div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: CENTRO BILING�E -->
				    			    <div role="tabpanel" class="tab-pane" id="mod_bilingue">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
						    			    		<input type="checkbox" name="mod_bilingue" value="1">
						    			    		<strong>Centro Biling�e</strong>
						    			    		<p class="help-block">Activa caracter�sticas para los Centros Biling�es, como el env�o de mensajes a los profesores que pertenecen al programa biling�e.</p>
						    			    	</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: CENTRO TIC -->
				    			    <div role="tabpanel" class="tab-pane" id="mod_centrotic">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
						    			    		<input type="checkbox" name="mod_centrotic" value="1">
						    			    		<strong>Centro TIC</strong>
						    			    		<p class="help-block">Aplicaciones propias de un Centro TIC: Incidencias, usuarios, etc.</p>
						    			    	</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: DOCUMENTOS --> 
				    			    <div role="tabpanel" class="tab-pane" id="mod_documentos">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
					    			    			<input type="checkbox" name="mod_documentos" value="1" checked>
					    			    			<strong>Documentos</strong>
					    			    			<p class="help-block">Directorio en el Servidor local donde tenemos documentos p�blicos que queremos administrar (visualizar, eliminar, subir, compartir, etc.) con la Intranet.</p>
					    			    		</label>
					    			    	</div>
					    			    </div>
				    			    				    			    	
				    			    	<div class="form-group">
				    			    		<label for="mod_documentos_dir">Directorio p�blico</label>
				    			    	    <input type="text" class="form-control" id="mod_documentos_dir" name="mod_documentos_dir" value="<?php echo $doc_dir; ?>">
				    			    	</div>
				    			    	
				    			    	<div class="checkbox">
				    			    		<label>
				    			    			<input type="checkbox" name="mod_documentos_biblioteca" value="1">
				    			    			<strong>Biblioteca</strong>
				    			    			<p class="help-block">Crear� una carpeta donde el personal de Biblioteca puede subir documentos de inter�s para la comunidad educativa.</p>
				    			    		</label>
				    			    	</div>
				    			    	
				    			    	<div class="checkbox">
				    			    		<label>
				    			    			<input type="checkbox" name="mod_documentos_recursos" value="1">
				    			    			<strong>Recursos educativos</strong>
				    			    			<p class="help-block">Crear� una carpeta con las unidades del centro donde los profesores que impartan alguna materia en la unidad podr� subir recursos para los alumnos/as.</p>
				    			    		</label>
				    			    	</div>
				    			    	
				    			    	<div class="checkbox">
				    			    		<label>
				    			    			<input type="checkbox" name="mod_documentos_departamentos" value="1">
				    			    			<strong>Departamentos</strong>
				    			    			<p class="help-block">Crear� una carpeta con los departamentos del centro donde se podr�n subir las programaciones u otros documentos de inter�s para la comunidad educativa.</p>
				    			    		</label>
				    			    	</div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: ENV�O DE SMS -->
				    			    <div role="tabpanel" class="tab-pane" id="mod_sms">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
					    			    			<input type="checkbox" name="mod_sms" value="1">
					    			    			<strong>Env�o de SMS</strong>
					    			    			<p class="help-block">Pone en funcionamiento el env�o de SMS en distintos lugares de la Intranet (Problemas de convivencia, faltas de asistencia, etc.). La aplicaci�n est� preparada para trabajar con la API de <a href="http://www.trendoo.es/" target="_blank">Trendoo</a>.</p>
					    			    		</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    	<div class="form-group">
				    			    		<label for="mod_sms_id">Nombre de identificaci�n (<abbr title="Transmission Path Originating Address">TPOA</abbr>)</label>
				    			    	    <input type="text" class="form-control" id="mod_sms_id" name="mod_sms_id" placeholder="P. ej. IMonterroso" maxlength="11">
				    			    	    <p class="help-block">11 caracteres como m�ximo.</p>
				    			    	</div>
				    			    	
				    			    	<div class="form-group">
				    			    		<label for="mod_sms_user">Usuario</label>
				    			    	    <input type="text" class="form-control" id="mod_sms_user" name="mod_sms_user">
				    			    	</div>
				    			    	
				    			    	<div class="form-group">
				    			    		<label for="mod_sms_pass">Contrase�a</label>
				    			    	    <input type="password" class="form-control" id="mod_sms_pass" name="mod_sms_pass">
				    			    	</div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: FALTAS DE ASISTENCIA -->
				    			    <div role="tabpanel" class="tab-pane" id="mod_asistencia">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
					    			    			<input type="checkbox" id="check_asistencia" name="mod_asistencia" value="1">
					    			    			<strong>Faltas de Asistencia</strong>
					    			    			<p class="help-block">El m�dulo de faltas permite gestionar las faltas a trav�s de la Intranet para luego exportarlas a S�neca. Es posible tambi�n descargar las faltas desde S�neca para utilizar los m�dulo de la aplicaci�n basados en faltas de asistencia (Informes de alumnos, Tutor�a, Absentismo, etc.).</p>
					    			    		</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    	<div class="alert alert-warning">Este m�dulo depende del m�dulo de Horarios. Si decide utilizarlo se activar� el m�dulo de Horarios autom�ticamente.</div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: HORARIOS -->
				    			    <div role="tabpanel" class="tab-pane" id="mod_horarios">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
					    			    			<input type="checkbox" id="check_horarios" name="mod_horarios" value="1">
					    			    			<strong>Horarios</strong>
					    			    			<p class="help-block">Si disponemos de un archivo de Horario en formato XML (como el que se utiliza para subir a S�neca) o DEL (como el que genera el programa Horw) para importar sus datos a la Intranet. Aunque no obligatoria, esta opci�n es necesaria si queremos hacernos una idea de todo lo que la aplicaci�n puede ofrecer.</p>
					    			    		</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    </div>
				    			    
				    			    
				    			    <!-- M�DULO: MATRICULACI�N -->
				    			    <div role="tabpanel" class="tab-pane" id="mod_matriculacion">
				    			    	
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
					    			    			<input type="checkbox" name="mod_matriculacion" value="1">
					    			    			<strong>Matriculaci�n</strong>
					    			    			<p class="help-block">Este m�dulo permite matricular a los alumnos desde la propia aplicaci�n o bien desde la p�gina p�blica del Centro incluyendo el c�digo correspondiente.</p>
					    			    		</label>
					    			    	</div>
					    			    </div>
					    			    
				    			    	<div class="form-group">
					    			    	<div class="checkbox">
					    			    		<label>
					    			    			<input type="checkbox" name="mod_transporte_escolar" value="1">
					    			    			<strong>Transporte escolar</strong>
					    			    			<p class="help-block">Activa la selecci�n de transporte escolar</p>
					    			    		</label>
					    			    	</div>
					    			    </div>
				    			    	
				    			    </div>
				    			  </div>
				    		</div>
				    		
				    		<br><br>
				    		
				    		<div class="pull-left">
				    			<a href="#base-datos" aria-controls="base-datos" data-toggle="tab" class="btn btn-default"><span class="fa fa-chevron-left fa-fw"></span> Anterior</a>
				    		</div>
				    		<div class="pull-right">
				    			<button type="submit" class="btn btn-success" name="instalar">Instalar</button>
				    		</div>
				    		<div class="clearfix"></div>
				    	</div>
				    	
				    </div>
				    
				    <?php else: ?>
				    
				    <!-- INSTALACION -->
				    <div role="tabpanel" class="tab-pane active" id="instalacion">
				    	
				    	
				    	<div class="well well-lg">
				    	
				    		<?php if ($esError): ?>
				    		<div class="text-center text-error">
				    			<span class="fa fa-meh-o fa-5x"></span>
				    		</div>
				    		<h3 class="text-center text-error">Error al conectar con la base de datos</h3>
				    		<br>
				    		<code style="display: block;"><?php echo $details_error; ?></code>
				    		<br>
				    		<div class="text-center">
				    			<a href="index2.php" class="btn btn-default">Volver</a>
				    		</div>
				    		<?php else: ?>
				    		<div class="text-center text-success">
				    			<span class="fa fa-check fa-5x"></span>
				    		</div>
				    		<h3 class="text-center text-success">La Intranet ha sido instalada correctamente</h3>
				    		
				    		<br>
				    		<p class="text-center" style="font-size: 1.12em;">Inicie sesi�n como Administrador para continuar con la importaci�n de los datos de S�neca.<br>En el men� de la p�gina principal encontrar� el enlace <em>Administraci�n de la Intranet</em>, lea atentamente las instrucciones que aparecen para la puesta en marcha de la aplicaci�n. Haga click en el bot�n Iniciar sesi�n y utilice el usuario <strong>admin</strong> y la siguiente contrase�a:</p>
				    		
				    		<div class="text-center">
				    			<code class="text-center lead"><?php echo $pass_admin; ?></code>
				    		</div>
				    		
				    		<br><br>
				    		
				    		<div class="text-center">
				    			<a href="../index.php" class="btn btn-primary">Iniciar sesi�n</a>
				    		</div>
				    		<?php endif; ?>
				    		
				    	</div>
				    	
				    	<br><br>
				    	
				    </div>
				    <?php endif; ?>
				    
				  </div><!-- /.tab-content -->
				
			</div><!-- /.col-sm-offset-2 .col-sm-8 -->
			
		</div><!-- /.row -->
		
		</form>
		
		
	</div><!-- /.container -->
	
	
	<footer class="hidden-print">
		<div class="container-fluid" role="footer">
			<hr>
			
			<p class="text-center">
				<small class="text-muted">Versi�n <?php echo INTRANET_VERSION; ?> - Copyright &copy; <?php echo date('Y'); ?> IESMonterroso</small><br>
				<small class="text-muted">Este programa es software libre, liberado bajo la GNU General Public License.</small>
			</p>
			<p class="text-center">
				<small>
					<a href="//<?php echo $config['dominio']; ?>/intranet/LICENSE.md" target="_blank">Licencia de uso</a>
					&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;
					<a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a>
				</small>
			</p>
		</div>
	</footer>
	
	
	<script src="../js/jquery-1.11.3.min.js"></script>  
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/validator/validator.min.js"></script>
	

	<script>
	$(document).ready(function()
	{
		
		$('#old-ie').modal({
			backdrop: true,
			keyboard: false,
			show: true
		});
		
		
		$("#terms-accept").click(function()
		{  
	        if($("#terms-accept").is(':checked'))
			{  
	            $("a").removeClass("disabled"); 
	        }
	        else
			{  
	            $("a").addClass("disabled");   
	        }  
	    });
	    
	    $("#check_asistencia").click(function()
		{  
	        if($("#check_asistencia").is(':checked'))
			{  
	            $("#check_horarios").prop('checked', true);
	        }
	        else
			{  
	            $("#check_horarios").prop('checked', false);
	        }  
	    });
	    
	    $("#check_horarios").click(function()
		{  
	        if(! $("#check_horarios").is(':checked'))
			{  
	            $("#check_asistencia").prop('checked', false);
	        }
	    });
	    
	    $('#form-instalacion').validator();
		
	});
	</script>
	
</body>
</html>
