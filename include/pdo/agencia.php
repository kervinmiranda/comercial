<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){
	
	//Get Active Agencies
	function getAgencies(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_agencia WHERE estatus = '1' ORDER BY descripcion ASC");		
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount(); // se confirma que el query exista	
		$data = null;		
		if($count){
			$data = $sql->fetchAll();			
		}
		$objdatabase = null;
		return $data;
	}

	// Get All Agencies
	function getAllAgencies(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_agencia ORDER BY descripcion ASC");		
		$sql->execute();//Exjecutamos la Query		
		$count = $sql->rowCount();//Verificamos el resultado
		$data = null;		
		if($count){
			$data = $sql->fetchAll();				
		}
		$objdatabase = null;
		$results = array("aaData"=>$data);
		echo json_encode($results);
	}

	// Insert Agency
	function insertAgency($codigo, $horario, $descripcion, $tipo, $departamento){
		if (searchByName($codigo) == '0'){
			$codigo = ucwords(strtoupper($codigo));
			$descripcion = ucwords(strtolower($descripcion));
			$tipo = ucwords(strtolower($tipo));
			$departamento = ucwords(strtolower($departamento));
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO comercial_agencia (codigo, horario, descripcion, tipo, departamento, estatus) VALUES (:codigo, :horario, :descripcion, :tipo, :departamento, 1)");
			//Definimos los parametros de la Query
			$sql->bindParam(':codigo', $codigo, PDO::PARAM_STR);
			$sql->bindParam(':horario', $horario, PDO::PARAM_STR);
			$sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
			$sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
			$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
			if ($sql->execute()){
			   	$data = "1";
			}else{
				$data = "0";
			}		
			$objdatabase = null;
		}else{
			$data = 'repetido';
		}
		return $data;
	}

	// Get Agency
	function getAgency($id){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_agencia WHERE id =:id");
		$sql->bindParam(':id', $id, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$result = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$result = "0";
		}
		$objdatabase = null;
		return $result;
	}

	// Edit Agency
	function editAgency($id, $tipo, $departamento, $horario, $descripcion ){
		$tipo = ucwords(strtolower($tipo));
		$descripcion = ucwords(strtolower($descripcion));
		$departamento = ucwords(strtolower($departamento));
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE comercial_agencia SET tipo =:tipo, horario =:horario, descripcion =:descripcion, departamento =:departamento WHERE id =:id");
		$sql->bindParam(':id', $id, PDO::PARAM_STR);
		$sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
		$sql->bindParam(':horario', $horario, PDO::PARAM_STR);
		$sql->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
		$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
		   $data = "1";
		} else {
		   $data = "0";
		}		
		return $data;
	}

	// Load Assistance List
	function loadAssistance(){
		$fecha = date('Y-m-d');
		$objdatabase = new Database();
		$nivel = $_SESSION['nivel'];		
		switch ($nivel) {			
			case '1':
				$sql = $objdatabase->prepare("SELECT DISTINCT agencia, descripcion FROM comercial_plantilla INNER JOIN comercial_agencia ON comercial_plantilla.agencia = comercial_agencia.codigo WHERE comercial_agencia.estatus = '1' AND agencia NOT IN(SELECT agencia FROM comercial_asistencia WHERE fecha = :fecha) ORDER BY agencia");				
				break;
			case '2':
				$departamento = $_SESSION['departamento'];
				$sql = $objdatabase->prepare("SELECT DISTINCT agencia, descripcion FROM comercial_plantilla INNER JOIN comercial_agencia ON comercial_plantilla.agencia = comercial_agencia.codigo WHERE comercial_agencia.estatus = '1' AND departamento =:departamento AND agencia NOT IN(SELECT agencia FROM comercial_asistencia WHERE fecha = :fecha) ORDER BY agencia ");
				$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
				break;
			case '3':
				$agencia = $_SESSION['agencia'];
				$sql = $objdatabase->prepare("SELECT DISTINCT agencia, descripcion FROM comercial_plantilla INNER JOIN comercial_agencia ON comercial_plantilla.agencia = comercial_agencia.codigo WHERE comercial_agencia.estatus = '1' AND codigo =:agencia AND agencia NOT IN(SELECT agencia FROM comercial_asistencia WHERE fecha = :fecha) ORDER BY agencia");
				$sql->bindParam(':agencia', $agencia, PDO::PARAM_STR);
				break;
			default:
				# code...
				break;
		}
		$sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount(); // se confirma que el query exista	
		$data = null;		
		if($count){
			$data = $sql->fetchAll();			
		}
		$objdatabase = null;
		return $data;
	}

	// Load Assistance List
	function loadAllAgencies(){
		$fecha = date('Y-m-d');
		$objdatabase = new Database();
		$nivel = $_SESSION['nivel'];
		switch ($nivel) {			
			case '1':
				$sql = $objdatabase->prepare("SELECT DISTINCT agencia, descripcion FROM comercial_plantilla INNER JOIN comercial_agencia ON comercial_plantilla.agencia = comercial_agencia.codigo WHERE comercial_agencia.estatus = '1' ORDER BY agencia");		
				break;
			case '2':
				$departamento = $_SESSION['departamento'];
				$sql = $objdatabase->prepare("SELECT DISTINCT agencia, descripcion FROM comercial_plantilla INNER JOIN comercial_agencia ON comercial_plantilla.agencia = comercial_agencia.codigo WHERE comercial_agencia.estatus = '1' AND departamento =:departamento ORDER BY agencia");
				$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
				break;
			case '3':
				$agencia = $_SESSION['agencia'];
				$sql = $objdatabase->prepare("SELECT DISTINCT agencia, descripcion FROM comercial_plantilla INNER JOIN comercial_agencia ON comercial_plantilla.agencia = comercial_agencia.codigo WHERE comercial_agencia.estatus = '1' AND codigo =:agencia ORDER BY agencia");
				$sql->bindParam(':agencia', $agencia, PDO::PARAM_STR);
				break;
			default:
				# code...
				break;
		}
		$sql->bindParam(':fecha', $fecha, PDO::PARAM_STR);
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount(); // se confirma que el query exista	
		$data = null;		
		if($count){
			$data = $sql->fetchAll();			
		}
		$objdatabase = null;
		return $data;
	}	

	// Search By Name
	function searchByName($codigo){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_agencia WHERE codigo =:codigo");
		$sql->bindParam(':codigo', $codigo, PDO::PARAM_STR);
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

	// Change Status
	function statusAgency($id){
		$search = getAgency($id);
		if ($search != "0"){
			$estatus = $search->estatus;
			switch ($estatus) {
				case '0':
					$newStatus = "1";
					break;
				case '1':
					$newStatus = "0";
					break;				
				default:
					$newStatus = "0";
					break;
			}
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("UPDATE comercial_agencia SET estatus =:newStatus WHERE id =:id");
			$sql->bindParam(':id', $id, PDO::PARAM_STR);
			$sql->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
			$sql->execute(); // se confirma que el query existas		
			$count = $sql->rowCount();//Verificamos el resultado
			if ($count){
			   $data = "1";
			} else {
			   $data = "0";
			}
		}else{
			$data = "0";
		}
		return $data;
	}

	//Get Horaries
	function getHorario(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT DISTINCT horario FROM comercial_agencia ORDER BY horario DESC");
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $sql->fetchAll();
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;		
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar
		switch ($function) {
			case "getAgencies":
				echo json_encode(getAgencies());
				break;
			case "getAllAgencies":
				getAllAgencies();
				break;
			case "getAgency":
				echo json_encode(getAgency($_POST['id']));
				break;
			case "editAgency":
				echo editAgency($_POST['id'], $_POST['tipo'], $_POST['departamento'], $_POST['horario'], $_POST['descripcion']);
				break;
			case "insertAgency":
				echo insertAgency($_POST['codigo'], $_POST['horario'], $_POST['descripcion'], $_POST['tipo'], $_POST['departamento']);
				break;
			case "statusAgency":
				echo statusAgency($_POST['id']);
				break;
			case "getHorario":
				echo json_encode(getHorario());
				break;
			case "loadAssistance":
				echo json_encode(loadAssistance());
				break;
			case "loadAllAgencies":
				echo json_encode(loadAllAgencies());
			default:
				break;
		}
	}
	
}else{
	echo "notSessionActive";
}

?>