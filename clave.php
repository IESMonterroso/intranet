<?php
require('bootstrap.php');


function validarContrasena($contrasena) {
	$result = preg_match("((?=.*\d)(?=.*[a-z])(?=.*[A-z])(?=.*[!\"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]).{8,20})", $contrasena);

	return $result;
}

function validarCorreo($correo) {
	$result = filter_var($correo, FILTER_VALIDATE_EMAIL);

	return $result;
}

// VALIDAMOS EL FORMULARIO
if (isset($_POST['submit'])) {

	$usuario = $_POST['usuario'];
	$codigo2 = $_POST['codigo2'];
	$codigo3 = $_POST['codigo3'];
	$correo  = $_POST['correo'];
	if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    		$correo = 'NULL'; //Comment: ADD HERE AN ALERT OR SOMETHING ALERTING USER EMAIL IS NOT VALID
	}
	$movil  = (isset($_POST['movil']) && ! empty($_POST['movil'])) ? $_POST['movil'] : '';
	$movil = preg_replace('([^0-9])', '', $movil);
	$mostrar_nombre = (isset($_POST['mostrar_nombre']) && $_POST['mostrar_nombre'] == 1) ? 1 : 0;

	$codigo2_has_error = 0;
	$codigo3_has_error = 0;
	$correo_has_error = 0;

	// Comprobamos que los campos no estén vacíos
	if(empty($correo) || empty($codigo2) || empty($codigo3)) {
		$msg_error = "Todos los campos del formulario son obligatorios.";
		$codigo2_has_error = 1;
		$codigo3_has_error = 1;
		$correo_has_error = 1;
	}
	else {
		// Comprobamos si se cumple los requisitos de seguridad de la contraseña
		if (! validarContrasena($codigo2)) {
			$msg_error = "La contraseña no cumple con los requisitos de seguridad.";
			$codigo2_has_error = 1;
		}
		else {
			// Comprobamos que las contraseñas coinciden
			if($codigo2 !== $codigo3) {
				$msg_error = "Las contraseñas no coinciden.";
				$codigo2_has_error = 1;
				$codigo3_has_error = 1;
			}
			else {
				// Comprobamos que la dirección de correo electrónico sea válida
				if(! validarCorreo($correo)) {
					$msg_error = "La dirección de correo electrónico no es válida.";
					$correo_has_error = 1;
				}
				elseif (! empty ($movil) && (! is_numeric($movil) || strlen($movil) != 9 || (substr($movil, 0, 1) != '6' && substr($movil, 0, 1) != '7'))) {
					$msg_error = "El número de teléfono móvil no es válido.";
					$movil_has_error = 1;
				}
				else {
					// Obtenemos el hash de la contraseña
					$hash = sha1($codigo2);

					$result = mysqli_query($db_con, "UPDATE c_profes SET pass='$hash', correo='$correo', telefono='$movil', rgpd_mostrar_nombre = '$mostrar_nombre' WHERE profesor='".$_SESSION['profi']."'");

					// Comprobamos si se ha relizado la consulta a la base de datos
					if(!$result) {
						$msg_error = "No se ha podido cambiar la contraseña, así que mejor te pongas en contacto con quien pueda arreglar el asunto.";
					}
					else {
						$_SESSION['cambiar_clave'] = 0;

						if (isset($_GET['tour']) && $_GET['tour']) {
							// Redirigimos a la página de autenticación en dos pasos
							header("Location:"."totp.php?tour=1");
						}
						else {
							// Redirigimos a la página principal
							header("Location:"."index.php");
						}
					}
				}
			}
		}
	}
}

$result = mysqli_query($db_con, "SELECT idea, correo, telefono, rgpd_mostrar_nombre FROM c_profes WHERE idea='".$_SESSION['ide']."' LIMIT 1");
$row = mysqli_fetch_assoc($result);

include("menu.php");
?>

	<div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2><?php echo (isset($_GET['tour']) && $_GET['tour']) ? 'Lo primero es lo primero. Cambie la contraseña y añada su correo electrónico.' : 'Cambiar la contraseña'; ?></h2>
		</div>

		<!-- MENSAJES -->
		<?php if(isset($msg_error)): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>

		<div class="row">

			<div class="col-sm-6">

				<div class="well">

					<form class="form-horizontal" method="post" action="">
						<fieldset>

							<div class="form-group">
						    <label for="usuario" class="col-sm-4 control-label">Usuario IdEA</label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo $row['idea']; ?>" readonly>
						    </div>
						  </div>

						  <div id="form-group-codigo2"  class="form-group">
						    <label for="codigo2" class="col-sm-4 control-label">Nueva contraseña</label>
						    <div class="col-sm-8">
						      <input type="password" class="form-control" id="codigo2" name="codigo2" placeholder="Nueva contraseña" maxlength="20">
						    </div>
						  </div>

						  <div id="form-group-codigo3"  class="form-group">
						    <label for="codigo3" class="col-sm-4 control-label">Repita la contraseña</label>
						    <div class="col-sm-8">
						      <input type="password" class="form-control" id="codigo3" name="codigo3" placeholder="Repita la contraseña" maxlength="20">
						    </div>
						  </div>

						  <div id="form-group-email" class="form-group">
						    <label for="correo" class="col-sm-4 control-label">Correo electrónico</label>
						    <div class="col-sm-8">
						      <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" value="<?php echo $row['correo'];?>" maxlength="64">
						    </div>
						  </div>

						  <div id="form-group-movil" class="form-group">
						    <label for="movil" class="col-sm-4 control-label">Teléfono móvil</label>
						    <div class="col-sm-8">
						      <input type="text" class="form-control" id="movil" name="movil" placeholder="Número de móvil" value="<?php echo $row['telefono'];?>" maxlength="9">
						    </div>
						  </div>

							<div class="checkbox">
						    <label for="mostrar_nombre">
						      <input type="checkbox" id="mostrar_nombre" name="mostrar_nombre" value="1" <?php echo ($row['rgpd_mostrar_nombre'] == 1) ? 'checked' : ''; ?>> Acepto que la Dirección publique mi nombre y apellidos en la página Web del Centro.
								</label>
						  </div>

							<br>

							<div class="form-group">
							  <div class="col-sm-8 col-sm-offset-4">
							    <button type="submit" class="btn btn-primary" name="submit">Guardar cambios</button>
							  </div>
							</div>
						</fieldset>
					</form>

				</div><!-- /.well -->

			</div><!-- /.col-sm-6 -->


			<div class="col-sm-6">

				<h3>Utilice una contraseña segura</h3>

				<p>A partir de este momento tendrás que usar tu nombre de usuario IdEA y esta clave para acceder a la intranet. La dirección de correo electrónico se usará para las notificaciones y para reiniciar la contraseña en caso de olvido.</p>

				<p>La clave debe cumplir las siguientes condiciones:</p>

				<ul class="">
					<li>Tener al menos una longitud de 8 caracteres y 20 como máximo.</li>
					<li>Contener al menos una letra, un número y un signo de puntuación o un símbolo.</li>
					<li>Los símbolos aceptados son !"#$%&amp;'()*+,-./:;&raquo;=>?@[\]^_`{|}~</li>
					<li>Las letras acentuadas y las eñes no están admitidas.</li>
					<li>No ser similar al nombre de usuario.</li>
					<li>No ser similar a su D.N.I. o pasaporte.</li>
				</ul>

			</div>

		</div><!-- /.row -->

	</div><!-- /.container -->

<?php include("pie.php"); ?>

<?php if($codigo2_has_error): ?>
    <script>$("#form-group-codigo2").addClass( "has-error" );</script>
<?php endif; ?>
<?php if($codigo3_has_error): ?>
    <script>$("#form-group-codigo3").addClass( "has-error" );</script>
<?php endif; ?>
<?php if($correo_has_error): ?>
    <script>$("#form-group-email").addClass( "has-error" );</script>
<?php endif; ?>
<?php if($movil_has_error): ?>
    <script>$("#form-group-movil").addClass( "has-error" );</script>
<?php endif; ?>

</body>
</html>
