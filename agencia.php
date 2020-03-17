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
<link rel="stylesheet" href="../DataTables/css/buttons.dataTables.min.css"> 
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../css/jquery-ui.css" type="text/css" media="all" />
<link rel="stylesheet" href="css/comercial.css">
<link rel="stylesheet" href="css/chat.css">
<!-- Archivos JavaScript -->
<script src="../js/jquery.js"></script>
<script src="js/jquery.bpopup.min.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
<script src="../DataTables/js/jquery.dataTables.js"></script>
<script src="../DataTables/js/dataTables.bootstrap.js"></script>
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
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script src="js/chat.js"></script>
<script>
$(document).ready(function(){

	var nivel = <?php echo $_SESSION['nivel'];?>;
	fillHorarios();

//Activar Menú
	$("#menu2").attr('class','active');

//Mostrar Formulario de Nueva Agencia
	$("#boton").click(function() {
		$('body input[type=text]').val('').attr('placeholder','').parent().removeClass('has-error has-success');		
		$('#tipo option:first').prop("selected", "selected");
		$('body select').parent().removeClass('has-error has-success');
		$('body textarea').val('').attr('placeholder','').parent().removeClass('has-error has-success');		
		$('#nuevo').bPopup();
	});
	
//Validar y Agregar Agencia
	$("#enviar").click(function(){		
		if ($("#codigo").val() == ''){
			$('#codigo').parent().addClass('has-error');
			$('#codigo').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#codigo').parent().removeClass('has-error').addClass('has-success');
		};		
		if ($("#tipo option:selected").index() == 0){
			$('#tipo').parent().addClass('has-error');
			$('#tipo').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#tipo').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#departamneto").val() == ''){
			$('#departamneto').parent().addClass('has-error');
			$('#departamneto').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#departamneto').parent().removeClass('has-error').addClass('has-success');
		}		
		if ($("#horario option:selected").index() == 0){
			$('#horario').parent().addClass('has-error');
			$('#horario').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#horario').parent().removeClass('has-error').addClass('has-success');
		};		
		if ($("#descripcion").val() == ''){
			$('#descripcion').parent().addClass('has-error');
			$('#descripcion').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#descripcion').parent().removeClass('has-error').addClass('has-success');
		};	
		accion= 'nuevo';
		codigo = $('#codigo').val();
		tipo = $('#tipo').val();
		departamento = $('#departamneto').val();
		horario = $('#horario').val();
		descripcion = $('#descripcion').val();		
		$('#nuevo').bPopup().close();
		bootbox.confirm('¿Seguro que desea Incluir la Agencia?', function(result){
			if (result == true){
				$.post('include/guardar_agencia.php', {accion:accion, codigo:codigo, tipo:tipo, departamento:departamento, horario:horario,descripcion:descripcion}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Incluir la Agencia, Intente mas tarde').fadeIn(1000).fadeOut(5000);	
					}else if (data == '1'){
						$('#mensaje').html('<strong>¡Exito!</strong> Agencia Incluida Correctamente').fadeIn(1000).fadeOut(5000);		 
						$('#codigo').val('');
						$('#horario').val('');
						$('#descripcion').val('');
						$('#lista').DataTable().ajax.reload();
					}else if (data == 'repetido'){
						$('#alerta').html('<strong>¡Alerta!</strong> Ya existe una Agencia con ese Código').fadeIn(1000).fadeOut(5000);
						$('#codigo').val('');
						$('#horario').val('');
						$('#descripcion').val('');			
					}//End if
				});//End post	
			}else{
			$('#nuevo').bPopup();
			}//End if			 
		});//End Function	
	});

//Mostrar Formulario de Editar Agencia
	$('#lista tbody').on('click', '.edit', function(){
		var id = $(this).attr('id');
		$.post('include/pdo/agencia.php', {id:id, function:"getAgency"}, function(data){
			var obj = jQuery.parseJSON(data);
			$('#id').val(obj.id);
			$('#codigo2').val(obj.codigo);
			$('#tipo2').val(obj.tipo).parent().removeClass('has-error has-success');
			$('#departamento2').val(obj.departamento).parent().removeClass('has-error has-success');
			$('#horario2').val(obj.horario).parent().removeClass('has-error has-success');
			$('#descripcion2').val(obj.descripcion).parent().removeClass('has-error has-success');			
		});//End post		
		$('#editar').bPopup();
	});

//Validar y Editar agencia
	$("#enviar2").click(function(){
		if ($("#tipo2 option:selected").index() == 0){
			$('#tipo2').parent().addClass('has-error');
			$('#tipo2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#tipo2').parent().removeClass('has-error').addClass('has-success');
		};
		if ($("#departamneto2").val() == ''){
			$('#departamneto2').parent().addClass('has-error');
			$('#departamneto2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#departamneto2').parent().removeClass('has-error').addClass('has-success');
		}
		if ($("#horario2").val() == ''){
			$('#horario2').parent().addClass('has-error');;
			$('#horario2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#horario2').parent().removeClass('has-error').addClass('has-success');
		};	
		if ($("#descripcion2").val() == ''){
			$('#descripcion2').parent().addClass('has-error');;
			$('#descripcion2').attr('placeholder','Campo Obligatorio');			
			return false;				
		}else{
			$('#descripcion2').parent().removeClass('has-error').addClass('has-success');
		};
		id = $('#id').val();
		tipo = $('#tipo2').val();
		departamento = $('#departamento2').val();
		horario = $('#horario2').val();
		descripcion = $('#descripcion2').val();
		$('#editar').bPopup().close();
		bootbox.confirm('¿Seguro que desea Editar la Agencia?', function(result){
			if (result == true){
				$.post('include/pdo/agencia.php', {function:"editAgency", id:id, tipo:tipo, departamento:departamento, horario:horario, descripcion:descripcion}, function(data){
					if (data  == '0'){
						$('#error').html('<strong>¡Error!</strong> Error al Editar la Agencia, Intente mas tarde').$('#error').fadeIn(1000).fadeOut(5000);												
					}else if (data == '1'){
						$('#mensaje').html('<strong>¡Exito!</strong> Agencia Editada Correctamente').fadeIn(1000).fadeOut(5000);
						$('#codigo2').val('');
						$('#horario2').val('');
						$('#descripcion2').val('');
						$('#lista').DataTable().ajax.reload();
					}//End if
				});//End post	
			}else{
			$('#editar').bPopup();
			}//End if			 
		});//End Function		
	});
		
//Cambiar el Estatus de la Agencia
	$('#lista tbody').on('click', '.camb', function(){	
		var accion = "estatus";
		var element = $(this).attr('id').split('│');
		var id = element[0];
		var estatus = element[1];		
		bootbox.confirm('¿Seguro que desea Cambiar el Estatus de la Agencia?', function(result){
			if (result == true){
				$.post("include/pdo/agencia.php", {function:"statusAgency", id:id, estatus:estatus}, function(data){
					respuesta = data;
					if (respuesta == 0){
						$('#error').html('<strong>¡Error!</strong> Error al Editar la Agencia, Intente mas tarde.').fadeIn(1000).fadeOut(5000);
					}else{	
						$('#mensaje').html('<strong>¡Exito!</strong> Estatus Editado Correctamente').fadeIn(1000).fadeOut(5000);
						$('#lista').DataTable().ajax.reload();
					}//End if
				});//End post
			}//End if			 
		});//End Function confirm	
	});//End Function

//Exportar a Excel
	 $('#excel').click(function(){
	 	$('#excel1').submit(); 
	 });

//Convertir la tabla en datatable
	$('#lista').DataTable( {
		"ajax": {
		    "url": "include/pdo/agencia.php",
		    "type": "POST",
		    "data": {
		        "function": "getAllAgencies"
		    }
		},
		"sPaginationType": "full_numbers",
		"columnDefs": [
				{         
		              "render": function ( data, type, row ) {
		              	switch (row[6]){
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
		              	edit =  '<img src="imagenes/edit.png" class="edit cursor" id="'+ row[0] +'" data-toggle="modal" data-placement="bottom" title="Editar">';
		              	switch (row[6]){
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
		              "targets": 7
		        }           
		      ],
		"language":{ 
			"url": "../DataTables/locale/Spanish.json"
		},		
		aLengthMenu: [[10,50,100],[10,50,100]],
		"iDisplayLength": 10,
		dom: 'Bflrtip',
		buttons: getbuttons(nivel, [0,1,2,3,4,5], 'Lista de Agencias')
	});

	function fillHorarios(){
		$.post("include/pdo/agencia.php", {function: "getHorario"}, function(data){
			if (data != 0){
			var json = jQuery.parseJSON(data);		
				$.each(json, function(idx, obj) {
					$('#horario, #horario2').append('<option>' + obj.horario + '</option>'); 
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
    	<h4>Lista de Agencias</h4>
    </div>       
    <div align="center" class="alert alert-success" id="mensaje" style="display:none">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    </div>
    <div align="center" class="alert alert-danger" id="error" style="display:none">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>        
    </div>
    <div align="center" class="alert alert-warning" id="alerta" style="display:none">
    	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>        
    </div>
   
    <div class="container-fluid">
    <div class="row">
    <div class="col-xs-12">
    <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Código</th>
            <th>Horario</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Departamento</th>
            <th>Estatus</th>   
            <th>Comandos</th>             
        </tr>
    </thead>
  	<tfoot>        
        <tr>
            <th>Id</th>
            <th>Código</th>
            <th>Horario</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Departamento</th>
            <th>Estatus</th>   
            <th>Comandos</th>           
        </tr>
	</tfoot>     
    <tbody>      
    </tbody>
	</table>
	</div><!-- End col -->
    </div><!-- End row -->
	
    <div class="row">
    <div class="col-xs-12 text-center">
		<img id="boton" src="imagenes/add.png" width="48" height="48" class="cursor" title="Agregar Agencia">
    </div><!-- End col -->  
    </div><!-- End row -->    
    </div><!-- End Container -->
    
	<div class="row login-popup" id="nuevo">
    <div class="col-xs-12 col-md-3"></div>
    <div class="col-xs-12 col-md-6">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Nueva Agencia</h3>
    	</div>
    	<div class="panel-body">
           <input type="hidden" name="accion" value="nuevo">
           <div class="form-group col-xs-12 col-md-6 text-center">
            	<label for="codigo">Código</label>
            	<input type="text" name="codigo" id="codigo" class="form-control text-center uncopypaste" maxlength="3" style="text-transform:uppercase">                 	                
            </div>
			<div class="form-group col-xs-12 col-md-6 text-center">
				<label for="tipo">Tipo</label>
               	<select name="tipo" id="tipo" class="form-control">
                	<option>Seleccionar...</option>
                    <option>Sucursal</option>
                    <option>Aliado</option>
                    <option>Oficina</option>
              	</select>                    
            </div>
            <div class="form-group col-xs-12 col-md-6 text-center">
				<label for="departamneto">Departamento</label>
               	<input name="departamneto" id="departamneto" type="text" size="40" maxlength="40" class="form-control  text-center">
            </div>
            <div class="form-group col-xs-12 col-md-6">
				<label for="horario">Horario</label>
                <select name="horario" id="horario" class="form-control">
                                       
                </select>
            </div>
			<div class="form-group col-xs-12 text-center">
				<label for="descripcion">Descripción</label>
               	<input type="text" name="descripcion" id="descripcion" size="40" maxlength="40" class="form-control text-center">
            </div>
			<div class="form-group col-xs-12 text-center">
				<input type="image" id="enviar" src="imagenes/save.png" title="Ingresar Agencia">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-12 col-md-3"></div>
    </div><!--End col -->
    </div><!--End row -->   
    
	<div class="row login-popup" id="editar">
    <div class="col-xs-12 col-md-3"></div>
    <div class="col-xs-12 col-md-6">   	
    <div class="panel panel-primary luminoso text-center">
    	<div class="panel-heading">
      		<h3 class="panel-title">Editar Agencia</h3>
    	</div>
    	<div class="panel-body">
          	<div class="form-group col-xs-12 col-md-6 text-center">
            	<label for="id">Id</label>
            	<input  type="text" name="id" id="id" style="text-transform:uppercase" readonly class="form-control  text-center">   
            </div>
            <div class="form-group col-xs-12 col-md-6 text-center">
				<label for="codigo2">Código</label>
               	<input name="codigo2" type="text" id="codigo2" size="3" maxlength="3" class="form-control  text-center" style="text-transform:uppercase" readonly>
            </div>
            <div class="form-group col-xs-12 col-md-6 text-center">
				<label for="tipo2">Tipo</label>
               	<select name="tipo2" id="tipo2" class="form-control">
                	<option>Seleccionar...</option>
                    <option value="Sucursal">Sucursal</option>
                    <option value="Aliado">Aliado</option>
                    <option value="Oficina">Oficina</option>
              	</select>
           	</div>           
			<div class="form-group col-xs-12 col-md-6 text-center">
				<label for="departamento2">Departamento</label>
               	<input name="departamento2" id="departamento2" type="text" size="40" maxlength="40" class="form-control  text-center">
            </div>
            <div class="form-group col-xs-12 col-md-6 text-center">
				<label for="horario2">Horario</label>
               	<select name="horario2" id="horario2" class="form-control">
                	
                </select>
            </div>
			<div class="form-group col-xs-12 text-center">
				<label for="descripcion2">Descripcion</label>
               	<input name="descripcion2" type="text" id="descripcion2" size="40" maxlength="40" class="form-control  text-center">
            </div>
			<div class="form-group col-xs-12 text-center">
				<input type="image" id="enviar2" src="imagenes/save.png" title="Editar Agencia">
            </div>                    	         
		</div>
  	</div><!--End panel -->
	<div class="col-xs-12 col-md-3"></div>
    </div><!--End col -->
    </div><!--End row -->  
    
    </div><!--End Contenido -->
    <?php echo $chat; echo $footer?>
</body>
	<form id="excel1" method="POST" action="excelagencia.php" target="_blank" style="display:none">    	
    </form>  
<?php
}else{
	header("location:index.php?alerta=salir");
}
?>