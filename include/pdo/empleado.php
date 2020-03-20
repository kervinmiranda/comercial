<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){
	
	//Get Active Employees
	function getEmployees(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_plantilla WHERE estatus = '1' ORDER BY descripcion ASC");		
		$sql->execute(); //Exjecutamos la Query
		$count = $sql->rowCount(); // se confirma que el query exista	
		$data = null;		
		if($count){
			$data = $sql->fetchAll();			
		}
		$objdatabase = null;
		return $data;
	}

	// Get All Employees
	function getAllEmployees(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT ci, nombre, apellido, agencia, cargo, fecha_ingreso, correo, telefono, userlib, cajafact, razon_social, estudio, instruccion, titulo, zonares, direccion, estatus, fecha_camb, diasd, diasp, observacion, hijos, supervisor, turno, dpto FROM comercial_plantilla ORDER BY nombre, apellido ASC");		
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

	// Insert Employee
	function insertEmployee($cedula, $nombre, $apellido, $cargo, $fecha_ingreso, $departamento, $razon_social, $agencia, $telefono, $correo, $turno, $supervisor, $hijos, $zonares, $direccion, $observacion, $estudio, $instruccion, $titulo, $userlib, $cajafact, $fecha_camb){
		if (searchByCedula($cedula) == '0'){
			$objdatabase = new Database();
			$sql = $objdatabase->prepare("INSERT INTO comercial_plantilla (ci, nombre, apellido, cargo, fecha_ingreso, correo, telefono, userlib, razon_social, cajafact, estudio, instruccion, titulo, zonares, direccion, estatus, fecha_camb, observacion, hijos, agencia, supervisor, turno, dpto) VALUES (:ci, :nombre, :apellido, :cargo, :fecha_ingreso, :correo, :telefono, :userlib, :razon_social, :cajafact, :estudio, :instruccion, :titulo, :zonares, :direccion, :fecha_camb, :observacion, :hijos, :agencia, :supervisor, :turno, :dpto)");
			//Definimos los parametros de la Query
			$sql->bindParam(':ci', $cedula, PDO::PARAM_STR);
			$sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
			$sql->bindParam(':apellido', $apellido, PDO::PARAM_STR);
			$sql->bindParam(':cargo', $cargo, PDO::PARAM_STR);
			$sql->bindParam(':fecha_ingreso', $fecha_ingreso, PDO::PARAM_STR);
			$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
			$sql->bindParam(':razon_social', $razon_social, PDO::PARAM_STR);
			$sql->bindParam(':agencia', $agencia, PDO::PARAM_STR);
			$sql->bindParam(':telefono', $telefono, PDO::PARAM_STR);
			$sql->bindParam(':correo', $correo, PDO::PARAM_STR);
			$sql->bindParam(':turno', $turno, PDO::PARAM_STR);
			$sql->bindParam(':supervisor', $supervisor, PDO::PARAM_STR);
			$sql->bindParam(':hijos', $hijos, PDO::PARAM_STR);
			$sql->bindParam(':zonares', $zonares, PDO::PARAM_STR);
			$sql->bindParam(':direccion', $direccion, PDO::PARAM_STR);
			$sql->bindParam(':observacion', $observacion, PDO::PARAM_STR);
			$sql->bindParam(':estudio', $estudio, PDO::PARAM_STR);
			$sql->bindParam(':instruccion', $instruccion, PDO::PARAM_STR);
			$sql->bindParam(':titulo', $titulo, PDO::PARAM_STR);
			$sql->bindParam(':userlib', $userlib, PDO::PARAM_STR);
			$sql->bindParam(':cajafact', $cajafact, PDO::PARAM_STR);
			$sql->bindParam(':fecha_camb', $fecha_camb, PDO::PARAM_STR);
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

	// Get Employee
	function getEmployee($cedula){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_plantilla WHERE ci =:cedula");
		$sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
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

	// Edit Employee
	function editEmployee($id, $tipo, $departamento, $horario, $descripcion ){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE comercial_plantilla SET tipo =:tipo, horario =:horario, descripcion =:descripcion, departamento =:departamento WHERE id =:id");
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

	// Search By Cedula
	function searchByCedula($ci){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT * FROM comercial_plantilla WHERE ci =:ci");
		$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
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
	function statusEmployee($cedula){
		$search = getEmployee($cedula);
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
			$sql = $objdatabase->prepare("UPDATE comercial_plantilla SET estatus =:newStatus WHERE ci =:cedula");
			$sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
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

	//Get Cargos
	function getCargos(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT id, descripcion FROM comercial_cargo WHERE estatus = '1' ORDER BY descripcion ASC");
		$sql->execute(); // se confirma que el query existas		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$result = $sql->fetchAll();
		}else{
			$result = "0";
		}
		$objdatabase = null;
		return $result;
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar
		switch ($function) {
			case "getAllEmployees":
				getAllEmployees();
				break;
			case "getEmployee":
				echo json_encode(getEmployee($_POST['id']));
				break;
			case "editEmployee":
				echo editEmployee($_POST['id'], $_POST['tipo'], $_POST['departamento'], $_POST['horario'], $_POST['descripcion']);
				break;
			case "insertEmployee":
				echo insertEmployee($_POST['cedula'], $_POST['nombre'], $_POST['apellido'], $_POST['cargo'], $_POST['ingreso'], $_POST['departamento'], $_POST['razon'], $_POST['agencia'], $_POST['telefono'], $_POST['correo'], $_POST['turno'], $_POST['supervisor'], $_POST['hijos'], $_POST['zonares'], $_POST['direccion'], $_POST['observacion'], $_POST['estudio'], $_POST['instruccion'], $_POST['titulo'], $_POST['userlib'], $_POST['cajafact'], $_POST['fcambio']);
				break;
			case "statusEmployee":
				echo statusEmployee($_POST['cedula']);
				break;
			case "getCargos":
				echo json_encode(getCargos());
				break;
			default:
				break;
		}
	}
	
}else{
	echo "notSessionActive";
}

?>