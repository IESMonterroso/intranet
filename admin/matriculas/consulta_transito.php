<?php
session_start();
include("../../config.php");
include_once('../../config/version.php');

// COMPROBAMOS LA SESION
if ($_SESSION['autentificado'] != 1) {
	$_SESSION = array();
	session_destroy();
	
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/salir.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/salir.php');
		exit();
	}
}
if(!(stristr($_SESSION['cargo'],'1') == TRUE) and !(stristr($_SESSION['cargo'],'7') == TRUE) and !(stristr($_SESSION['cargo'],'8') == TRUE))
{
header('Location:'.'http://'.$dominio.'/intranet/salir.php');
exit;	
}

if($_SESSION['cambiar_clave']) {
	if(isset($_SERVER['HTTPS'])) {
	    if ($_SERVER["HTTPS"] == "on") {
	        header('Location:'.'https://'.$dominio.'/intranet/clave.php');
	        exit();
	    } 
	}
	else {
		header('Location:'.'http://'.$dominio.'/intranet/clave.php');
		exit();
	}
}


registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);

?>
<? $PLUGIN_DATATABLES = 1;?>
<? include("../../menu.php");?>
<? include("./menu.php");?>
<div class="container">
	
	<!-- TITULO DE LA PAGINA -->
	<div class="page-header">
	
			
	  
<!-- Button trigger modal -->
<a href="#" class="pull-right" data-toggle="modal" data-target="#myModal">
 <span class="fa fa-question-circle fa-2x"></span>
</a>

 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Instrucciones de uso.</h4>
      </div>
      <div class="modal-body">
		<p class="help-block">
		Las celdas que contienen datos num�ricos variables o bolitas de colores (diversos tipos de dificultades propios de un alumno, p. ej.) presentan informaci�n sobre el texto simbolizado por el n�mero o la bolita colocando el cursor encima de la celda. Aparecer� entonces el texto correspondiente a la opci�n num�rica o el color de la bola.
		</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
		<h2>Consulta Informes de Tr�nsito <small>Alumnado de Primaria</small>
</h2>
</div>

	<!-- SCAFFOLDING -->
	<div class="row">
	<div class="col-sm-12">
	<div class="table-responsive">
	<table class="table table-striped table-bordered datatable"><thead>
	<?
	$cabecera="<tr><th>Alumno</th><th>Colegio</th>";
	$col = mysqli_query($db_con,"select distinct tipo from transito_tipo where tipo not like 'norelacion' and tipo not like 'funciona' and tipo not like 'actitud' and tipo not like 'observaciones'");
	while ($ncol=mysqli_fetch_array($col)) {
		$cabecera.= "<th>$ncol[0]</th>";
	}
	$cabecera.="</tr>";
	echo $cabecera;	
	?>
	</thead>
	<?
	$cl = mysqli_query($db_con,"select distinct claveal, apellidos, nombre, colegio from alma_primaria");
	while ($clav = mysqli_fetch_array($cl)) {
	
		$link="";
		$claveal=$clav[0];
		$con = mysqli_query($db_con,"select * from transito_datos where claveal = '$claveal'");
		if (mysqli_num_rows($con)>0) { $link = 1; }
		echo "<tr><td nowrap>$ni ";
		if ($link==1) {
			echo "<a href='informe_transito.php?claveal=$claveal' target='_blank'>";
		}
		echo "$clav[1], $clav[2]";
		if ($link == 1) {
			echo "</a>";;
		}
		echo "</td><td nowrap>$clav[3]</td>";
		$col = mysqli_query($db_con,"select distinct tipo from transito_tipo where tipo not like 'norelacion' and tipo not like 'funciona' and tipo not like 'actitud' and tipo not like 'observaciones'");
		while ($ncol=mysqli_fetch_array($col)) {
			
			$tipo = $ncol[0];
			$col1 = mysqli_query($db_con,"select dato from transito_datos where claveal = '$claveal' and tipo = '$tipo'");
			$dat = mysqli_fetch_array($col1);
			//$dato="";
			$dato = $dat[0];
			$tt="";
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"1")==TRUE) {$ttd = "Tiene carencias en aprendizajes b�sicos. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"2")==TRUE) {$ttd.= "Tiene dificultades en la lectura. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"3")==TRUE) {$ttd.= "Tiene dificultades de comprensi�n oral / escrita. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"4")==TRUE) {$ttd.= "Tiene dificultades de expresi�n oral / escrita. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"5")==TRUE) {$ttd.= "Tiene dificultades de razonamiento matem�tico. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"6")==TRUE) {$ttd.= "Tiene dificultades en h�bitos /  m�todo de estudio. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and stristr($dato,"7")==TRUE) {$ttd.= "Tiene dificultades de c�lculo. <br>";}
			if (stristr($tipo,"dificulta")==TRUE and strlen($dato)>0) {$tt=" id='dific' data-bs='tooltip' data-html='true' title='$ttd'";$dato='<span class="fa fa-circle" style="color: red;"></span>';}
				
			if (stristr($tipo,"expuls")==TRUE and $dato==2) {$tt=" data-bs='tooltip' title='El alumno ha sido expulsado.'";$dato='<span class="fa fa-circle" style="color: red;"></span>';}elseif(stristr($tipo,"expuls")==TRUE and strlen($dato)==1){$tt="";$dato='';}
			
			if (stristr($tipo,"exento")==TRUE and $dato==1) {$tt=" data-bs='tooltip' title='Alumnado que por sus dificultades no se le recomienda cursar optativa'";$dato='<span class="fa fa-circle" style="color: green;"></span>';}
		
			if (stristr($tipo,"acompa")==TRUE and $dato==1) {$tt=" data-bs='tooltip' title='Se aconseja asistencia al Programa de Acompa�amiento Escolar'";$dato='<span class="fa fa-circle" style="color: green;"></span>';}
			
			if (stristr($tipo,"asiste")==TRUE and $dato==1) {$tt=" data-bs='tooltip' title='El alumno presenta faltas de asistencia.'";$dato='<span class="fa fa-circle" style="color: yellow;"></span>';}elseif(stristr($tipo,"asiste")==TRUE and $dato==2){$tt=" data-bs='tooltip' title='El alumno falta m�s de lo normal'";$dato='<span class="fa fa-circle" style="color: orange;"></span>';}elseif(stristr($tipo,"asiste")==TRUE and $dato==3){$tt=" data-bs='tooltip' title='El alumno es absentista'";$dato='<span class="fa fa-circle" style="color: red;"></span>';}
						
			if (stristr($tipo,"nacion")==TRUE and $dato==1) {$tt = " data-bs='tooltip' title='No conoce el espa�ol'";}
			if (stristr($tipo,"nacion")==TRUE and $dato==2) {$tt = " data-bs='tooltip' title='Nociones b�sicas de espa�ol'";}
			if (stristr($tipo,"nacion")==TRUE and $dato==3) {$tt = " data-bs='tooltip' title='Dificultades lectoescritiras en espa�ol'";}
			if (stristr($tipo,"nacion")==TRUE and $dato==4) {$tt = " data-bs='tooltip' title='Puede seguir el Curriculum'";}
			if (stristr($tipo,"integra")==TRUE and $dato==1) {$tt = " data-bs='tooltip' title='L�der'";}
			if (stristr($tipo,"integra")==TRUE and $dato==2) {$tt = " data-bs='tooltip' title='Integrado'";}
			if (stristr($tipo,"integra")==TRUE and $dato==3) {$tt = " data-bs='tooltip' title='Poco integrado'";}
			if (stristr($tipo,"integra")==TRUE and $dato==4) {$tt = " data-bs='tooltip' title='Se a�sla'";}
			if (stristr($tipo,"integra")==TRUE and $dato==5) {$tt = " data-bs='tooltip' title='Alumno rechazado'";}
			if (stristr($tipo,"relacion")==TRUE and $dato==1) {$tt = " data-bs='tooltip' title='Colaboraci�n constante de la familia'";}
			if (stristr($tipo,"relacion")==TRUE and $dato==2) {$tt = " data-bs='tooltip' title='Colaboraci�n s�lo cuando el Centro la ha solicitado'";}
			if (stristr($tipo,"relacion")==TRUE and $dato==3) {$tt = " data-bs='tooltip' title='Demanda constante por parte de los Padres'";}
			if (stristr($tipo,"disruptivo")==TRUE and $dato==1) {$tt = " data-bs='tooltip' title='Nunca'";}
			if (stristr($tipo,"disruptivo")==TRUE and $dato==2) {$tt = " data-bs='tooltip' title='Ocasionalmente'";}
			if (stristr($tipo,"disruptivo")==TRUE and $dato==3) {$tt = " data-bs='tooltip' title='Alumno disruptivo'";}
			if (stristr($tipo,"expulsion")==TRUE and $dato==1) {$tt = " data-bs='tooltip' title='No ha sido expulsado'";}
			if (stristr($tipo,"expulsion")==TRUE and $dato==2) {$tt = " data-bs='tooltip' title='Ha sido expulsado'";}
			
			echo "<td $tt>$dato</td>";
		}
		echo "</tr>";
	}
	?>
	</table>
	</div>
	</div>

	</div><!-- /.row -->	
</div><!-- /.container -->
  
<?php include("../../pie.php"); ?>
	<script>
	$(document).ready(function() {
	  var table = $('.datatable').DataTable({
	  	  "paging":   true,
	      "ordering": true,
	      "info":     false,
	      
			"lengthMenu": [[15, 35, 50, -1], [15, 35, 50, "Todos"]],
	  		
	  		"order": [[ 1, "asc" ]],
	  		
	  		"language": {
	  		            "lengthMenu": "_MENU_",
	  		            "zeroRecords": "No se ha encontrado ning�n resultado con ese criterio.",
	  		            "info": "P�gina _PAGE_ de _PAGES_",
	  		            "infoEmpty": "No hay resultados disponibles.",
	  		            "infoFiltered": "(filtrado de _MAX_ resultados)",
	  		            "search": "Buscar: ",
	  		            "paginate": {
	  		                  "first": "Primera",
	  		                  "next": "�ltima",
	  		                  "next": "",
	  		                  "previous": ""
	  		                }
	  		        }
	  	});
	});
	</script>
</body>
</html>