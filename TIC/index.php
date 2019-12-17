<?php
require('../bootstrap.php');
require('inc_variables.php');

if (file_exists('config.php')) {
	include('config.php');
}

$id = $_GET['id'];
$estado = $_GET['estado'];

$id = preg_replace('([^0-9])', '', $id);

$estado = preg_replace('([^A-Za-z0-9])', '', $estado);

// Cambio de estado rápido y eliminado
if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento') {
    if (isset($_GET['id']) && intval($id) && isset($_GET['estado']) && intval($estado)) {
        $result = mysqli_query($db_con, "UPDATE `incidencias_tic` SET `estado` = '".$estado."' WHERE `id` = '".$id."' LIMIT 1");
        if (! $result) {
            $msg_error = true;
            $msg_error_text = "Ha ocurrido un error al actualizar el estado de la incidencia. Error: ".mysqli_error($db_con);
        }
        else {
            header("Location:"."index.php");
            exit();
        }
    }

    if (isset($_GET['id']) && intval($id) && isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
        $result = mysqli_query($db_con, "DELETE FROM `incidencias_tic` WHERE `id` = '".$id."' LIMIT 1");
        if (! $result) {
            $msg_error = true;
            $msg_error_text = "Ha ocurrido un error al eliminar la incidencia. Error: ".mysqli_error($db_con);
        }
        else {
            header("Location:"."index.php");
            exit();
        }
    }

}
else {
	// Eliminado de incidencias para profesores
	if (isset($_GET['id']) && intval($id) && isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
			$result = mysqli_query($db_con, "SELECT `estado`, `solicitante` FROM `incidencias_tic` WHERE `id` = '".$id."' LIMIT 1");
			$row = mysqli_fetch_array($result);

			if ($row['solicitante'] == $idea) {
				if ($row['estado'] == 1) {
					$result = mysqli_query($db_con, "DELETE FROM `incidencias_tic` WHERE `id` = '".$id."' LIMIT 1");
					if (! $result) {
							$msg_error = true;
							$msg_error_text = "Ha ocurrido un error al eliminar la incidencia. Error: ".mysqli_error($db_con);
					}
					else {
							header("Location:"."index.php");
							exit();
					}
				}
				else {
					$msg_error = true;
					$msg_error_text = "No puede eliminar una incidencia en curso o cerrada.";
				}
			}
			else {
				$msg_error = true;
				$msg_error_text = "No tiene privilegios para eliminar la incidencia.";
			}
	}
}

// Obtenemos las incidencias registradas
$result = mysqli_query($db_con, "SELECT `id`, `fecha`, `solicitante`, `dependencia`, `problema`, `descripcion`, `estado`, `numincidencia`, `resolucion` FROM `incidencias_tic` ORDER BY estado ASC, id DESC");

$array_incidencias = array();
$array_incidencias_estadisticas = array(1 => 0, 2 => 0, 3 => 0, 4 => 0);

while ($row = mysqli_fetch_array($result)) {
    $array_incidencias_estadisticas[$row['estado']]++;

    $exp_fecha = explode('-', $row['fecha']);
    $row['fecha'] = $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
    $problema = obtener_problema_por_id_asunto($row['problema'], $tipos_incidencia);
    $row['problema'] = $problema['asunto'];
    $row['solicitante'] = obtener_nombre_profesor_por_idea($row['solicitante']);
    array_push($array_incidencias, $row);
}

$PLUGIN_DATATABLES = 1;
include("../menu.php");
include("menu.php");
?>

    <div class="container">

		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Centro TIC <small>Incidencias TIC</small></h2>
		</div>

        <?php if (isset($msg_error) && $msg_error): ?>
        <div class="alert alert-danger">
            <?php echo $msg_error_text; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-3 col-xs-6">
                <h3 class="text-info text-center">
                    <?php echo $array_incidencias_estadisticas[1]; ?><br>
                    <small class="text-uppercase"><span class="hidden-xs">Incidencias </span>abiertas</small>
                </h3>
            </div>

            <div class="col-sm-3 col-xs-6">
                <h3 class="text-info text-center">
                    <?php echo $array_incidencias_estadisticas[2]; ?><br>
                    <small class="text-uppercase"><span class="hidden-xs">Incidencias </span>en curso</small>
                </h3>
            </div>

            <div class="col-sm-3 col-xs-6">
                <h3 class="text-info text-center">
                    <?php echo $array_incidencias_estadisticas[3]; ?><br>
                    <small class="text-uppercase"><span class="hidden-xs">Incidencias </span>cerradas</small>
                </h3>
            </div>

            <div class="col-sm-3 col-xs-6">
                <h3 class="text-info text-center">
                    <?php echo $array_incidencias_estadisticas[4]; ?><br>
                    <small class="text-uppercase"><span class="hidden-xs">Incidencias </span>canceladas</small>
                </h3>
            </div>
        </div>

        <br>

        <div class="row">

            <div class="col-sm-12">

                <table class="table table-bordered table-striped datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Aula</th>
                            <th>Problema</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($array_incidencias as $incidencia): ?>
                        <tr>
                            <td data-order="<?php echo $incidencia['estado']; ?>">
                                #<?php echo $incidencia['id']; ?>

                                <div style="margin-top: 10px;">
                                    <?php
                                    switch ($incidencia['estado']) {
                                        case 1 : $btn_estado = 'btn-warning'; break;
                                        case 2 : $btn_estado = 'btn-info'; break;
                                        case 3 : $btn_estado = 'btn-default'; break;
                                        case 4 : $btn_estado = 'btn-danger'; break;
                                    }
                                    ?>
                                    <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento'): ?>
                                    <div class="btn-group">
                                        <button type="button" class="btn <?php echo $btn_estado; ?> btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?php echo $estados_incidencia[$incidencia['estado']]; ?> <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php foreach ($estados_incidencia as $id_estado => $estado): ?>
                                            <?php if ($incidencia['estado'] != $id_estado): ?>
                                            <li><a href="?id=<?php echo $incidencia['id']; ?>&estado=<?php echo $id_estado; ?>"><?php echo $estado; ?></a></li>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php else: ?>
                                    <button type="button" class="btn <?php echo $btn_estado; ?> btn-sm" style="margin-top: 10px;"><?php echo $estados_incidencia[$incidencia['estado']]; ?></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><?php echo $incidencia['dependencia']; ?></td>
                            <td>
                                <?php  ?>
                                <strong><?php echo $incidencia['problema']; ?></strong>
                                <p class="text-muted"><small><?php echo $incidencia['descripcion']; ?></small></p>
                                <p class="text-muted"><small><strong>Solicitante: <?php echo $incidencia['solicitante']; ?></strong> &middot; <strong>Fecha: <?php echo $incidencia['fecha']; ?></strong></small></p>
                            </td>
                            <td nowrap>
                                <?php if (acl_permiso($_SESSION['cargo'], array('1')) || (isset($config['tic']['coordinador']) && $pr == $config['tic']['coordinador']) || $_SESSION['dpt'] == 'Servicio Técnico y/o Mantenimiento' || ($pr == $incidencia['solicitante'] && $incidencia['estado'] == 1)): ?>
                                <a href="incidencia.php?id=<?php echo $incidencia['id']; ?>" class="btn btn-sm btn-default"><span class="far fa-edit fa-lg fa-fw"></span></a>
                                <a href="?id=<?php echo $incidencia['id']; ?>&accion=eliminar" class="btn btn-sm btn-danger" data-bb="confirm-delete"><span class="far fa-trash-alt fa-lg fa-fw"></span></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

        </div>

    </div>

    <?php include("../pie.php"); ?>

    <script>
    $(document).ready(function() {
    var table = $('.datatable').DataTable({
        "paging":   true,
        "ordering": true,
        "info":     false,

            "lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],

            "order": [[ 0, "asc" ]],

            "language": {
                        "lengthMenu": "_MENU_",
                        "zeroRecords": "No se ha encontrado ningún resultado con ese criterio.",
                        "info": "PÃ¡gina _PAGE_ de _PAGES_",
                        "infoEmpty": "No hay resultados disponibles.",
                        "infoFiltered": "(filtrado de _MAX_ resultados)",
                        "search": "Buscar: ",
                        "paginate": {
                            "first": "Primera",
                            "next": "Ãltima",
                            "next": "",
                            "previous": ""
                            }
                    }
        });
    });
    </script>

</body>
</html>
