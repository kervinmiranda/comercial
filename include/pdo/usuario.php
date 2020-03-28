<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){

	// New User
	function newUser($cedula, $nombre, $userid, $cargo,  $agencia, $departamento, $nivel, $clave){
		$exists = searchUser($cedula, $userid);
		if ($exists ==  false){
			$nombre = utf8_decode(ucwords(strtolower($nombre)));
			$userid = utf8_decode(strtolower($userid));
			$cargo = utf8_decode(ucwords(strtolower($cargo)));
			$departamento = utf8_decode(ucwords(strtolower($departamento)));
			$clave = md5($clave);
			$objdatabase = new Database();	
		 	$sql = $objdatabase->prepare("INSERT INTO comercial_usuario(ci, nombre, cargo, agencia, userid, departamento, clave, nivel,  estatus) VALUES (:ci, :nombre, :cargo, :agencia, :userid, :departamento, :clave, :nivel, 1)");
			//Definimos los parametros de la Query
			$sql->bindParam(':ci', $cedula, PDO::PARAM_STR);
			$sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
			$sql->bindParam(':cargo', $cargo, PDO::PARAM_STR);
			$sql->bindParam(':agencia', $agencia, PDO::PARAM_STR);
			$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
			$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
			$sql->bindParam(':clave', $clave, PDO::PARAM_STR);
			$sql->bindParam(':nivel', $nivel, PDO::PARAM_STR);
			if ($sql->execute()) { 
			   	$data = "1";
			}else{
				$data = "0";
			}
			$objdatabase = null;		
		}else{
			$data = "repetido";
		}
		$objdatabase = null;
		echo $data;
	}

	// Edit User
	function editUser($ci, $nombre, $cargo, $agencia, $departamento, $nivel){
		$nombre = utf8_decode(ucwords(strtolower($nombre)));
		$cargo = utf8_decode(ucwords(strtolower($cargo)));
		$departamento = utf8_decode(ucwords(strtolower($departamento)));
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE comercial_usuario SET nombre =:nombre, cargo =:cargo, departamento =:departamento, agencia =:agencia, nivel =:nivel WHERE ci =:ci");
		//Definimos los parametros de la Query
		$sql->bindParam(':ci', $ci , PDO::PARAM_STR);
		$sql->bindParam(':nombre', $nombre, PDO::PARAM_STR);
		$sql->bindParam(':cargo', $cargo, PDO::PARAM_STR);
		$sql->bindParam(':departamento', $departamento, PDO::PARAM_STR);
		$sql->bindParam(':agencia', $agencia, PDO::PARAM_STR);
		$sql->bindParam(':nivel', $nivel, PDO::PARAM_STR);
		$sql->execute();		
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
		   $data = "1";
		} else {
		   $data = "0";
		}
		$objdatabase = null;
		echo $data;
	}

	// Search User
	function searchUser($ci, $userid){
		$boolean = false;
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT ci FROM comercial_usuario WHERE ci =:ci OR userid =:userid");
		//Definimos los parametros de la Query
		$sql->bindParam(':ci', $ci, PDO::PARAM_STR);
		$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
		//Exjecutamos la Query
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if ($count){
			$boolean = true;
		}else{
		}
		$objdatabase = null;
		return $boolean;
	}

	// Get User
	function getUser($cedula){
	//Recogemos los datos del POST
	$objdatabase = new Database();
	$sql = $objdatabase->prepare("SELECT * FROM comercial_usuario WHERE ci =:ci");
	$sql->bindParam(':ci', $cedula, PDO::PARAM_STR);
	$sql->execute(); // se confirma que el query exista	
	$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$result = $sql->fetch(PDO::FETCH_OBJ);
		}else{
			$result = "0";
		}
		$objdatabase = null;
		return $result;
	}
	
	// Reset Password
	function resetPassword($cedula, $clave){
		$clave = md5($clave);
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("UPDATE comercial_usuario SET clave =:clave_nueva WHERE ci =:ci");
		$sql->bindParam(':ci', $cedula, PDO::PARAM_STR);
		$sql->bindParam(':clave_nueva', $clave, PDO::PARAM_STR);
		$sql->execute(); // se confirma que el query exista	
		//Verificamos el resultado
		$count = $sql->rowCount();
			if ($count){
				$data = "1";
			}else{
				$data = "0";
			}
		$objdatabase = null;
		echo $data;
	}

	// Change Status
	function changeStatus($cedula){
		$search = getUser($cedula);
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
			$sql = $objdatabase->prepare("UPDATE comercial_usuario SET estatus =:newStatus WHERE ci =:ci");
			$sql->bindParam(':ci', $cedula, PDO::PARAM_STR);
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

	// Get All Users
	function getUsers(){
		$objdatabase = new Database();
		$sql = $objdatabase->prepare("SELECT ci, nombre, userid, cargo, agencia.descripcion, usuario.departamento, nivel, usuario.estatus FROM comercial_usuario usuario INNER JOIN comercial_agencia agencia ON usuario.agencia = agencia.codigo");
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

	// Change Password
	function changePassword(){
		$objdatabase = new Database();
		$cedula = $_POST['cedula'];
		$claveActual = md5($_POST['actual']);
		$claveNueva = md5($_POST['clave1']);
		//tomamos los datos y los comparamos
		$sql = $objdatabase->prepare("SELECT clave FROM comercial_usuario WHERE ci =:cedula AND clave =:claveActual AND estatus = 1");
		//Definimos los parametros de la Query
		$sql->bindParam(':cedula', $cedula, PDO::PARAM_STR);
		$sql->bindParam(':claveActual', $claveActual, PDO::PARAM_STR);
		//Exjecutamos la Query
		$sql->execute(); // se confirma que el query exista
		//Verificamos el resultado
		$count = $sql->rowCount();
		if($count){
			$objdatabase2 = new Database();
			$sql2 = $objdatabase2->prepare("UPDATE comercial_usuario SET clave =:claveNueva WHERE ci =:cedula");
			$sql2->bindParam(':cedula', $cedula, PDO::PARAM_STR);
			$sql2->bindParam(':claveNueva', $claveNueva, PDO::PARAM_STR);
			$sql2->execute(); // se confirma que el query exista
			//Verificamos el resultado
			$count2 = $sql2->rowCount();
			if ($count2){
				$data = "1";
			}else{
				$data = "0";
			}
			$objdatabase2 = null;
		}else{
			$data = "error";
		}
		$objdatabase = null;
		echo $data;
	}

	$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
	switch ($function) {
		case "newUser":
			newUser($_POST['cedula'], $_POST['nombre'], $_POST['userid'], $_POST['cargo'], $_POST['agencia'], $_POST['departamento'], $_POST['tipousuario'], $_POST['clave']);
			break;
		case "editUser":
			editUser($_POST['cedula'], $_POST['nombre'], $_POST['cargo'], $_POST['agencia'], $_POST['departamento'], $_POST['tipousuario']);
			break;
		case "getUser":
			echo json_encode(getUser($_POST['cedula']));
			break;
		case "resetPassword":
			resetPassword($_POST['cedula'], $_POST['clave_nueva']);
			break;
		case "changeStatus":
			echo changeStatus($_POST['cedula']);
			break;
		case "getUsers":
			getUsers();
			break;
		case "changePassword":
			changePassword();
			break;
		default:
			break;
	}	
}else{
	echo "notSessionActive";
}

?>