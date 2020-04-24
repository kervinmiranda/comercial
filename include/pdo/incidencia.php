<?php
/***************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
include_once 'database.php';
@session_start();
if(isset($_SESSION['user'])){

	function insertIncidence($tipo, $cedula, $nombre, $sucursal, $modulo, $observacion){		
		try {
			$nombre = mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
			$sucursal = mb_convert_case($sucursal, MB_CASE_TITLE, "UTF-8");				
			$fecha = date('Y-m-d');
			$observacion = mb_convert_case($observacion, MB_CASE_TITLE, "UTF-8");
			$usuario = $_SESSION['username']; // Nombre del usuario
			$fechahora = date('Y-m-d H:i:s'); //Fecha y Hora del registro			
		   	$sql = new Database();
		   	$sql->beginTransaction();
		    //Insert Incidence
		    $sql->query("INSERT INTO comercial_incidencia (fecha, modulo, usuario, observacion) VALUES ('$fechahora', '$modulo', '$usuario', '$observacion')");
		    $id = $sql->lastInsertId();
		    //Inserta Comment
		    if($tipo == 'Cambiar') {
		    	$sucursal = mb_convert_case($sucursal, MB_CASE_TITLE, "UTF-8");
				$comentario = utf8_decode($usuario.' ha solicitado Cambiar el empleado '.$nombre.' con CI: '.$cedula.' a la Sucursal '.$sucursal);
			}else{
				$comentario = utf8_decode($usuario.' ha solicitado '.$tipo.' al empleado '.$nombre.' con CI: '.$cedula);
			}
			$sql->query("INSERT INTO comercial_gestion (id, tabla, fecha, gestor, comentario, estatus) VALUES ('$id', 'comercial_incidencia', '$fechahora', '$usuario', '$comentario','Abierta')");
		    $sql->query("INSERT INTO comercial_notificacion (destino, autor, comentario, fecha) VALUES ('administrador', '$usuario', '$comentario', '$fechahora')");
	    	$sql->commit();
	    	$data = $id;
		} catch (Exception $e){
		    $sql->rollback();
		    $data = "0";
		}
		return $data;
	}

	if (isset($_POST['function'])){
		$function  = $_POST['function']; //Obtener la Opción a realizar (Nuevo, editar, bloquear)
		switch ($function) {
			case  "insertIncidence":
				echo insertIncidence($_POST['tipo'], $_POST['cedula'], $_POST['nombre'], $_POST['sucursal'], $_POST['modulo'], $_POST['observacion']);
				break;
			default:
				break;
		}
	}
		
}else{
	echo "notSessionActive";
}

?>