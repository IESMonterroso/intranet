<?php if (! defined('INC_TUTORIA')) die ('<h1>Forbidden</h1>'); ?>

<!-- MENSAJES DE TUTORIA -->

<h3>Mensajes de tutor�a</h3>

<?php setlocale(LC_ALL, 'es_ES'); ?>
<?php $result = mysqli_query($db_con, "SELECT id, ahora, apellidos, nombre, asunto, texto FROM alma, mensajes WHERE alma.claveal = mensajes.claveal AND mensajes.unidad = '".$_SESSION['mod_tutoria']['unidad']."' AND DATE(ahora) > '$inicio_curso' ORDER BY id DESC"); ?>

<?php if (mysqli_num_rows($result) > 0): ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th>De</th>
			<th>Fecha</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		<?php while ($row = mysqli_fetch_array($result)): ?>
		<tr>
			<td><a href="#mensaje<?php echo $i; ?>" data-toggle="modal"><?php echo $row['nombre'].' '.$row['apellidos']; ?></a></td>
			<td><?php echo strftime('%e %b',strtotime($row['ahora'])); ?></td>
		</tr>
		<?php $i++; ?>
		<?php endwhile; ?>
	</tbody>
</table>

<!-- MODAL -->
<?php $i = 1; ?>
<?php mysqli_data_seek($result,0); ?>
<?php while ($row = mysqli_fetch_array($result)): ?>
<div id="mensaje<?php echo $i; ?>" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
        <h4 class="modal-title"><?php echo $row['asunto']; ?><br /><small>Enviado por <?php echo $row['nombre'].' '.$row['apellidos']; ?> el <?php echo strftime('%A, %e de %B',strtotime($row['ahora'])); ?>.</small></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $row['texto']; ?></p>
      </div>
      <div class="modal-footer">
        <form method="post" name="mensaje_enviado" action="index.php" class="form-inline">
        	<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        <a class="btn btn-primary" href="../mensajes/redactar.php?padres=1&asunto=<?php echo $row['asunto']; ?>&origen=<?php echo $row['apellidos'].', '.$row['nombre']; ?>">Responder</a>
	        <input type="hidden" name="id_ver" value="<?php echo $id; ?>">
	      </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php $i++; ?>
<?php endwhile; ?>
<?php mysqli_free_result($result); ?>

<?php else: ?>

<br>
<p class="lead text-muted">No hay mensajes para el tutor.</p>
<br>

<?php endif; ?>

<!-- FIN MENSAJES DE TUTORIA -->