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
<link rel="stylesheet" href="../DataTables/css/buttons.dataTables.min.css"> 
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="css/comercial.css">
<link rel="stylesheet" href="css/chat.css">
<!-- Archivos JavaScript -->	
<script src="../js/jquery.js"></script>
<script src="js/jquery.bpopup.min.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.select.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../DataTables/js/dataTables.buttons.min.js"></script>
<script src="../DataTables/js/buttons.flash.min.js"></script>
<script src="../DataTables/js/jszip.min.js"></script>
<script src="../DataTables/js/pdfmake.min.js"></script>
<script src="../DataTables/js/vfs_fonts.js"></script>
<script src="../DataTables/js/buttons.html5.min.js"></script>
<script src="../DataTables/js/buttons.print.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
<script src="../bootstrap/js/locale/bootstrap-datepicker.es.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script src="js/chat.js"></script>
<script>
$(document).ready(function(){

	var nivel = <?php echo $_SESSION['nivel'];?>;
	fillCargos();
	fillAgencies();

//Activar Menú
	$("#menu2").attr('class','active');

//Campos fecha
	$('#fingreso').datepicker({
		language: "es",
	  	format: "dd/mm/yyyy",
	  	endDate: new Date(),
	  	autoclose: true,
	    todayBtn:  1
	});
	
	$('#fingreso2').datepicker({
		language: "es",
	  	format: "dd/mm/yyyy",
	  	endDate: new Date(),
	  	autoclose: true,
	    todayBtn:  1		
	});

	$('#fcambio').datepicker({
		language: "es",
	  	format: "dd/mm/yyyy",
	  	endDate: new Date(),
	  	autoclose: true,
	    todayBtn:  1	
	});
	
	$('#fcambio2').datepicker({
		language: "es",
	  	format: "dd/mm/yyyy",
	  	endDate: new Date(),
	  	autoclose: true,
	    todayBtn:  1	
	});
	
//Función para buscar el autocompletado de Supervisor
	$(function(){
		$('#supervisor, #supervisor2').autocomplete({
			minLength: 5,
			source: function( request, response ) {
			   	// Fetch data
			   	$.ajax({
			    	url: "include/pdo/empleado.php",
			    	type: 'post',
			    	dataType: "json",
			    	data: {
			     		function:"autocompleteSupervisor",
			     		supervisor: request.term
			    	},
			    	success: function( data ) {
			     		response(data);
			    	}
			   	});
		  	},
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html();
				});
				$('#resultados').slideDown('slow');
			} 
		});
	});

//Función para buscar el autocompletado de Zona de Residencia
	$(function(){
		$('#zonares, #zonares2').autocomplete({
			minLength: 3,
			source: function( request, response ) {
			   	// Fetch data
			   	$.ajax({
			    	url: "include/pdo/empleado.php",
			    	type: 'post',
			    	dataType: "json",
			    	data: {
			     		function:"autocompleteZonares",
			     		zonares: request.term
			    	},
			    	success: function( data ) {
			     		response(data);
			    	}
			   	});
		  	},
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html();
				});
				$('#resultados').slideDown('slow');
			} 
		});
	});

//Función para buscar el autocompletado de Grado de Instruccion
	$(function(){
		$('#instruccion, #instruccion2').autocomplete({
			minLength: 3,
			source: function( request, response ) {
			   	// Fetch data
			   	$.ajax({
			    	url: "include/pdo/empleado.php",
			    	type: 'post',
			    	dataType: "json",
			    	data: {
			     		function:"autocompleteInstruccion",
			     		instruccion: request.term
			    	},
			    	success: function( data ) {
			     		response(data);
			    	}
			   	});
		  	},
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html();
				});
				$('#resultados').slideDown('slow');
			} 
		});
	});	

//Función para buscar el autocompletado de Grado de Titulo
	$(function(){
		$('#titulo, #titulo2').autocomplete({
			minLength: 3,
			source: function( request, response ) {
			   	// Fetch data
			   	$.ajax({
			    	url: "include/pdo/empleado.php",
			    	type: 'post',
			    	dataType: "json",
			    	data: {
			     		function:"autocompleteTitulo",
			     		titulo: request.term
			    	},
			    	success: function( data ) {
			     		response(data);
			    	}
			   	});
		  	},
			select : function(event, ui){
				$('#resultados').slideUp('slow', function(){
					$('#resultados').html();
				});
				$('#resultados').slideDown('slow');
			} 
		});
	});
	
//Mostrar Formulario de Nuevo Empleado
	$('#newUser').click(function(){
		$('#nuevo').bPopup();		
	});

//Funcion para Validar y Guardar Nuevo Empleado
	$('#enviar').click(function(){
		if (validateForm('#nuevo')){
			$('#nuevo').bPopup().close();
			bootbox.confirm('¿Seguro que desea Incluir el empleado?', function(result){
				if (result == true){
					cedula = $('#ci').val();
					nombre = $('#nombre').val();
					apellido = $('#apellido').val();
					cargo = $('#cargo').val();
					ingreso = $('#fingreso').val();
					departamento = $('#departamento').val();
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
					$.post('include/pdo/empleado.php', {function:"insertEmployee", cedula:cedula, nombre:nombre, apellido:apellido, cargo:cargo, ingreso:ingreso, departamento:departamento, razon:razon, agencia:agencia, telefono:telefono, correo:correo, turno:turno, supervisor:supervisor, hijos:hijos, zonares:zonares, direccion:direccion, observacion:observacion, estudio:estudio, instruccion: instruccion, titulo:titulo, userlib:userlib, cajafact:cajafact, fcambio:fcambio}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente mas tarde.').fadeIn(1000).fadeOut(15000);
						}if (data == '1'){				
							$('#mensaje').html('<strong>¡Exito!</strong> Empleado Agregado Correctamente').fadeIn(1000).fadeOut(15000);	
							$('#lista').DataTable().ajax.reload();
							clearForm('#nuevo');
						}if (data == 'repetido'){
							$('#error').html('<strong>¡Error!</strong> El Número de Cédula ya está registrada. Verifique e Intente Nuevamente').fadeIn(1000).fadeOut(15000);					
						}//End if
					});//End post	
				}else{
					$('#nuevo').bPopup();
				}//End if//End if
			});// End Function boot.confirm
		}//End validate Form
	});//End Function

//Mostrar Formulario de editar Empleado
	$('#lista tbody').on('click', '.edit', function(){
	var cedula = $(this).attr('id');
		$.post("include/pdo/empleado.php", {function:"getEmployee", cedula:cedula}, function(data){
			var obj = jQuery.parseJSON(data);
			$('#ci2').val(obj.ci);
			$('#nombre2').val(obj.nombre).parent().removeClass('has-error has-success');
			$('#apellido2').val(obj.apellido).parent().removeClass('has-error has-success');
			$('#cargo2').val(obj.cargo).parent().removeClass('has-error has-success');
			$('#fingreso2').val(obj.fecha_ingreso).parent().removeClass('has-error has-success');
			$('#correo2').val(obj.correo).parent().removeClass('has-error has-success');
			$('#telefono2').val(obj.telefono).parent().removeClass('has-error has-success');
			$('#departamento2').val(obj.dpto).parent().removeClass('has-error has-success');
			$('#razon2').val(obj.razon_social).parent().removeClass('has-error has-success');
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
			$('#fcambio2').val(obj.fecha_camb).parent().removeClass('has-error has-success');
		});//End post				
		$('#editar').bPopup();		
	});
	
//Funcion para Validar y Editar Empleado
	$('#enviar2').click(function(){
		if (validateForm('#editar')){
			$('#editar').bPopup().close();
			bootbox.confirm('¿Seguro que desea Editar el empleado?', function(result){
				if (result == true){
					cedula = $('#ci2').val();
					nombre = $('#nombre2').val();
					apellido = $('#apellido2').val();
					cargo = $('#cargo2').val();
					ingreso = $('#fingreso2').val();
					hijos = $('#hijos2').val();
					departamento = $('#departamento2').val();
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
					$.post('include/pdo/empleado.php', {function:"editEmployee", cedula:cedula, nombre:nombre, apellido:apellido, cargo:cargo, ingreso:ingreso, hijos:hijos, departamento:departamento, razon:razon, agencia:agencia, telefono:telefono, correo:correo, turno:turno, supervisor:supervisor, zonares:zonares, direccion:direccion, observacion:observacion, estudio:estudio, instruccion: instruccion, titulo:titulo, userlib:userlib, cajafact:cajafact, fcambio:fcambio}, function(data){
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
		}
	});//End Function

//Cambiar el estatus del Empleado
	$('#lista tbody').on('click', '.camb', function(){	
		var element = $(this).attr('id').split('│');
		var cedula = element[0];
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Empleado?', function(result){
			if (result == true){
				$.post("include/pdo/empleado.php", {function:"statusEmployee" , cedula:cedula}, function(data){
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


	//Convertir la tabla en Datatable
	$('#lista').dataTable({
		"ajax": {
		    "url": "include/pdo/empleado.php",
		    "type": "POST",
		    "data": {
		        "function": "getAllEmployees"
		    }
		},
		"sPaginationType": "full_numbers",
		"columnDefs": [
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[11]){
		              		case "1":
		              			status = 'Si';
		              		break;
		              		case "0":
		              			status = 'No';
		              		break;
		              		default:
		              			status = '';
		              		break;
		              	}
		                  return status;
		              },
		              "targets": 11
		        },
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[16]){
		              		case "1":
		              		status = '<img src="imagenes/activo.png">';
		              		break;
		              		case "0":
		              		status = '<img src="imagenes/inactivo.png">';
		              		break;
		              		default:
		              		status = '';
		              		break;
		              	}
		                  return status;
		              },
		              "targets": 16
		        },
		      	{         
		              "render": function ( data, type, row ) {
		              	edit =  '<img src="imagenes/edit.png" class="edit cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" title="Editar">';
		              	switch (row[16]){
			              		case "1":
			              			block = '<img src="imagenes/block.png" class="camb cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" data-target="#block" title="Deshabilitar">';
			              		break;
			              		case "0":
			              			block = '<img src="imagenes/block2.png" class="camb cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" data-target="#block" title="Habilitar">';
			              		break;
			              		default:
			              			block = '';
			              		break;
			              	}		              		
		                  return  edit + ' ' + block;
		              },
		              "targets":25
		        }           
		      ],
		"language":{ 
			"url": "../DataTables/locale/Spanish.json"
		},
		aLengthMenu: [[10,50,100],[10,50,100]],
		"iDisplayLength": 10,
		dom: 'Bflrtip',
		buttons: getbuttons(nivel, [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24], 'Lista de Empleados')
	});

	function fillCargos(){
		$.post("include/pdo/empleado.php", {function: "getCargos"}, function(data){
			if (data != 0){
			var json = jQuery.parseJSON(data);		
				$.each(json, function(idx, obj) {
					$('#cargo, #cargo2').append('<option value = "'+ obj.descripcion +'">' + obj.descripcion + '</option>'); 
				})
			}
		});//End post
	}

	function fillAgencies(){
		$.post("include/pdo/agencia.php", {function: "getAgencies"}, function(data){
			if (data != 0){
			var json = jQuery.parseJSON(data);		
				$.each(json, function(idx, obj) {
					$('#agencia, #agencia2').append('<option value = "'+ obj.codigo +'">' + obj.descripcion + '</option>'); 
				})
			}
		});//End post
	}

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
    </div><!-- End col -->  
    </div><!-- End row -->    
    </div><!-- End Container -->

    <div class="row login-popup" id="nuevo">
    <div class="col-xs-1 col-md-1 col-lg-1"></div>
    <div class="col-xs-12 col-md-12 col-lg-10">   	
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
            	<input  type="text" name="ci" id="ci" class="form-control integer uncopypaste text-center" maxlength="8" required>
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nombres</label>
               	<input  type="text" name="nombre" id="nombre"class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Apellidos</label>
               	<input  type="text" name="apellido" id="apellido" class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Cargo</label>
               	<select name="cargo" name="cargo" id="cargo" class="form-control" required>
               		<option>Seleccionar...</option> 	                    
                </select>          
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Fecha Ingreso</label>
               	<input  type="text" name="fingreso" id="fingreso"class="form-control text-center" required>
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Razon Social</label>
               	<input type="text" name="razon" id="razon"class="form-control text-center" required>
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Departamento</label>
               	<input type="text" name="departamento" id="departamento"class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Agencia</label>
               	<select name="agencia" id="agencia" class="form-control text-center" required>
               		<option>Seleccionar...</option>
              	</select> 
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Teléfono</label>
               	<input type="text" name="telefono" id="telefono" class="form-control integer text-center" maxlength="30" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Correo</label>
               	<input type="text" name="correo" id="correo" class="form-control text-center" maxlength="30">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Turno</label>
               	<select name="turno" id="turno" class="form-control text-center" required>
               		<option>Seleccionar...</option>
                    <option value="1">Lunes a Viernes</option>
                    <option value="2">Martes a Sábado</option>
              	</select>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Supervisor</label>
               	<input type="text" name="supervisor" id="supervisor"class="form-control text-center" required>
            </div>        
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nro de Hijos</label>
               	<input type="text" name="hijos" id="hijos" class="form-control integer text-center" maxlength="2" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Zona de Residencia</label>
               	<input type="text" name="zonares" id="zonares"class="form-control text-center" required>
            </div>           
           	<div class="form-group col-xs-12">
				<label>* Dirección</label>
               	<textarea name="direccion" id="direccion" class="form-control text-center" required></textarea>
            </div>         
       	</div>
		<div class="panel">
      		<h3 class="panel-title">Información Académica</h3>
    	</div>
 		<div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Estudia Actualmente</label>
               	<select name="estudio" id="estudio" class="form-control text-center" required>
                    <option>Seleccionar...</option>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
            </div>
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Grado de Instrucción</label>
               	<input type="text" name="instruccion" id="instruccion"class="form-control text-center" required>
            </div> 
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Titulo</label>
               	<input type="text" name="titulo" id="titulo"class="form-control text-center" required>
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
               	<input type="text" name="nombre2" id="nombre2"class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Apellidos</label>
               	<input type="text" name="apellido2" id="apellido2" class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Cargo</label>
               	<select name="cargo2" name="cargo2" id="cargo2" class="form-control" required>
               		<option>Seleccionar...</option>
                </select>  
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Fecha Ingreso</label>
               	<input type="text" name="fingreso2" id="fingreso2"class="form-control text-center" required>
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Departamento</label>
               	<input type="text" name="departamento2" id="departamento2"class="form-control text-center" required>
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Razon Social</label>
               	<input type="text" name="razon2" id="razon2"class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Agencia</label>
               	<select name="agencia2" id="agencia2" class="form-control text-center" required>
               		<option>Seleccionar...</option>			   
              	</select> 
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Teléfono</label>
               	<input type="text" name="telefono2" id="telefono2"class="form-control integer text-center" maxlength="11" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Correo</label>
               	<input type="text" name="correo2" id="correo2" class="form-control text-center" maxlength="30">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Turno</label>
               	<select name="turno2" id="turno2" class="form-control text-center" required>
               		<option>Seleccionar...</option>
                    <option value="1">Lunes a Viernes</option>
                    <option value="2">Martes a Sábado</option>
              	</select>            
            </div>            
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Supervisor</label>
               	<input type="text" name="supervisor2" id="supervisor2"class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Nro de Hijos</label>
               	<input type="text" name="hijos2" id="hijos2" class="form-control integer text-center" maxlength="2" required>
            </div>           
           	<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Zona de Residencia</label>
               	<input type="text" name="zonares2" id="zonares2" class="form-control text-center" required>
            </div>
			<div class="form-group col-xs-12 text-center">
				<label>* Dirección</label>
               	<textarea name="direccion2" id="direccion2" class="form-control text-center" required></textarea>
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
               	<input type="text" name="instruccion2" id="instruccion2" class="form-control text-center" required>
            </div> 
   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>* Titulo</label>
               	<input type="text" name="titulo2" id="titulo2"class="form-control text-center" required>
            </div>
		</div>
		<div class="panel">
      		<h3 class="panel-title">Otros Datos</h3>
    	</div>
 		<div class="panel-body">
            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
				<label>Usuario LIB</label>
               	<input type="text" name="userlib2" id="userlib2" class="form-control text-center">
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