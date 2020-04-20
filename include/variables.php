<?php
//Hora Local
setlocale(LC_TIME, 'es_RB'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
//Variables:
$doctype = '<!DOCTYPE html PUBLIC "Sistema Gebnet"><html lang="es"><head><title>Gebnet</title><link rel="shortcut icon" href="favicon.ico"><meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" charset="UTF-8">';
$titulo = '<h4><b>Gebnet</b></h4>Comercialización'; //Título del Sistema
$pestana = 'Gebnet';//Titulo de la pestaña
$imagen='<img src="imagenes/libertylogo.png">';// Logo de la cabecera
$color= '#1a2848'; // Color del sistema
$header = '<header class="container-fluid">			
				<div class="col-xs-12 col-md-4">'.$imagen.'</div>        
				<div class="col-xs-12 col-md-4">'.$titulo.'</div>
				<div class="col-xs-12 col-md-4"><br>'.$time.'</div>          
			</header>
		';
$chat = '<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 visible-lg-block">
            	<img src="imagenes/chat.png" title="Chat con los Administradores">
				<a href="javascript:void(0)" onclick="javascript:chatWith('."'".'erebolledo'."'".')">Edwin Rebolledo</a> │
				<a href="javascript:void(0)" onclick="javascript:chatWith('."'".'lmartinez'."'".')">Control Operativo</a> │
				<a href="javascript:void(0)" onclick="javascript:chatWith('."'".'mbello'."'".')">Maria Bello</a> │
			<!--<a href="javascript:void(0)" onclick="javascript:chatWith('."'".'kmiranda'."'".')">Kervin Miranda</a>-->			
           	</div>
			</div>
			</div>';

$footer = '<div class="container-fluid">
           	<footer class="row vertical-align">
        		<div class="col-xs-12 text-center">│ Liberty Express C.A - RIF J-31116772-2 │ Departamento de Tecnología │ Departamento de Comercialización │</div>        
    		</footer>
    	  </div>';
//Contenido del pie de página
session_start();
if(isset($_SESSION['user'])){
	$userid = $_SESSION['nick'];
	$nombre = $_SESSION['user'];
	$cedula = $_SESSION['cedula'];
	$nivel = $_SESSION['nivel'];
	$t_agencia = $_SESSION['t_agencia'];
	$departamento = $_SESSION['departamento'];
	$principal = '<li id="menu1"><a href="principal">Principal</a></li>';
	$administracion = '<li id="menu2" class="dropdown"><a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Administracion<span class="caret"></span></a>
			 <ul class="dropdown-menu">				          
			      <li><a href="agencia">Agencias</a></li>
			      <li><a href="empleado">Empleados</a></li>
			      <li><a href="usuario">Usuarios</a></li>
                       </ul>
		</li>';
	if ($t_agencia != 'Aliado'){
	$plantilla_a = '<li id="menu3" class="dropdown"><!-- Menú Plantilla -->
        			<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Plantilla de Personal<span class="caret"></span></a>
        				<ul class="dropdown-menu">
          					<li><a href="asistencia">Asistencia</a></li>
          					<li><a href="solicitud">Solicitudes</a></li>
						</ul>
				</li>';
	$plantilla_u = '<li id="menu3" class="dropdown"><!-- Menú Plantilla -->
        			<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Plantilla de Personal<span class="caret"></span></a>
        				<ul class="dropdown-menu">
          					<li><a href="asistencia">Asistencia</a></li>
                            <li><a href="solicitud">Solicitudes</a></li>
						</ul>
				</li>';
	}else{
		$plantilla_a = '';
		$plantilla_u = '';
	}	
	if ($t_agencia != 'Oficina'){
	$reportest_a = '<li id="menu4" class="dropdown"><!-- Menú Reportes de Tienda -->
        			<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Reportes de Tienda<span class="caret"></span></a>
        				<ul class="dropdown-menu">
          					<li class="dropdown-submenu"><a tabindex="0">Apertura</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "apertura" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultaapertura" tabindex="0">Consulta</a></li>
									<li><a href = "exportarapertura" tabindex="0">Exportar</a></li>			             					
            					</ul>                        
          					</li>
                            <li class="dropdown-submenu"><a tabindex="0">Cierre</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="cierre" tabindex="0">Carga</a></li>              					
                                    <li><a href="consultacierre" tabindex="0">Consulta</a></li>
									<li><a href = "exportarcierre" tabindex="0">Exportar</a></li>            					
            					</ul>                        
          					</li>
                            <li class="dropdown-submenu"><a tabindex="0">Valijas</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "valija" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultavalija" tabindex="0">Consulta</a></li>
									<li><a href = "exportarvalija" tabindex="0">Exportar</a></li>          					
            					</ul>                        
          					</li>
							<li class="dropdown-submenu"><a tabindex="0">Paquetes Identificados</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "paquete" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultapaquete" tabindex="0">Consulta</a></li>
									<li><a href = "exportarpaquetei" tabindex="0">Exportar</a></li>			             					
            					</ul>                        
          					</li>
						</ul>
				</li>';
	$reportest_u = '<li id="menu4" class="dropdown"><!-- Menú Reportes de Tienda -->
        			<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Reportes de Tienda<span class="caret"></span></a>
        				<ul class="dropdown-menu">
          					<li class="dropdown-submenu"><a tabindex="0">Apertura</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "apertura" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultaapertura" tabindex="0">Consulta</a></li>		             					
            					</ul>                        
          					</li>
                            <li class="dropdown-submenu"><a tabindex="0">Cierre</a>
                            	<ul class="dropdown-menu">
                                    <li><a href="cierre" tabindex="0">Carga</a></li>              					
                                    <li><a href="consultacierre" tabindex="0">Consulta</a></li>							           					
            					</ul>                        
          					</li>
                            <li class="dropdown-submenu"><a tabindex="0">Valijas</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "valija" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultavalija" tabindex="0">Consulta</a></li>									          					
            					</ul>                        
          					</li>
							<li class="dropdown-submenu"><a tabindex="0">Paquetes Identificados</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "paquete" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultapaquete" tabindex="0">Consulta</a></li>				             					
            					</ul>                        
          					</li>
						</ul>
				</li>';
				
	}else{
	$reportest_a = '<li id="menu4" class="dropdown"><!-- Menú Reportes de Tienda -->
        			<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Reportes de Tienda<span class="caret"></span></a>
        				<ul class="dropdown-menu">
          					<li class="dropdown-submenu"><a tabindex="0">Paquetes Identificados</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "paquete" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultapaquete" tabindex="0">Consulta</a></li>
									<li><a href = "exportarpaquetei" tabindex="0">Exportar</a></li>			             					
            					</ul>                        
          					</li>                            
						</ul>
				</li>';
	$reportest_u = '<li id="menu4" class="dropdown"><!-- Menú Reportes de Tienda -->
        			<a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Reportes de Tienda<span class="caret"></span></a>
        				<ul class="dropdown-menu">
          					<li class="dropdown-submenu"><a tabindex="0">Paquetes Identificados</a>
                            	<ul class="dropdown-menu">
                                    <li><a href = "paquete" tabindex="0">Carga</a></li>              					
                                    <li><a href = "consultapaquete" tabindex="0">Consulta</a></li>				             					
            					</ul>                        
          					</li>                            
						</ul>
				</li>';
	}
	$indicadores = '<li id="menu5"><a href = "indicadores">Indicadores Gráficos</a></li>';  	
	$incidencias = '<li id="menu6" class="dropdown"><a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Incidencias<span class="caret"></span></a>
			  		<ul class="dropdown-menu" role="menu">	
                        <li class="dropdown-submenu"><a tabindex="0">Generar</a>
			      		<ul class="dropdown-menu">
			      			<li><a href="incidencia">Carga de Incidencias</a></li>	
			      		</ul>
			      </li>
                        <li><a href="consultaincidencia">Consulta</a></li>
						<li><a href="historialincidencia">Historial</a></li>                 
					</ul>
					</li> <!-- .dropdown -->';
	$incidencias_sg = '<li id="menu6" class="dropdown"><a tabindex="0" data-toggle="dropdown" data-submenu="" aria-expanded="false">Incidencias<span class="caret"></span></a>
			  		<ul class="dropdown-menu" role="menu">	
                        <li class="dropdown-submenu"><a tabindex="0">Generar</a>
				      		<ul class="dropdown-menu">
				      			<li><a href="incidencia">Carga de Incidencias</a></li>
					      		<li><a href="novedad" tabindex="0">Carga de Novedades</a></li>
					      		<li><a href="falla" tabindex="0">Carga de Fallas</a></li>		
				      		</ul>
			      		</li>
                        <li><a href="consultaincidencia">Consulta</a></li>
						<li><a href="historialincidencia">Historial</a></li>
						<li class="dropdown-submenu"><a tabindex="0">Gestionar Incidencias</a>
							<ul class="dropdown-menu">
								<li><a href="gestionincidencia">Consulta</a></li>				
				      		</ul>

                        <li><a href="gestionarproductos">Gestionar Productos</a></li>
<!--
                        <li><a href="consultacajachica">Caja Chica</a></li>    // pendiente con los permisos al agregar el modulo, debe ser 0644                      
-->
					</ul>
					</li> <!-- .dropdown -->';
	$notificaciones = '<li id="menu7"><a href = "notificacion">Notificaciones</a><span id = "burbuja" class="burbuja" hres>2</span></li>';
	$sesion = '<ul class="nav navbar-nav navbar-right">
				<li id = "session" class="dropdown"><a href="#" class="dropdown" data-toggle="dropdown">'.$nombre.'<b class="caret"></b></a>
                	<ul class="dropdown-menu">
                        <li><a href="clave">Cambiar Contraseña</a></li>
						<li class="divider"></li>
                        <li><a href="include/salir">Salir</a></li>
					</ul>
              	</li>
            </ul>';
            $sesion_sg = '<ul class="nav navbar-nav navbar-right">
				<li id = "session" class="dropdown"><a href="#" class="dropdown" data-toggle="dropdown">'.$nombre.'<b class="caret"></b></a>
                	<ul class="dropdown-menu">
                        <li><a href="clave">Cambiar Contraseña</a></li>
						<li class="divider"></li>
						<li><a href="misincidencias">Mis Solicitudes</a></li>
						<li class="divider"></li>
                        <li><a href="include/salir">Salir</a></li>
					</ul>
              	</li>
            </ul>';
	

$menu1= '
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand">Menú</a>
  		</div>
		<div class="navbar-collapse collapse in" aria-expanded="true">
    		<ul class="nav navbar-nav">'.
				$principal.$administracion.$plantilla_a.$reportest_a.$indicadores.$incidencias.$notificaciones.
			'		
			</ul>'.$sesion.'            
  		</div>
	</nav>';

$menu2= '
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand">Menú</a>
  		</div>
		<div class="navbar-collapse collapse in" aria-expanded="true">
    		<ul class="nav navbar-nav">'.
				$principal.$plantilla_a.$reportest_a.$incidencias.$notificaciones.
			'		
			</ul>'.$sesion.'            
  		</div>
	</nav>';

$menu3= '
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand">Menú</a>
  		</div>
		<div class="navbar-collapse collapse in" aria-expanded="true">
    		<ul class="nav navbar-nav">'.
				$principal.$plantilla_u.$reportest_u.$incidencias.$notificaciones.
			'		
			</ul>'.$sesion.'            
  		</div>
	</nav>';
	
$menu4= '
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand">Menú</a>
  		</div>
		<div class="navbar-collapse collapse in" aria-expanded="true">
    		<ul class="nav navbar-nav">'.
				$principal.$administracion.$plantilla_a.$reportest_a.$indicadores.$incidencias_sg.$notificaciones.
			'		
			</ul>'.$sesion_sg.'            
  		</div>
	</nav>';
		
	switch ($nivel){
		case 1: 
//		if($departamento == 'Servicios Generales'){
			$menu = $menu4;
//		}else{
//			$menu = $menu1;
//		}
		break;
		case 2: $menu = $menu2;
		break;
		case 3: $menu = $menu3;
		break;
	}
}//Enf If Isset SESSION	
?>