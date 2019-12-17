<?php
require('bootstrap.php');
require_once(INTRANET_DIRECTORY."/lib/phpmailer/PHPMailerAutoload.php");

if ($_SERVER['SERVER_NAME'] == "iesmonterroso.org") {
	header("Location:loginSeneca.php");
	exit();
}

// Comienzo de sesión
$_SESSION['intranet_auth'] = 0;

if (! isset($_SESSION['intentos'])) $_SESSION['intentos'] = 0;

// DESTRUIMOS LAS VARIABLES DE SESIÓN
if (isset($_SESSION['profi'])) {
	$_SESSION = array();
	session_destroy();
}

mysqli_query($db_con,"SET NAMES 'utf8'");

// Entramos
if (isset($_POST['submit']) and ! ($_POST['idea'] == "" or $_POST['clave'] == "")) {
	$clave0 = $_POST['clave'];
	$clave = sha1 ( $_POST['clave'] );

	$pass0 = mysqli_query($db_con, "SELECT c_profes.pass, c_profes.profesor , departamentos.dni, c_profes.estado, c_profes.correo, c_profes.telefono, c_profes.totp_secret FROM c_profes, departamentos where c_profes.profesor = departamentos.nombre and c_profes.idea = '".$_POST['idea']."' LIMIT 1");
	$usuarioExiste = mysqli_num_rows($pass0);

	$pass1 = mysqli_fetch_array($pass0);
	$codigo = $pass1['pass'];
	$profe = $pass1['profesor'];
	$dni = $pass1['dni'];
	$bloqueado = $pass1['estado'];
	$telefono = $pass1['telefono'];
	$correo = $pass1['correo'];

	if ($pass1['totp_secret'] != NULL) {
		$totp_activado = 1;
		$_SESSION['totp_secreto'] = $pass1['totp_secret'];
	}
	else {
		$totp_activado = 0;
	}


	if (! $bloqueado) {

		if ($codigo == $clave) {

			// COMPROBAMOS SI SE HA INSTALADO LA PLATAFORMA WEBCENTROS
			// Versión en otros centros educativos
			if (file_exists('../alumnado/login.php')) {
				$_SESSION['pagina_centro'] = 1;
			}
			// Versión IES Monterroso
			elseif (file_exists('/home/e-smith/files/ibays/Primary/html/alumnado/login.php')) {
				$_SESSION['pagina_centro'] = 1;
			}
			else {
				$_SESSION['pagina_centro'] = 0;
			}

			$_SESSION['profi'] = $pass1[1];
			$profe = $_SESSION['profi'];

			// Variables de sesión del cargo del Profesor
			$cargo0 = mysqli_query($db_con, "select cargo, departamento, idea from departamentos where nombre = '$profe'" );
			$cargo1 = mysqli_fetch_array ( $cargo0 );
			$_SESSION['cargo'] = $cargo1 [0];
			$carg = $_SESSION['cargo'];
			$_SESSION['dpt'] = $cargo1[1];
			$_SESSION['ide'] = $cargo1[2];

			// Si es tutor
			if (stristr ( $_SESSION['cargo'], '2' ) == TRUE) {
				$result = mysqli_query($db_con, "select distinct unidad from FTUTORES where tutor = '$profe'" );
				$row = mysqli_fetch_array ( $result );
				$_SESSION['mod_tutoria']['tutor'] = $profe;
				$_SESSION['mod_tutoria']['unidad'] = $row [0];
			}

			// Si tiene Horario
			$cur0 = mysqli_query($db_con, "SELECT distinct profesor FROM profesores where profesor = '$profe'" );
			$cur00 = mysqli_query($db_con, "SELECT distinct prof FROM horw where prof = '$profe'" );
			$cur1 = mysqli_num_rows ( $cur0 );
			$cur11 = mysqli_num_rows ( $cur00 );
			if ($cur1>0 or $cur11>0) {
				$_SESSION['n_cursos'] = 1;
			}

			// Si tiene tema personalizado
			$res = mysqli_query($db_con, "select distinct tema, fondo from temas where idea = '".$_SESSION['ide']."'" );
			if (mysqli_num_rows($res)>0) {
				$ro = mysqli_fetch_array ( $res );
				$_SESSION['tema'] = $ro[0];
				$_SESSION['fondo'] = $ro[1];
			}
			else{
				$_SESSION['tema']="bootstrap.min.css";
				$_SESSION['fondo'] = "navbar-default";
			}

			// Obtenemos los datos de navegación del usuario
			$direccionIP = getRealIP();
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$useragent_parseado = getBrowser($useragent);

			// Comprobamos si inicia sesión desde la red local del centro o red externa y establecemos si es de confianza o no.
			if (! isPrivateIP($direccionIP)) {
				$meta_geoip = @unserialize(file_get_contents('http://ip-api.com/php/'.$direccionIP));
				$isp_usuario = $meta_geoip['isp'];
				unset($meta_geoip);

				$red_confianza = 0;
			}
			else {
				$red_confianza = 1;
			}

			// Comprobamos si el usuario ha iniciado sesión alguna vez con este dispositivo y establecemos si es de confianza o no.
			$result_dispositivo_confianza = mysqli_query($db_con, "SELECT ip FROM reg_intranet WHERE profesor = '".$_SESSION['ide']."' AND useragent LIKE '%".$useragent_parseado['platform_name']."%' AND useragent LIKE '%".$useragent_parseado['browser_name']."%' AND useragent LIKE '%".$useragent_parseado['browser_version']."%' AND ip = '".$direccionIP."' LIMIT 1");
			if (! mysqli_num_rows($result_dispositivo_confianza)) {
				$result_dispositivo_confianza = mysqli_query($db_con, "SELECT ip FROM reg_intranet WHERE profesor = '".$_SESSION['ide']."' AND useragent LIKE '%".$useragent_parseado['platform_name']."%' AND useragent LIKE '%".$useragent_parseado['browser_name']."%' AND useragent LIKE '%".$useragent_parseado['browser_version']."%' LIMIT 1");
			}

			if (mysqli_num_rows($result_dispositivo_confianza)) {
				$dispositivo_confianza = 1;
				$row_dispositivo_confianza = mysqli_fetch_array($result_dispositivo_confianza);
				$ip_dispositivo_confianza = $row_dispositivo_confianza['ip'];
				if (! isPrivateIP($ip_dispositivo_confianza)) {
					$meta_geoip = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip_dispositivo_confianza));
					$isp_dispositivo_confianza = $meta_geoip['isp'];
					unset($meta_geoip);

					if ($isp_dispositivo_confianza == $isp_usuario) {
						$red_confianza = 1;
					}
					else {
						$red_confianza = 0;
					}
				}
				else {
					$red_confianza = 1;
				}

			}
			else {
				$dispositivo_confianza = 0;
			}

			mysqli_query($db_con, "INSERT INTO reg_intranet (profesor, fecha, ip, useragent) VALUES ('".$_SESSION['ide']."','".date('Y-m-d H:i:s')."','".$direccionIP."', '".$useragent."')");
			$id_reg = mysqli_query($db_con, "SELECT id FROM reg_intranet WHERE profesor = '".$_SESSION['ide']."' ORDER BY id DESC LIMIT 1" );
			$id_reg0 = mysqli_fetch_array ( $id_reg );
			$_SESSION['id_pag'] = $id_reg0[0];

			unset($_SESSION['intentos']);

			session_regenerate_id(true);

			if ($dni == $clave0 || (strlen($codigo) < '12'))
			{

				$_SESSION['intranet_auth'] = 1;
				$_SESSION['cambiar_clave'] = 1;
				header("location:usuario.php?tab=cuenta&pane=password");
				exit();
			}
			else {
				// Comprobamos si el usuario tiene habilitada la autenticación en dos pasos.
				// Si el dispositivo es de confianza saltamos el proceso de autenticación en dos pasos.
				if ($totp_activado && $dispositivo_confianza && $red_confianza) {
					$_SESSION['intranet_auth'] = 1;
					header("location:index.php");
					exit();
				}
				elseif ($totp_activado && (! $dispositivo_confianza || ! $red_confianza)) {
					$fecha_conexion = strftime('%e de %B de %Y a las %H:%M horas', strtotime(date('Y-m-d H:i:s')));

					$mail = new PHPMailer();
					$mail->Host = "localhost";
					$mail->From = 'no-reply@'.$config['dominio'];
					$mail->FromName = utf8_decode($config['centro_denominacion']);
					$mail->IsHTML(true);

					$message = file_get_contents(INTRANET_DIRECTORY.'/lib/mail_template/index.htm');
					$message = str_replace('{{dominio}}', $config['dominio'], $message);
					$message = str_replace('{{centro_denominacion}}', $config['centro_denominacion'], $message);
					$message = str_replace('{{centro_codigo}}', $config['centro_codigo'], $message);
					$message = str_replace('{{centro_direccion}}', $config['centro_direccion'], $message);
					$message = str_replace('{{centro_codpostal}}', $config['centro_codpostal'], $message);
					$message = str_replace('{{centro_localidad}}', $config['centro_localidad'], $message);
					$message = str_replace('{{centro_provincia}}', $config['centro_provincia'], $message);
					$message = str_replace('{{centro_telefono}}', $config['centro_telefono'], $message);
					$message = str_replace('{{centro_fax}}', $config['centro_fax'], $message);
					$message = str_replace('{{centro_email}}', $config['centro_email'], $message);
					$message = str_replace('{{titulo}}', 'Tu usuario IdEA se ha usado para iniciar sesión en la Intranet', $message);
					$message = str_replace('{{contenido}}', '<p>Tu usuario IdEA '.$_SESSION['ide'].' se ha usado para iniciar sesión en la Intranet desde un navegador web.</p><br><p>Fecha y hora: '.$fecha_conexion.'</p><br><p>Si la información mencionada más arriba te resulta familiar, puedes ignorar este mensaje.</p><p>Si no has iniciado sesión recientemente en la Intranet y crees que alguien podría haber accedido a tu cuenta, te recomendamos que restablezcas tu contraseña.</p>', $message);
					$message = str_replace('{{autor}}', 'Dirección del Centro', $message);

					$mail->msgHTML(utf8_decode($message));
					$mail->Subject = utf8_decode('Tu usuario IdEA se ha usado para iniciar sesión en la Intranet');
					$mail->AltBody = utf8_decode("Tu usuario IdEA ".$_SESSION['ide']." se ha usado para iniciar sesión en la Intranet desde un navegador web. \n\n\nFecha y hora: ".$fecha_conexion." \n\n\nSi la información mencionada más arriba te resulta familiar, puedes ignorar este mensaje. \n\nSi no has iniciado sesión recientemente en la Intranet y crees que alguien podría haber accedido a tu cuenta, te recomendamos que restablezcas tu contraseña.</p>");

					$mail->AddAddress($correo, $profe);
					$mail->Send();

					// Enviamos SMS con el código temporal de inicio de sesión
					require_once(INTRANET_DIRECTORY.'/lib/google-authenticator/GoogleAuthenticator.php');
					$ga = new PHPGangsta_GoogleAuthenticator();

					$_SESSION['totp_codigo_movil'] = 0;
					if (isset($config['mod_sms']) && $config['mod_sms'] && $telefono != '') {
						$oneCode = $ga->getCode($_SESSION['totp_secreto']);

						include_once(INTRANET_DIRECTORY.'/lib/trendoo/sendsms.php');
						$sms = new Trendoo_SMS();
						$sms->sms_type = SMSTYPE_GOLD_PLUS;
						$sms->add_recipient('+34'.$telefono);
						$sms->message = 'Tu código temporal de inicio de sesión en la Intranet es '.$oneCode.'.';
						$sms->sender = $config['mod_sms_id'];
						$sms->set_immediate();
						if ($sms->validate()) $sms->send();
						$_SESSION['totp_codigo_movil'] = substr($telefono, 6, 3);
					}

					include("login_totp.php");
					exit();
				}
				else {
					$_SESSION['intranet_auth'] = 1;
					header("location:index.php");
					exit();
				}
			}

		}
		// La contraseña no es correcta
		else {

			if ($_SESSION['intentos'] > 4) {
				$idea_usr = preg_replace('([^A-Za-z0-9])', '', $_POST['idea']);
				mysqli_query($db_con, "UPDATE c_profes SET estado=1 WHERE idea='".$idea_usr."' LIMIT 1");

				if (stristr($profe, ', ') == true) {
					$exp_profe = explode(', ', $profe);
					$nombre_profe = trim($exp_profe[1]);
				}
				else {
					$nombre_profe = $profe;
				}


				$mail = new PHPMailer();
				if (isset($config['email_smtp']['isSMTP']) && $config['email_smtp']['isSMTP']) {
					$mail->isSMTP();
					$mail->Host = $config['email_smtp']['hostname'];
					$mail->SMTPAuth = $config['email_smtp']['smtp_auth'];
					$mail->Port = $config['email_smtp']['port'];
					$mail->SMTPSecure = $config['email_smtp']['smtp_secure'];

					$mail->Username = $config['email_smtp']['username'];
					$mail->Password = $config['email_smtp']['password'];

					$mail->setFrom($config['email_smtp']['username'], utf8_decode($config['centro_denominacion']));
				}
				else {
					$mail->Host = "localhost";
					$mail->setFrom('no-reply@'.$config['dominio'], utf8_decode($config['centro_denominacion']));
				}
				$mail->IsHTML(true);

				$message = file_get_contents(INTRANET_DIRECTORY.'/lib/mail_template/index.htm');
				$message = str_replace('{{dominio}}', $config['dominio'], $message);
				$message = str_replace('{{centro_denominacion}}', $config['centro_denominacion'], $message);
				$message = str_replace('{{centro_codigo}}', $config['centro_codigo'], $message);
				$message = str_replace('{{centro_direccion}}', $config['centro_direccion'], $message);
				$message = str_replace('{{centro_codpostal}}', $config['centro_codpostal'], $message);
				$message = str_replace('{{centro_localidad}}', $config['centro_localidad'], $message);
				$message = str_replace('{{centro_provincia}}', $config['centro_provincia'], $message);
				$message = str_replace('{{centro_telefono}}', $config['centro_telefono'], $message);
				$message = str_replace('{{centro_fax}}', $config['centro_fax'], $message);
				$message = str_replace('{{centro_email}}', $config['centro_email'], $message);
				$message = str_replace('{{titulo}}', 'Cuenta temporalmente bloqueada', $message);
				$message = str_replace('{{contenido}}', 'Estimado '.$nombre_profe.',<br><br>Para ayudar a proteger tu cuenta contra fraudes o abusos, hemos tenido que bloquear el acceso temporalmente porque se ha detectado alguna actividad inusual. Sabemos que el hecho de que tu cuenta esté bloqueada puede resultar frustrante, pero podemos ayudarte a recuperarla fácilmente en unos pocos pasos.<br><br>Pónte en contacto con algún miembro del equipo directivo para restablecer tu contraseña. Una vez restablecida podrás acceder a la Intranet utilizando tu DNI con letra mayúscula como contraseña. Para mantener tu seguridad utilice una contraseña segura.', $message);
				$message = str_replace('{{autor}}', 'Dirección del Centro', $message);

				$mail->msgHTML(utf8_decode($message));
				$mail->Subject = utf8_decode('Cuenta temporalmente bloqueada');

				$mail->AltBody = 'Estimado '.$nombre_profe.',<br><br>Para ayudar a proteger tu cuenta contra fraudes o abusos, hemos tenido que bloquear el acceso temporalmente porque se ha detectado alguna actividad inusual. Sabemos que el hecho de que tu cuenta esté bloqueada puede resultar frustrante, pero podemos ayudarte a recuperarla fácilmente en unos pocos pasos.<br><br>Pónte en contacto con algún miembro del equipo directivo para restablecer tu contraseña. Una vez restablecida podrás acceder a la Intranet utilizando tu DNI con letra mayúscula como contraseña. Para mantener tu seguridad utilice una contraseña segura.';

				$mail->AddAddress($correo, $profe);
				$mail->Send();

				$msg_error = "La cuenta de usuario ha sido bloqueada";
				unset($_SESSION['intentos']);
			}
			else {
				$msg_error = "Nombre de usuario y/o contraseña incorrectos";

				if ($usuarioExiste) {
					$_SESSION['intentos']++;
				}
				else {
					unset($_SESSION['intentos']);
				}
			}
		}
	}
	else {
		$msg_error = "La cuenta de usuario está bloqueada";
	}
}

include('control_acceso.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Intranet del <?php echo $config['centro_denominacion']; ?>">
	<meta name="author" content="IESMonterroso (https://github.com/IESMonterroso/intranet/)">
	<meta name="robots" content="noindex, nofollow">

	<title>Intranet &middot; <?php echo $config['centro_denominacion']; ?></title>

	<link href="//<?php echo $config['dominio']; ?>/intranet/css/bootstrap.min.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/vendor/fontawesome-free-5.11.2-web/css/all.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/animate.css" rel="stylesheet">
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/otros.css" rel="stylesheet">
</head>

<body id="login">

	<div id="old-ie" class="modal">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-body">
	      	<br>
	        <p class="lead text-center">Estás utilizando una versión de Internet Explorer demasiado antigua. <br>Actualiza tu navegador o cámbiate a <a href="https://www.google.com/chrome/browser/desktop/index.html">Chrome</a> o <a href="https://www.mozilla.org/es-ES/firefox/new/">Firefox</a>.</p>
	        <br>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div id="wrapper">

		<div class="container">

		  <div class="text-center" style="-webkit-animation: fadeInDown 1s;">
		    <h1><?php echo $config['centro_denominacion']; ?></h1>
		    <h4>Inicia sesión para acceder</h4>
		  </div>

		  <form id="form-signin" class="form-signin well" method="POST" autocomplete="on">
		      <div class="text-center text-muted form-signin-heading">
						<i class="far fa-user-circle fa-7x"></i>
		      </div>

		      <div id="form-group" class="form-group">
		        <input type="text" class="form-control" id="idea" name="idea" placeholder="Usuario IdEA" maxlength="12" required autofocus>
		        <input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña" maxlength="20" required>

		        <?php if($msg_error): ?>
		            <label class="control-label text-danger"><?php echo $msg_error; ?></label>
		        <?php endif; ?>
		      </div>



		      <button type="submit" class="btn btn-lg btn-primary btn-block" name="submit">Iniciar sesión</button>

		      <div class="form-signin-footer">

		      </div>
		  </form>

		</div><!-- /.container -->

	</div><!-- /#wrap -->

	<footer class="hidden-print">
		<div class="container-fluid">
			<p class="pull-left text-muted">&copy; <?php echo date('Y'); ?>, IES Monterroso</p>

			<ul class="pull-right list-inline">
				<li>Versión <?php echo INTRANET_VERSION; ?></li>
				<li><a href="//<?php echo $config['dominio']; ?>/intranet/aviso-legal/">Aviso legal</a></li>
				<li><a href="//<?php echo $config['dominio']; ?>/intranet/LICENSE.md" target="_blank">Licencia</a></li>
				<li><a href="https://github.com/IESMonterroso/intranet" target="_blank">Github</a></li>
			</ul>
		</div>
	</footer>


	<script src="//<?php echo $config['dominio']; ?>/intranet/js/jquery-2.1.1.min.js"></script>
	<script src="//<?php echo $config['dominio']; ?>/intranet/js/bootstrap.min.js"></script>

	<?php if($msg_error): ?>
	<script>$("#form-group").addClass( "has-error" );</script>
	<?php endif; ?>
	<script>
	$(function(){

		function msieversion() {
	    var ua = window.navigator.userAgent;
	    var msie = ua.indexOf("MSIE ");

	    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, return version number
	    {
	    	return parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)));
			}
			else
			{
				return false;
			}
		}

		if(msieversion() != false && msieversion() < 11)
		{
			$('#old-ie').modal({
				backdrop: true,
				keyboard: false,
				show: true
			});
		}

		// Deshabilitamos el botón
		$("button[type=submit]").attr("disabled", "disabled");

		// Cuando se presione una tecla en un input del formulario
		// realizamos la validación
		$('input').keyup(function(){
		      // Validamos el formulario
		      var validated = true;
		      if($('#idea').val().length < 5) validated = false;
		      if($('#clave').val().length < 8) validated = false;

		      // Si el formulario es válido habilitamos el botón, en otro caso
		      // lo volvemos a deshabilitar
		      if(validated) $("button[type=submit]").removeAttr("disabled");
		      else $("button[type=submit]").attr("disabled", "disabled");

		});

		$('input:first').trigger('keyup');

	});
	</script>

</body>
</html>
