<?php
require('../../bootstrap.php');
include("../../menu.php");
?>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Alumnos y Grupos <small>Asignación de mesas</small></h2>
		</div>
		
		
		<!-- SCAFFOLDING -->
		<div class="row">
		
			<!-- COLUMNA CENTRAL -->
			<div class="col-sm-6 col-sm-offset-3">
				
				<div class="well">
					
					<form method="post" action="muestra_mesas.php">
						<fieldset>
							<legend>Seleccione grupo</legend>
							
							<div class="form-group">
						    <label for="tutor">Grupo - Tutor/a del grupo</label>
						    <?php $result = mysqli_query($db_con, "SELECT DISTINCT unidad, tutor FROM FTUTORES ORDER BY unidad ASC"); ?>
						    <?php if(mysqli_num_rows($result)): ?>
						    <select class="form-control" id="tutor" name="tutor" onChange="submit()">
						    	<option></option>
						    	<?php while($row = mysqli_fetch_array($result)): ?>
						    	<option value="<?php echo $row['tutor'].' ==> '.$row['unidad']; ?>"><?php echo $row['unidad'].' - '.nomprofesor($row['tutor']); ?></option>
						    	<?php endwhile; ?>
						    </select>
						    <?php else: ?>
						    <select class="form-control" id="tutor" name="tutor" disabled>
						    	<option value=""></option> 
						    </select>
						    <?php endif; ?>
						    <?php mysqli_free_result($result); ?>
						  </div>
						  
						   <!-- <button type="submit" class="btn btn-primary" name="enviar">Consultar</button> -->
					  </fieldset>
					</form>
					
				</div><!-- /.well -->
				
			</div><!-- /.col-sm-6 -->
			
		
		</div><!-- /.row -->
		
	</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>

</body>
</html>
