<?php
require('../../bootstrap.php');

if (file_exists('config.php')) {
	include('config.php');
}

acl_acceso($_SESSION['cargo'], array(1, 2, 8));

// COMPROBAMOS SI ES EL TUTOR, SI NO, ES DEL EQ. DIRECTIVO U ORIENTADOR
if (stristr($_SESSION['cargo'],'2') == TRUE) {
	
	$_SESSION['mod_tutoria']['tutor']  = $_SESSION['mod_tutoria']['tutor'];
	$_SESSION['mod_tutoria']['unidad'] = $_SESSION['mod_tutoria']['unidad'];
	
}
else {

	if(isset($_POST['tutor'])) {
		$exp_tutor = explode('==>', $_POST['tutor']);
		$_SESSION['mod_tutoria']['tutor'] = trim($exp_tutor[0]);
		$_SESSION['mod_tutoria']['unidad'] = trim($exp_tutor[1]);
	}
	else{
		if (!isset($_SESSION['mod_tutoria'])) {
			header('Location:'.'tutores.php');
		}
	}
	
}


// TABLA DE PUESTOS
mysqli_query($db_con, "CREATE TABLE IF NOT EXISTS `puestos_alumnos` (
  `unidad` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `puestos` text COLLATE utf8_general_ci,
  PRIMARY KEY (`unidad`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");


// ESTRUCTURA DE LA CLASE, SE AJUSTA AL NUMERO DE ALUMNOS
$result = mysqli_query($db_con, "SELECT apellidos, nombre, claveal FROM alma WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."' ORDER BY apellidos ASC, nombre ASC");
$n_alumnos = mysqli_num_rows($result);
mysqli_free_result($result);

if ($n_alumnos <= 36) $estructura_clase = '222';
elseif ($n_alumnos > 36 && $n_alumnos <= 42) $estructura_clase = '232';
elseif ($n_alumnos > 42) $estructura_clase = '242';

if ($estructura_clase == '242') { $mesas_col = 9; $mesas = 48; $col_profesor = 9; }
if ($estructura_clase == '232') { $mesas_col = 8; $mesas = 42; $col_profesor = 8; }
if ($estructura_clase == '222') { $mesas_col = 7; $mesas = 36; $col_profesor = 7; }


function al_con_nie($db_con, $var_nie, $var_grupo) {
	$result = mysqli_query($db_con, "SELECT CONCAT(nombre, ', ', apellidos) AS alumno FROM alma WHERE unidad='".$var_grupo."' AND claveal='".$var_nie."' ORDER BY apellidos ASC, nombre ASC LIMIT 1");
	$row = mysqli_fetch_array($result);
	mysqli_free_result($result);
	
	if ($row['alumno'] != ", ") {
		return($row['alumno'].', '.$row['nombre']);
	}
	else {
		return('');
	}
	
}


// ACTUALIZAR PUESTOS
if (isset($_POST['listOfItems'])){
	$result = mysqli_query($db_con, "UPDATE puestos_alumnos SET puestos='".$_POST['listOfItems']."' WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."'");
	
	if(!$result) $msg_error = "La asignación de puestos en el aula no se ha podido actualizar. Error: ".mysqli_error($db_con);
	else $msg_success = "La asignación de puestos en el aula se ha actualizado correctamente.";	
}


// OBTENEMOS LOS PUESTOS, SI NO, EXISTE LOS CREAMOS
$result = mysqli_query($db_con, "SELECT * FROM puestos_alumnos WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."' LIMIT 1");

if (! mysqli_num_rows($result)) {
	mysqli_query($db_con, "INSERT INTO puestos_alumnos (unidad, puestos) VALUES ('".$_SESSION['mod_tutoria']['unidad']."', '')");
	
	if(!$result) $msg_error = "La asignación de puestos en el aula no se ha podido guardar. Error: ".mysqli_error($db_con);
	else $msg_success = "La asignación de puestos en el aula se ha guardado correctamente.";	
}
else {
	$row = mysqli_fetch_array($result);
	$cadena_puestos = $row[1];
	mysqli_free_result($result);
}

$matriz_puestos = explode(';', $cadena_puestos);

foreach ($matriz_puestos as $value) {
	$los_puestos = explode('|', $value);
	
	if ($los_puestos[0] == 'allItems') {
		$sin_puesto[] = $los_puestos[1];
	}
	else {
		$con_puesto[$los_puestos[0]] = $los_puestos[1];
	}

}

include("../../menu.php");
include("menu.php");
?>
	
	<style class="text/css">
	
	table tr td {
		vertical-align: top;
	}
	
	table tr td.active {
		background-color: #333;
	}
	
	#allItems {
		width: 100%;
		border: 1px solid #ecf0f1;
	}
	
	#allItems p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 15px;
		margin-bottom: 4px;
	}
	
	#allItems ul li {
		background-color: #efefef;
		padding: 5px 15px;
		margin: 5px;
		font-size: 0.9em;
		cursor: move;
	}
	
	#dhtmlgoodies_mainContainer table tr td div {
		border: 1px solid #ecf0f1;
		margin: 0 5px 10px 5px;
	}
	
	#dhtmlgoodies_mainContainer table tr td div {
		<?php if ($estructura_clase == '242') echo 'width: 95px;'; ?>
		<?php if ($estructura_clase == '232') echo 'width: 110px;'; ?>
		<?php if ($estructura_clase == '222') echo 'width: 130px;'; ?>
	}
	
	#dhtmlgoodies_mainContainer table tr td div p {
		background-color: #2c3e50;
		color: #fff;
		font-weight: bold;
		padding: 4px 2px;
		margin-bottom: 4px;
	}
	
	#dhtmlgoodies_mainContainer table tr td div ul {
		margin: 0 4px 4px 4px;
		min-height: 50px;
		background-color: #efefef;
	}
	
	#dhtmlgoodies_mainContainer table tr td div ul li {
		height: 100%;
		cursor: move;
	}
	
	
	#dhtmlgoodies_dragDropContainer .mouseover ul {
		background-color:#E2EBED;
		border: 1px solid #3FB618;
	}
	
	#dragContent {
		position: absolute;
		margin-top: -280px;
		margin-left: -150px;
		width: 150px;
		height: 60px;
		font-size: 0.8em;
		z-index: 2000;
		cursor: move;
	}
	
	.text-sm {
		font-size: 0.8em;
	}
	
	</style>

	<div class="container">
		
		<!-- TITULO DE LA PAGINA -->
		<div class="page-header">
			<h2>Tutoría de <?php echo $_SESSION['mod_tutoria']['unidad']; ?> <small>Asignación de mesas en el aula</small></h2>
			<h4 class="text-info">Tutor/a: <?php echo nomprofesor($_SESSION['mod_tutoria']['tutor']); ?></h4>
		</div>
		
		
		<!-- MENSAJES -->
		<?php if(isset($msg_success) && $msg_success): ?>
		<div class="alert alert-success" role="alert">
			<?php echo $msg_success; ?>
		</div>
		<?php endif; ?>
		
		<?php if(isset($msg_error) && $msg_error): ?>
		<div class="alert alert-danger" role="alert">
			<?php echo $msg_error; ?>
		</div>
		<?php endif; ?>
		
		
		<!-- SCAFFOLDING -->
		<div id="dhtmlgoodies_dragDropContainer" class="row">
			
			<!-- COLUMNA IZQUIERDA -->
			<div id="dhtmlgoodies_listOfItems" class="col-sm-3 hidden-print">
				
				<div id="allItems">
					<p>Alumnos/as</p>
					<ul class="list-unstyled">
						<?php $result = mysqli_query($db_con, "SELECT apellidos, nombre, claveal FROM alma WHERE unidad='".$_SESSION['mod_tutoria']['unidad']."' ORDER BY apellidos ASC, nombre ASC"); ?>
						<?php while ($row = mysqli_fetch_array($result)): ?>
						<?php if (!in_array($row['claveal'],$con_puesto)): ?>
					  <li id="<?php echo $row['claveal']; ?>">
					    <?php echo $row['apellidos'].', '.$row['nombre']; ?>
					  </li>
					  <?php endif; ?>
					  <?php endwhile; ?>
					</ul>
				</div>
				
				<ul id="dragContent" class="list-unstyled"></ul>
				
			</div><!-- /.col-sm-3 -->
			
			
			<!-- COLUMNA DERECHA -->
			<div id="dhtmlgoodies_mainContainer" class="col-sm-9">
				
				<div class="table-responsive">
					<table>
						<?php for ($i = 1; $i < 7; $i++): ?>
						<tr>
							<?php for ($j = 1; $j < $mesas_col; $j++): ?>
							<td>
								<div><p class="text-center">Mesa <?php echo $mesas; ?></p>
									<ul id="<?php echo $mesas; ?>" class="list-unstyled text-sm">
										<?php if (isset($con_puesto[$mesas])): ?>
											<li id="<?php echo $con_puesto[$mesas]; ?>"><?php echo al_con_nie($db_con, $con_puesto[$mesas], $_SESSION['mod_tutoria']['unidad']); ?></li>		 
										<?php endif; ?>  
									</ul>
								</div>
							</td>
							<?php if ($j == 2 || $j == $mesas_col-3): ?>
							<td class="text-center active">|</td>
							<?php endif; ?>
							<?php $mesas--; ?>
							<?php endfor; ?>
						</tr>
						<?php endfor; ?>
						<tr>
							<td colspan="<?php echo $col_profesor; ?>">
								<br><p id="dragDropIndicator" class="text-info hidden-print">Arrastre un alumno/a a la mesa correspondiente</p>
							</td>
							<td class="text-center">
								<div>
									<p>Profesor/a</p>
									<br><br><br>
								</div>
							</td>
						</tr>
					</table>
				</div>
			
			</div><!-- /.col-sm-9 -->
		
		</div><!-- /.row -->
		
		<br>
		
		<div class="row">
			
			<div class="col-sm-12">
				
				<div class="hidden-print">
					<form id="myForm" name="myForm" method="post" action="" onsubmit="saveDragDropNodes()">
						<input type="hidden" name="listOfItems" value="">
						<button type="submit" class="btn btn-primary" name="saveButton">Guardar cambios</button>
						<a href="#" class="btn btn-default" onclick="javascript:print();">Imprimir</a>
					</form>
				</div>
				
			</div><!-- /col-sm-12 -->
			
		</div><!-- /.row -->
	
	</div><!-- /.container -->


<?php include("../../pie.php"); ?>

	<script type="text/javascript">
	/************************************************************************************************************
	(C) www.dhtmlgoodies.com, November 2005
	
	Update log:
	
	December 20th, 2005 : Version 1.1: Added support for rectangle indicating where object will be dropped
	January 11th, 2006: Support for cloning, i.e. "copy & paste" items instead of "cut & paste"
	January 18th, 2006: Allowing multiple instances to be dragged to same box(applies to "cloning mode")
	
	This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.
	
		Terms of use:
	You are free to use this script as long as the copyright message is kept intact. However, you may not
	redistribute, sell or repost it without our permission.
	
	Thank you!
	
	www.dhtmlgoodies.com
	Alf Magne Kalleland
	
	************************************************************************************************************/
	
	/* VARIABLES YOU COULD MODIFY */
	/*if ($estructura_clase=='232') {*/
		var boxSizeArray = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
		/*}
	if ($estructura_clase=='222') {
	var boxSizeArray = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
	var boxSizeArray = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];}	*/
	// Array indicating how many items  there is rooom for in the right column ULs 
	
	
	var verticalSpaceBetweenListItems = 3;	// Pixels space between one <li> and next
											// Same value or higher as margin bottom in CSS for #dhtmlgoodies_dragDropContainer ul li,#dragContent li
	
	
	var indicateDestionationByUseOfArrow = false;	// Display arrow to indicate where object will be dropped(false = use rectangle)
	
	var cloneSourceItems = false;	// Items picked from main container will be cloned(i.e. "copy" instead of "cut").
	var cloneAllowDuplicates = false;	// Allow multiple instances of an item inside a small box(example: drag Student 1 to team A twice
	
	/* END VARIABLES YOU COULD MODIFY */
	
	var dragDropTopContainer = false;
	var dragTimer = -1;
	var dragContentObj = false;
	var contentToBeDragged = false;	// Reference to dragged <li>
	var contentToBeDragged_src = false;	// Reference to parent of <li> before drag started
	var contentToBeDragged_next = false; 	// Reference to next sibling of <li> to be dragged
	var destinationObj = false;	// Reference to <UL> or <LI> where element is dropped.
	var dragDropIndicator = false;	// Reference to small arrow indicating where items will be dropped
	var ulPositionArray = new Array();
	var mouseoverObj = false;	// Reference to highlighted DIV
	
	var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
	var navigatorVersion = navigator.appVersion.replace(/.*?MSIE (\d\.\d).*/g,'$1')/1;
	
	var arrow_offsetX = -5;	// Offset X - position of small arrow
	var arrow_offsetY = 0;	// Offset Y - position of small arrow
	
	if(!MSIE || navigatorVersion > 6){
		arrow_offsetX = -6;	// Firefox - offset X small arrow
		arrow_offsetY = -13; // Firefox - offset Y small arrow
	}
	
	var indicateDestinationBox = false;
	function getTopPos(inputObj)
	{
	  var returnValue = inputObj.offsetTop;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
	  }
	  return returnValue;
	}
	
	function getLeftPos(inputObj)
	{
	  var returnValue = inputObj.offsetLeft;
	  while((inputObj = inputObj.offsetParent) != null){
	  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
	  }
	  return returnValue;
	}
	
	function cancelEvent()
	{
		return false;
	}
	function initDrag(e)	// Mouse button is pressed down on a LI
	{
		if(document.all)e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
	
		dragTimer = 0;
		dragContentObj.style.left = e.clientX + sl + 'px';
		dragContentObj.style.top = e.clientY + st + 'px';
		contentToBeDragged = this;
		contentToBeDragged_src = this.parentNode;
		contentToBeDragged_next = false;
		if(this.nextSibling){
			contentToBeDragged_next = this.nextSibling;
			if(!this.tagName && contentToBeDragged_next.nextSibling)contentToBeDragged_next = contentToBeDragged_next.nextSibling;
		}
		timerDrag();
		return false;
	}
	
	function timerDrag()
	{
		if(dragTimer>=0 && dragTimer<10){
			dragTimer++;
			setTimeout('timerDrag()',10);
			return;
		}
		if(dragTimer==10){
	
			if(cloneSourceItems && contentToBeDragged.parentNode.id=='allItems'){
				newItem = contentToBeDragged.cloneNode(true);
				newItem.onmousedown = contentToBeDragged.onmousedown;
				contentToBeDragged = newItem;
			}
			dragContentObj.style.display='block';
			dragContentObj.appendChild(contentToBeDragged);
		}
	}
	
	function moveDragContent(e)
	{
		if(dragTimer<10){
			if(contentToBeDragged){
				if(contentToBeDragged_next){
					contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
				}else{
					contentToBeDragged_src.appendChild(contentToBeDragged);
				}
			}
			return;
		}
		if(document.all)e = event;
		var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
		var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
	
	
		dragContentObj.style.left = e.clientX + sl + 'px';
		dragContentObj.style.top = e.clientY + st + 'px';
	
		if(mouseoverObj)mouseoverObj.className='';
		destinationObj = false;
		dragDropIndicator.style.display='none';
		if(indicateDestinationBox)indicateDestinationBox.style.display='none';
		var x = e.clientX + sl;
		var y = e.clientY + st;
		var width = dragContentObj.offsetWidth;
		var height = dragContentObj.offsetHeight;
	
		var tmpOffsetX = arrow_offsetX;
		var tmpOffsetY = arrow_offsetY;
	
		for(var no=0;no<ulPositionArray.length;no++){
			var ul_leftPos = ulPositionArray[no]['left'];
			var ul_topPos = ulPositionArray[no]['top'];
			var ul_height = ulPositionArray[no]['height'];
			var ul_width = ulPositionArray[no]['width'];
	
			if((x+width) > ul_leftPos && x<(ul_leftPos + ul_width) && (y+height)> ul_topPos && y<(ul_topPos + ul_height)){
				var noExisting = ulPositionArray[no]['obj'].getElementsByTagName('LI').length;
				if(indicateDestinationBox && indicateDestinationBox.parentNode==ulPositionArray[no]['obj'])noExisting--;
				if(noExisting<boxSizeArray[no-1] || no==0){
					dragDropIndicator.style.left = ul_leftPos + tmpOffsetX + 'px';
					var subLi = ulPositionArray[no]['obj'].getElementsByTagName('LI');
	
					var clonedItemAllreadyAdded = false;
					if(cloneSourceItems && !cloneAllowDuplicates){
						for(var liIndex=0;liIndex<subLi.length;liIndex++){
							if(contentToBeDragged.id == subLi[liIndex].id)clonedItemAllreadyAdded = true;
						}
						if(clonedItemAllreadyAdded)continue;
					}
	
					for(var liIndex=0;liIndex<subLi.length;liIndex++){
						var tmpTop = getTopPos(subLi[liIndex]);
						if(!indicateDestionationByUseOfArrow){
							if(y<tmpTop){
								destinationObj = subLi[liIndex];
								indicateDestinationBox.style.display='block';
								subLi[liIndex].parentNode.insertBefore(indicateDestinationBox,subLi[liIndex]);
								break;
							}
						}else{
							if(y<tmpTop){
								destinationObj = subLi[liIndex];
								dragDropIndicator.style.top = tmpTop + tmpOffsetY - Math.round(dragDropIndicator.clientHeight/2) + 'px';
								dragDropIndicator.style.display='block';
								break;
							}
						}
					}
	
					if(!indicateDestionationByUseOfArrow){
						if(indicateDestinationBox.style.display=='none'){
							indicateDestinationBox.style.display='block';
							ulPositionArray[no]['obj'].appendChild(indicateDestinationBox);
						}
	
					}else{
						if(subLi.length>0 && dragDropIndicator.style.display=='none'){
							dragDropIndicator.style.top = getTopPos(subLi[subLi.length-1]) + subLi[subLi.length-1].offsetHeight + tmpOffsetY + 'px';
							dragDropIndicator.style.display='block';
						}
						if(subLi.length==0){
							dragDropIndicator.style.top = ul_topPos + arrow_offsetY + 'px'
							dragDropIndicator.style.display='block';
						}
					}
	
					if(!destinationObj)destinationObj = ulPositionArray[no]['obj'];
					mouseoverObj = ulPositionArray[no]['obj'].parentNode;
					mouseoverObj.className='mouseover';
					return;
				}
			}
		}
	}
	
	/* End dragging
	Put <LI> into a destination or back to where it came from.
	*/
	function dragDropEnd(e)
	{
		if(dragTimer==-1)return;
		if(dragTimer<10){
			dragTimer = -1;
			return;
		}
		dragTimer = -1;
		if(document.all)e = event;
	
	
		if(cloneSourceItems && (!destinationObj || (destinationObj && (destinationObj.id=='allItems' || destinationObj.parentNode.id=='allItems')))){
			contentToBeDragged.parentNode.removeChild(contentToBeDragged);
		}else{
	
			if(destinationObj){
				if(destinationObj.tagName=='UL'){
					destinationObj.appendChild(contentToBeDragged);
				}else{
					destinationObj.parentNode.insertBefore(contentToBeDragged,destinationObj);
				}
				mouseoverObj.className='';
				destinationObj = false;
				dragDropIndicator.style.display='none';
				if(indicateDestinationBox){
					indicateDestinationBox.style.display='none';
					document.body.appendChild(indicateDestinationBox);
				}
				contentToBeDragged = false;
				return;
			}
			if(contentToBeDragged_next){
				contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
			}else{
				contentToBeDragged_src.appendChild(contentToBeDragged);
			}
		}
		contentToBeDragged = false;
		dragDropIndicator.style.display='none';
		if(indicateDestinationBox){
			indicateDestinationBox.style.display='none';
			document.body.appendChild(indicateDestinationBox);
	
		}
		mouseoverObj = false;
	
	}
	
	/*
	Preparing data to be saved
	*/
	function saveDragDropNodes()
	{
		var saveString = "";
		var uls = dragDropTopContainer.getElementsByTagName('ul');
		for(var no=0;no<uls.length;no++){	// LOoping through all <ul>
			var lis = uls[no].getElementsByTagName('li');
			for(var no2=0;no2<lis.length;no2++){
				if(saveString.length>0)saveString = saveString + ";";
				saveString = saveString + uls[no].id + '|' + lis[no2].id;
			}
		}
	document.forms['myForm'].listOfItems.value = saveString; 
		document.getElementById('saveContent').innerHTML = '<h1>Ready to save these nodes:</h1> ' + saveString.replace(/;/g,';<br>') + '<p>Format: ID of ul |(pipe) ID of li;(semicolon)</p><p>You can put these values into a hidden form fields, post it to the server and explode the submitted value there</p>';
		
	
	}
	
	function initDragDropScript()
	{
		dragContentObj = document.getElementById('dragContent');
		dragDropIndicator = document.getElementById('dragDropIndicator');
		dragDropTopContainer = document.getElementById('dhtmlgoodies_dragDropContainer');
		document.documentElement.onselectstart = cancelEvent;;
		var listItems = dragDropTopContainer.getElementsByTagName('LI');	// Get array containing all <LI>
		var itemHeight = false;
		for(var no=0;no<listItems.length;no++){
			listItems[no].onmousedown = initDrag;
			listItems[no].onselectstart = cancelEvent;
			if(!itemHeight)itemHeight = listItems[no].offsetHeight;
			if(MSIE && navigatorVersion/1<6){
				listItems[no].style.cursor='hand';
			}
		}
	
		var mainContainer = document.getElementById('dhtmlgoodies_mainContainer');
		var uls = mainContainer.getElementsByTagName('UL');
		itemHeight = itemHeight + verticalSpaceBetweenListItems;
		for(var no=0;no<uls.length;no++){
			uls[no].style.height = itemHeight * boxSizeArray[no]  + 'px';
		}
	
		var leftContainer = document.getElementById('dhtmlgoodies_listOfItems');
		var itemBox = leftContainer.getElementsByTagName('UL')[0];
	
		document.documentElement.onmousemove = moveDragContent;	// Mouse move event - moving draggable div
		document.documentElement.onmouseup = dragDropEnd;	// Mouse move event - moving draggable div
	
		var ulArray = dragDropTopContainer.getElementsByTagName('UL');
		for(var no=0;no<ulArray.length;no++){
			ulPositionArray[no] = new Array();
			ulPositionArray[no]['left'] = getLeftPos(ulArray[no]);
			ulPositionArray[no]['top'] = getTopPos(ulArray[no]);
			ulPositionArray[no]['width'] = ulArray[no].offsetWidth;
			ulPositionArray[no]['height'] = ulArray[no].clientHeight;
			ulPositionArray[no]['obj'] = ulArray[no];
		}
	
		if(!indicateDestionationByUseOfArrow){
			indicateDestinationBox = document.createElement('LI');
			indicateDestinationBox.id = 'indicateDestination';
			indicateDestinationBox.style.display='none';
			document.body.appendChild(indicateDestinationBox);
	
	
		}
	}
	
	window.onload = initDragDropScript;
	</script>

</body>
</html>
