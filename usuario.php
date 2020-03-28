<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user']) && ($_SESSION['nivel'] < 2)){
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="css/comercial.css">
<link rel="stylesheet" href="css/chat.css">
<!-- Archivos JavaScript -->	
<script src="../js/jquery.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
<script src="../DataTables/js/dataTables.responsive.min.js"></script>
<script src="../bootstrap/js/bootstrap-submenu.js"></script>
<script src="../bootstrap/js/bootbox.min.js"></script>
<script src="js/jquery.bpopup.min.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script src="js/chat.js"></script>
<script>
$(document).ready(function(){

	var nivel = <?php echo $_SESSION['nivel'];?>;

	fillAgencies();

//Activar Menú
	$("#menu2").attr('class','active');

//Mostrar Formulario de nuevo Usuario
	$("#boton").click(function() {
		$('#nuevo').bPopup();
	});
	
//Validar y Agregar Usuario Nuevo
	$("#save").click(function(){
		if (validateForm('#nuevo')){
			if ($("#clave").val()!= $("#clave2").val()){
				$('#clave2').parent().addClass('has-error');
				return false;	
			}
			$('#nuevo').bPopup().close();
				bootbox.confirm('¿Seguro que desea Incluir el Usuario?', function(result){
				if (result == true){
					cedula = $('#cedula').val();
					nombre = $('#nombre').val();
					userid = $('#userid').val();
					cargo = $('#cargo').val();
					agencia = $('#agencia').val();
					departamento = $('#departamento').val();
					tipousuario = $('#tipousuario').val();
					clave = $('#clave').val();	
					$.post('include/pdo/usuario.php', {function:"newUser", cedula:cedula, nombre:nombre, userid:userid, cargo:cargo, agencia:agencia, departamento:departamento, tipousuario:tipousuario, clave:clave}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error a Incluir el Usuario, Intente Nuevamente').fadeIn(1000).fadeOut(5000);						
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> Usuario Incluido Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}else if (data == 'repetido'){
							$('#alerta').html('<strong>¡Alerta!</strong> Ya existe una Usuario con esa Cédula o con ese Userid').fadeIn(1000).fadeOut(5000);
							$('#cedula').val('');
							$('#userid').val('');						
						}//End if
					});//End post	
				}else{
					$('#nuevo').bPopup();
				}//End if			 
			});//End Function
		}//End Validate Form		
	});//End Function
	
//Mostrar Formulario de editar Usuario
	$('#lista tbody').on('click', '.edit', function(){	
		var cedula = $(this).attr('id');
		$.post('include/pdo/usuario.php', {cedula:cedula, function:"getUser"}, function(data){
			var obj = jQuery.parseJSON(data);
			$('#cedula2').val(obj.ci).parent().removeClass('has-error has-success');
			$('#nombre2').val(obj.nombre).parent().removeClass('has-error has-success');
			$('#cargo2').val(obj.cargo).parent().removeClass('has-error has-success');
			$('#userid2').val(obj.userid).parent().removeClass('has-error has-success');
			$('#departamento2').val(obj.departamento).parent().removeClass('has-error has-success');
			$('#tipousuario2').val(obj.nivel).parent().removeClass('has-error has-success');
			$('#agencia2').val(obj.agencia).parent().removeClass('has-error has-success');
		});//End post		
		$('#editar').bPopup();
	});

//Validar y Editar Usuario
	$("#enviar").click(function(){
		if (validateForm('#editar')){
			cedula = $('#cedula2').val();
			nombre = $('#nombre2').val();
			userid = $('#userid2').val();
			cargo = $('#cargo2').val();
			agencia = $('#agencia2').val();
			departamento = $('#departamento2').val();
			tipousuario = $('#tipousuario2').val();
			$('#editar').bPopup().close();
				bootbox.confirm('¿Seguro que desea el Editar el Usuario?', function(result){
				if (result == true){
					$.post('include/pdo/usuario.php', {function:"editUser", cedula:cedula, nombre:nombre, cargo:cargo, agencia:agencia, departamento:departamento, tipousuario:tipousuario}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> "Error al Editar el Usuario, Intente mas tarde"').fadeIn(1000).fadeOut(5000);	
						}else if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> Usuario Editado Correctamente').fadeIn(1000).fadeOut(5000);
							$('#lista').DataTable().ajax.reload();
						}//End if
					});//End post	
				}else{
					$('#editar').bPopup();
				}//End if		 
			});//End Function
		}//End Validate Form
	});
		
//Mostra el formulario Resetear Clave
	$('#lista tbody').on('click', '.reset', function(){
		var cedula = $(this).attr('id');
		$.post('include/pdo/usuario.php', {cedula:cedula, function:"getUser"}, function(data){
			var obj = jQuery.parseJSON(data);	
			$('#cedula3').val(obj.ci).parent().removeClass('has-error has-success');
			$('#nombre3').val(obj.nombre).parent().removeClass('has-error has-success');
			$('#userid3').val(obj.userid).parent().removeClass('has-error has-success');
			$('#clave_nueva').val('').attr('placeholdeer','').parent().removeClass('has-error has-success');
			$('#clave_nueva2').val('').attr('placeholdeer','').parent().removeClass('has-error has-success');
		});//End post
		$('#resetear').bPopup();
	})

//Validar y Resetear Clave
	$("#enviar2").click(function(){
		if (validateForm('#resetear')){
			if ($("#clave_nueva").val()!= $("#clave_nueva2").val()){
				$('#clave_nueva2').parent().addClass('has-error');
				return false;	
			}
			cedula = $('#cedula3').val();
			clave_nueva = $('#clave_nueva').val();
			$('#resetear').bPopup().close();
			bootbox.confirm('¿Seguro que desea el resetear la contraseña del Usuario?', function(result){
				if (result == true){
					$.post("include/pdo/usuario.php", {function:"resetPassword", cedula:cedula, clave_nueva:clave_nueva}, function(data){
						if (data  == '0'){
							$('#error').html('<strong>¡Error!</strong> Error al Editar la Contraseña, Intente mas tarde').fadeIn(1000).fadeOut(5000);
							}else if (data == '1'){
							$('#clave_nueva').val('');
							$('#clave_nueva2').val('');
							$('#mensaje').html('<strong>¡Exito!</strong> Contraseña Reseteada Correctamente').fadeIn(1000).fadeOut(5000);							
						}//End if
					});//End post
				}else{
					$('#resetear').bPopup();
				}//End if		 
			});//End Function
		}//End Form Validate			
	});
	
//Cambiar el estatus del usuario
	$('#lista tbody').on('click', '.camb', function(){	
		var cedula = $(this).attr('id');
		bootbox.confirm('¿Seguro que desea el cambiar el Estatus del Usuario?', function(result){
			if (result == true){
				$.post("include/pdo/usuario.php", {function:"changeStatus" , cedula:cedula}, function(data){
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
		    "url": "include/pdo/usuario.php",
		    "type": "POST",
		    "data": {
		        "function": "getUsers"
		    }
		},
		"sPaginationType": "full_numbers",
		"columnDefs": [
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[7]){
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
		              "targets": 6
		        },
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[6]){
		              		case "1":
		              			status = 'Administrador';
		              		break;
		              		case "2":
		              			status = 'Supervisor';
		              		break;
		              		case "3":
		              			status = 'Usuario';
		              		break;
		              		default:
		              			status = '';
		              		break;
		              	}
		                  return status;
		              },
		              "targets": 7
		        },		        
		      	{         
		              "render": function ( data, type, row ) {
		              	edit =  '<img src="imagenes/edit.png" class="edit cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" title="Editar">';
		              	switch (row[7]){
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
			            reset = '<img src="imagenes/clave.png" class="reset cursor" title="Resetear Contraseña" id="'+ row[0] + '" data-toggle="modal" data-placement="bottom">';
		               	return  edit + ' ' + block + ' ' + reset;
		              },
		              "targets":8
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

	//LLenar lista de agencias
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
    	<h4 >Usuarios del Sistema</h4>
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
            <th>Usuario</th>
            <th>Cargo</th>
            <th>Agencia</th> 
            <th>Departamento</th>  
            <th>Estatus</th>   
            <th>Nivel</th>
            <th>Comandos</th>             
        </tr>
    </thead>
  	<tfoot>        
        <tr>
            <th>CI</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Cargo</th>
            <th>Agencia</th>
            <th>Departamento</th>  
            <th>Estatus</th>   
            <th>Nivel</th>
            <th>Comandos</th>           
        </tr>
	</tfoot>     
    <tbody>      
    </tbody>
	</table>
	<div align="center">
    	<img src="imagenes/usuarioadd.png" width="50" height="50" class="cursor" title="Agregar Usuario" id="boton">
   	</div> 
    </div><!-- End col -->
    </div><!-- End row -->
	</div><!-- End Container -->
    
    <div class="row login-popup" id="nuevo">
    <div class="col-xs-12 col-md-2 col-lg-3"></div>
    <div class="col-xs-12 col-md-8 col-lg-6">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Nuevo Usuario</h3>
    	</div>
    	<div class="panel-body">
        	<input type="hidden" name="accion" value="nuevo">
            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
            	<label>Cédula</label>
            	<input type="text" name="cedula" id="cedula" class="form-control integer uncopypaste text-center" maxlength="8" required>     
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Nombre</label>
               	<input type="text" name="nombre" id="nombre" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>User Id</label>
               	<input type="text" name="userid" id="userid" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Cargo</label>
               	<input type="text" name="cargo" id="cargo" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Agencia</label>
				<select name="agencia" id="agencia" class="form-control text-center" required>
					<option>Seleccionar...</option>
				</select>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Departamento</label>
               	<input type="text" name="departamento" id="departamento" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Tipo de Usuario</label>
               	<select name="tipousuario" id="tipousuario" class="form-control text-center" required>
                    <option value="" selected>Seleccionar...</option>
                    <option value="1">Administrador</option>
                    <option value="2">Supervisor</option>
                    <option value="3">Usuario</option>
              	</select>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Contraseña</label>
               	<input type="password" name="clave" id="clave" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Confirmar</label>
               	<input type="password" name="clave2" id="clave2" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 text-center">
				<input type="image" id="save" src="imagenes/save.png" title="Ingresar Usuario">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-12 col-md-2 col-lg-3"></div>
    </div><!--End col -->
    </div><!--End row --> 

	<div class="row login-popup" id="editar">
    <div class="col-xs-12 col-md-2 col-lg-3"></div>
    <div class="col-xs-12 col-md-8 col-lg-6">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Editar Usuario</h3>
    	</div>
    	<div class="panel-body">
              <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
            	<label>Cédula</label>
            	<input  type="text" name="cedula2" id="cedula2" size="15" maxlength="8" class="form-control uncopypaste text-center" readonly>                 	                
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>User Id</label>
               	<input name="userid2" type="text" id="userid2" size="15" maxlength="15" class="form-control uncopypaste text-center" readonly>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Nombre</label>
               	<input name="nombre2" id="nombre2" type="text" size="40" maxlength="40" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Cargo</label>
               	<input name="cargo2" type="text" id="cargo2" size="40" maxlength="40" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Agencia</label>
               	<select name="agencia2" id="agencia2" class="form-control text-center" required>
				   <option>Seleccionar...</option>
              	</select>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Departamento</label>
               	<input name="departamento2" type="text" id="departamento2" size="40" maxlength="40" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Tipo de Usuario</label>
               	<select name="tipousuario2" id="tipousuario2" class="form-control text-center" required>
                    <option value="" selected>Seleccionar...</option>
                    <option value="1">Administrador</option>
                    <option value="2">Supervisor</option>
                    <option value="3">Usuario</option> 
              	</select>
            </div>
			<div class="form-group col-xs-12 text-center">
				<input name="enviar" type="image" id="enviar" src="imagenes/save.png" title="Editar Usuario">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-12 col-md-2 col-lg-3"></div>
    </div><!--End col -->
    </div><!--End row --> 
    
    <div class="row login-popup" id="resetear">
    <div class="col-xs-12 col-md-2 col-lg-3"></div>
    <div class="col-xs-12 col-md-8 col-lg-6">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Resetear Contraseña</h3>
    	</div>
    	<div class="panel-body">
              <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
            	<label>Cédula:</label>
            	<input  type="text" name="cedula3" id="cedula3" size="15" maxlength="8" style="text-transform:uppercase" readonly class="form-control text-center">                 	                
            </div>
            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Nombre:</label>
               	<input name="nombre3" id="nombre3" type="text" size="40" maxlength="40" style="text-transform:uppercase" readonly class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
				<label>Userid:</label>
               	<input name="userid3" type="text" id="userid3" size="15" maxlength="15" readonly class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 col-md-6 text-center">
				<label>Contraseña Nueva:</label>
               	<input name="clave_nueva" type="password" id="clave_nueva" size="40" maxlength="40" class="form-control uncopypaste text-center" required>
            </div>
			<div class="form-group col-xs-12 col-md-6 text-center">
				<label>Confirmar Contraseña:</label>
               	<input name="clave_nueva2" type="password" id="clave_nueva2" size="40" maxlength="40" class="uncopypaste form-control text-center" required>
            </div>		
			<div class="form-group col-xs-12 text-center">
				<input name="enviar" type="image" id="enviar2" src="imagenes/save.png" title="Resetear Contraseña de Usuario">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-12 col-md-2 col-lg-3"></div>
    </div><!--End col -->
    </div><!--End row -->  
    
    </div><!--End Contenido -->
    <?php echo $chat; echo $footer?>
</body>
</html>
<?php
}else{
	header("location:index.php?alerta=salir");
}
?>