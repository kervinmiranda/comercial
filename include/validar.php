<?php
	session_start();
	/* codigo para la validacion de los diferentes usuarios que ingresan al sistema*/
	require 'pdo/database.php';
	$objdatabase = new Database();
	//tomamos los datos y los comparamos
	$sql = $objdatabase->prepare('SELECT ci, nombre, nivel, userid, agencia, usuario.departamento, tipo FROM comercial_usuario usuario
	INNER JOIN comercial_agencia agencia ON usuario.agencia = agencia.codigo WHERE userid =:userid AND clave =:clave AND usuario.estatus = 1');
	//Definimos los parametros de la Query
	$sql->bindParam(':userid', $_POST['usuario'], PDO::PARAM_STR);
	$sql->bindParam(':clave', md5($_POST['clave']), PDO::PARAM_STR);
	//Exjecutamos la Query
	$sql->execute(); // se confirma que el query exista
	//Verificamos el resultado
	$count = $sql->rowCount();
	if($count){
		$data = $sql->fetch(PDO::FETCH_OBJ);
		$_SESSION['cedula'] = $data->ci;
		$_SESSION['user'] = utf8_encode($data->nombre);
		$_SESSION['nivel'] = $data->nivel;
		$_SESSION['nick'] = $data->userid;
		$_SESSION['username'] = $data->userid;
		$_SESSION['agencia'] = $data->agencia;
		$_SESSION['departamento'] = $data->departamento;
		$_SESSION['t_agencia'] = $data->tipo;
		header('location:../principal.php');
	}else{
		header('location:../index.php?error=acceso');
	}
?>