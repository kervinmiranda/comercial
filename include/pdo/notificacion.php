<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){

	function getNotifications(){
		$userid = $_SESSION['nick'];
		$nivel = $_SESSION['nivel'];
		if ($nivel == 3){
			$consulta = "SELECT COUNT(id) FROM comercial_notificacion WHERE destino = :userid AND vista = '0' AND estatus = '1' ORDER BY fecha DESC";		
		}
		if ($nivel == 2){
			if ($t_agencia != 'Oficina'){
				$consulta = "SELECT COUNT(id) FROM comercial_notificacion WHERE (destino = 'administrador' OR destino = :userid) AND vista = '0' AND estatus = '1' ORDER BY fecha DESC";
			}else{
				$consulta = "SELECT COUNT(id) FROM comercial_notificacion WHERE destino = :userid AND vista = '0' AND estatus = '1' ORDER BY fecha DESC";
			}
		}
		if ($nivel < 2){
			$consulta = "SELECT * FROM comercial_notificacion WHERE (destino = 'administrador' OR destino = :userid) AND vista = '0' AND estatus = '1' ORDER BY fecha DESC";	
		}
		$objdatabase = new Database();	
		$sql = $objdatabase->prepare($consulta);
		//Definimos los parametros de la Query
		$sql->bindParam(':userid', $userid, PDO::PARAM_STR);
		$sql->execute();//Exjecutamos la Query
		$count = $sql->rowCount();//Verificamos el resultado
		if ($count){
			$data = $count;
		}else{
			$data = "0";
		}
		$objdatabase = null;
		return $data;
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case  "getNotifications":
				echo getNotifications();
			default:
				break;
		}
	}
		
}else{
	echo "notSessionActive";
}

?>