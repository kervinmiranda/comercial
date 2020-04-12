<?php
/*************************************************************************************************************************
                                                         SISTEMA GEBNET
****************************************************************************************************************************/
include_once 'include/fecha.php';
include_once 'include/variables.php';
if(isset($_SESSION['user'])){
?>
<?php echo $doctype?>
<!-- Achivos CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/dataTables.bootstrap.css">
<link rel="stylesheet" href="../DataTables/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="../DataTables/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../DataTables/css/select.dataTables.min.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-submenu.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="css/comercial.css">

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
<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
<script src="../bootstrap/js/locale/bootstrap-datepicker.es.js"></script>
<script src="../js/jquery.numeric.js"></script>
<script src="js/libreriajs.js"></script>
<script src="js/chat.js"></script>
<script>
$(document).ready(function(){

	var nivel = <?php echo $_SESSION['nivel']?>;
	var nombreAgencia;
	var agencia = "BNS";
	var agenciaConsulta = "-";
	var desde = new Date();
	var hasta = new Date();
	var detalle;

	fillAgencies();
	fillAllAgencies();

//Activar Menú
	$("#menu3").attr('class','active');

//Buscar Plantilla de Agencia
	$('#agencias').on('change', '.radioGroup', function(){
		agencia = $(this).val();
		nombreAgencia = $(this).attr('title');
		$('#agencia').html(nombreAgencia);
		$('#comentario').val('');
		$('#formulario').fadeOut(500);
		$('#lista').DataTable().ajax.reload();
		$('#formulario').fadeIn(500);
		$('#lista').DataTable().responsive.recalc().columns.adjust();
	});

//Buscar Plantilla de Agencia
	$('#agenciasTodas').on('change', '.radioGroup', function(){
		agenciaConsulta = $(this).val();
		nombreAgencia = $(this).attr('title');
		$('#agenciaconsulta').html(nombreAgencia);
		validateSearch();
	});

//Función para colocar los Textos a tipo fecha
	$("#desde").datepicker({
		language: "es",
		startDate: '-10y',
	  	format: "yyyy-mm-dd",
	  	endDate: new Date(),
	  	autoclose: true,
	    todayBtn:  1,
	}).on('changeDate', function (selected) {
	    var minDate = new Date(selected.date.valueOf());
	    $('#hasta').datepicker('setStartDate', minDate);
	    desde = $(this).val();
	    validateSearch();
	});

	$("#hasta").datepicker({
		language: "es",
		startDate: '-10y',
	  	format: "yyyy-mm-dd",
	  	endDate: new Date(),
	  	autoclose: true,
	}).on('changeDate', function (selected) {
        var maxDate = new Date(selected.date.valueOf());
        $('#desde').datepicker('setEndDate', maxDate);
        hasta = $(this).val();
        $('#listaConsulta').dataTable().fnClearTable();
	    validateSearch();
	});

//Validar y Enviar
	$('#guardar').click(function(){
		if (validateForm('#formulario')){
			bootbox.confirm('¿Seguro que desea agregar el reporte de Asistencia?', function(result){
				if (result == true){
					var asistencia = getAsistencia('#formulario');
					var comentario = $('#comentario').val();
					$.post("include/pdo/asistencia.php", {function: "saveAssists", agencia:agencia, comentario:comentario, asistencia:asistencia}, function(data){
						if (data != '0'){
							fillAgencies();
							$('#lista').dataTable().fnClearTable();
							$('#formulario').fadeOut(500);
							$('#mensaje').html('<strong>¡Exito!</strong> Asistencia Agregada Correctamente').fadeIn(1000).fadeOut(15000);	
						}else{
							$('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente mas tarde.').fadeIn(1000).fadeOut(15000);
						}
					});//End post
				}
			});// End Function boot.confirm
		}	
	});//End Fucntion

//Mostrar Formulario de editar Asistencia
	$('#listaConsulta tbody').on('click', '.edit', function(){	
		var element = $(this).attr('id').split('_');
		detalle = element[0];
		var cedula = element[1];		
		$.post('include/pdo/asistencia.php', {function:"getAsistenciaById", detalle:detalle, cedula:cedula}, function(data){
			var obj = jQuery.parseJSON(data);
			$('#fecha').val(obj.fecha);
			$('#agenciaEdit').val(obj.descripcion);
			$('#cedula').val(obj.empleado);
			$('#nombre').val(obj.nombre);
			$('#observacion').val(obj.observacion);		
		});//End post				
		$('#editar').bPopup();
	});

//Validar y editar
	$('#enviar').click(function(){
		if (validateForm('#editar')){
			$('#editar').bPopup().close();
			bootbox.confirm('¿Seguro que desea editar la asistencia?', function(result){
				if (result == true){
					var asistencia = getAsistencia('#formulario');
					var comentario = $('#comentario').val();
					var cedula = $('#cedula').val();
					var asistencia = $('#asistenciaEdit').val();
					var observacion = $('#observacion').val();
					$.post("include/pdo/asistencia.php", {function: "editAssist", detalle:detalle, cedula:cedula, asistencia:asistencia, observacion:observacion}, function(data){
						if (data == '1'){
							$('#mensaje').html('<strong>¡Exito!</strong> Asistencia Editada Correctamente').fadeIn(1000).fadeOut(15000);
							$('#formularioConsulta').fadeOut(500);
							$('#listaConsulta').dataTable().fnClearTable();
							validateSearch();
							$('#formularioConsulta').fadeIn(500);
						}else{
							$('#error').html('<strong>¡Error!</strong> Error al editar la asistencia, Intente mas tarde.').fadeIn(1000).fadeOut(15000);
						}
					});//End post
				}else{
					$('#editar').bPopup();
				}
			});// End Function boot.confirm
		}	
	});//End Fucntion
	
//Convertir la tabla en Datatable
	$('#lista').dataTable({
		"responsive":true,
		"ajax": {
		    "url": "include/pdo/empleado.php",
		    "type": "POST",
		    "data": function (d) {
		    	d.function = "getEmployeesByAgencie",
                d.agencia = agencia;                
            }
		},
		"sPaginationType": "full_numbers",		
		"language":{ 
			"url": "../DataTables/locale/Spanish.json"
		},
		"columnDefs": [
			{         
              "render": function ( data, type, row ) {              	
                  return '<input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_0" class="option" checked required value="ASI"></option>'
            	},
              	"targets": 5
	        },
	        {         
              "render": function ( data, type, row ) {              	
                  return '<input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_1" class="option" value="SSO" title="Reposo">SSO </option><input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_2" class="option" value="AI" title="Ausencia Injustificada">AI </option><input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_3" class="option" value="PNR" title="Permiso no Remunerado">PNR </option><input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_4" class="option" value="LIB" title="Día Libre">LIB </option>'
            	},
              	"targets": 6
	        },
	        {         
              "render": function ( data, type, row ) {              	
                  return '<input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_5" class="option" value="VAC"></option>'
            	},
              	"targets": 7
	        },
	        {         
              "render": function ( data, type, row ) {              	
                  return '<input type="radio" name="EMP' + row[1] + '" id="' + row[1] + '_6" class="option" value="APO"></option>'
            	},
              	"targets": 8
	        },
	        {         
              "render": function ( data, type, row ) {              	
                  return '<input type="text" name="OBS' + row[1] + '" id="OBS' + row[1] + '" class="form-group">'
            	},
              	"targets": 9
	        }
		],
		aLengthMenu: [[10,50,100],[10,50,100]],
		"iDisplayLength": 10
	});

//Convertir la tabla en Datatable
	$('#listaConsulta').dataTable({
		"responsive":true,
		"ajax": {
		    "url": "include/pdo/asistencia.php",
		    "type": "POST",
		    "data": function (d) {
		    	d.function = "getAssists",
                d.agencia = agenciaConsulta,
                d.desde = desde,
                d.hasta = hasta
            }
		},
		"sPaginationType": "full_numbers",		
		"language":{ 
			"url": "../DataTables/locale/Spanish.json"
		},
		"columnDefs": [
			{         
				"render": function ( data, type, row ) {		              			              		
					return '<img src="imagenes/edit.png" class="edit cursor" id="'+ row[8] +'_'+ row[2] +'" data-toggle="modal" data-placement="bottom" title="Editar">';
				},
				"targets":8
		    }
		],
		aLengthMenu: [[10,50,100],[10,50,100]],
		"iDisplayLength": 10,
		dom: 'Bflrtip',
		buttons: getbuttons(nivel, [0,1,2,3,4,5,6,7], 'Reporte de Asistencia')
	});



//LLenar los input radio con las agencias
	function fillAgencies(){
		$('#agencias').empty();
		$.post("include/pdo/agencia.php", {function: "loadAssistance"}, function(data){
			if (data != 0){
			var json = jQuery.parseJSON(data);		
				$.each(json, function(idx, obj) {
					$('#agencias').append('<label class="btn btn-primary btn-sm" data-toggle="tooltip" title = "' + obj.descripcion + '"><input type="radio" name="agencia" id = "' + obj.descripcion +'" value="' + obj.agencia + '" class="radioGroup" title = "' + obj.descripcion + '" required>' + obj.agencia + '</label> ');
				})
			}else{
				$('#agencias').append('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> No hay Plantillas para cargar la asistencia</div>');
			}
		});//End post
	}

	function fillAllAgencies(){
		$('#agencias').empty();
		$.post("include/pdo/agencia.php", {function: "loadAllAgencies"}, function(data){
			if (data != 0){
			var json = jQuery.parseJSON(data);		
				$.each(json, function(idx, obj) {
					$('#agenciasTodas').append('<label class="btn btn-primary btn-sm" data-toggle="tooltip" title = "' + obj.descripcion + '"><input type="radio" name="agenciaConsulta" id = "' + obj.descripcion +'_2" value="' + obj.agencia + '" class="radioGroup" title = "' + obj.descripcion + '" required>' + obj.agencia + '</label> ');
				});
				$('#agenciasTodas').append('<label class="btn btn-primary btn-sm" data-toggle="tooltip" title = "Todas"><input type="radio" name="agenciaConsulta" id = "AllAgencies" value="TODAS" class="radioGroup" title = "TODAS" required>TODAS</label> ');
			}else{
				$('#agenciasTodas').append('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> No hay Plantillas para cargar la asistencia</div>');
			}
		});//End post
	}

	function validateSearch(){
		$('#listaConsulta').dataTable().fnClearTable();
		if ($('#desde').val()!='' && $('#hasta').val()!='' && agenciaConsulta != "-"){			
			$('#formularioConsulta').fadeOut(500);
			$('#listaConsulta').DataTable().ajax.reload();
			$('#formularioConsulta').fadeIn(500);
			$('#listaConsulta').DataTable().responsive.recalc().columns.adjust();			
		}		
	}

	//Borrar filtro de fechas
	$('#clearInit').click( function() {
		if ($('#desde').val()!=''){
         	$('#desde').val('');
			$('#listaConsulta').dataTable().fnClearTable();
		}
    });

    $('#clearEnd').click( function() {
		if ($('#hasta').val()!=''){
         	$('#hasta').val('');
			$('#listaConsulta').dataTable().fnClearTable();
		}
    });

});
</script>
</head>
<body>
	<?php echo $header?>
    <div class="container-fluid contenido">
		<?php echo $menu?>
	    <div class="text-center">
	    	<h4>Asistencia de Personal</h4>
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
	    
	    <!-- Nav tabs -->
	    <ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#cargar" aria-controls="cargar" role="tab" data-toggle="tab">Cargar</a></li>
			<li role="presentation"><a href="#consulta" aria-controls="consulta" role="tab" data-toggle="tab">Consulta</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="cargar">
				<div class="container-fluid">
				    <div class="btn-group btn-group-toggle text-center" data-toggle="buttons" id="agencias"></div>
				</div>	    
			    <form name="asistencia" id="asistencia">
				    <div class="container-fluid">					    
						<div class="text-center">
					    	<h4 id="agencia"></h4>
					    </div>
					    <div class="row oculto" id="formulario">
					    	<div class="col-xs-12">    
							    <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap table-responsive" cellspacing="0" width="100%">
							    <thead>
							        <tr>
							            <th>Agencia</th>
							            <th>CI</th>
							            <th>Nombre</th>
							            <th>Apellido</th>
							            <th>Cargo</th>
							            <th>Asistente</th>
							            <th>Ausente</th>
							            <th>Vacaciones</th> 
							            <th>Apoyo</th>
							            <th>Observación</th>          
							        </tr>
							    </thead>
							  	<tfoot>        
							        <tr>
							            <th>Agencia</th>
							            <th>CI</th>
							            <th>Nombre</th>
							            <th>Apellido</th>
							            <th>Cargo</th>
							            <th>Asistente</th>
							            <th>Ausente</th>
							            <th>Vacaciones</th>
							            <th>Apoyo</th>
							            <th>Observación</th>       
							        </tr>
								</tfoot>     
							    <tbody>    
							    </tbody>
								</table>
							    <div class="form-group" align="center">
							        <label for="comentario">Comentario</label>
							        <textarea name="comentario" id="comentario" class="form-control" required></textarea>
							    </div>
						    	<div class="form-group" align="center">
						        	<input type="image" name="guardar" id="guardar" src="imagenes/save.png" title="Cargar Asistencia" onClick="return false;">
						    	</div>
					   		</div><!-- End col -->
					    </div><!-- End row -->
				    </div><!-- End Container -->
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="consulta">
				<div class="container-fluid">
				    <div class="btn-group btn-group-toggle text-center" data-toggle="buttons" id="agenciasTodas"></div>
					
					<div class="row">
						<div class="form-group col-xs-12 col-md-3 col-md-offset-3 col-lg-2 col-lg-offset-4 text-center">
					        <label>Desde</label>
					        <div class="input-group">
						        <input  type="text" name="desde" id="desde" class="form-control text-center" readonly>
						        <div class="input-group-btn">                    
				                    <button class="btn btn btn-default" data-toggle="tooltip" id="clearInit" title="" data-original-title="borrar"><i class="glyphicon glyphicon-erase"></i></button>
				                </div>
					        </div>
					    </div>

					    <div class="form-group col-xs-12 col-md-3 col-lg-2 text-center">
					        <label>Hasta</label>
					        <div class="input-group">
					        	<input  type="text" name="hasta" id="hasta" class="form-control text-center" readonly>
					        	<div class="input-group-btn">
				                    <button class="btn btn btn-default" data-toggle="tooltip" id="clearEnd" title="borrar"><i class="glyphicon glyphicon-erase"></i></button>
				                </div>
					        </div>
					    </div>
					</div>
				</div>

			    <div class="row oculto" id="formularioConsulta">	
			    	<div class="text-center">
				    	<h4 id="agenciaconsulta"></h4>
				    </div>
				    <div class="col-xs-12">
					    <table id="listaConsulta" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
					    	<thead>
						        <tr>
						          <th>Fecha</th>
						            <th>Agencia</th>
						            <th>CI</th>
						            <th>Nombre</th>
						            <th>Apellido</th>
						            <th>Cargo</th>
						            <th>Asistencia</th>
						            <th>Observación</th>
						            <?php
									if ($nivel < 2){
										echo "<th>Editar</th>";
										}			
									?>      
						        </tr>
						    </thead>
						  	<tfoot>        
						        <tr>
						          <th>Fecha</th>
						            <th>Agencia</th>
						            <th>CI</th>
						            <th>Nombre</th>
						            <th>Apellido</th>
						            <th>Cargo</th>
						            <th>Asistencia</th>
						            <th>Observación</th>
						            <?php
									if ($nivel < 2){
										echo "<th>Editar</th>";
										}
									?>	   
						        </tr>
							</tfoot>
						    <tbody>    
						    </tbody>
						</table>
					</div>
				</div>
								
			</div>
		</div>

		<div class="row login-popup" id="editar">
	    	<div class="col-xs-12 col-md-2 col-lg-3"></div>
	    	<div class="col-xs-12 col-md-8 col-lg-6">   	
		    <div class="panel panel-primary luminoso text-center">
		    	<div class="panel-heading">
		      		<h3 class="panel-title">Editar Registro de Asistencia</h3>
		    	</div>
		    	<div class="panel-body">
		              <div class="form-group col-xs-12 col-md-6 text-center">
		            	<label for="fecha">Fecha</label>
		            	<input  type="text" name="fecha" id="fecha" class="form-control text-center" readonly>                 	                
		            </div>
		            <div class="form-group col-xs-12 col-md-6 text-center">
						<label for="agenciaEdit">Agencia</label>
		               	<input name="agenciaEdit" type="text" id="agenciaEdit" class="form-control text-center" readonly>
		            </div>
					<div class="form-group col-xs-12 col-md-6 text-center">
						<label for="cedula">Cédula</label>
		               	<input name="cedula" id="cedula" type="text" class="form-control text-center" readonly>
		            </div>
					<div class="form-group col-xs-12 col-md-6 text-center">
						<label for="">Nombre y Apellido</label>
		               	<input type="text" id="nombre" class="form-control text-center" readonly>
		            </div>			
					<div class="form-group col-xs-12 col-md-6 text-center">
						<label for="asistenciaEdit">Asistencia</label>
		               	<select name="asistenciaEdit" id="asistenciaEdit" class="form-control" required>
		                    <option>Seleccionar...</option>
		                    <option value="ASI">Asistente (ASI)</option>
		                    <option value="SSO">Ausente (SSO)</option>
		                    <option value="AI">Ausente (AI)</option>
		                    <option value="PNR">Ausente (PNR)</option>
		                    <option value="LIB">Ausente (LIB)</option>
		                    <option value="VAC">Vacaciones (VAC)</option>
		                    <option value="APO">Apoyo (APO)</option>
		             	</select>
		            </div>  
					<div class="form-group col-xs-12 col-md-6 text-center">
						<label for="observacion">Observación</label>
		               	<textarea name="observacion" id="observacion" class="form-control" required></textarea>
		            </div>         	
					<div class="form-group col-xs-12 text-center">
						<input name="enviar" type="image" id="enviar" src="imagenes/save.png" title="Editar Registro">
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
	header("location:../index.php?error=ingreso");
}
?>