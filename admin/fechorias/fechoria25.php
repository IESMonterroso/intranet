<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed'); 

$dia0 = explode ( "-", $fecha );
$fecha3 = "$dia0[2]-$dia0[1]-$dia0[0]";

// Control de errores
if (! $notas or ! $grave or ! $_POST['nombre'] or ! $asunto or ! $fecha or ! $informa or $fecha=='0000-00-00') {
	echo '<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI&Oacute;N:</legend>
            No has introducido datos en alguno de los campos, y <strong>todos son obligatorios</strong>.<br> Vuelve atr&aacute;s, rellena los campos vac&iacute;os e int&eacute;ntalo de nuevo.
          </div></div>';
}
elseif (strlen ($notas) < '10' ) {
	echo '<div align="center"><div class="alert alert-danger alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
			<legend>ATENCI&Oacute;N:</legend>
            La descripci&oacute;n de lo sucedido es demasiado breve. Es necesario que proporciones m&aacute;s detalles de lo ocurrido para que Jefatura de Estudios y Tutor puedan hacerse una idea precisa del suceso.<br />Vuelve atr&aacute;s e int&eacute;ntalo de nuevo.
          </div></div>';
}
elseif (isset($_POST['nombre'])) {
	
if (is_array($nombre)) {
	$num_a = count($_POST['nombre']);
}
else{
	$num_a=1;
}

$z=0;
for ($i=0;$i<$num_a;$i++){
	if ($num_a==1 and !is_array($nombre)) {
		$claveal = $nombre;
	}
	else{
	$claveal = $nombre[$i];
	}
	$ya_esta = mysqli_query($db_con, "select claveal, fecha, grave, asunto, notas, informa from Fechoria where claveal = '$claveal' and fecha = '$fecha3' and grave = '$grave' and asunto = '$asunto' and informa = '$informa'");

	if (mysqli_num_rows($ya_esta)>0) {
		echo '<br /><div align="center"><div class="alert alert-warning alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <legend>Atenci&oacute;n:</legend>
            Ya hay un problema de convivencia registrado que contiene los mismos datos que est&aacute;s enviando, y no queremos repetirlos... .
          </div></div><br />';
	}
	else{

		$alumno = mysqli_query($db_con, " SELECT distinct FALUMNOS.APELLIDOS, FALUMNOS.NOMBRE, FALUMNOS.unidad, FALUMNOS.nc, FALUMNOS.CLAVEAL, alma.TELEFONO, alma.TELEFONOURGENCIA FROM FALUMNOS, alma WHERE FALUMNOS.claveal = alma.claveal and FALUMNOS.claveal = '$claveal'" );
		$rowa = mysqli_fetch_array ( $alumno );
		$apellidos = trim ( $rowa [0] );
		$nombre_alum = trim ( $rowa [1] );
		$unidad = trim ( $rowa [2] );
		$tfno = trim ( $rowa [5] );
		$tfno_u = trim ( $rowa [6] );

		// SMS
		if ($config['mod_sms']) {


			$hora_f = date ( "G" );
			if (($grave == "grave" or $grave == "muy grave") and (substr ( $tfno, 0, 1 ) == "6" or substr ( $tfno, 0, 1 ) == "7" or substr ( $tfno_u, 0, 1 ) == "6" or substr ( $tfno_u, 0, 1 ) == "7") and $hora_f > '8' and $hora_f < '17') {
				$sms_n = mysqli_query($db_con, "select max(id) from sms" );
				$n_sms = mysqli_fetch_array ( $sms_n );
				$extid = $n_sms [0] + 1;

				if (substr ( $tfno, 0, 1 ) == "6" or substr ( $tfno, 0, 1 ) == "6") {
					$mobile = $tfno;
				} else {
					$mobile = $tfno_u;
				}
				$message = "Su hijo/a ha cometido una falta contra las normas de convivencia del Centro. Hable con su hijo/a y, ante cualquier duda, consulte en http://".$config['dominio'];
				mysqli_query($db_con, "insert into sms (fecha,telefono,mensaje,profesor) values (now(),'$mobile','$message','$informa')" );
				
				// ENVIO DE SMS
				$sms = new Trendoo_SMS();
				$sms->sms_type = SMSTYPE_GOLD_PLUS;
				$sms->add_recipient('+34'.$mobile);
				$sms->message = $message;
				$sms->sender = $config['mod_sms_id'];
				$sms->set_immediate();
				if ($sms->validate()) $sms->send();

				$fecha2 = date ( 'Y-m-d' );
				$observaciones = $message;
				$accion = "Env&iacute;o de SMS";
				$causa = "Problemas de convivencia";
				mysqli_query($db_con, "insert into tutoria (apellidos, nombre, tutor,unidad,observaciones,causa,accion,fecha, claveal) values ('" . $apellidos . "','" . $nombre_alum . "','" . $informa . "','" . $unidad ."','" . $observaciones . "','" . $causa . "','" . $accion . "','" . $fecha2 . "','" . $claveal . "')" );
			}
		}

		// FIN SMS

		$dia = explode ( "-", $fecha );
		$fecha2 = "$dia[2]-$dia[1]-$dia[0]";
		$query = "insert into Fechoria (CLAVEAL,FECHA,ASUNTO,NOTAS,INFORMA,grave,medida,expulsionaula) values ('" . $claveal . "','" . $fecha2 . "','" . $asunto . "','" . $notas . "','" . $informa . "','" . $grave . "','" . $medida . "','" . $expulsionaula . "')";
	 //echo $query."<br>";
	 $inserta = mysqli_query($db_con, $query );
	 if ($inserta) {
	 	$z++;
	 }
	 $nfechoria = "select max(id) from Fechoria where claveal = '$claveal'";
	 $nfechoria0 = mysqli_query($db_con, $nfechoria );
	 $nfechoria1 = mysqli_fetch_row ( $nfechoria0 );
	 $id = $nfechoria1 [0];

	 // Envío de Email
	 $cor_control = mysqli_query($db_con,"select correo from control where claveal='$claveal'");
	 $cor_alma = mysqli_query($db_con,"select correo from alma where claveal='$claveal'");
	 if(mysqli_num_rows($cor_alma)>0){
	 	$correo1=mysqli_fetch_array($cor_alma);
	 	$correo = $correo1[0];
	 }
	 elseif(mysqli_num_rows($cor_control)>0){
	 	$correo2=mysqli_fetch_array($cor_control);
	 	$correo = $correo2[0];
	 }
	 if (strlen($correo)>0) {
	 	/*	
	 	 $texto_pie = '<br><br><hr>Este correo es informativo. Por favor no responder a esta direcci&oacute;n de correo, ya que no se encuentra habilitada para recibir mensajes. Si necesita mayor informaci&oacute;n sobre el contenido de este mensaje, p&oacute;ngase en contacto con <strong> Jefatura de Estudios</strong>.';
	 	 $mail = new PHPMailer();
	 	 $mail->Host = "localhost";
	 	 $mail->From = 'no-reply@'.$config['dominio'];
	 	 $mail->FromName = $config['centro_denominacion'];
	 	 $mail->Sender = 'no-reply@'.$config['dominio'];
	 	 $mail->IsHTML(true);
	 	 $mail->Subject = $config['centro_denominacion'].': Comunicaci&oacute;n de Problemas de Convivencia a la familia del Alumno.';
	 	 $mail->Body = "El ".$config['centro_denominacion']." le comunica que, con fecha $fecha, su hijo ha cometido una falta $grave contra las normas de convivencia del Centro. El tipo de falta es el siguiente: $asunto.<br>Le recordamos que puede conseguir informaci&oacute;n m&aacute;s detallada en la p&aacute;gina del alumno de nuestra web en http://"-$config['dominio'].", o bien contactando con la Jefatura de Estudios del Centro. <hr><br><br> $texto_pie";
	 	 $mail->AddAddress($correo, $nombre_alumno);
	 	 $mail->Send();	
	 	 */
	 }
	}
}

//if (is_array($nombre)) {
unset ($unidad);
//}
unset($nombre);
unset ($id);
unset ($claveal);
if ($z>0) {
	echo '<br /><div align="center"><div class="alert alert-success alert-block fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Se han registrado correctamente los Problemas de Convivencia de '.$z.' alumnos.
          </div></div><br />';
}
}
?>

