    
    <footer class="hidden-print">
    	<div class="container-fluid" role="footer">
    		<hr>
    		
    		<p class="text-center">
    			<small class="text-muted"><?php echo date('Y'); ?> &copy;  IESMonterroso. Todos los derechos reservados.</small>
    		</p>
    		<p class="text-center">
    			<small>
    				<a href="http://<?php echo $dominio; ?>/intranet/GPL.html">Licencia de uso</a>
    				&nbsp;&nbsp;&nbsp;&middot;&nbsp;&nbsp;&nbsp;
    				<a href="https://github.com/IESMonterroso/intranet">Github</a>
    			</small>
    		</p>
    	</div>
    </footer>
    
    <!-- BOOTSTRAP JS CORE -->
    <script src="http://<? echo $dominio;?>/intranet/js/jquery.js"></script>  
    <script src="http://<? echo $dominio;?>/intranet/js/bootstrap.min.js"></script>
    
    <!-- PLUGINS JS -->
    <script src="http://<? echo $dominio;?>/intranet/js/summernote.min.js"></script>
    <script src="http://<? echo $dominio;?>/intranet/js/summernote-es-ES.js"></script>
    <script src="http://<? echo $dominio;?>/intranet/js/bootstrap-datepicker.js"></script>
    <script src="http://<? echo $dominio;?>/intranet/js/buscarAlumnos.js"></script>
    
    <!--  Tablas de Bootstrap DataTables.  -->   

 <?
if (isset($datatables_activado)){
if ($datatables_activado){
	include("js/datatables/carga_datatables.php");
}        
}
?>
 <?
if (isset($imprimir_activado)){
if ($imprimir_activado){
	include("js/datatables/imprimir_datatables.php");
}        
}
?>
 <?
if (isset($datatables_min)){
if ($datatables_min){
	include("js/datatables/datatables_min.php");
}        
}
?>

 <script type="text/javascript">
    $("[rel=tooltip]").tooltip();
</script>

<script>
function confirmacion() {
	var answer = confirm("ATENCI�N:\n �Est�s seguro de que quieres borrar los datos? Esta acci�n es irreversible. Para borrarlo, pulsa Aceptar; de lo contrario, pulsa Cancelar.")
	if (answer){
return true;
	}
	else{
return false;
	}
}
</script>