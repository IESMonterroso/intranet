<? include("../conf_principal.php");?>
<? include "../cabecera.php"; ?>
<? include('../menu.php'); ?>
<div class="span9">	
<br>
<h3 align='center'><i class='icon icon-gears'> </i> Direcci�n del Centro</h3><hr />

<div class="tabbable" style="margin-bottom: 18px;">
<ul class="nav nav-tabs">
<li class="active"><a href="#tab1" data-toggle="tab">Equipo directivo</a></li>
<li><a href="#tab2" data-toggle="tab">Funciones</a></li>
<li><a href="#tab3" data-toggle="tab">Tel�fonos y Contacto</a></li>
<li><a href="http://<? echo $dominio;?>doc/index.php?&direction=0&order=&directory=Documentos%20del%20Centro">Documentaci�n</a></li>
</ul>
<div class="tab-content" style="padding-bottom: 9px; border-bottom: 1px solid #ddd;">
<div class="tab-pane fade in active" id="tab1">
<?
echo "<br /><p class='lead muted' align='center'><i class='icon icon-user'></i> Equipo directivo</p>";
include("direccion.php");
?>
</div>
<div class="tab-pane fade in" id="tab2">
<?
echo "<br /><p class='lead muted' align='center'><i class='icon icon-gears'> </i> Funciones del Equipo directivo</p>";
include("funciones.htm");
?>
</div>
<div class="tab-pane fade in" id="tab3">
<?
echo "<br /><p class='lead muted' align='center'><i class='icon icon-phone'> </i> Tel�fonos y contacto</p>";
include("telefonos.html");
?>
</div>
<div class="tab-pane fade in" id="tab4">
<?
echo "<br /><p class='lead muted' align='center'><i class='icon icon-file'> </i> Documentaci�n</p>";
?>
</div>
</div>
</div>
</div>

<? include("../pie.php");?>

