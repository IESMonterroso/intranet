<?
ini_set("memory_limit","192M");
session_start();
include("../../config.php");
if($_SESSION['autentificado']!='1')
{
	session_destroy();
	header("location:http://$dominio/intranet/salir.php");
	exit;
}
registraPagina($_SERVER['REQUEST_URI'],$db_host,$db_user,$db_pass,$db);
?>

<?php
include("../../menu.php");
if (isset($_POST['nivel'])) {
	$nivel = $_POST['nivel'];
} 
elseif (isset($_GET['nivel'])) {
	$nivel = $_GET['nivel'];
} 
else
{
$nivel="";
}
if (isset($_POST['grupo'])) {
	$grupo = $_POST['grupo'];
}
elseif (isset($_GET['grupo'])) {
	$grupo = $_GET['grupo'];
} 
else
{
$grupo="";
}
if (isset($_POST['nombre'])) {
	$nombre = $_POST['nombre'];
}
elseif (isset($_GET['nombre'])) {
	$nombre = $_GET['nombre'];
} 
if (isset($_POST['claveal'])) {
	$claveal = $_POST['claveal'];
}
elseif (isset($_GET['claveal'])) {
	$claveal = $_GET['claveal'];
} 
?>
<br />
  <div align=center>
  <div class="page-header" align="center">
  <h2>Fotos de los Alumnos <small>Registro de fotograf�as de <? echo $nivel."-".$grupo;?></small></h2>
</div>
<?

if (isset($_POST['enviar']))
{
	$ok=0;
	if ($_FILES['File']['size']>0) {
	$fotos_dir = "../../xml/fotos/";
	if ($_FILES['File']['size']>'30000' and $_FILES['File']['size']<'2000000') {
		if (stristr($_FILES['File']['type'],"image/jp")==TRUE) {			
			$extension="jpg";
			$n_foto=$claveal.".".$extension;
			$n_foto0=$claveal."0.".$extension;
			$arch0 = $fotos_dir.$n_foto0;

			$arch = $fotos_dir.$n_foto;
			move_uploaded_file($_FILES['File']['tmp_name'], $arch0) or die("No es posible subir la foto");
			chmod($arch0,0777);

			function redimensionar_jpeg($img_original, $img_nueva, $img_nueva_anchura, $img_nueva_altura, $img_nueva_calidad) {
				// crear imagen desde original
				$img = ImageCreateFromJPEG($img_original);
				// crear imagen nueva
				$thumb = ImageCreatetruecolor($img_nueva_anchura,$img_nueva_altura);
				// redimensionar imagen original copiandola en la imagen
				ImageCopyResampled($thumb,$img,0,0,0,0,$img_nueva_anchura,$img_nueva_altura,ImageSX($img),ImageSY($img));
				// guardar la imagen redimensionada donde indicia $img_nueva
				ImageJPEG($thumb,$img_nueva,$img_nueva_calidad);
			}
			// Calcular tama�o ideal
			$tam = getimagesize($arch0);
			$ancho0 = $tam[0];
			$alto0 = $tam[1];
			$cent = 600 * 100 / $ancho0;
			$cent_alto = $cent *$alto0 / 100;

			redimensionar_jpeg($arch0,$arch,600,$cent_alto,100);
			$nuevo_tama�o = filesize($arch);
			copy($arch0,"../../xml/fotos/".$claveal.".jpg");
			unlink($arch0);
		}
		else {
			$ok.="1";
			echo '<div align="center"><div class="alert alert-danger alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
El archivo que est&aacute;s enviando no es un tipo de imagen v&aacute;lido. Selecciona un archivo de imagen con formato JPG.
          </div></div>';
		}
		
	}
	else{
	
		if ($_FILES['File']['size']< '30000') {
			$ok.="1";
			echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
La fotograf&iacute;a no tiene suficiente resoluci&oacute;n, por lo que su visualizaci&oacute;n ser&aacute; necesariamente defectuosa. Es conveniente que actualices la foto eligiendo una nueva con mayor calidad. 
          </div></div>';
		}
		else{
			$ok.="1";
			echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
La fotograf&iacute;a tiene excesiva resoluci&oacute;n. Es conveniente que actualices la foto eligiendo una nueva con menor resoluci�n (o tama�o, como quieras). 
          </div></div>';	
		}
		
	}
	if ($ok==0) {
		echo '<div align="center"><div class="alert alert-success alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
            La fotograf�a se ha actualizado correctamente. Si la foto que ves abajo es la antigua, sal de esta p�gina y vuelve a entrar: comprobar�s que la foto se ha actualizado.
          </div></div>';	
	}	
	}
	else{
		$ok.="1";
		echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
            No has seleccionado ninguna fotograf&iacute;a. Elige una archivo con la fotograf�a e int�ntalo de nuevo.
          </div></div>';
	}
}
?>
<form action="fotos.php" method="POST">

<select name="nombre"  onchange="submit()" style="" class="span3"  />
<?
if (strlen($nombre) > '5')
    {
?>
<OPTION><? echo $nombre;?></OPTION>
<?
    }
$alumno = mysql_query(" SELECT distinct APELLIDOS, NOMBRE , CLAVEAL FROM FALUMNOS WHERE NIVEL = '$nivel' AND GRUPO = '$grupo' order by NC asc");
echo "<OPTION></OPTION>";
    while ($falumno = mysql_fetch_array($alumno))
       {
          echo "<OPTION>$falumno[0], $falumno[1] --> $falumno[2] </OPTION>";
       }
?>
</select>
<INPUT TYPE=hidden NAME="nivel" value="<? echo $nivel;?>">
<INPUT TYPE=hidden NAME="grupo" value="<? echo $grupo;?>">
 </form>
 
 <div class="well well-large" align='center' style="width:500px;">
<?
echo "<p class='help-block' style='text-align:left;'>Haz click en el bot�n de abajo para seleccionar el archivo con la fotograf�a que quieres registrar o actualizar. La fotograf�a debe tener una resoluci�n m�nima de 40KB y m�xima de 600KB. El archivo de imagen debe ser JPG.</p>" ;
$foto_ya="";

if (strlen($_POST['nombre']) > '5')
 {
 	$tr = explode(" --> ",$_POST['nombre']);
	$claveal = $tr[1];
	if(file_exists("../../xml/fotos/".$claveal.".jpg")){
	$grande=filesize("../../xml/fotos/".$claveal.".jpg");
	$foto_ya='1';
	}
	echo "<Form action='fotos.php' METHOD=Post ENCTYPE='multipart/form-data'>";
	echo '<INPUT TYPE="hidden" NAME="claveal" value="'.$claveal.'">';
	echo '<INPUT TYPE="hidden" NAME="nombre" value="'.$nombre.'">';
	?>
	<INPUT TYPE=hidden NAME="nivel" value="<? echo $nivel;?>">
	<INPUT TYPE=hidden NAME="grupo" value="<? echo $grupo;?>">	
	<?
	print("<INPUT TYPE='file' NAME='File' ><br /> ");
	echo "<br />";
	if ($foto_ya=='1') {
		print("<INPUT TYPE=SUBMIT NAME='enviar' VALUE='Actualizar fotograf&iacute;a' class='btn btn-primary' ></FORM>\n");
	}
	else {
		print("<INPUT TYPE=SUBMIT NAME='enviar' VALUE='Enviar fotograf&iacute;a' class='btn btn-primary'></FORM>");
	}

	echo "<div align='center'><hr>";
	if ($foto_ya=='1') {
		echo "<img src='../../xml/fotos/$claveal.jpg' border='2' width='200' height='238' style='margin-top:10px;border:1px solid #bbb;'  /><br /><br />";
	}
	else {
		echo "<div style='margin-top:10px;border:1px solid #bbb;width:100px;height:119px;color:#9d261d;' />Sin Foto</div><br />";
	}
	if ($foto_ya=='1' and $grande < '30000') {
		echo '<div align="center"><div class="alert alert-warning alert-block fade in" style="max-width:500px;">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<h5>ATENCI�N:</h5>
La fotograf&iacute;a no tiene suficiente resoluci&oacute;n, por lo que su visualizaci&oacute;n ser&aacute; necesariamente defectuosa. Es conveniente que actualizes la foto eligiendo una nueva con mayor calidad. 
          </div></div>';
	}
}
  ?>
</div>
<? include("../../pie.php");?>
</body>
</html>
