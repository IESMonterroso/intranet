<?php
session_start ();
include ("../../config.php");
if ($_SESSION ['autentificado'] != '1') {
	session_destroy ();
	header ( "location:http://$dominio/intranet/salir.php" );
	exit();
}
registraPagina ( $_SERVER ['REQUEST_URI'], $db_host, $db_user, $db_pass, $db );
$pr = $_SESSION['profi'];

if (isset($_POST['profes'])) {
	$profes = $_POST['profes'];
} 
elseif (isset($_GET['profes'])) {
	$profes = $_GET['profes'];
} 

if (isset($_POST['texto'])) {
	$texto = $_POST['texto'];
} 
elseif (isset($_GET['texto'])) {
	$texto = $_GET['texto'];
} 
$profeso = $_POST['profeso'];
$tutores = $_POST['tutores'];
$tutor = $_POST['tutor'];
$departamentos = $_POST['departamentos'];
$departamento = $_POST['departamento'];
$equipos = $_POST['equipos'];
$equipo = $_POST['equipo'];
$claustro = $_POST['claustro'];
$etcp = $_POST['etcp'];
$ca = $_POST['ca'];
$direccion = $_POST['direccion'];
$orientacion = $_POST['orientacion'];
$bilingue = $_POST['bilingue'];
$biblio = $_POST['biblio'];

$profesor = $_POST['profesor'];

if (isset($_POST['padres'])) {
	$padres = $_POST['padres'];
} 
elseif (isset($_GET['padres'])) {
	$padres = $_GET['padres'];
} 
else
{
$padres="";
}
if (isset($_POST['asunto'])) {
	$asunto = $_POST['asunto'];
} 
elseif (isset($_GET['asunto'])) {
	$asunto = $_GET['asunto'];
} 
else
{
$asunto="";
}
if (isset($_POST['origen'])) {
	$origen = $_POST['origen'];
} 
elseif (isset($_GET['origen'])) {
	$origen = $_GET['origen'];
} 
else
{
$origen="";
}

$verifica = $_GET['verifica'];
if($verifica){
 mysql_query("UPDATE mens_profes SET recibidoprofe = '1' WHERE id_profe = '$verifica'");
}

include("profesores.php");

include('../../menu.php');
include('menu.php');
$page_header = "Redactar mensaje";
?>
	<div class="container">
  	
  	
  	<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
	    <h2>Mensajes <small><?php echo $page_header; ?></small></h2>
	  </div>
	  
	  <!-- MENSAJES -->
	  <?php if (isset($msg_error)): ?>
	  <div class="alert alert-danger">
	  	<?php echo $msg_error; ?>
	  </div>
	  <?php endif; ?>
	  
	  
	  <form method="post" action="">
	  
	  <!-- SCALLFODING -->
		<div class="row">
    
    	<!-- COLUMNA IZQUIERDA -->
      <div class="col-sm-7">
      
      	<div class="well">
      		
      		<fieldset>
      			<legend>Redactar mensaje</legend>
      		
	      		<div class="form-group">
	      			<label for="asunto">Asunto</label>
	      			<input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto del mensaje" value="<?php echo (isset($asunto)) ? $asunto : ''; ?>" maxlength="120" autofocus>
	      		</div>
	      		
	      		<div class="form-group">
	      			<label for="texto" class="sr-only">Contenido</label>
	      			<textarea class="form-control" id="texto" name="texto" rows="10" maxlength="3000"><?php echo (isset($texto)) ? stripslashes($texto) : ''; ?></textarea>
	      		</div>
	      		
	      		<button type="submit" class="btn btn-primary" name="submit1">Enviar mensaje</button>
	      		<a href="index.php" class="btn btn-default">Volver</a>
      		
      		</fieldset>
      		
      	</div><!-- /.well-->
         
      </div><!-- /.col-sm-7 -->
      
      <!-- COLUMNA DERECHA -->
      <div class="col-sm-5">
      
      	<div id="grupos_destinatarios" class="well">
      		
      		<fieldset>
      			<legend>Grupos de destinatarios</legend>
      			
      			<input type="hidden" name="profesor" value="<?php echo $pr; ?>">
      		
            <div class="row">
            	
            	<!-- COLUMNA IZQUIERDA -->
              <div class="col-sm-6">
                
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input name="profes" type="checkbox" value="1" onClick="submit()" <?php if($profes=='1' and !$claustro) echo 'checked'; ?>> Profesores
                		</label>
                	</div>
                </div>
                
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input name="tutores" type="checkbox" value="1" onClick="submit()" <?php if($tutores=='1' and !$claustro) echo 'checked'; ?>> Tutores
                		</label>
                	</div>
                </div>
                
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input name="departamentos" type="checkbox" value="1" onClick="submit()" <?php if($departamentos=='1' and !$claustro) echo 'checked'; ?>> Departamentos
                		</label>
                	</div>
                </div>
                
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input name="equipos" type="checkbox" value="1" onClick="submit()" <?php if($equipos=='1' and !$claustro) echo 'checked'; ?>> Equipos educativos
                		</label>
                	</div>
                </div>
                
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input name="claustro" type="checkbox" value="1" onClick="submit()" <?php if($claustro=='1') echo 'checked'; ?>> Todo el claustro
                		</label>
                	</div>
                </div>
                
                <?php if(isset($mod_biblio) && $mod_biblio): ?>
                <div class="form-group">
                	<div class="checkbox">
                		<label>
                			<input name="biblio" type="checkbox" value="1" onClick="submit()" <?php if($biblio=='1' and !$claustro) echo 'checked'; ?>> Biblioteca
                		</label>
                	</div>
                </div>
                <?php endif; ?>
                
              </div>
              
              
              <!-- COLUMNA DERECHA -->
              <div class="col-sm-6">
              	
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input name="etcp" type="checkbox" value="1" onClick="submit()" <?php if($etcp=='1' and !$claustro) echo 'checked'; ?>> Jefes/as de departamento
              			</label>
              		</div>
              	</div>
              	
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input name="ca" type="checkbox" value="1" onClick="submit()" <?php if($ca=='1' and !$claustro) echo 'checked'; ?>> Coordinadores de area
              			</label>
              		</div>
              	</div>
              	
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input name="direccion" type="checkbox" value="1" onClick="submit()" <?php if($direccion=='1' and !$claustro) echo 'checked'; ?>> Equipo directivo
              			</label>
              		</div>
              	</div>
              	
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input name="orientacion" type="checkbox" value="1" onClick="submit()" <?php if($orientacion=='1' and !$claustro) echo 'checked'; ?>> Orientaci�n
              			</label>
              		</div>
              	</div>
              	
              	<?php if(isset($mod_bilingue) && $mod_bilingue): ?>
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input name="bilingue" type="checkbox" value="1" onClick="submit()" <?php if($bilingue=='1' and !$claustro) echo 'checked'; ?>> Profesores biling�e
              			</label>
              		</div>
              	</div>
              	<?php endif; ?>
              	
              	<div class="form-group">
              		<div class="checkbox">
              			<label>
              				<input name="padres" type="checkbox" value="1" onClick="submit()" <?php if($padres=='1' and !$claustro) echo 'checked'; ?>> Familias y alumnos
              			</label>
              		</div>
              	</div>
              
              </div>
            
      		</fieldset>
      	
      	</div>
      	
				
				<?php if(isset($profes) && $profes == 1 && !isset($claustro)): ?>
				<!-- PROFESORES -->
				<div id="grupo_profesores" class="well">
					
					<fieldset>
						<legend>Seleccione profesores</legend>
						
						<?php $s_origen = mb_strtoupper($origen); ?>
						
						<div class="form-group">
							<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos ORDER BY nombre ASC"); ?>
							<?php if(mysql_num_rows($result)): ?>
							<select class="form-control" name="profeso[]" multiple="multiple" size="23">
								<?php while($row = mysql_fetch_array($result)): ?>
								<option value="<?php echo $row['nombre']; ?>" <?php echo (isset($origen) && mb_strtoupper($origen) == mb_strtoupper($row['nombre'])) ? 'selected' : ''; ?>><?php echo $row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysql_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="profeso[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>
							
							<div class="help-block">Mant�n apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el rat�n para seleccionar m�ltiples profesores.</div>
						</div>
						
					</fieldset>
					
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
     
     
    		<?php if(isset($tutores) && $tutores == 1 && !isset($claustro)): ?>
				<!-- TUTORES -->
				<div id="grupo_tutores" class="well">
					
					<fieldset>
						<legend>Seleccione tutores</legend>
						
						<div class="form-group">
							<?php $result = mysql_query("SELECT DISTINCT tutor, unidad FROM FTUTORES ORDER BY unidad ASC"); ?>
							<?php if(mysql_num_rows($result)): ?>
							<select class="form-control" name="tutor[]" multiple="multiple" size="23">
								<?php while($row = mysql_fetch_array($result)): ?>
								<option value="<?php echo $row['tutor']; ?> --> <?php echo $row['unidad']; ?>-"><?php echo $row['unidad']; ?> - <?php echo $row['tutor']; ?></option>
								<?php endwhile; ?>
								<?php mysql_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="tutor[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>
							
							<div class="help-block">Mant�n apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el rat�n para seleccionar m�ltiples tutores.</div>
						</div>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($departamentos) && $departamentos == 1 && !isset($claustro)): ?>
				<!-- JEFES DE DEPARTAMENTO -->
				<div id="grupo_departamentos" class="well">
					
					<fieldset>
						<legend>Seleccione departamentos</legend>
						
						<div class="form-group">
							<?php $result = mysql_query("SELECT DISTINCT departamento FROM departamentos ORDER BY departamento ASC"); ?>
							<?php if(mysql_num_rows($result)): ?>
							<select class="form-control" name="departamento[]" multiple="multiple" size="23">
								<?php while($row = mysql_fetch_array($result)): ?>
								<option value="<?php echo $row['departamento']; ?>"><?php echo $row['departamento']; ?></option>
								<?php endwhile; ?>
								<?php mysql_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="departamento[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>
							
							<div class="help-block">Mant�n apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el rat�n para seleccionar m�ltiples departamentos.</div>
						</div>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($equipos) && $equipos == 1 && !isset($claustro)): ?>
				<!-- EQUIPOS EDUCATIVOS -->
				<div id="grupo_equipos" class="well">
					
					<fieldset>
						<legend>Seleccione equipos educativos</legend>
						
						<div class="form-group">
							<?php $result = mysql_query("SELECT DISTINCT grupo FROM profesores ORDER BY grupo ASC"); ?>
							<?php if(mysql_num_rows($result)): ?>
							<select class="form-control" name="equipo[]" multiple="multiple" size="23">
								<?php while($row = mysql_fetch_array($result)): ?>
								<option value="<?php echo $row['grupo']; ?>"><?php echo $row['grupo']; ?></option>
								<?php endwhile; ?>
								<?php mysql_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="equipo[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>
							
							<div class="help-block">Mant�n apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el rat�n para seleccionar m�ltiples equipos educativos.</div>
						</div>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($claustro)): ?>
				<!-- CLAUSTRO DEL CENTRO -->
				<div id="grupo_claustro" class="well">
					
					<fieldset>
						<legend>Claustro de profesores</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($biblio) && $biblio == 1 && !isset($claustro)): ?>
				<!-- BIBLIOTECA -->
				<div id="grupo_biblioteca" class="well">
					
					<fieldset>
						<legend>Biblioteca</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos WHERE cargo LIKE '%c%' ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($etcp) && $etcp == 1 && !isset($claustro)): ?>
				<!-- JEFES DE DEPARTAMENTO -->
				<div id="grupo_etcp" class="well">
					
					<fieldset>
						<legend>Jefes de departamento</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos WHERE cargo LIKE '%4%' ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($ca) && $ca == 1 && !isset($claustro)): ?>
				<!-- COORDINADORES DE AREA -->
				<div id="grupo_coordinadores" class="well">
					
					<fieldset>
						<legend>Coordinadores de �rea</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos WHERE cargo LIKE '%9%' ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($direccion) && $direccion == 1 && !isset($claustro)): ?>
				<!-- EQUIPO DIRECTIVO -->
				<div id="grupo_directivo" class="well">
					
					<fieldset>
						<legend>Equipo directivo</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos WHERE cargo LIKE '%1%' ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(isset($orientacion) && $orientacion == 1 && !isset($claustro)): ?>
				<!-- ORIENTACION -->
				<div id="grupo_orientacion" class="well">
					
					<fieldset>
						<legend>Orientaci�n</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos WHERE cargo LIKE '%8%' ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				
				<?php if(isset($bilingue) && $bilingue == 1 && !isset($claustro)): ?>
				<!-- BILING�E -->
				<div id="grupo_bilingue" class="well">
					
					<fieldset>
						<legend>Orientaci�n</legend>
						
						<?php $result = mysql_query("SELECT DISTINCT nombre FROM departamentos WHERE cargo LIKE '%a%' ORDER BY nombre ASC"); ?>
						<?php if(mysql_num_rows($result)): ?>
						<ul style="height: auto; max-height: 520px; overflow: scroll;">
							<?php while($row = mysql_fetch_array($result)): ?>
							<li><?php echo $row['nombre'] ; ?></li>
							<?php endwhile; ?>
							<?php mysql_free_result($result); ?>
						</ul>
						<?php endif; ?>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				
				<?php if(stristr($_SESSION['cargo'],'1') == TRUE || stristr($_SESSION['cargo'],'2') == TRUE): ?>
				
				<?php $sql_where = ""; ?>
				
				<?php if(stristr($_SESSION['cargo'],'2')): ?>
					<?php $result = mysql_query("SELECT unidad FROM FTUTORES WHERE tutor='$pr'"); ?>
					<?php $unidad = mysql_fetch_array($result); ?>
					<?php $unidad = $unidad['unidad']; ?>
					<?php mysql_free_result($result); ?>
					
					<?php $sql_where = "WHERE unidad='$unidad'"; ?>
				<?php endif; ?>
										
				
				<?php if(isset($padres) && $padres == 1 && !isset($claustro)): ?>
				<!-- FAMILIAS Y ALUMNOS -->
				<div id="grupo_padres" class="well">
					
					<fieldset>
						<legend>Familias y alumnos</legend>
						
						<div class="form-group">
							<?php $result = mysql_query("SELECT DISTINCT apellidos, nombre, unidad FROM alma $sql_where ORDER BY unidad ASC, apellidos ASC, nombre ASC"); ?>
							<?php if(mysql_num_rows($result)): ?>
							<select class="form-control" name="padres[]" multiple="multiple" size="23">
								<?php while($row = mysql_fetch_array($result)): ?>
								<option value="<?php echo $row['apellidos'].', '.$row['nombre']; ?>" <?php echo (isset($origen) && $origen == $row['apellidos'].', '.$row['nombre']) ? 'selected' : ''; ?>><?php echo $row['unidad'].' - '.$row['apellidos'].', '.$row['nombre']; ?></option>
								<?php endwhile; ?>
								<?php mysql_free_result($result); ?>
							</select>
							<?php else: ?>
							<select class="form-control" name="padres[]" multiple="multiple" disabled>
								<option value=""></option>
							</select>
							<?php endif; ?>
							
							<div class="help-block">Mant�n apretada la tecla <kbd>Ctrl</kbd> mientras haces click con el rat�n para seleccionar m�ltiples alumnos.</div>
						</div>
						
					</fieldset>
				</div>
				
				<?php $ocultar_grupos = 1; ?>
				<?php endif; ?>
				
				<?php endif; ?>
				
				<?php if(isset($ocultar_grupos)): ?>
				<button type="button" class="btn btn-primary btn-block" id="mostrar_grupos">Seleccionar otro grupo de destinatarios</button>
				<?php endif; ?>
				
				
<?php
//$perfil = $_SESSION['cargo'];
// Queda preparado para que todos los profesores puedan enviar mensajes a los padres en la p�gina exterior.
//S�lo hay que eliminar $perfil == '1', y a�adir la posibilidad de responder al mensaje del profesor
//desde la p�gina principal(actualmente s�lo es posible responder al tutor del grupo).
/*					
if (!($perfil == '1')) {
$extra0 = "where profesor = '$pr'";
}

if($padres == '1' and $perfil == '1') {
echo "<hr /><legend class='text-warning'>Padres de Alumnos</legend><div class='well well-transparent'>";
echo '<SELECT  name=padres[] multiple=multiple size=15 >';
$tut = mysql_query("select distinct grupo from profesores $extra0");
while ($tuto = mysql_fetch_array($tut)) {
$unidad = $tuto[0];
echo "<OPTION style='color:brown;background-color:#cf9;' disabled>$unidad</OPTION>";
$extra = "where unidad='$unidad'";
$padre = mysql_query("SELECT distinct APELLIDOS, NOMBRE  FROM alma $extra order by unidad, apellidos");
while($filapadre = mysql_fetch_array($padre))
{
$al_sel = "$filapadre[0], $filapadre[1]";
if ($al_sel==$origen) {
$seleccionado='selected';
}else{$seleccionado="";}
echo "<OPTION $seleccionado>$filapadre[0], $filapadre[1]</OPTION>";
}

}
}
echo  '</select>';
echo "</div>";
*/
?>

			</div><!-- /.col-sm-5 -->
			
		</div><!-- /.row -->
		
		</form>
	
	</div><!-- /.container -->
  

<?php include("../../pie.php"); ?>
	
	<script>
	$(document).ready(function() {
	
		// EDITOR DE TEXTO
	  $('#texto').summernote({
	  	height: 300,
	  	lang: 'es-ES'
	  });
	  
	  function ocultar_grupos() {
	  	$('#grupos_destinatarios').slideUp();
	  	
	  	$('#mostrar_grupos').show();
	  }
	  
	  function mostrar_grupos() {
	  	$('#grupos_destinatarios').slideDown();
	  	
	  	$('#grupo_profesores').slideUp();
	  	$('#grupo_tutores').slideUp();
	  	$('#grupo_departamentos').slideUp();
	  	$('#grupo_equipos').slideUp();
	  	$('#grupo_claustro').slideUp();
	  	$('#grupo_biblioteca').slideUp();
	  	$('#grupo_etcp').slideUp();
	  	$('#grupo_coordinadores').slideUp();
	  	$('#grupo_directivo').slideUp();
	  	$('#grupo_orientacion').slideUp();
	  	$('#grupo_padres').slideUp();
	  	
	  	$('#mostrar_grupos').hide();
	  }
	  
	  <?php if($ocultar_grupos): ?>
	  ocultar_grupos();
	  <?php endif; ?>
	  
	  $('#mostrar_grupos').click(function()�{
	  	mostrar_grupos();
	  });
	  
	});
	</script>

</body>
</html>
