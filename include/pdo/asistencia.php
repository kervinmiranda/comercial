<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){

//Guardar Asistencia de Agencia
	function saveAssists($agencia, $comentario, $asistencia){
		try {
			//Save Assists
			$fecha = date('Y-m-d');//Obtener la fecha del día
			$hora = date("H:i:s");//Obtener la hora de la operación
			$fechahora = date('Y-m-d H:i:s'); //Fecha y Hora del registro
			$usuario = $_SESSION['username']; // Nombre del usuario
		   	$sql = new Database();
		   	$sql->beginTransaction();
		    //Insert Header
		    $sql->query("INSERT INTO comercial_asistencia (fecha, hora, usuario, agencia, comentario) VALUES ('$fecha', '$hora', '$usuario', '$agencia', '$comentario')");
		    $id = $sql->lastInsertId();
		    //Inserta Detail
		    foreach ($asistencia as $valor) {
		    	$ced = $valor[0];
		    	$tipo = $valor[1];
		    	$observacion = $valor[2];
			   	$sql->query("INSERT INTO comercial_asistencia_detalle (id, empleado, asistencia, observacion) VALUES ('$id', '$ced', '$tipo', '$observacion')");
			}
		    $sql->commit();		    
		    return $id;
		} catch (Exception $e){
		    $sql->rollback();
		    return 0;
		}
	}

	//Get Assits
	function getAssists($agencie, $from, $to){
		$objdatabase = new Database();
		switch ($agencie) {
			case 'TODAS':
				$sql = $objdatabase->prepare("SELECT DATE_FORMAT(asi.fecha, '%d/%m/%Y') as fecha, age.descripcion, det.empleado, pla.nombre, pla.apellido, pla.cargo, det.asistencia, det.observacion, asi.id FROM comercial_asistencia_detalle det INNER JOIN comercial_asistencia asi ON det.id = asi.id INNER JOIN comercial_plantilla pla ON det.empleado = pla.ci INNER JOIN comercial_agencia age ON asi.agencia = age.codigo WHERE fecha BETWEEN :desde AND :hasta");
				break;			
			default:
				$sql = $objdatabase->prepare("SELECT DATE_FORMAT(asi.fecha, '%d/%m/%Y') as fecha, age.descripcion, det.empleado, pla.nombre, pla.apellido, pla.cargo, det.asistencia, det.observacion, asi.id FROM comercial_asistencia_detalle det INNER JOIN comercial_asistencia asi ON det.id = asi.id INNER JOIN comercial_plantilla pla ON det.empleado = pla.ci INNER JOIN comercial_agencia age ON asi.agencia = age.codigo WHERE asi.agencia =:agencia AND fecha BETWEEN :desde AND :hasta");
				//Definimos los parametros de la Query
				$sql->bindParam(':agencia', $agencie, PDO::PARAM_STR);
				break;
		}		
		$sql->bindParam(':desde', $from, PDO::PARAM_STR);
		$sql->bindParam(':hasta', $to, PDO::PARAM_STR);
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount(); // se confirma que el query exista	
		$data = null;		
		if($count){
			$data = $sql->fetchAll();			
		}
		$objdatabase = null;
		return $data;
	}

	//Get Asistence By Id
	function getAsistenciaById($detalle, $cedula){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT DATE_FORMAT(asi.fecha, '%d/%m/%Y') fecha, age.descripcion, det.empleado, CONCAT(pla.nombre, ' ' , pla.apellido) nombre, det.asistencia, det.observacion FROM comercial_asistencia_detalle det INNER JOIN comercial_asistencia asi ON det.id = asi.id INNER JOIN comercial_plantilla pla ON det.empleado = pla.ci INNER JOIN comercial_agencia age ON asi.agencia = age.codigo WHERE det.id =:detalle AND empleado =:cedula");
		//Definimos los parametros de la Query
		$sql->bindParam(':detalle', $detalle, PDO::PARAM_STR);
		$sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$result = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$result = "0";
		}
		$objdatabase = null;
		return $result;
	}

	//Edit Assistence
	function editAssist($detalle, $cedula, $asistencia, $observacion){
		$edicion = date('Y-m-d H:i:s');
		$editor = $_SESSION['username'];
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE comercial_asistencia_detalle SET asistencia =:asistencia, observacion =:observacion, edicion =:edicion, editor =:editor WHERE id =:detalle AND empleado =:cedula");
		//Definimos los parametros de la Query		$
		$sql->bindParam(':asistencia', $asistencia, PDO::PARAM_STR);
		$sql->bindParam(':observacion', $observacion, PDO::PARAM_STR);
		$sql->bindParam(':edicion', $edicion, PDO::PARAM_STR);
		$sql->bindParam(':editor', $editor, PDO::PARAM_STR);
		$sql->bindParam(':detalle', $detalle, PDO::PARAM_STR);
		$sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
		   $data = "1";
		} else {
		   $data = "0";
		}		
		return $data;
	}
	

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case "saveAssists":
				echo saveAssists($_POST['agencia'], $_POST['comentario'], $_POST['asistencia']);
				break;
			case "getAssists":
				$results = array("aaData"=>getAssists($_POST['agencia'], $_POST['desde'], $_POST['hasta']));
				echo json_encode($results);
				break;
			case "getAsistenciaById":
				echo json_encode(getAsistenciaById($_POST['detalle'], $_POST['cedula']));	
				break;
			case "editAssist":
				echo editAssist($_POST['detalle'], $_POST['cedula'], $_POST['asistencia'], $_POST['observacion']);
				break;
			default:
				break;
		}
	}
		
}else{
	echo "notSessionActive";
}

?>