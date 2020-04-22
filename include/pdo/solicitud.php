<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
include_once '../fecha.php';
@session_start();
if(isset($_SESSION['user'])){

	function getInitHours($horario){
		$data.= '<option value="">Seleccionar...</option>';
		switch ($horario){
			case '08:00 a.m 05:00 p.m':
				$horas = array("17:00", "18:00", "19:00", "20:00","21:00","22:00","23:00");							
				break;
			case '08:00 a.m 02:00 p.m':
				$horas = array("14:00", "15:00","16:00","17:00","18:00", "19:00");				
				break;
			case '02:00 p.m 08:00 p.m':
				$horas = array("08:00","09:00","10:00","11:00", "12:00", "13:00");
				break;
			default:
				break;
		}
		foreach ($horas as $hora){
			$data.= '<option value="'.$hora.'">'.date("h:i a", strtotime($hora)).'</option>';
		}
		return $data;		
	}

	function getEndHours($horario, $elegido){
		$hinicio = utf8_decode($elegido);
		$data.= '<option value="">Seleccionar...</option>';
		switch ($horario){
			case '08:00 a.m 05:00 p.m':
				$horas = array("18:00", "19:00", "20:00", "21:00","22:00","23:00","24:00");
				break;
			case '08:00 a.m 02:00 p.m':
				$horas = array("15:00","16:00","17:00","18:00", "19:00", "20:00");	
				break;
			case '02:00 p.m 08:00 p.m':
				$horas = array("08:00","09:00","10:00","44:00", "12:00", "13:00","14:00");				
				break;
			default:
				break;
		}
		foreach ($horas as $hora){
			if (strtotime($hora) > strtotime($hinicio)){
				$data.= '<option value="'.$hora.'">'.date("h:i a", strtotime($hora)).'</option>';
			}
		}
		return $data;
	}

	// Search By Cedula and Date
	function searchHours($ci, $date){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT id FROM comercial_horae WHERE ci =:ci AND fecha =:fechahe");
		$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
		$sql->bindParam(':fechahe', $date, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;
	}

	//Insert Hours
	function insertHours($cedula, $nombre, $fecha, $inicio, $final, $observacion){		
		$fechahe = cambiarFormatoFecha($fecha);	
		if (searchHours($cedula, $fechahe) == '0'){
			try {									
				$observacion = utf8_decode(ucwords(strtolower($observacion)));
				$usuario = $_SESSION['username']; // Nombre del usuario
				$fechahora = date('Y-m-d H:i:s'); //Fecha y Hora del registro
				//Calculamos las horas
				$total = (int)$final - (int)$inicio;
				if ($final > '19:00'){
					$nocturna = (int)$final - (int)'19:00';
					$diurna = $total - $nocturna;	
				}else{
					$nocturna = 0;
					$diurna = $total;
				}
			   	$sql = new Database();
			   	$sql->beginTransaction();
			    //Insert Hours
			    $sql->query("INSERT INTO comercial_horae(ci, fecha, inicio, final, diurna, nocturna, observacion, usuario, fecha_carga) VALUES ('$cedula', '$fechahe', '$inicio', '$final', '$diurna', '$nocturna', '$observacion', '$usuario', '$fechahora')");
			    $id = $sql->lastInsertId();
			    //Inserta Comment
			    $comentario2 = utf8_decode($usuario.' ha Realizado una de horas extras del empleado '.$nombre.' correspondiente a la fecha '.cambiarFormatoFecha2($fechahe));
			    $sql->query("INSERT INTO comercial_notificacion (destino, autor, comentario, fecha) VALUES ('administrador', '$usuario', '$comentario2', '$fechahora')");
		    	$sql->commit();
		    	$data = $id;				
			} catch (Exception $e){
			    $sql->rollback();
			    $data = "0";
			}
		}else{
			$data = "repetido";
		}
		return $data;
	}

	// Search By Cedula and Date Off
	function searchDayOff($ci, $date){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT id FROM comercial_diasl WHERE ci =:ci AND fecha =:fechahe");
		$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
		$sql->bindParam(':fechahe', $date, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;
	}

	//Insert DAy Off
	function insertDayOff($cedula, $nombre, $fecha, $observacion){
		$fechadl = cambiarFormatoFecha($fecha);	
		if (searchDayOff($cedula, $fechadl) == '0'){
			try {									
				$observacion = utf8_decode(ucwords(strtolower($observacion)));
				$usuario = $_SESSION['username']; // Nombre del usuario
				$fechahora = date('Y-m-d H:i:s'); //Fecha y Hora del registro				
			   	$sql = new Database();
			   	$sql->beginTransaction();
			    //Insert Hours
			    $sql->query("INSERT INTO comercial_diasl(ci, fecha, observacion, usuario, fecha_carga) VALUES ('$cedula', '$fechadl', '$observacion', '$usuario', '$fechahora')");
			    $id = $sql->lastInsertId();
			    //Inserta Comment
			   	$comentario2 = utf8_decode($usuario.' ha generado una carga de día libre no laborado del empleado '.$nombre.' correspondiente a la fecha '.cambiarFormatoFecha2($fechadl));
			    $sql->query("INSERT INTO comercial_notificacion (destino, autor, comentario, fecha) VALUES ('administrador', '$usuario', '$comentario2', '$fechahora')");
		    	$sql->commit();
		    	$data = $id;				
			} catch (Exception $e){
			    $sql->rollback();
			    $data = "0";
			}
		}else{
			$data = "repetido";
		}
		return $data;
	}

	// Search Vacations by Cedula
	function searchVacations($ci, $start, $end){
		$inicio = cambiarFormatoFecha($start);
		$final = cambiarFormatoFecha($end);
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT id FROM comercial_vacacion WHERE ci =:ci AND (inicio  BETWEEN :inicio AND :final OR final BETWEEN :inicio AND :final) AND estatus != 'No Aprobada'");
		$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
		$sql->bindParam(':inicio', $inicio, PDO::PARAM_STR);
		$sql->bindParam(':final', $final, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;
	}

	//Insert Vacations
	function insertVacations($cedula, $nombre, $apellido, $ingreso, $agencia, $telefono, $periodo, $inicio, $final, $supervisor, $observacion, $turno){
		$inicio = cambiarFormatoFecha($inicio);
		$final = cambiarFormatoFecha($final);
		if (searchVacations($cedula, $inicio, $final) == '0'){
			try {
				$fecha = date('Y-m-d');
				$nombre = mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
				$apellido = mb_convert_case($apellido, MB_CASE_TITLE, "UTF-8");
				$ingreso = cambiarFormatoFecha($ingreso);
				$agencia = mb_convert_case($agencia, MB_CASE_TITLE, "UTF-8");
				$supervisor = mb_convert_case($supervisor, MB_CASE_TITLE, "UTF-8");
				$observacion = mb_convert_case($observacion, MB_CASE_TITLE, "UTF-8");
				$usuario = $_SESSION['username']; // Nombre del usuario
				$fechahora = date('Y-m-d H:i:s'); //Fecha y Hora del registro			
			   	$sql = new Database();
			   	$sql->beginTransaction();
			    //Insert Hours
			    $sql->query("INSERT INTO comercial_vacacion (fecha, ci, nombre, apellido, ingreso, agencia, telefono, periodo, inicio, final, supervisor, usuario, observacion, turno, fecha_carga) VALUES ('$fecha', '$cedula', '$nombre', '$apellido', '$ingreso','$agencia', '$telefono', '$periodo', '$inicio', '$final', '$supervisor', '$usuario', '$observacion', '$turno', '$fechahora')");
			    $id = $sql->lastInsertId();
			    //Inserta Comment
			   $comentario2 = utf8_decode($usuario.' ha generado una solicitud de vacaciones del empleado '.$nombre.' '.$apellido);
			    $sql->query("INSERT INTO comercial_notificacion (destino, autor, comentario, fecha) VALUES ('administrador', '$usuario', '$comentario2', '$fechahora')");
		    	$sql->commit();
		    	$data = $id;				
			} catch (Exception $e){
			    $sql->rollback();
			    $data = "0";
			}
		}else{
			$data = "repetido";
		}
		return $data;
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case "getInitHours":
				echo getInitHours($_POST['horario']);
				break;
			case "getEndHours":
				echo getEndHours($_POST['horario'], $_POST['elegido']);
				break;
			case "insertHours":
				echo insertHours($_POST['cedula'], $_POST['nombre'], $_POST['fecha'], $_POST['inicio'], $_POST['final'], $_POST['observacion']);
				break;
			case "insertDayOff":
				echo insertDayOff($_POST['cedula'], $_POST['nombre'], $_POST['fecha'], $_POST['observacion']);
				break;
			case "insertVacations":
				echo insertVacations($_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['ingreso'], $_POST['agencia'], $_POST['telefono'], $_POST['periodo'],$_POST['inicio'], $_POST['final'], $_POST['supervisor'], $_POST['observacion'], $_POST['turno']);
				break;
			default:
				break;
		}
	}
		
}else{
	echo "notSessionActive";
}

?>