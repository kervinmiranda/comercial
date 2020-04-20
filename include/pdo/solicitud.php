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

	// Search By Cedula
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
			    $data = 0;
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
			default:
				break;
		}
	}
		
}else{
	echo "notSessionActive";
}

?>