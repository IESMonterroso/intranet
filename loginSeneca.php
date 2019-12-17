<?php
require('bootstrap.php');

// Intentamos cargar la configuración de webcentros si existe
if (file_exists("../config.php")) {
	define("WEBCENTROS_DOMINIO", $_servername);

	include("../config.php");
	if (isset($config['color_primario'])) {
		$background_color = cmykcolor($config['color_primario'], 'hex');
	}
	else {
		$background_color = "#fa6432";
	}
}
else {
	$background_color = "#fa6432";
}

// Comienzo de sesión
$_SESSION['intranet_auth'] = 0;

// DESTRUIMOS LAS VARIABLES DE SESIÓN
if (isset($_SESSION['profi'])) {
	$_SESSION = array();
	session_destroy();
}

// Entramos
if (isset($_POST['submit']) && ! (strlen($_POST['USUARIO']) < 5 || strlen($_POST['CLAVE']) < 6)) {
	$cmp_idea = preg_replace('([^A-Za-z0-9])', '', trim(htmlspecialchars($_POST['USUARIO'])));
	
	$cmp_clave = htmlspecialchars($_POST['CLAVE']);
	$hash_clave = sha1($cmp_clave);
	$cmp_clavecifrada = trim($_POST['CLAVECIFRADA']);

	$result_usuario = mysqli_query($db_con, "SELECT `c_profes`.`pass`, `c_profes`.`profesor`, `departamentos`.`dni`, `c_profes`.`estado`, `c_profes`.`correo`, `c_profes`.`telefono`, `c_profes`.`totp_secret`, `departamentos`.`nombre` FROM `c_profes` JOIN `departamentos` ON `c_profes`.`profesor` = `departamentos`.`nombre` WHERE `c_profes`.`idea` = '".$cmp_idea."' LIMIT 1");
	$usuarioExiste = mysqli_num_rows($result_usuario);

	if ($usuarioExiste) {
		$sesionIntranet = 0;
		$usuarioBloqueado = 0;
		$_SESSION['session_seneca'] = 0;

		$datosIntranet = mysqli_fetch_array($result_usuario);

		// Cualquier usuario diferente del Administrador de la Intranet o personal no docente debe poder acceder a Séneca
		$result_profesor = mysqli_query($db_con,"SELECT `departamentos`.`departamento` FROM `departamentos` WHERE `departamentos`.`idea` = '".$cmp_idea."' AND `departamentos`.`nombre` IN (SELECT `nomprofesor` FROM `profesores_seneca` WHERE `nomprofesor` = '".$datosIntranet['nombre']."')");
		$usuarioProfesor = mysqli_num_rows($result_profesor);

		if ($usuarioProfesor) {

			// ESTABLECEMOS CONEXIÓN CON SÉNECA
			$LOGIN_NV = "NV_" . intval( rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) );
			$LOGIN_RNDVAL = intval( rand(1,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9) );
			$LOGIN_C_INTERFAZ = "SENECA";

			$ch = curl_init();
			$post = [
				'N_V_' => $LOGIN_NV,
				'rndval' => $LOGIN_RNDVAL,
				'CLAVECIFRADA'   => $cmp_clavecifrada,
				'USUARIO' => $cmp_idea,
				'C_INTERFAZ' => $LOGIN_C_INTERFAZ
			];

			curl_setopt($ch, CURLOPT_URL, 'https://www.juntadeandalucia.es/educacion/senecav2/seneca/jsp/ComprobarUsuarioExt.jsp');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($ch);

			// COMPROBAMOS EL CÓDIGO HTTP DE LA PETICIÓN
			switch (curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
				case '200' :
					$xml = simplexml_load_string($server_output);
					$json = json_encode($xml);
					$response = json_decode($json, true);

					if ($response['correcto'] == "NO") {
						$sesionIntranet = 0;
						$msg_error = $response['mensaje'];
						
						if ($response['mensaje'] == "Usuario bloqueado") {
							mysqli_query($db_con, "UPDATE FROM `c_profes` SET `estado` = 1 WHERE `idea` = '".$cmp_idea."' LIMIT 1");
						}
					}
					elseif ($response['correcto'] == "SI") {
						$_SESSION['session_seneca'] = 1;

						mysqli_query($db_con, "UPDATE FROM `c_profes` SET `estado` = 0 WHERE `idea` = '".$cmp_idea."' LIMIT 1");

						// Actualizamos la contraseña de la Intranet con la de Séneca
						mysqli_query($db_con, "UPDATE FROM `c_profes` SET `pass` = '".$hash_clave."' WHERE `idea` = '".$cmp_idea."' LIMIT 1");
						$sesionIntranet = 1;
					}
					else {
						// USUARIO Y CONTRASEÑA CORRECTOS EN LOCAL
						if ($datosIntranet['pass'] == $hash_clave) {
							$sesionIntranet = 1;
						}
						break;
					}

					break;

				// Si no es posible conectar con Séneca, pasa a inicio de sesión local
				default :
					// USUARIO Y CONTRASEÑA CORRECTOS EN LOCAL
					if ($datosIntranet['pass'] == $hash_clave) {
						$sesionIntranet = 1;
					}
					break;
			}
		}
		// Si el usuario es el Administrador de la Intranet o personal no docente
		else {
			// USUARIO Y CONTRASEÑA CORRECTOS EN LOCAL
			if ($datosIntranet['pass'] == $hash_clave) {
				$sesionIntranet = 1;
			}
		}

		// CONSTRUIMOS LA SESIÓN EN LA INTRANET
		if ($sesionIntranet) {
			$_SESSION['intranet_auth'] = 1;

			if (file_exists('../alumnado/login.php')) {
				$_SESSION['pagina_centro'] = 1;
			}
			else {
				$_SESSION['pagina_centro'] = 0;
			}

			$_SESSION['profi'] = $datosIntranet['profesor'];
			$profe = $_SESSION['profi'];

			// Variables de sesión del cargo del Profesor
			$cargo0 = mysqli_query($db_con, "select cargo, departamento, idea from departamentos where nombre = '$profe'" );
			$cargo1 = mysqli_fetch_array($cargo0);
			$_SESSION['cargo'] = $cargo1[0];
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
			
			// Registramos la sesión
			mysqli_query($db_con, "INSERT INTO reg_intranet (profesor, fecha, ip, useragent) VALUES ('".$_SESSION['ide']."','".date('Y-m-d H:i:s')."','".$direccionIP."', '".$useragent."')");
			$id_reg = mysqli_query($db_con, "SELECT id FROM reg_intranet WHERE profesor = '".$_SESSION['ide']."' ORDER BY id DESC LIMIT 1" );
			$id_reg0 = mysqli_fetch_array ( $id_reg );
			$_SESSION['id_pag'] = $id_reg0[0];

			session_regenerate_id(true);

			if (! $_SESSION['session_seneca'] && $datosIntranet['dni'] == $hash_clave) {
				$_SESSION['cambiar_clave'] = 1;
				header("location:usuario.php?tab=cuenta&pane=password");
				exit();
			}
			else {
				header("location:index.php");
				exit();
			}

		}
		// Cualquier problema que impida crear la sesión
		else {
			if (! isset($msg_error)) {
				$msg_error = "Usuario incorrecto";
			}
		}

	}
	// El usuario no existe o no es de este centro educativo
	if (! isset($msg_error)) {
		$msg_error = "Usuario incorrecto";
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
	<link href="//<?php echo $config['dominio']; ?>/intranet/css/login.css" rel="stylesheet">
	<?php if (isset($background_color)): ?>
	<style type="text/css">
	canvas { background-color: <?php echo $background_color; ?> !important; }
	</style>
	<?php endif; ?>
</head>

<body id="login">

	<div class="container">

		<div class="">
			<h1 class="text-center"><?php echo $config['centro_denominacion']; ?></h1>
		</div>

	    <div class="card card-container animated zoomIn faster">
			<div class="text-center text-muted">
				<i class="far fa-user-circle fa-7x"></i>
				<h4>Inicia sesión para acceder</h4>
			</div>

	        <form class="form-signin" method="post" autocomplete="off">
				<?php if($msg_error): ?>
				<p class="text-error text-danger"><?php echo $msg_error; ?></p>
				<?php endif; ?>

				<div class="form-group">
					<input type="text" id="USUARIO" name="USUARIO" class="form-control" placeholder="Nombre de usuario" required autofocus>
				</div>
				<div class="form-group">
					<input type="password" id="CLAVE" name="CLAVE" class="form-control" placeholder="Contraseña" required>
					<input type="hidden" id="CLAVECIFRADA" name="CLAVECIFRADA" value="">
				</div>

	            <button type="submit" class="btn btn-primary btn-block btn-signin" name="submit">Iniciar sesión</button>

				<a href="#" id="forgot-password" class="forgot-password">¿Olvidó su contraseña?</a>
	        </form><!-- /form -->

			<p class="copyright text-muted text-center">&copy; <?php echo date('Y'); ?> I.E.S. Monterroso - Versión <?php echo INTRANET_VERSION; ?></p>

	    </div><!-- /card-container -->
	</div><!-- /container -->

	<script src="//<?php echo $config['dominio']; ?>/intranet/js/jquery-2.1.1.min.js"></script>
	<script src="//<?php echo $config['dominio']; ?>/intranet/js/bootstrap.min.js"></script>
	<script src="//<?php echo $config['dominio']; ?>/intranet/js/cifrado.js"></script>
	<!-- particles.js lib - https://github.com/VincentGarreau/particles.js -->
	<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
	<script>
	particlesJS.load('login', 'js/particles/particlesjs-config.json', function() {});
	</script>

	<?php if($msg_error): ?>
	<script>$("#form-group").addClass( "has-error" );</script>
	<?php endif; ?>
	<script>
	$(document).ready(function () {

		$("#CLAVE").keyup(function() {
			var CLAVE = $('#CLAVE').val();
			var CLAVECIFRADA = cifrar(CLAVE);
			$("#CLAVECIFRADA").val(CLAVECIFRADA);
		});

		$("#forgot-password").click(function () {
	    	window.open("https://www.juntadeandalucia.es/educacion/seneca/seneca/jsp/general/DetOlvCon.jsp", "popupWindow", "width=600, height=420, scrollbars=yes");
    	});
	});
	</script>

</body>
</html>
