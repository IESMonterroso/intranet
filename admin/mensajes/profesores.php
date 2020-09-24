<?php defined('INTRANET_DIRECTORY') OR exit('No direct script access allowed');

if(isset($_POST['submit1'])) {

	if(!$asunto or empty($texto) or empty($profesor)) {
  		$msg_error = "Todos los campos del formulario son obligatorios.";
  	}
	elseif(!$profeso and !$tutor and !$departamento and !$equipo and !$profesores_nocturnos and !$profesores_diurnos and !$etcp and !$ca and !personal and !$claustro and !$direccion and !$orientacion and !$bilingue and !$biblio and !$convivencia and !($padres) and !$dfeie and !$pas) {
		$msg_error = "Debes seleccionar al menos un destinatario.";
	}
	else {

		$result = mysqli_query($db_con, "INSERT INTO mens_texto (asunto, texto, origen) VALUES ('".$asunto."','".$texto."','".$profesor."')");
		$id = mysqli_insert_id($db_con);

		if (! $result) {
			$msg_error = "Se ha producido un error al enviar el mensaje. Error: ".mysqli_error($db_con);
		}
		else {

			$ok=0;

			if($profeso)
				{
			$profiso = $_POST["profeso"];
				foreach($profiso as $nombre)
				{
				$trozo = explode(";",$nombre);
				$idea = $trozo[0];
				$query1="insert into mens_profes (id_texto, profesor) values ('".$id."','".$idea."')";
				mysqli_query($db_con, $query1);
				$t_nombres.=$idea."; ";
				}
				$ok=1;
				mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
				}

			if($tutor)
				{
			$tu = $_POST["tutor"];
				foreach($tu as $nombre_tutor)
				{
				$nombre_tut = explode("-->",$nombre_tutor);
				$nombre_tuto = trim($nombre_tut[0]);
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$nombre_tuto'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$nombre_tuto."')");
				$t_nombres.=$nombre_tuto."; ";
				}
				mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
				$ok=1;
				}

			if($departamento)
				{
			$dep = $_POST["departamento"];
				foreach($dep as $nombre_dep)
				{
				$dep0 = mysqli_query($db_con, "select distinct idea from departamentos where departamento = '$nombre_dep'");
				while($dep1 = mysqli_fetch_array($dep0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$dep1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$dep1[0]."')");
				}
				$t_nombres.="Departamento de ".$nombre_dep."; ";
				}
				mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
				$ok=1;
				}

			if($equipo)
				{
			$eq = $_POST["equipo"];
			foreach($eq as $nombre_eq)
				{
				$eso = mysqli_query($db_con,"select distinct curso from alma where unidad = '$nombre_eq' and curso like '%E.S.O.%'");
				if(mysqli_num_rows($eso)>0){$extra_eso="or cargo like '%8%'";}else{$extra_eso="";}
				$eq0 = mysqli_query($db_con, "select distinct idea from profesores, departamentos where nombre = profesor and grupo = '$nombre_eq' $extra_eso");
				while($eq1 = mysqli_fetch_array($eq0))
				{
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$eq1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$eq1[0]."')");
				}
				$t_nombres.="Equipo Educativo de ".$nombre_eq."; ";
				}
				mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
				$ok=1;
				}

			$prof_nocturnos = $_POST["profesores_nocturnos"];
			foreach ($prof_nocturnos as $idea_prof) {
				$prof_nocturnos0 = mysqli_query($db_con, "select distinct idea from departamentos where idea = '$idea_prof'");

				while ($prof_nocturnos1 = mysqli_fetch_array($prof_nocturnos0)) {
					$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$prof_nocturnos1[0]'");
					$num0 = mysqli_fetch_row($rep0);

					if (strlen($num0[0]) < 1) {
						mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$prof_nocturnos1[0]."')");
					}

					$t_nombres.="Profesorado nocturno";
					mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
					$ok=1;
				}
			}

			$prof_diurnos = $_POST["profesores_diurnos"];
			foreach ($prof_diurnos as $idea_prof) {
				$prof_diurnos0 = mysqli_query($db_con, "select distinct idea from departamentos where idea = '$idea_prof'");

				while ($prof_diurnos1 = mysqli_fetch_array($prof_diurnos0)) {
					$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$prof_diurnos1[0]'");
					$num0 = mysqli_fetch_row($rep0);

					if (strlen($num0[0]) < 1) {
						mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$prof_diurnos1[0]."')");
					}

					$t_nombres.="Profesorado diurno";
					mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
					$ok=1;
				}
			}

			if($ca == '1')
				{
				$ca0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%9%'");
				while($ca1 = mysqli_fetch_array($ca0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$ca1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$ca1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'CA' where id = '$id'");
				$ok=1;
				}

			if($etcp == '1')
				{
				$etcp0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%4%'");
				while($etcp1 = mysqli_fetch_array($etcp0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$etcp1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$etcp1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'ETCP' where id = '$id'");
				$ok=1;
				}

            if($personal == '1')
            {
                $cl0 = mysqli_query($db_con, "select distinct idea from departamentos where departamento not like 'Admin'");
                while($cl1 = mysqli_fetch_array($cl0)){
                    $rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$cl1[0]'");
                    $num0 = mysqli_fetch_row($rep0);
                    if(strlen($num0[0]) < 1)
                        mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$cl1[0]."')");
                }
                mysqli_query($db_con, "update mens_texto set destino = 'Personal del Centro' where id = '$id'");
                $ok=1;
            }

			if($claustro == '1')
				{
				$cl0 = mysqli_query($db_con, "select distinct idea from departamentos where departamento not like 'Administracion' and departamento not like 'Admin' and departamento not like 'Conserjeria'");
				while($cl1 = mysqli_fetch_array($cl0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id_texto = '$id' and profesor = '$cl1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$cl1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'Claustro del Centro' where id = '$id'");
				$ok=1;
				}

			if($direccion == '1')
				{
				$dir0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%1%' and idea <> 'admin'");
				while($dir1 = mysqli_fetch_array($dir0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$dir1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$dir1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'Equipo Directivo' where id = '$id'");
				$ok=1;
				}

			if($orientacion == '1')
				{
				$orienta0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%8%'");
				while($orienta1 = mysqli_fetch_array($orienta0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$orienta1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$orienta1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'Departamento de Orientaci&oacute;n' where id = '$id'");
				$ok=1;
				}

			if($bilingue == '1')
				{
				$bilingue0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%a%'");
				while($bilingue1 = mysqli_fetch_array($bilingue0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$bilingue1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$bilingue1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'Bilinguismo' where id = '$id'");
				$ok=1;
				}

			if($convivencia == '1')
				{
				$convivencia0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%b%'");
				while($convivencia1 = mysqli_fetch_array($convivencia0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$convivencia1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$convivencia1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'Aula de Convivencia' where id = '$id'");
				$ok=1;
				}

			if($dfeie == '1')
				{
				$dfeie0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%f%'");
				while($dfeie1 = mysqli_fetch_array($dfeie0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$dfeie1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$dfeie1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'DFEIE' where id = '$id'");
				$ok=1;
				}

			if($pas == '1')
				{
				$pas0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%7%'");
				while($pas1 = mysqli_fetch_array($pas0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$pas1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$pas1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'PAS' where id = '$id'");
				$ok=1;
				}

			if($biblio == '1')
				{
				$biblio0 = mysqli_query($db_con, "select distinct idea from departamentos where cargo like '%c%'");
				while($biblio1 = mysqli_fetch_array($biblio0)){
				$rep0 = mysqli_query($db_con, "select * from mens_profes where id = '$id' and profesor = '$biblio1[0]'");
				$num0 = mysqli_fetch_row($rep0);
				if(strlen($num0[0]) < 1)
				mysqli_query($db_con, "insert into mens_profes (id_texto, profesor) values ('".$id."','".$biblio1[0]."')");
				}
				mysqli_query($db_con, "update mens_texto set destino = 'Biblioteca' where id = '$id'");
				$ok=1;
				}

			if($padres)
				{
			$pa = $_POST["padres"];
				foreach($pa as $nombre)
				{
				$query1="insert into mens_profes (id_texto, profesor) values ('".$id."','".$nombre."')";
				mysqli_query($db_con, $query1);
				$t_nombres.=$nombre."; ";
				}
				mysqli_query($db_con, "update mens_texto set destino = '$t_nombres' where id = '$id'");
				$ok=1;
				}

			if($ok) {
				header('Location:'.'index.php?inbox=recibidos&action=send');
				exit;
			}
		}

	}
}
