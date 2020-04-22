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
    var date = new Date();
	var turnov;
	var nombreAgencia;
	var agencia = "-"
	var horario;
	fillAllAgencies();

//Buscar Plantilla de Agencia
    $('#agenciasTodas').on('change', '.radioGroup', function(){
        agencia = $(this).val();
        $('#agencia').html($(this).attr('title'));
        $('#comentario').val('');
        $('#formulario').fadeOut(500);
        $('#lista').DataTable().ajax.reload();
        $('#formulario').fadeIn(500);
        $('#lista').DataTable().responsive.recalc().columns.adjust();
    });

//Mostrar ventana de Solicitud de Horas Extras
    $('#lista tbody').on('click', '.extra', function(){
        cedula = $(this).attr('id');
        $.post("include/pdo/empleado.php", {function:"getEmployee", cedula:cedula}, function(data){
            var obj = jQuery.parseJSON(data);
            $('#cedulahe').val(obj.ci);
            $('#nombrehe').val(obj.nombre);
            $('#apellidohe').val(obj.apellido);
            $('#cargohe').val(obj.cargo);
            $('#agenciahe').val(obj.descripcion);
            switch(obj.turno){
                case "1":
                    $('#turnohe').val("Lunes a Viernes");
                    $('#fechahe').datepicker({
                        language: "es",
                        format: "dd/mm/yyyy",
                        endDate: new Date(),
                        autoclose: true,
                        todayBtn: 1,
                        daysOfWeekDisabled: [0,6]  
                    });
                break;
                case "2":
                    $('#turnohe').val("Martes a Sábado");
                    $('#fechahe').datepicker({
                        language: "es",
                        format: "dd/mm/yyyy",
                        endDate: new Date(),
                        autoclose: true,
                        todayBtn: 1,
                        daysOfWeekDisabled: [0,1]
                    });
                break;
            }
            horario = obj.horario;
            $.post("include/pdo/solicitud.php", { function: "getInitHours", horario:horario}, function(data){
                $("#iniciohe").html(data);
            });                                                                             
        });//End post
        $('#fechahe').val('').parent().removeClass('has-error has-success');
        $('#iniciohe option:first').prop("selected", "selected");
        $('#iniciohe').parent().removeClass('has-error has-success');
        $("#finalhe").empty().append("<option>Seleccionar...</option>").parent().removeClass('has-error has-success');          
        $('#observacionhe').val('').attr('placeholder','').parent().removeClass('has-error has-success');   
        $('#horas').bPopup();   
    });

//Función para buscar las horas una vez seleccionada la primera opcion
    $("#iniciohe").change(function () {
        $('#finalhe').empty();         
           $("#iniciohe option:selected").each(function () {
            $.post("include/pdo/solicitud.php", { function: "getEndHours", horario:horario, elegido:$(this).val()}, function(data){
            $("#finalhe").html(data);
            });
        });
   });

//Validar y Guardar Horas Extras
    $('#guardarhe').click(function(){
        if (validateForm('#horas')){
            $('#horas').bPopup().close();
            bootbox.confirm('¿Seguro que desea Cargar las Horas Extras?', function(result){
                if (result == true){
                cedula = $('#cedulahe').val();
                nombre = $('#nombrehe').val() + ' ' + $('#apellidohe').val();
                fecha = $('#fechahe').val();
                inicio = $('#iniciohe').val();
                final = $('#finalhe').val();
                observacion = $('#observacionhe').val();
                    $.post("include/pdo/solicitud.php", {function:"insertHours", cedula:cedula, nombre:nombre, fecha:fecha, inicio:inicio, final:final, observacion:observacion}, function(data){
                        if (data == 0){
                            $('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente Nuevamente.').fadeIn(1000).fadeOut(15000);           
                        }if (data == 'repetido'){
                            $('#alerta').html('<strong>¡Alerta!</strong> El Empleado tiene una Carga previa de ese día. Verifique e Intente Nuevamente').fadeIn(1000).fadeOut(15000);
                        }else{
                            $('#horas').bPopup().close();                       
                            $('#mensaje').html('<strong>¡Exito!</strong> Solicitud Ingresada Correctamente con el N°: <strong>'+data+'</strong>').fadeIn(1000).fadeOut(15000);                                                        
                        }//End if
                    });//End pos            
                }else{
                    $('#horas').bPopup();
                }//End if result == true//End If
            });//End Function bootbox.confirm 
        }    
    });

//Mostrar ventana de Solicitud de Día Libre laborado
    $('#lista tbody').on('click', '.dialab', function(){
        cedula = $(this).attr('id');
        $.post("include/pdo/empleado.php", {function:"getEmployee", cedula:cedula}, function(data){
            var obj = jQuery.parseJSON(data);
            $('#ceduladl').val(obj.ci);
            $('#nombredl').val(obj.nombre);
            $('#apellidodl').val(obj.apellido);
            $('#cargodl').val(obj.cargo);
            $('#agenciadl').val(obj.descripcion);
            $('#supervisordl').val(obj.supervisor);
            switch(obj.turno){
                case "1":
                    $('#turnodl').val("Lunes a Viernes");
                    $('#fechadl').datepicker({
                        language: "es",
                        format: "dd/mm/yyyy",
                        endDate: new Date(),
                        autoclose: true,
                        todayBtn: 1,
                        daysOfWeekDisabled: [1,2,3,4,5]
                    });
                break;
                case "2":
                    $('#turnodl').val("Martes a Sábado");
                    $('#fechadl').datepicker({
                        language: "es",
                        format: "dd/mm/yyyy",
                        endDate: new Date(),
                        autoclose: true,
                        todayBtn: 1,
                        daysOfWeekDisabled: [2,3,4,5,6]
                    });
                break;
            }                                                   
        });//End post
        $('#fechadl').val('').parent().removeClass('has-error has-success');;
        $('#observaciondl').val('').parent().removeClass('has-error has-success');;
        $('#diaslaborados').bPopup();
    });//End function

//Validar y Guardar Día Libre Laborado
    $('#guardardl').click(function(){
        if (validateForm('#diaslaborados')){
            $('#diaslaborados').bPopup().close();
            bootbox.confirm('¿Seguro que desea Registrar el día libre laborado?', function(result){
                if (result == true){    
                    cedula = $('#ceduladl').val();
                    nombre = $('#nombredl').val() + ' ' + $('#apellidodl').val();
                    fecha = $('#fechadl').val();
                    observacion = $('#observaciondl').val();            
                    $.post("include/pdo/solicitud.php", {function:"insertDayOff", cedula:cedula , nombre:nombre, fecha:fecha, observacion:observacion}, function(data){
                        if (data == '0'){
                            $('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente más tarde.').fadeIn(1000).fadeOut(20000);            
                        }if (data == 'repetido'){
                            $('#alerta').html('<strong>¡Alerta!</strong> Ya hay una Registro creado en ese día para el empleado, Verifique los datos e intente de nuevo.').fadeIn(1000).fadeOut(20000);         
                        }else{
                            $('#vacacion').bPopup().close();
                            $('#mensaje').html('<strong>¡Exito!</strong> Solicitud Ingresada Correctamente con el N°: <strong>'+data+'</strong>').fadeIn(1000).fadeOut(20000);                                                
                        }//End if
                    });//End pos
                }else{
                    $('#diaslaborados').bPopup();
                }//End if result == true
            });//End Function bootbox.confirm
        }
    });//End Function guardardl

//Mostrar ventana de Solicitud de vacaciones
    $('#lista tbody').on('click', '.vacacion', function(){
        cedula = $(this).attr('id');
        $.post("include/pdo/empleado.php", {function:"getEmployee", cedula:cedula}, function(data){
            var obj = jQuery.parseJSON(data);
            $('#cedulavac').val(obj.ci);
            $('#nombrevac').val(obj.nombre);
            $('#apellidovac').val(obj.apellido);
            $('#cargovac').val(obj.cargo);
            $('#agenciavac').val(obj.agencia);
            $('#ingresovac').val(obj.fecha_ingreso);
            $('#periodovac').html(calculateYearVacations(obj.fecha_ingreso));
            $('#telefonovac').val(obj.telefono);
            $('#supervisorvac').val(obj.supervisor);
            turnov = obj.turno;
            switch(obj.turno){
                case "1":
                    $('#turnovac').val("Lunes a Viernes");                    
                break;
                case "2":
                    $('#turnovac').val("Martes a Sábado");                    
                break;
            } 
            $('#finiciovac').datepicker({
                language: "es",
                format: "dd/mm/yyyy",
                startDate: '0d', 
                endDate: '+30d',          
                autoclose: true,
                todayBtn: 1,                  
            }).on('changeDate', function (selected) {
                var minDate = new Date(selected.date);   
                var maxDate = new Date(selected.date);
                maxDate.setDate(maxDate.getDate() + 30) 
                $('#ffinalvac').datepicker('setStartDate', minDate);
                $('#ffinalvac').datepicker('setEndDate', maxDate);
                $('#ffinalvac').val('');
            });
            $("#ffinalvac").datepicker({
                language: "es",
                format: "dd/mm/yyyy", 
                endDate: '+30d',              
                autoclose: true,
            });                              
        });//End post
        $('#periodo option:first').prop("selected", "selected");
        $('#periodovac').val('').parent().removeClass('has-error has-success');
        $('#finiciovac').val('').parent().removeClass('has-error has-success');
        $('#ffinalvac').val('').parent().removeClass('has-error has-success');
        $('#observacionvac').val('').parent().removeClass('has-error has-success');    
        $('#vacacion').bPopup();    
    });

//Guardar Solicitud de Vacaciones
    $('#guardarv').click(function(){
        if (validateForm('#vacacion')){
            $('#vacacion').bPopup().close();
            bootbox.confirm('¿Seguro que desea Registrar la Solicitud de Vacaciones?', function(result){
                if (result == true){    
                    cedula = $('#cedulavac').val();
                    nombre = $('#nombrevac').val();
                    apellido = $('#apellidovac').val();
                    ingreso = $('#ingresovac').val();
                    agencia = $('#agenciavac').val();          
                    periodo = $('#periodovac').val();
                    telefono = $('#telefonovac').val();
                    inicio = $('#finiciovac').val();
                    final = $('#ffinalvac').val();
                    supervisor = $('#supervisorvac').val();
                    observacion = $('#observacionvac').val();          
                    $.post("include/pdo/solicitud.php", {function:'insertVacations', cedula:cedula , nombre:nombre, apellido:apellido, ingreso:ingreso, agencia:agencia, periodo:periodo, telefono:telefono, inicio:inicio, final:final, supervisor:supervisor, observacion:observacion, turno:turnov }, function(data){
                        if (data == '0'){
                            $('#error').html('<strong>¡Error!</strong> Error al incluir los datos, Intente mas tarde.').fadeIn(1000).fadeOut(20000);            
                        }if (data == 'repetido'){
                            $('#alerta').html('<strong>¡Alerta!</strong> Ya hay una Solicitud creada en ese lapso de tiempo para el empleado, Verifique los datos e intente de nuevo.').fadeIn(1000).fadeOut(20000);            
                        }else{
                            $('#vacacion').bPopup().close();
                            $('#mensaje').html('<strong>¡Exito!</strong> Solicitud Ingresada Correctamente con el N°: <strong>'+data+'</strong>').fadeIn(1000).fadeOut(20000);            
                            $('#id_plantilla').val(data);
                            $('#planilla_vacaciones').submit();                                     
                        }//End if
                    });//End pos
                }else{
                    $('#vacacion').bPopup();
                }//End if result == true
            });//End Function bootbox.confirm
        }
    });//End Function guardarv

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
        "columns" : [
                {"data": 1},
                {"data": 2},
                {"data": 3}, 
                {"data": 4},
                {"data": 0},
                {         
                  "render": function ( data, type, row ) {                     
                    dialab = '<img src="imagenes/cal.png" class="dialab cursor" data-toggle="tooltip" title="Día libre Laborado" id="'+ row[1] + '">';
                    editar = '<img src="imagenes/edit.png" class="edit cursor" data-toggle="tooltip" title="Solicitar Edición" id="' + row[1] + '">';
                    horase = '<img src="imagenes/time.png" class="extra cursor" data-toggle="tooltip" title="Cargar Horas Extras" id="' + row[1] + '">';
                    if (row[5] < 335){
                        vacaciones = '<img src="imagenes/travelbag2.png" class="disable" data-toggle="tooltip" title="Este Empleado no cumple con la cantidad mínima de servicio para solicitar vacaciones" id="' + row[1] + '">';
                    }else{
                        vacaciones = '<img src="imagenes/travelbag.png" class="vacacion cursor" data-toggle="tooltip" title="Solicitud de Vacaciones" id="' + row[1] + '">';
                    }
                    return  horase + ' ' + dialab + ' ' + vacaciones + ' ' + editar;
                  },
                  "targets": 5
                }
            ],
        aLengthMenu: [[10,50,100],[10,50,100]],
        "iDisplayLength": 10
    });


//LLenar los input radio con las agencias
	function fillAllAgencies(){
		$('#agenciasTodas').empty();
		$.post("include/pdo/agencia.php", {function: "loadAllAgencies"}, function(data){
			if (data != 0){
			var json = jQuery.parseJSON(data);		
				$.each(json, function(idx, obj) {
					$('#agenciasTodas').append('<label class="btn btn-primary btn-sm" data-toggle="tooltip" title = "' + obj.descripcion + '"><input type="radio" name="agenciaConsulta" id = "' + obj.descripcion +'_2" value="' + obj.agencia + '" class="radioGroup" title = "' + obj.descripcion + '" required>' + obj.agencia + '</label> ');
				});				
			}else{
				$('#agenciasTodas').append('<div class="alert alert-warning text-center"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>¡Info!</strong> No hay Plantillas para cargar la asistencia</div>');
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
	    	<h4>Solicitudes de Personal</h4>
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
			<li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Consulta
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
				<li><a href="#diasL" aria-controls="diasL" role="tab" data-toggle="tab">Días laborados</a></li>
				<li><a href="#horasE" aria-controls="horasE" role="tab" data-toggle="tab">Horas Extra</a></li>
				<li><a href="#vacaciones" aria-controls="vacaciones" role="tab" data-toggle="tab">Vacaciones</a></li>
				</ul>
			</li>
			<li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">Historial
				<span class="caret"></span></a>
				<ul class="dropdown-menu">
				<li><a href="#diasL2" aria-controls="diasL2" role="tab" data-toggle="tab">Días laborados</a></li>
				<li><a href="#horasE2" aria-controls="horasE"2 role="tab" data-toggle="tab">Horas Extra</a></li>
				<li><a href="#vacaciones2" aria-controls="vacaciones2" role="tab" data-toggle="tab">Vacaciones</a></li>
				</ul>
			</li>			
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="cargar">
				<div class="container-fluid">
				    <div class="btn-group btn-group-toggle text-center" data-toggle="buttons" id="agenciasTodas"></div>
				</div>
                <div class="text-center">
                    <h4 id="agencia"></h4>
                </div>
				<div id="formulario" class="col-xs-12 oculto">
			        <table id="lista" class="table table-striped table-bordered text-center dt-responsive table-hover nowrap" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th>CI</th>
			                <th>Nombre</th>
			                <th>Apellido</th>
			                <th>Cargo</th>
			                <th>Agencia</th>
			                <th>Solicitudes</th>
			                </tr>
			        </thead>
			        <tfoot>        
			            <tr>
			                <th>CI</th>                
			                <th>Nombre</th>
			                <th>Apellido</th>
			                <th>Cargo</th>
			                <th>Agencia</th>
			                <th>Solicitudes</th>
			             </tr>
			        </tfoot>     
			        <tbody>    
			        </tbody>
			        </table>
		       		<div class="text-center"><img src="imagenes/usuarioadd.png" id="nuevou" title="Agregar Empleado" class="cursor"></div>
		        </div><!-- End col -->
			</div>
			<div role="tabpanel" class="tab-pane" id="diasL">
			</div>
			<div role="tabpanel" class="tab-pane" id="horasE">
			</div>
			<div role="tabpanel" class="tab-pane" id="vacaciones">
			</div>
			<div role="tabpanel" class="tab-pane" id="diasL2">
			</div>
			<div role="tabpanel" class="tab-pane" id="horasE2">
			</div>
			<div role="tabpanel" class="tab-pane" id="vacaciones2">
			</div>
		</div>
        
        <div class="row login-popup" id="vacacion">
        <div class="col-xs-12 col-md-2 col-lg-3"></div>
        <div class="col-xs-12 col-md-8 col-lg-6">   	
        <div class="panel panel-primary luminoso text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Solicitud de Vacaciones</h3>
            </div>
            <div class="panel-body">
                  <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="cedula">Cédula</label>
                    <input type="text" name="cedulavac" id="cedulavac" class="form-control  text-center" readonly required>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="nombrevac">Nombre</label>
                    <input type="text" name="nombrevac" id="nombrevac" class="form-control  text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="apellidovac">Apellido</label>
                    <input type="text" name="apellidovac" id="apellidovac" class="form-control  text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="cargovac">Cargo</label>
                    <input type="text" name="cargovac" id="cargovac" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="agenciavac">Agencia</label>
                    <input type="text" name="agenciavac" id="agenciavac" class="form-control text-center" readonly>
                </div>           
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="turnovac">Turno</label>
                    <input type="text" name="turnovac" id="turnovac" class="form-control text-center" readonly>
                </div>          
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="supervisorvac">Supervisor</label>
                    <input type="text" name="supervisorvac" id="supervisorvac" class="form-control text-center " readonly>
                </div>          
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="ingresovac">Fecha Ingreso</label>
                    <input type="text" name="ingresovac" id="ingresovac" class="form-control  text-center" readonly>
                </div>           
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="telefonovac">Teléfono</label>
                    <input type="text" name="telefonovac" id="telefonovac" class="form-control text-center" readonly>
                </div>	            
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="periodovac">Periodo</label>
                        <select name="periodovac" id="periodovac" class="form-control" required></select>
                </div>	            
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="finiciovac">Inicio</label>
                    <input type="text" name="finiciovac" id="finiciovac" class="form-control uncopypaste text-center" required>
                </div>        
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="ffinalvac">Final</label>
                    <input type="text" name="ffinalvac" id="ffinalvac" class="form-control uncopypaste text-center" required>
                </div>
                <div class="form-group col-xs-12 text-center">
                    <label for="observacionvac">Observaciones</label>
                    <textarea name="observacionvac" id="observacionvac" class="form-control text-center" required></textarea>
                </div>                      	
                <div class="form-group col-xs-12 text-center">
                    <input type="image" id="guardarv" src="imagenes/save.png" title="Registrar Solicitud">
                </div>                    	         
            </div>
        </div><!--End panel -->
        <div class="col-xs-12 col-md-2 col-lg-3"></div>
        </div><!--End col -->
        </div><!--End row -->
        
        <div class="row login-popup" id="horas">
        <div class="col-xs-12 col-md-2 col-lg-3"></div>
        <div class="col-xs-12 col-md-8 col-lg-6">   	
        <div class="panel panel-primary luminoso text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Carga de Horas Extras</h3>
            </div>
            <div class="panel-body">
                  <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="cedulahe">Cédula</label>
                    <input type="text" name="cedulahe" id="cedulahe" class="form-control text-center" readonly required>   	                
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="nombrehe">Nombre</label>
                    <input type="text" name="nombrehe" id="nombrehe" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="apellidohe">Apellido</label>
                    <input type="text" name="apellidohe" id="apellidohe" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="cargohe">Cargo</label>
                    <input type="text" name="cargohe" id="cargohe" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="agenciahe">Agencia</label>
                    <input type="text" name="agenciahe" id="agenciahe" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="turnohe">Turno</label>
                    <input type="text" name="turnohe" id="turnohe" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="fechahe">Fecha</label>
                    <input type="text" name="fechahe" id="fechahe" class="form-control uncopypaste text-center" required>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="iniciohe">Inicio</label>
                        <select name="iniciohe" id="iniciohe" class="form-control" required>
                        </select>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="finalhe">Final</label>
                    <select name="finalhe" id="finalhe" class="form-control" required>  
                            <option>Seleccionar...</option>
                    </select>
                </div>
                <div class="form-group col-xs-12 text-center">
                    <label for="observacionhe">Observación</label>
                    <textarea name="observacionhe" id="observacionhe" class="form-control text-center" required></textarea>
                </div>
                <div class="form-group col-xs-12 text-center">
                    <input type="image" id="guardarhe" src="imagenes/save.png" title="Registrar Solicitud">
                </div>
            </div>
        </div><!--End panel -->
        <div class="col-xs-12 col-md-2 col-lg-3"></div>
        </div><!--End col -->
        </div><!--End row -->
    
        <div class="row login-popup" id="diaslaborados">
        <div class="col-xs-12 col-md-2 col-lg-3"></div>
        <div class="col-xs-12 col-md-8 col-lg-6">   	
        <div class="panel panel-primary luminoso text-center">
            <div class="panel-heading">
                <h3 class="panel-title">Carga de días Laborados</h3>
            </div>
            <div class="panel-body">
				<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="ceduladl">Cédula</label>
                    <input type="text" name="ceduladl" id="ceduladl" class="form-control text-center" readonly required>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="nombredl">Nombre</label>
                    <input type="text" name="nombredl" id="nombredl" class="form-control text-center" readonly required>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="apellidodl">Apellido</label>
                    <input type="text" name="apellidodl" id="apellidodl" class="form-control text-center" readonly required>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="cargodl">Cargo</label>
                    <input type="text" name="cargodl" id="cargodl" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="agenciadl">Agencia</label>
                    <input type="text" name="agenciadl" id="agenciadl" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="turnodl">Turno</label>
                    <input type="text" name="turnodl" id="turnodl" class="form-control text-center" readonly>
                </div>
                <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
                    <label for="fechadl">Fecha</label>
                    <input type="text" name="fechadl" id="fechadl" class="form-control uncopypaste text-center" required>
                </div>
                <div class="form-group col-xs-12 text-center">
                    <label for="observaciondl">Observación</label>
                    <textarea name="observaciondl" id="observaciondl" class="form-control text-center" required></textarea>
                </div>
                <div class="form-group col-xs-12 text-center">
                    <input type="image" id="guardardl" src="imagenes/save.png" title="Registrar Solicitud" >
                </div>
            </div>
        </div><!--End panel -->
        <div class="col-xs-12 col-md-2 col-lg-3"></div>
        </div><!--End col -->
        </div><!--End row -->	          
        
		<div class="row login-popup" id="edit">
	    <div class="col-xs-12 col-md-2 col-lg-3"></div>    
	    <div class="col-xs-12 col-md-8 col-lg-6">   	
	    <div class="panel panel-primary luminoso text-center">
	    	<div class="panel-heading">
	      		<h3 class="panel-title">Solicitud de Edición de Empleado</h3>
	    	</div>
	    	<div class="panel-body">
				<div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                <label for="cedulaedit">Cédula</label>
	                <input type="text" name="cedulaedit" id="cedulaedit" class="form-control text-center" readonly>                
	            </div>
	            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                <label for="nombreedit">Nombre</label>
	                <input type="text" name="nombreedit" id="nombreedit" class="form-control text-center" readonly>
	            </div>
	            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                <label for="apellidoedit">Apellido</label>
	                <input type="text" name="apellidoedit" id="apellidoedit" class="form-control text-center" readonly>
	            </div>
	            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                <label for="cargoedit">Cargo</label>
	                <input type="text" name="cargoedit" id="cargoedit" class="form-control text-center" readonly>
	            </div>
	            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                <label for="agenciaedit">Agencia</label>
	                <input type="text" name="agenciaedit" id="agenciaedit" class="form-control text-center" readonly>
	            </div>           
	            <div class="form-group col-xs-12 col-md-6 col-lg-4 text-center">
	                <label for="turnoedit">Turno</label>
	                <input type="text" name="turnoedit" id="turnoedit" class="form-control text-center" readonly>
	            </div>
	            <div class="form-group col-xs-12 col-md-4 text-center">
	            	<label for="accionedit">Acción</label>
	            	<select name="accionedit" id="accionedit" class="form-control text-center">
						<option>Seleccionar...</option>
	                	<option value="Editar">Editar</option>
	                    <option value="Cambiar">Cambiar de Agencia</option>
	                    <option value="Eliminar">Eliminar</option>                                         
	              	</select>                  	                
	            </div>
	            <div class="form-group col-xs-12 col-md-4 oculto text-center" id="tiendaedit">
	            	<label for="sucursaledit">Sucursal</label>
	            	<select name="sucursaledit" id="sucursaledit" class="form-control text-center">
						<option>Seleccionar...</option>
						
	              	</select>                  	                
	            </div>
	            <div class="form-group col-xs-12 text-center">
	                <label for="comentarioedit">Comentario</label>
	                <textarea name="comentarioedit" id="comentarioedit" class="form-control"></textarea>
	            </div>                   
				<div class="form-group col-xs-12 text-center">
	                 <input type="image" name="guardaredit" id="guardaredit" src="imagenes/save.png" title="Generar Incidencia">
	            </div>           	         
			</div>
	  	</div><!--End col -->
	    <div class="col-xs-12 col-md-2 col-lg-3"></div>
	    </div><!--End row -->
	    </div><!--End edit -->
	    
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
	            	<label>* Cedula</label>
	            	<input  type="text" name="cinu" id="cinu" class="form-control integer uncopypaste text-center" maxlength="8">     	                
	            </div>
	            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Nombres</label>
	               	<input  type="text" name="nombrenu" id="nombrenu"class="form-control text-center">
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* apellidos</label>
	               	<input  type="text" name="apellidonu" id="apellidonu" class="form-control text-center">
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Cargo</label>
	                <select name="cargonu" name="cargonu" id="cargonu" class="form-control">
	                	                 
	                </select>
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Fecha Ingreso</label>
	               	<input  type="text" name="fingresonu" id="fingresonu"class="form-control text-center">
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Agencia</label>                
	               	<input  type="text" name="agencianu" id="agencianu" class="form-control text-center" maxlength="30" readonly>     	 
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Teléfono</label>
	               	<input  type="text" name="telefononu" id="telefononu" class="form-control integer text-center" maxlength="30">
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>Correo</label>
	               	<input  type="text" name="correnuo" id="correonu" class="form-control text-center" maxlength="30">
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Turno</label>
	               	<select name="turnonu" id="turnonu" class="form-control text-center">
	               		<option>Seleccionar...</option>
	                    <option value="1">Lunes a Viernes</option>
	                    <option value="2">Martes a Sábado</option>
	              	</select>
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Supervisor</label>
	               	<input  type="text" name="supervisornu" id="supervisornu"class="form-control text-center">
	            </div>            
	            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Nro de Hijos</label>
	               	<input  type="text" name="hijosnu" id="hijosnu" class="form-control integer text-center" maxlength="2">
	            </div>
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Zona de Residencia</label>
	               	<input  type="text" name="zonaresnu" id="zonarenus"class="form-control text-center">
	            </div>           
	           	<div class="form-group col-xs-12">
					<label>* Dirección</label>
	               	<textarea name="direccionnu" id="direccionnu" class="form-control text-center"></textarea>
	            </div>            
	       	</div>
			<div class="panel">
	      		<h3 class="panel-title">Información Académica</h3>
	    	</div>
	 		<div class="panel-body">
	            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Estudia Actualmente</label>
	               	<select name="estudionu" id="estudionu" class="form-control text-center">
	                    <option>Seleccionar...</option>
	                    <option value="1">Si</option>
	                    <option value="0">No</option>
	                </select>
	            </div>
	   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Grado de Instrucción</label>
	               	<input  type="text" name="instruccionnu" id="instruccionnu"class="form-control text-center">
	            </div> 
	   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>* Titulo</label>
	               	<input  type="text" name="titulonu" id="titulnonu"class="form-control text-center">
	            </div>
			</div>
			<div class="panel">
	      		<h3 class="panel-title">Otros Datos</h3>
	    	</div>
	 		<div class="panel-body">
	            <div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>Usuario LIB</label>
	               	<input  type="text" name="userlibnu" id="userlibnu" class="form-control text-center">
	            </div>        
				<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>Caja Facturación</label>
	               	<input  type="text" name="cajafactnu" id="cajafactnu"class="form-control text-center">
	            </div>         
	   			<div class="form-group col-xs-12 col-md-6 col-lg-3 text-center">
					<label>Fecha Cambio</label>
	               	<input  type="text" name="fcambionu" id="fcambionu" class="form-control text-center">
	            </div> 
	 			<div class="form-group col-xs-12">
					<label>Observacion</label>
	               	<textarea name="observacionnu" id="observacionnu" class="form-control text-center"></textarea>
	            </div>        
				<div class="form-group col-xs-12 text-center">
					<input type="image" id="enviarnu" src="imagenes/save.png" title="Ingresar Usuario">
	            </div>                    	         
			</div>
	  	</div><!--End panel -->
		<div class="col-xs-1 col-md-1 col-lg-1"></div>
	    </div><!--End col -->
	    </div><!--End row -->       
            
    </div><!--End Contenido --> 
    <?php echo $chat; echo $footer?>
</body>
	<form id="planilla_vacaciones" method="POST" action="planillav.php" target="_blank" class="oculto">
    	<input type="hidden" name="id_planilla" id="id_plantilla">
    </form>  
</html>
<?php
}else{
	header("location:../index.php?error=ingreso");
}
?>