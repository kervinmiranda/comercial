<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user']) && ($nivel < 2)){
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../css/jquery-ui.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/comercial.css">
<link rel="stylesheet" href="css/chat.css">
<!-- Archivos JavaScript -->	
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="js/jquery.bpopup.min.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script src="js/chat.js"></script>
<script>
$(document).ready(function(){
//Activar Menú
	$("#menu2").attr('class','active');

//Campos fecha
	$('#fingreso').datepicker({
		dateFormat: 'dd/mm/yy',
		maxDate: 0, minDate:'-2M'		
	});
	
	$('#fingreso2').datepicker({
		dateFormat: 'dd/mm/yy',
		maxDate: '0'		
	});

	$('#fcambio').datepicker({
		dateFormat: 'dd/mm/yy',
		maxDate: 0, minDate:'-2M'		
	});
	
	$('#fcambio2').datepicker({
		dateFormat: 'dd/mm/yy',
		maxDate: 0		
	});
	
//Función para buscar el autocompletado de Supervisor
	$(function(){
		$('#supervisor, #supervisor2').autocomplete({
			minLength: 3,
			source : 'include/buscar_supervisor.php',
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html(
				 );
		});
		$('#resultados').slideDown('slow');
		} 
		});
	});

//Función para buscar el autocompletado de Zona de Residencia
	$(function(){
		$('#zonares, #zonares2').autocomplete({
			minLength: 3,
			source : 'include/buscar_zona.php',
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html(
				 );
		});
		$('#resultados').slideDown('slow');
		} 
		});
	});

//Función para buscar el autocompletado de Grado de Instruccion
	$(function(){
		$('#instruccion, #instruccion2').autocomplete({
			minLength: 3,
			source : 'include/buscar_grado.php',
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html(
				 );
		});
		$('#resultados').slideDown('slow');
		} 
		});
	});

//Función para buscar el autocompletado de Grado de Titulo
	$(function(){
		$('#titulo, #titulo2').autocomplete({
			minLength: 3,
			source : 'include/buscar_titulo.php',
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html(
				 );
		});
		$('#resultados').slideDown('slow');
		} 
		});
	});

//Mostrar Formulario de Nuevo Empleado
	$('#newUser').click(function(){
		$('body input[type=text]').val('').attr('placeholder','').parent().removeClass('has-error has-success');		
		$('body select option:first').prop("selected", "selected");
		$('body select').parent().removeClass('has-error has-success');
		$('body textarea').val('').attr('placeholder','').parent().removeClass('has-error has-success');
		$('#nuevo').bPopup();		
	});

//Funcion para Validar y Guardar Nuevo Empleado
	$('#enviar').click(function(){
		if ($("#ci").val() == ''){
			$('#ci').parent().addClass('has-error');
			$('#ci').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#ci').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#nombre").val() == ''){
			$('#nombre').parent().addClass('has-error');
			$('#nombre').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#nombre').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#apellido").val() == ''){
			$('#apellido').parent().addClass('has-error');
			$('#apellido').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#apellido').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#cargo option:selected").index() == 0){
			$('#cargo').parent().addClass('has-error');
			$('#cargo').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#cargo').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#fingreso").val() == ''){
			$('#fingreso').parent().addClass('has-error');
			$('#fingreso').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#fingreso').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#departamento").val() == ''){
			$('#departamento').parent().addClass('has-error');
			$('#departamento').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#departamento').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#agencia option:selected").index() == 0){
			$('#agencia').parent().addClass('has-error');
			$('#agencia').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#agencia').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#telefono").val() == ''){
			$('#telefono').parent().addClass('has-error');
			$('#telefono').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#telefono').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#turno option:selected").index() == 0){
			$('#turno').parent().addClass('has-error');
			$('#turno').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#turno').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#supervisor").val() == ''){
			$('#supervisor').parent().addClass('has-error');
			$('#supervisor').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#supervisor').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#hijos").val() == ''){
			$('#hijos').parent().addClass('has-error');
			$('#hijos').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#hijos').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#zonares").val() == ''){
			$('#zonares').parent().addClass('has-error');
			$('#zonares').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#zonares').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#direccion").val() == ''){
			$('#direccion').parent().addClass('has-error');
			$('#direccion').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#direccion').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#estudio option:selected").index() == 0){
			$('#estudio').parent().addClass('has-error');
			$('#estudio').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#estudio').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#instruccion").val() == ''){
			$('#instruccion').parent().addClass('has-error');
			$('#instruccion').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#instruccion').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#titulo").val() == ''){
			$('#titulo').parent().addClass('has-error');
			$('#titulo').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#titulo').parent().removeClass('has-error').addClass('has-success');
		}
		$('#nuevo').bPopup().close();
		bootbox.confirm('¿Seguro que desea Incluir el empleado?', function(result){
			if (result == true){
				accion= 'nuevo';
				cedula = $('#ci').val();
				nombre = $('#nombre').val();
				apellido = $('#apellido').val();
				cargo = $('#cargo').val();
				ingreso = $('#fingreso').val();
				dept = $('#departamento').val();
				razon = $('#razon').val();
				agencia = $('#agencia').val();
				telefono = $('#telefono').val();
				correo = $('#correo').val();
				turno = $('#turno').val();
				supervisor = $('#supervisor').val();
				hijos = $('#hijos').val();
				zonares = $('#zonares').val();
				direccion = $('#direccion').val();
				observacion = $('#observacion').val();
				estudio = $('#estudio').val();
				instruccion = $('#instruccion').val();
				titulo = $('#titulo').val();
				userlib = $('#userlib').val();
				cajafact = $('#cajafact').val();
				fcambio = $('#fcambio').val();				
				$.post('include/guardar_empleado.php', {accion:accion, cedula:cedula, nombre:nombre, apellido:apellido, cargo:cargo, ingreso:ingreso, dept:dept, razon:razon, agencia:agencia, telefono:telefono, correo:correo, turno:turno, supervisor:supervisor, hijos:hijos, zonares:zonares, direccion:direccion, observacion:observacion, estudio:estudio, instruccion: instruccion, titulo:titulo, userlib:userlib, cajafact:cajafact, fcambio:fcambio}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente mas tarde.').fadeIn(1000).fadeOut(15000);
					}if (data == '1'){				
						$('#mensaje').html('<strong>¡Exito!</strong> Empleado Agregado Correctamente').fadeIn(1000).fadeOut(15000);	
						$('#ci').val('');
						$('#nombre').val('');
						$('#apellido').val('');
						$('#cargo').val('');
						$('#fingreso').val('');
						$('#dept').val('');
						$('#razon').val('');
						$('#agencia option:first-child').attr('selected', 'selected');
						$('#telefono').val('');
						$('#correo').val('');
						$('#turno option:first-child').attr('selected', 'selected');
						$('#supervisor').val('');
						$('#hijos').val('');
						$('#zonares').val('');
						$('#direccion').val('');
						$('#observacion').val('');
						$('#estudio option:first-child').attr('selected', 'selected');
						$('#instruccion').val('');
						$('#titulo').val('');
						$('#userlib').val('');
						$('#cajafact').val('');
						$('#fcambio').val('');						
						$('#lista').DataTable().ajax.reload();
					}if (data == 'repetido'){
						$('#error').html('<strong>¡Error!</strong> El Número de Cédula ya está registrada. Verifique e Intente Nuevamente').fadeIn(1000).fadeOut(15000);					
					}//End if
				});//End post			
			}else{
				$('#nuevo').bPopup();
			}//End if//End if
		});// End Function boot.confirm
	});//End Function

//Mostrar Formulario de editar Empleado
	$('#lista tbody').on('click', '.edit', function(){
	var elemento = $(this).attr('id').split('|');
	var elem = elemento[0].split('|');
	var empleado = ci = elem[0];
	var modulo = 'solicitud';
	
		ci = elem[0];
		nombre = elemento[1];
		apellido = elemento[2];
		var cargo = elemento[3];
		ingreso = elemento[4];
		dpto = elemento[5];
		razon = elemento[6];
		agencia = elemento[7];
		telefono = elemento[8];
		correo = elemento[9];
		turno = elemento[10];
		supervisor = elemento[11];
		hijos = elemento[12];
		zonares = elemento[13];
		direccion = elemento[14];
		estudio = elemento[15];
		instruccion = elemento[16];
		titulo = elemento[17];
		userlib = elemento[18];
		cajafact = elemento[19];
		fcambio = elemento[20];
		observacion = elemento[21];
		
		$('#ci2').val(ci);
		$('#nombre2').val(nombre).parent().removeClass('has-error has-success');
		$('#apellido2').val(apellido).parent().removeClass('has-error has-success');
		$('#cargo2 option[value="'+ cargo +'"]').prop('selected', 'selected');
		$('#fingreso2').val(ingreso).parent().removeClass('has-error has-success');
		$('#departamento2').val(dpto).parent().removeClass('has-error has-success');
		$('#razon2').val(razon).parent().removeClass('has-error has-success');
		$('#agencia2 option[value="'+ agencia +'"]').prop('selected', 'selected');
		$('#telefono2').val(telefono).parent().removeClass('has-error has-success');
		$('#correo2').val(correo).parent().removeClass('has-error has-success');
		$('#turno2').val(turno).parent().removeClass('has-error has-success');
		$('#supervisor2').val(supervisor).parent().removeClass('has-error has-success');
		$('#hijos2').val(hijos).parent().removeClass('has-error has-success');
		$('#zonares2').val(zonares).parent().removeClass('has-error has-success');
		$('#direccion2').val(direccion).parent().removeClass('has-error has-success');
		$('#observacion2').val(observacion).parent().removeClass('has-error has-success');
		$('#estudio2').val(estudio).parent().removeClass('has-error has-success');
		$('#instruccion2').val(instruccion).parent().removeClass('has-error has-success');
		$('#titulo2').val(titulo).parent().removeClass('has-error has-success');
		$('#userlib2').val(userlib).parent().removeClass('has-error has-success');
		$('#cajafact2').val(cajafact).parent().removeClass('has-error has-success');
		$('#fcambio2').val(fcambio).parent().removeClass('has-error has-success');	

		$.post("include/buscar_empleado.php", {empleado:empleado, modulo:modulo}, function(data){
				var obj = jQuery.parseJSON(data);
					$('#ci2').val(obj.ci);
					$('#nombre2').val(obj.nombre).parent().removeClass('has-error has-success');
					$('#apellido2').val(obj.apellido).parent().removeClass('has-error has-success');
					$('#cargo2').val(obj.cargo).parent().removeClass('has-error has-success');
					$('#fingreso2').val(obj.ingreso).parent().removeClass('has-error has-success');
					$('#correo2').val(obj.correo).parent().removeClass('has-error has-success');
					$('#telefono2').val(obj.telefono).parent().removeClass('has-error has-success');
					$('#telefono2').val(obj.telefono).parent().removeClass('has-error has-success');
					$('#departamento2').val(obj.dpto).parent().removeClass('has-error has-success');
					$('#razon2').val(obj.razon).parent().removeClass('has-error has-success');
					$('#agencia2').val(obj.agencia).parent().removeClass('has-error has-success');
					$('#turno2').val(obj.turno).parent().removeClass('has-error has-success');
					$('#supervisor2').val(obj.supervisor).parent().removeClass('has-error has-success');
					$('#hijos2').val(obj.hijos).parent().removeClass('has-error has-success');
					$('#zonares2').val(obj.zonares).parent().removeClass('has-error has-success');
					$('#direccion2').val(obj.direccion).parent().removeClass('has-error has-success');
					$('#observacion2').val(obj.observacion).parent().removeClass('has-error has-success');
					$('#estudio2').val(obj.estudio).parent().removeClass('has-error has-success');
					$('#instruccion2').val(obj.instruccion).parent().removeClass('has-error has-success');
					$('#titulo2').val(obj.titulo).parent().removeClass('has-error has-success');
					$('#cajafact2').val(obj.cajafact).parent().removeClass('has-error has-success');
					$('#fcambio2').val(obj.fcambio).parent().removeClass('has-error has-success');	
			});//End post				
		$('#editar').bPopup();		
	});
	

//Funcion para Validar y Editar Empleado
	$('#enviar2').click(function(){
		if ($("#nombre2").val() == ''){
			$('#nombre2').parent().addClass('has-error');
			$('#nombre2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#nombre2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#apellido2").val() == ''){
			$('#apellido2').parent().addClass('has-error');
			$('#apellido2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#apellido2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#cargo2 option:selected").index() == 0){
			$('#cargo2').parent().addClass('has-error');
			$('#cargo2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#cargo2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#fingreso2").val() == ''){
			$('#fingreso2').parent().addClass('has-error');
			$('#fingreso2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#fingreso2').parent().removeClass('has-error').addClass('has-success');
		}


/*
		if ($("#correo2").val() == ''){
			$('#correo2').parent().addClass('has-error');
			$('#correo2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#correo2').parent().removeClass('has-error').addClass('has-success');
		}
*/


		if ($("#departamento2").val() == ''){
			$('#departamento2').parent().addClass('has-error');
			$('#departamento2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#departamento2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#razon2").val() == ''){
			$('#razon2').parent().addClass('has-error');
			$('#razon2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{
			$('#razon2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#agencia2 option:selected").index() == 0) {
			$('#agencia2').parent().addClass('has-error');
			$('#agencia2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#agencia2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#telefono2").val() == ''){
			$('#telefono2').parent().addClass('has-error');
			$('#telefono2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#telefono2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#turno2 option:selected").index() == 0){
			$('#turno2').parent().addClass('has-error');
			$('#turno2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#turno2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#supervisor2").val() == ''){
			$('#supervisor2').parent().addClass('has-error');
			$('#supervisor2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#supervisor2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#hijos2").val() == ''){
			$('#hijos2').parent().addClass('has-error');
			$('#hijos2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#hijos2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#zonares2").val() == ''){
			$('#zonares2').parent().addClass('has-error');
			$('#zonares2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#zonares2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#direccion2").val() == ''){
			$('#direccion2').parent().addClass('has-error');
			$('#direccion2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#direccion2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#estudio2 option:selected").index() == 0){
			$('#estudio2').parent().addClass('has-error');
			$('#estudio2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#estudio2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#instruccion2").val() == ''){
			$('#instruccion2').parent().addClass('has-error');
			$('#instruccion2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#instruccion2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#titulo2").val() == ''){
			$('#titulo2').parent().addClass('has-error');
			$('#titulo2').attr('placeholder','Campo Obligatorio');
			return false;				
		}else{		
			$('#titulo2').parent().removeClass('has-error').addClass('has-success');
		}
		$('#editar').bPopup().close();
		bootbox.confirm('¿Seguro que desea Editar el empleado?', function(result){
			if (result == true){
				accion= 'editar';
				cedula = $('#ci2').val();
				nombre = $('#nombre2').val();
				apellido = $('#apellido2').val();
				cargo = $('#cargo2').val();
				ingreso = $('#fingreso2').val();

				//correo = $('#correo2').val();
				hijos = $('#hijos2').val();


				depto = $('#departamento2').val();
				razon = $('#razon2').val();
				agencia = $('#agencia2').val();
				telefono = $('#telefono2').val();
				correo = $('#correo2').val();
				turno = $('#turno2').val();
				supervisor = $('#supervisor2').val();
				zonares = $('#zonares2').val();
				direccion = $('#direccion2').val();
				observacion = $('#observacion2').val();
				estudio = $('#estudio2').val();
				instruccion = $('#instruccion2').val();
				titulo = $('#titulo2').val();
				userlib = $('#userlib2').val();
				cajafact = $('#cajafact2').val();
				fcambio = $('#fcambio2').val();				
				$.post('include/guardar_empleado.php', {accion:accion, cedula:cedula, nombre:nombre, apellido:apellido, cargo:cargo, ingreso:ingreso, hijos:hijos, depto:depto, razon:razon, agencia:agencia, telefono:telefono, correo:correo, turno:turno, supervisor:supervisor,  zonares:zonares, direccion:direccion, observacion:observacion, estudio:estudio, instruccion: instruccion, titulo:titulo, userlib:userlib, cajafact:cajafact, fcambio:fcambio}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Editar los datos, Intente mas tarde.').fadeIn(1000).fadeOut(15000);
					}if (data == '1'){				
						$('#mensaje').html('<strong>¡Exito!</strong> Empleado Editado Correctamente').fadeIn(1000).fadeOut(15000);	
						$('#ci2').val('');											
						$('#lista').DataTable().ajax.reload();
					}//End if
				});//End post			
			}else{
				$('#editar').bPopup();
			}//End if//End if
		});// End Function boot.confirm
	});//End Function

//Cambiar el estatus del Empleado
	$('#lista tbody').on('click', '.camb', function(){	
		var accion = "estatus";
		var element = $(this).attr('id').split('│');
		var cedula = element[0];
		var estatus = element[1];
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Empleado?', function(result){
			if (result == true){
				$.post("include/guardar_empleado.php", {accion:accion , cedula:cedula, estatus:estatus}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Editar el Estatus, Intente mas tarde').$('#error').fadeIn(1000).fadeOut(5000);
					}else if (data == '1'){
						$('#mensaje').html('<strong>¡Exito!</strong> Estatus Editado Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').DataTable().ajax.reload();
					}//End if
				});//End post	
			}//End if		 
		});//End Function		
	});//End Function

//Exportar a Excel
 $('#excel').click(function(){
 	$('#excel1').submit(); 
 });	

//Convertir la tabla en Datatable
	$('#lista').dataTable({
		"ajax": "include/consultap.php",
		"sPaginationType": "full_numbers",
		"language": {
			"sProcessing":     "Procesando...",
			"sLengthMenu":     "Mostrar _MENU_ registros",
			"sZeroRecords":    "No se encontraron resultados",
			"sEmptyTable":     "Ningún dato disponible en esta tabla",
			"sInfo":           "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
			"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix":    "",
			"sSearch":         "Buscar:",
			"sUrl":            "",
			"sInfoThousands":  ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst":    "Primero",
				"sLast":     "Último",
				"sNext":     "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}		
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
			"iDisplayLength": 10
	});
});
</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
	<?php echo $menu?>
    <div class="text-center">
    	<h4>Lista de Empleados</h4>
    </div> 
	<div align="center" class="alert alert-success oculto" id="mensaje">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    </div>
    <div align="center" class="alert alert-danger oculto" id="error">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>        
    </div>
    <div align="center" class="alert alert-warning oculto" id="alerta">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>        
    </div>
    <div class="container-fluid">
    <div class="row">
    <div class="col-xs-12">
    <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>CI</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Agencia</th>
            <th>Cargo</th>
            <th>Fecha Ingreso</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Usuario LIB</th>
            <th>Caja Fact</th>
            <th>Razon Social</th>
            <th>Est</th>
            <th>Grado Inst</th>
            <th>Título</th>
            <th>Zona</th>
            <th>Dirección</th>
            <th>Estatus</th>
            <th>Fecha Cambio</th>
            <th>Días Dis</th>
            <th>Días Pen</th>
            <th>Observación</th>
            <th>Hijos</th>
            <th>Supervisor</th>
            <th>Turno</th>
            <th>Departamento</th>
            <th>Comandos</th>          
        </tr>
    </thead>
    <tbody>      
    </tbody>
	<tfoot>
		<tr>
            <th>CI</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Agencia</th>
            <th>Cargo</th>
            <th>Fecha Ingreso</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Usuario LIB</th>
            <th>Caja Fact</th>
            <th>Razon Social</th>
            <th>Est</th>
            <th>Grado Inst</th>
            <th>Título</th>
            <th>Zona</th>
            <th>Dirección</th>
            <th>Estatus</th>
            <th>Fecha Cambio</th>
            <th>Días Dis</th>
            <th>Días Pen</th>
            <th>Observación</th>
            <th>Hijos</th>
            <th>Supervisor</th>
            <th>Turno</th>
            <th>Departamento</th>
            <th>Comandos</th>          
        </tr>
    </tfoot>
	</table>  
    </div><!-- End col -->   
    </div><!-- End row -->
    <div class="row">
    <div class="col-xs-12 text-center">
		<img src="imagenes/usuarioadd.png" id="newUser" title="Agregar Empleado" class="cursor">
		<img id="excel" src="imagenes/excel.png" width="48" height="48" class="cursor" title="Exportar para Excel">     
    </div><!-- End col -->  
    </div><!-- End row -->    
    </div><!-- End Container -->

    <div class="row login-popup" id="nuevo">
    <div class="col-xs-1 col-md-1 col-lg-1"></div>
    <div class="col-xs-12 col-md-10 col-lg-10">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Nuevo Empleado</h3>
    	</div>
  		<div class="panel">
      		<h3 class="panel-title">Información Básica</h3>
    	</div>   
        <div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
            	<label>* Cédula</label>
            	<input  type="text" name="ci" id="ci" class="form-control integer uncopypaste text-center" maxlength="8">     	                
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nombres</label>
               	<input  type="text" name="nombre" id="nombre"class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Apellidos</label>
               	<input  type="text" name="apellido" id="apellido" class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Cargo</label>
               	<select name="cargo" name="cargo" id="cargo" class="form-control">
                	<?php
					$consulta = mysql_query("SELECT id, descripcion FROM comercial_cargo WHERE estatus = '1' ORDER BY descripcion ASC");
						if (mysql_num_rows($consulta)){
							echo '<option>Seleccionar...</option>';
							while ($row = mysql_fetch_array($consulta)){
								 echo '<option>'.utf8_encode($row['descripcion']).'</option>';
							}//End While						
						}//End if						
					?>                    
                </select>          
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Fecha Ingreso</label>
               	<input  type="text" name="fingreso" id="fingreso"class="form-control text-center">
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Razon Social</label>
               	<input  type="text" name="razon" id="razon"class="form-control text-center">
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Departamento</label>
               	<input  type="text" name="departamento" id="departamento"class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Agencia</label>
               	<select name="agencia" id="agencia" class="form-control text-center">
				   <?php 
                   $consulta = mysql_query("SELECT codigo, descripcion FROM comercial_agencia WHERE estatus = '1' ORDER BY descripcion ASC");
                        if (mysql_num_rows($consulta)){
                                echo '<option>Seleccionar...</option>';
                            while ($row= mysql_fetch_array($consulta)){
                                echo '<option value="'.$row['codigo'].'">'.utf8_encode($row['descripcion']).'</option>';
                            }//End while					
                        }//En if				
                   ?>
              </select> 
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Teléfono</label>
               	<input  type="text" name="telefono" id="telefono" class="form-control integer text-center" maxlength="30">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Correo</label>
               	<input  type="text" name="correo" id="correo" class="form-control text-center" maxlength="30">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Turno</label>
               	<select name="turno" id="turno" class="form-control text-center">
               		<option>Seleccionar...</option>
                    <option value="1">Lunes a Viernes</option>
                    <option value="2">Martes a Sábado</option>
              	</select>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Supervisor</label>
               	<input  type="text" name="supervisor" id="supervisor"class="form-control text-center">
            </div>            
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nro de Hijos</label>
               	<input  type="text" name="hijos" id="hijos" class="form-control integer text-center" maxlength="2">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Zona de Residencia</label>
               	<input  type="text" name="zonares" id="zonares"class="form-control text-center">
            </div>           
           	<div class="form-group col-xs-12">
				<label>* Dirección</label>
               	<textarea name="direccion" id="direccion" class="form-control text-center"></textarea>
            </div>            
       	</div>
		<div class="panel">
      		<h3 class="panel-title">Información Académica</h3>
    	</div>
 		<div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Estudia Actualmente</label>
               	<select name="estudio" id="estudio" class="form-control text-center">
                    <option>Seleccionar...</option>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
            </div>
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Grado de Instrucción</label>
               	<input  type="text" name="instruccion" id="instruccion"class="form-control text-center">
            </div> 
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Titulo</label>
               	<input  type="text" name="titulo" id="titulo"class="form-control text-center">
            </div>
		</div>
		<div class="panel">
      		<h3 class="panel-title">Otros Datos</h3>
    	</div>
 		<div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Usuario LIB</label>
               	<input  type="text" name="userlib" id="userlib" class="form-control text-center">
            </div>        
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Caja Facturación</label>
               	<input  type="text" name="cajafact" id="cajafact"class="form-control text-center">
            </div>         
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Fecha Cambio</label>
               	<input  type="text" name="fcambio" id="fcambio" class="form-control text-center">
            </div> 
 			<div class="form-group col-xs-12">
				<label>Observacion</label>
               	<textarea name="observacion" id="observacion" class="form-control text-center"></textarea>
            </div>        
			<div class="form-group col-xs-12 text-center">
				<input type="image" id="enviar" src="imagenes/save.png" title="Ingresar Usuario">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-1 col-md-1 col-lg-1"></div>
    </div><!--End col -->
    </div><!--End row -->  
       
    <div class="row login-popup" id="editar">
    <div class="col-xs-1 col-md-1 col-lg-1"></div>
    <div class="col-xs-12 col-md-10 col-lg-10">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Editar Empleado</h3>
    	</div>
  		<div class="panel">
      		<h3 class="panel-title">Información Básica</h3>
    	</div>   
        <div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
            	<label>Cédula</label>
            	<input  type="text" name="ci2" id="ci2" class="form-control uncopypaste text-center" readonly>               
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nombres</label>
               	<input  type="text" name="nombre2" id="nombre2"class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Apellidos</label>
               	<input  type="text" name="apellido2" id="apellido2" class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Cargo</label>
               	<select name="cargo2" name="cargo2" id="cargo2" class="form-control">
                	<?php
					$consulta = mysql_query("SELECT descripcion FROM comercial_cargo WHERE estatus = '1' ORDER BY descripcion ASC");
						if (mysql_num_rows($consulta)){
							echo '<option>Seleccionar...</option>';
							while ($row = mysql_fetch_array($consulta)){
								 echo '<option value="'.utf8_encode($row['descripcion']).'">'.utf8_encode($row['descripcion']).'</option>';
							}//End While						
						}//End if						
					?>                    
                </select>  
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Fecha Ingreso</label>
               	<input  type="text" name="fingreso2" id="fingreso2"class="form-control text-center">
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Departamento</label>
               	<input  type="text" name="departamento2" id="departamento2"class="form-control text-center">
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Razon Social</label>
               	<input  type="text" name="razon2" id="razon2"class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Agencia</label>
               	<select name="agencia2" id="agencia2" class="form-control text-center">
				   <?php 
                   $consulta = mysql_query("SELECT codigo, descripcion FROM comercial_agencia WHERE estatus = '1' ORDER BY descripcion ASC");
                        if (mysql_num_rows($consulta)){
                                echo '<option>Seleccionar...</option>';
                            while ($row= mysql_fetch_array($consulta)){
                                echo '<option value="'.$row['codigo'].'">'.utf8_encode($row['descripcion']).'</option>';
                            }//End while					
                        }//En if				
                   ?>
              	</select> 
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Teléfono</label>
               	<input  type="text" name="telefono2" id="telefono2"class="form-control integer text-center" maxlength="11">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Correo</label>
               	<input  type="text" name="correo2" id="correo2" class="form-control text-center" maxlength="30">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Turno</label>
               	<select name="turno2" id="turno2" class="form-control text-center">
               		<option>Seleccionar...</option>
                    <option value="1">Lunes a Viernes</option>
                    <option value="2">Martes a Sábado</option>
              	</select>            
            </div>            
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Supervisor</label>
               	<input  type="text" name="supervisor2" id="supervisor2"class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nro de Hijos</label>
               	<input  type="text" name="hijos2" id="hijos2" class="form-control integer text-center" maxlength="2">
            </div>           
           	<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Zona de Residencia</label>
               	<input  type="text" name="zonares2" id="zonares2" class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 text-center">
				<label>* Dirección</label>
               	<textarea name="direccion2" id="direccion2" class="form-control text-center"></textarea>
            </div>            
       	</div>       
		<div class="panel">
      		<h3 class="panel-title">Información Académica</h3>
    	</div>
 		<div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Estudia Actualmente</label>
               	<select name="estudio2" id="estudio2" class="form-control text-center">
                    <option>Seleccionar...</option>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
            </div>
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Grado de Instrucción</label>
               	<input  type="text" name="instruccion2" id="instruccion2" class="form-control text-center">
            </div> 
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Titulo</label>
               	<input type="text" name="titulo2" id="titulo2"class="form-control text-center">
            </div>
		</div>
		<div class="panel">
      		<h3 class="panel-title">Otros Datos</h3>
    	</div>
 		<div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Usuario LIB</label>
               	<input  type="text" name="userlib2" id="userlib2" class="form-control text-center">
            </div>        
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Caja Facturación</label>
               	<input  type="text" name="cajafact2" id="cajafact2"class="form-control text-center">
            </div>         
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Fecha Cambio</label>
               	<input  type="text" name="fcambio2" id="fcambio2" class="form-control text-center">
            </div> 
 			<div class="form-group col-xs-12 text-center">
				<label>Observación</label>
               	<textarea name="observacion2" id="observacion2" class="form-control text-center"></textarea>
            </div>        
			<div class="form-group col-xs-12 text-center">
				<input type="image" id="enviar2" src="imagenes/save.png" title="Ingresar Usuario">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-1 col-md-1 col-lg-1"></div>
    </div><!--End col -->
    </div><!--End row -->
    
    </div>       
    </div><!--End Contenido -->    
    <?php echo $chat; echo $footer?>
</body>
	<form id="excel1" method="POST" action="excelempleado.php" target="_blank" style="display:none">    	
    </form>  
</html>
<?php
}else{
	header("location:index.php?alerta=salir");
}
?>