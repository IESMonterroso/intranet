<?php if (! defined('INC_TUTORIA')) die ('<h1>Forbidden</h1>'); ?>

<!-- PROBLEMAS DE CONVIVENCIA -->

<h3>Problemas de convivencia</h3>

<?php $result = mysql_query("SELECT FALUMNOS.apellidos, FALUMNOS.nombre, FALUMNOS.claveal FROM FALUMNOS WHERE unidad='$unidad'"); ?>

<?php if (mysql_num_rows($result)): ?>
<table class="table table-hover" style="margin-bottom: 5px">
	<thead>
		<tr>
			<th>Alumno/a</th>
			<th>Fecha</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
		<?php while ($row = mysql_fetch_array($result)): ?>
		<?php $result1 = mysql_query("SELECT DISTINCT Fechoria.fecha, Fechoria.grave FROM Fechoria WHERE claveal='".$row['claveal']."' ORDER BY Fechoria.fecha DESC LIMIT 1"); ?>
		<?php while ($row2 = mysql_fetch_array($result1)): ?>
		<tr>
			<td><a href="../fechorias/fechorias.php?claveal=<?php echo $row['claveal']; ?>&submit1=1"><?php echo $row['nombre'].' '.$row['apellidos']; ?></a></td>
			<td><span class="<?php echo (stristr($row2['grave'], 'grave') == true) ? 'text-danger' : 'text-info'; ?>"><?php echo strftime('%e %b',strtotime($row2['fecha'])); ?></span></td>
			<?php $result2 = mysql_query("SELECT Fechoria.claveal FROM Fechoria WHERE Fechoria.claveal='".$row['claveal']."'"); ?>
			<td class="text-center"><div class="badge"><?php echo mysql_num_rows($result2); ?></div></td>
		</tr>
		<?php mysql_free_result($result2); ?>
		<?php endwhile; ?>
		<?php mysql_free_result($result1); ?>
		<?php endwhile; ?>
		<?php mysql_free_result($result); ?>
	</tbody>
</table>

<p><small class="text-muted">En azul problemas leves y en rojo graves y muy graves</small></p>
<?php endif; ?>

<!-- FIN PROBLEMAS DE CONVIVENCIA -->
