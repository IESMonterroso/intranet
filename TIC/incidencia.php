<?php
require('../bootstrap.php');
require('inc_variables.php');

if (file_exists('config.php')) {
	include('config.php');
}

// Obtenemos los datos de la incidencia si se trata de una actualización de datos
if (isset($_GET['id']) && intval($_GET['id'])) {
    $id_ticket = $_GET['id'];

    $result = mysqli_query($db_con, "SELECT `fecha`, `solicitante`, `dependencia`, `problema`, `descripcion`, `estado`, `numincidencia`, `resolucion` FROM `incidencias_tic` WHERE `id` = $id_ticket LIMIT 1") or die (mysqli_error($db_con));
    if (! mysqli_num_rows($result)) {
        header("Location:"."index.php");
        exit();
    }
    else {
        $row = mysqli_fetch_array($result);

        if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento' || $_SESSION['ide'] == $row['solicitante']) {
            $fecha_sql = $row['fecha'];
            $exp_fecha = explode('-', $row['fecha']);
            $fecha = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
            $solicitante = $row['solicitante'];
            $dependencia = $row['dependencia'];
            $array_tipoproblema = obtener_problema_por_id_asunto($row['problema'], $tipos_incidencia);
            $tipoproblema = $array_tipoproblema['problema'];
            $asunto = $row['problema'];
            $descripcion = $row['descripcion'];

            $estado = $row['estado'];
            $cga_nincidencia = $row['numincidencia'];
            $resolucion = $row['resolucion'];
        }
        else {
            header("Location:"."index.php");
            exit();
        }

    }
}

// Procesando variables del formulario
if (isset($_POST['fecha'])) $fecha = mysqli_real_escape_string($db_con, $_POST['fecha']);
if (isset($_POST['solicitante'])) $solicitante = mysqli_real_escape_string($db_con, $_POST['solicitante']);
if (isset($_POST['dependencia'])) $dependencia = mysqli_real_escape_string($db_con, $_POST['dependencia']);
if (isset($_POST['tipoproblema'])) $tipoproblema = $_POST['tipoproblema'];
if (isset($_POST['asunto'])) $asunto = $_POST['asunto'];
if (isset($_POST['descripcion'])) $descripcion = mysqli_real_escape_string($db_con, trim($_POST['descripcion']));

if (isset($_POST['estado'])) $estado = $_POST['estado'];
if (isset($_POST['cga_nincidencia'])) $cga_nincidencia = mysqli_real_escape_string($db_con, trim($_POST['cga_nincidencia']));
if (isset($_POST['resolucion'])) $resolucion = mysqli_real_escape_string($db_con, trim($_POST['resolucion']));

// Envío del formulario
if (isset($_POST['registrar']) || isset($_POST['registrar-y-notificar'])) {

    if (! empty($fecha) && ! empty($solicitante) && ! empty($dependencia) && ! empty($asunto)) {

        // Si ha seleccionado la opción "Otros..." de cualquier categoria, se comprueba que ha rellenado el campo descripción
        if (substr($asunto, -2, 2) != '00' || (substr($asunto, -2, 2) == '00' && ! empty($descripcion))) {
            $exp_fecha = explode('-', $fecha);
            $fecha_sql = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
            $fecha_estado = date('Y-m-d');

            // Comprobamos si se trata de una actualización o nuevo registro
            if (isset($id_ticket)) {
                if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento') {
                    $result = mysqli_query($db_con, "UPDATE `incidencias_tic` SET `fecha` = '".$fecha_sql."', `solicitante` = '".$solicitante."', `dependencia` = '".$dependencia."', `problema` = ".$asunto.", `descripcion` = '".$descripcion."', `estado` = '".$estado."', `fecha_estado` = '".$fecha_estado."', `numincidencia` = '".$cga_nincidencia."', `resolucion` = '".$resolucion."' WHERE `id` = $id_ticket LIMIT 1");

                    // Notificamos al solicitante de los cambios realizados en la incidencia mediante un mensaje interno
                    if (isset($_POST['registrar-y-notificar']) && ((! isset($config['tic']['notificaciones_solicitante']) || $config['tic']['notificaciones_solicitante']) && $estado != 1 && ! empty($resolucion))) {
                        $notificacion_idea_coordinador = (! empty($config['tic']['coordinador'])) ? obtener_idea_por_nombre_profesor($config['tic']['coordinador']) : 'admin';
                        $notificacion_array_tipoproblema = obtener_problema_por_id_asunto($asunto, $tipos_incidencia);
                        $notificacion_tipoproblema = $notificacion_array_tipoproblema['asunto'];
                        $notificacion_mensaje = "<p>El estado de la incidencia que registró el día $fecha, con número de caso <strong>#$id_ticket</strong> con el asunto $notificacion_tipoproblema ha sido actualizado.</p><p><br></p><p><a href=\"//".$config['dominio']."/intranet/TIC/incidencia.php?id=$id_ticket\" class=\"btn btn-info\" target=\"_blank\">Ver incidencia</a></p><p><br></p>";

                        $notificacion_sql = "INSERT INTO `mens_texto` (`origen`, `asunto`, `texto`, `destino`, `oculto`) VALUES ('$notificacion_idea_coordinador', 'Registro de incidencia TIC #$id_ticket', '$notificacion_mensaje', '$solicitante', 0)";
                        mysqli_query($db_con, $notificacion_sql) or die (mysqli_error($db_con));
                        $id_mens_texto = mysqli_insert_id($db_con);
                        mysqli_query($db_con, "INSERT INTO `mens_profes` (`id_texto`, `profesor`, `recibidoprofe`, `recibidojefe`) VALUES ($id_mens_texto, '$solicitante', 0, 0)") or die (mysqli_error($db_con));
                    }

                }
                else {
                    $result = mysqli_query($db_con, "UPDATE `incidencias_tic` SET `dependencia` = '".$dependencia."', `problema` = ".$asunto.", `descripcion` = '".$descripcion."' WHERE `id` = $id_ticket;");
                }

                if (! $result) {
                    $msg_error = true;
                    $msg_error_text = "Ha ocurrido un error al actualizar la incidencia en la base de datos. Error: ".mysqli_error($db_con);
                }
                else {
                    header("Location:"."index.php");
                    exit();
                }
            }
            else {
                if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento') {
                    $result = mysqli_query($db_con, "INSERT INTO `incidencias_tic` (`fecha`, `solicitante`, `dependencia`, `problema`, `descripcion`, `estado`, `fecha_estado`, `numincidencia`, `resolucion`) VALUES ('".$fecha_sql."', '".$solicitante."', '".$dependencia."', ".$asunto.", '".$descripcion."', '".$estado."', '".$fecha_estado."', '".$cga_nincidencia."', '".$resolucion."')");

                }
                else {
                    $result = mysqli_query($db_con, "INSERT INTO `incidencias_tic` (`fecha`, `solicitante`, `dependencia`, `problema`, `descripcion`, `estado`) VALUES ('".$fecha_sql."', '".$solicitante."', '".$dependencia."', ".$asunto.", '".$descripcion."', 1)");
                }

                $id_ticket_nuevo = mysqli_insert_id($db_con);

                if (! $result) {
                    $msg_error = true;
                    $msg_error_text = "Ha ocurrido un error al registrar la incidencia en la base de datos. Error: ".mysqli_error($db_con);
                }
                else {

                    // Notificamos al coordinador TIC mediante un mensaje interno
                    if (! isset($config['tic']['notificaciones']) || $config['tic']['notificaciones']) {
                        $notificacion_idea_coordinador = (! empty($config['tic']['coordinador'])) ? obtener_idea_por_nombre_profesor($config['tic']['coordinador']) : 'admin';
                        $notificacion_array_tipoproblema = obtener_problema_por_id_asunto($asunto, $tipos_incidencia);
                        $notificacion_tipoproblema = $notificacion_array_tipoproblema['asunto'];
                        $notificacion_mensaje = "<p>El profesor/a <strong>$solicitante</strong> ha registrado una incidencia el día $fecha, con número de caso <strong>#$id_ticket_nuevo</strong> con el siguiente asunto:</p><p><strong>Dependencia:</strong> $dependencia</p><p><strong>$notificacion_tipoproblema</strong></p><p>$descripcion</p><p><br></p><p><a href=\"//".$config['dominio']."/intranet/TIC/incidencia.php?id=$id_ticket_nuevo\" class=\"btn btn-info\" target=\"_blank\">Ver incidencia</a></p><p><br></p>";

                        $notificacion_sql = "INSERT INTO `mens_texto` (`origen`, `asunto`, `texto`, `destino`, `oculto`) VALUES ('admin', 'Registro de incidencia TIC #$id_ticket_nuevo', '$notificacion_mensaje', '$notificacion_idea_coordinador', 0)";
                        mysqli_query($db_con, $notificacion_sql) or die (mysqli_error($db_con));
                        $id_mens_texto = mysqli_insert_id($db_con);
                        mysqli_query($db_con, "INSERT INTO `mens_profes` (`id_texto`, `profesor`, `recibidoprofe`, `recibidojefe`) VALUES ($id_mens_texto, '$notificacion_idea_coordinador', 0, 0)") or die (mysqli_error($db_con));
                    }

                    header("Location:"."index.php");
                    exit();
                }
            }

        }
        else {
            $msg_error = true;
            $msg_error_text = "Debe rellenar el campo <strong>Descripción de la incidencia</strong>";
        }

    }
    else {
        $msg_error = true;
        $msg_error_text = "Debe rellenar los campos obligatorios marcados con (*).";
    }

}

include("../menu.php");
include("menu.php");
?>

    <div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small><?php echo (! isset($id_ticket)) ? 'Nueva incidencia' : 'Incidencia #'.$id_ticket; ?></small></h2>
		</div>

        <?php if (isset($msg_error) && $msg_error): ?>
        <div class="alert alert-danger">
            <?php echo $msg_error_text; ?>
        </div>
        <?php endif; ?>

        <div class="row">

            <div class="col-sm-12">

                <div class="well">

                    <form action="" method="POST">

                        <fieldset>
                            <legend><?php echo (! isset($id_ticket)) ? 'Nueva incidencia' : 'Incidencia #'.$id_ticket; ?></legend>

                            <div class="row">

                                <div class="col-sm-3">
                                    <div class="form-group" id="datetimepicker1">
                                        <label for="fecha">Fecha <span class="text-danger">(*)</span></label>
                                        <div class="input-group">
						  			 	    <input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo (isset($fecha) && !empty($fecha)) ? $fecha : date('d-m-Y'); ?>" data-date-format="DD-MM-YYYY" <?php echo (isset($id_ticket)) ? 'readonly' : ''; ?>>
						  			        <span class="input-group-addon">
						  			  	        <span class="far fa-calendar fa-fw"></span>
						  			        </span>
						  			    </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="solicitante">Solicitante <span class="text-danger">(*)</span></label>
                                        <?php if ($pr == 'Administrador' || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento'): ?>
                                        <select class="form-control" id="solicitante" name="solicitante">
                                            <?php $result = mysqli_query($db_con, "SELECT nombre, idea FROM departamentos ORDER BY nombre ASC"); ?>
                                            <?php while ($row = mysqli_fetch_array($result)): ?>
                                            <option value="<?php echo $row['idea']; ?>"<?php echo (isset($solicitante) && $solicitante == $row['idea']) ? ' selected' : ''; ?>><?php echo $row['nombre']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                        <?php else: ?>
                                        <input type="hidden" class="form-control" id="solicitante" name="solicitante" value="<?php echo $idea; ?>">
                                        <p class="form-control-static"><?php echo $pr; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <label for="dependencia">Aula o dependencia <span class="text-danger">(*)</span></label>
                                        <?php $result = mysqli_query($db_con, "SELECT nomdependencia FROM dependencias ORDER BY nomdependencia ASC"); ?>
                                        <?php if (mysqli_num_rows($result)): ?>
                                        <select class="form-control" id="dependencia" name="dependencia"<?php echo ($pr == 'Administrador' || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador'])) ? ' onchange="submit()"' : ''; ?>>
                                            <option value=""></option>
                                            <?php while ($row = mysqli_fetch_array($result)): ?>
                                            <option value="<?php echo $row['nomdependencia']; ?>"<?php echo (isset($dependencia) && $dependencia == $row['nomdependencia']) ? ' selected' : ''; ?>><?php echo $row['nomdependencia']; ?></value>
                                            <?php endwhile; ?>
                                        </select>
                                        <?php else: ?>
                                        <input type="text" class="form-control" id="dependencia" name="dependencia" placeholder="P. ej.: Aula de 1ºA, Aula 101, Sala de profesores" value="<?php echo (isset($dependencia) && !empty($dependencia)) ? $dependencia : ''; ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div><!-- /.row -->

                            <div class="form-group">
                                <label for="tipoproblema">Tipo de problema <span class="text-danger">(*)</span></label>
                                <select class="form-control" id="tipoproblema" name="tipoproblema" onchange="submit()">
                                    <option value=""></option>
                                    <?php foreach ($tipos_incidencia as $grupo_incidencias => $array_incidencias): ?>
                                    <option value="<?php echo $grupo_incidencias; ?>"<?php echo (isset($tipoproblema) && $tipoproblema == $grupo_incidencias) ? ' selected' : ''; ?>><?php echo $grupo_incidencias; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                             <div class="form-group">
                                <label for="asunto">Asunto <span class="text-danger">(*)</span></label>
                                <select class="form-control" id="asunto" name="asunto" onchange="submit()" <?php echo (!isset($tipoproblema) || empty($tipoproblema)) ? 'disabled' : ''; ?>>
                                    <?php foreach ($tipos_incidencia[$tipoproblema] as $incidencia_id => $incidencia_descripcion): ?>
                                    <option value="<?php echo $incidencia_id; ?>"<?php echo (isset($asunto) && $asunto == $incidencia_id) ? ' selected' : ''; ?>><?php echo $incidencia_descripcion; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="descripcion">Descripción de la incidencia <?php if (isset($asunto) && substr($asunto, -2, 2) == '00'): ?><span class="text-danger">(*)</span><?php endif; ?></label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="5" placeholder="Describa brevemente el problema para ayudar a su resolución. Si hay varios ordenadores en el aula, recuerda indicar qué ordenador presenta el problema."><?php echo (isset($descripcion) && !empty($descripcion)) ? $descripcion : ''; ?></textarea>
                            </div>

                            <p class="text-danger"><small>Si el problema ha sido causado por el mal uso que le ha dado un alumno/a, registra el incidente en el modulo de Problemas de Convivencia.</small></p>

                        </fieldset>

                        <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento'): ?>
                        <hr>

                        <fieldset>

                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="estado">Estado de la incidencia</label>
                                        <select class="form-control" id="estado" name="estado">
                                            <?php foreach ($estados_incidencia as $estado_id => $estado_descripcion): ?>
                                            <option value="<?php echo $estado_id; ?>"<?php echo (isset($estado) && $estado == $estado_id) ? ' selected' : ''; ?>><?php echo $estado_descripcion; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="cga_nincidencia">Nº Incidencia (CAUCE-CGA) <span class="fas fa-question-circle fa-lg fa-fw" data-bs="tooltip" data-html="yes" title="Teléfonos: <br>300 300 | 955 06 10 71"></span></label>
                                        <input type="text" class="form-control" id="cga_nincidencia" name="cga_nincidencia" value="<?php echo (isset($cga_nincidencia) && !empty($cga_nincidencia)) ? $cga_nincidencia : ''; ?>">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Inventario del aula</label>
                                        <p class=""><a href="//<?php echo $config['dominio']; ?>/intranet/TIC/inventario.php?localizacion=<?php echo $dependencia; ?>" class="btn btn-default btn-block" target="_blank">Consultar inventario TIC del aula</a></p>
                                    </div>
                                </div>

                            </div><!-- /.row -->

                             <div class="form-group">
                                <label for="resolucion">Resolución de la incidencia</label>
                                <textarea class="form-control" id="resolucion" name="resolucion" rows="5" placeholder=""><?php echo (isset($resolucion) && !empty($resolucion)) ? $resolucion : ''; ?></textarea>
                            </div>

                        </fieldset>
                        <?php elseif (isset($id_ticket)): ?>
                        <hr>

                        <fieldset>

                            <div class="row">

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="estado">Estado de la incidencia</label>
                                        <?php foreach ($estados_incidencia as $estado_id => $estado_descripcion): ?>
                                        <?php if (isset($estado) && $estado == $estado_id): ?>
                                        <p class="form-control-static"><?php echo $estado_descripcion; ?></p>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <?php if (isset($cga_nincidencia) && !empty($cga_nincidencia)): ?>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label for="cga_nincidencia">Nº Incidencia (CAUCE-CGA)</label>
                                        <p class="form-control-static">Incidencia tramitada al CGA con nº <?php echo $cga_nincidencia; ?></p>
                                    </div>
                                </div>
                                <?php endif; ?>

                            </div><!-- /.row -->

                            <?php if (isset($resolucion) && !empty($resolucion)): ?>
                            <div class="form-group">
                                <label for="resolucion">Resolución de la incidencia</label>
                                <p class="form-control-static"><?php echo (isset($resolucion) && !empty($resolucion)) ? $resolucion : ''; ?></p>
                            </div>
                            <?php endif; ?>

                            <br>

                        </fieldset>
                        <?php endif; ?>

                        <?php if (!isset($estado) || $estado == 1 || acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento'): ?>
                        <button type="submit" class="btn btn-primary" name="registrar"><?php echo (! isset($id_ticket)) ? 'Registrar incidencia' : 'Guardar cambios'; ?></button>
                        <?php if (isset($id_ticket) && (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento') && (! isset($config['tic']['notificaciones_solicitante']) || $config['tic']['notificaciones_solicitante'] == 1)): ?>
                        <button type="submit" class="btn btn-primary" name="registrar-y-notificar">Guardar cambios y notificar</button>
                        <?php endif; ?>
                        <?php endif; ?>
                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php include("../pie.php"); ?>

    <script>
	$(function() {
		$('#datetimepicker1').datetimepicker({
			language: 'es',
			pickTime: false
		});
	});
    </script>

</body>
</html>
