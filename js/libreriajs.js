// JavaScript Document 
	//Validate Form
	function validateForm(div){
		var validate = true;
		//Validate TextBox
		$("#nuevo input").each(function (index) {
			if($(this).prop('required')){
		        if ($(this).val() == ''){
					$(this).parent().addClass('has-error');
					$(this).attr('placeholder','Campo Obligatorio');
					validate = false;
				}else{
					$(this).parent().removeClass('has-error').addClass('has-success');
				}
		    }		    
		});
		//Validate Select
		$("#nuevo select").each(function (index) {
			if($(this).prop('required')){
				if ($('option:selected', this).index() == 0){
					$(this).parent().addClass('has-error');
					$(this).attr('placeholder','Campo Obligatorio');
					validate = false;				
				}else{
					$(this).parent().removeClass('has-error').addClass('has-success');
				}
			}
		});

		return validate;
	}

	// Function Show and hide buttons report
	function getbuttons(niv, columns, title){
		var buttons = "";
		if (niv < 3){
			var buttons = [
				{ 
					extend: 'copy',
					title: title,
					text: 'Copiar',
					exportOptions: {
					  columns: columns
					}
				},
				{
					extend: 'excel',
					title: title,
					exportOptions: {
					  columns: columns
				}
			}];			
		}else{
			var buttons = [];
		}
		return buttons;
	}

// Función para validar entradas (Keypress)
	function validar(key, campo, formato){
		var num = [48,49,50,51,52,53,54,55,56,57];
		var alfa = [65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121];

		//Texto de la Palabra completa
		texto = campo.val();
		//Cantidad de Caracateres presionados
		i = texto.length;
		//Formato de la letra a Validar de la Cadena
		letra = formato.charAt(i);
		//Tecla presionada
		press = key.which;
		//Si el usuario presiona "'" se convierte a "-"
		if ((press == 39)){
			press = 45;
		}		

		//Borramos todos las clases
		campo.parent().removeClass('has-warning has-success has-error');		

		//Si la Tecla Presionada es distinta a Backspace
		if ((press != 8) && (i < formato.length)){
			switch (letra){
				//Validar Sólo Números
				case '#': if ($.inArray(press, num) == -1){
							 key.preventDefault();
							 campo.parent().addClass('has-warning');
						  }
				break;
				
				//Validar Sólo Letras
				case '&': if ($.inArray(press, alfa) == -1){
							 key.preventDefault();
							 campo.parent().addClass('has-warning');
						  }
				break;				

				//Validar Alfanumerico
				case '*': if(($.inArray(press, num) == -1) && ($.inArray(press, alfa) == -1)){
							 key.preventDefault();
							 campo.parent().addClass('has-warning');
						  }
				break;							

				//Validar Formato Individual ejemplo(WR01-)
				default: if(String.fromCharCode(press) != letra){
							key.preventDefault();
							 campo.parent().addClass('has-warning');
						 }else{
							 //Si el usuario presiona "'" se convierte a "-"
							 if (press == 45){
									key.preventDefault();
									campo.val(campo.val() + '-');
							 }
						 }
				}
		}//End if	

	}//End Function
		
//Función para validar al guardar
	function validarFormato(campo, formato){
	var num = [48,49,50,51,52,53,54,55,56,57];
	var alfa = [65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121];

	if (campo.val().length < formato.length){
		campo.parent().addClass('has-error');
		return false;
	}	

	for (var i = 0; i < campo.val().length; i++) {
    	char = campo.val().charCodeAt(i);
		letra = formato.charAt(i);
			switch (letra){
				//Validar Sólo Números
				case '#': if ($.inArray(char, num) == -1){
							 campo.parent().addClass('has-warning');
							 return false;
						  }
				break;				

				//Validar Sólo Letras
				case '&': if ($.inArray(char, alfa) == -1){
							campo.parent().addClass('has-warning');
							return false;
						  }
				break;				

				//Validar Alfanumerico
				case '*': if(($.inArray(char, num) == -1) && ($.inArray(char, alfa) == -1)){
							 campo.parent().addClass('has-warning');
							 return false;
						  }
				break;

				//Validar Formato Individual ejemplo(WR01-)

				default: if(char != formato.charCodeAt(i)){
							campo.parent().addClass('has-warning');
							return false;
						 }
			}//End Switch

			campo.parent().removeClass('has-success');
		}//Enf for
	}//End Function

$(document).ready(function(){
//Buscar Notificaciones
function notificacion(){
	$.post("include/pdo/notificacion.php",{function:"getNotifications"},function(data){
		if (data > "0"){
			$('#burbuja').fadeIn(5000).html(data);
		}else{
			$('#burbuja').fadeOut();
		}
	});//End post

}

notificacion();
setInterval(notificacion, 10000);	

//Validación de campos
	$(".numeric").numeric();
	$(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });

//Activar Submenú
	$('[data-submenu]').submenupicker();	

//Borrar clases al enfocar elemento
	$('body').on('click', '.form-control', function(){
		$(this).attr('placeholder','').parent().removeClass('has-error has-success has-warning');
	});	



//Función para tabular luego de leer los codigos de barra con el lector.
	$('body input:text').keypress(function(e){
		if (e.keyCode == 13) {
            var campos = $('body input'); //Campos input del body
            for (var x = 0; x < campos.length; x++) {
                if (campos[x] == this) {
                    while ((campos[x]).id == (campos[x + 1]).id) {
                    x++;
                    }
                    if ((x + 1) < campos.length) $(campos[x + 1]).focus();
                }
            }   e.preventDefault();
        }
	});
});//End Ready

natDays = [
			  [1, 1, 'Año Nuevo'],
			  [2, 8, 'Lunes Carnaval'],
			  [2, 9, 'Martes Carnaval'],
			  [3, 24, 'Jueves Santo'],
			  [3, 25, 'Viernes Santo'],
			  [4, 19, 'Declaración de independiencia'],
			  [5, 1, 'Día del trabajador'],
			  [6, 24, 'Batalla Carabobo'],
			  [7, 5, 'Independencia'],
			  [7, 24, 'Natalicio Libertador'],
			  [10, 12, 'Dia de la Raza'],
			  [12,24, 'Noche Buena'],
			  [12, 25, 'Navidad'],
			  [12, 31, 'Fin de año']
		  ];

function lunesaViernes(date) {
	for (i = 0; i < natDays.length; i++) {
		if (date.getMonth() == natDays[i][0] - 1 && date.getDate() == natDays[i][1]) {
        return [false, natDays[i][2] + '_day'];
      }
    }
	var day = date.getDay();
	return [(day != 0 && day != 6), ''];
	return [true, ''];
}

function martesaSabado(date) {
	for (i = 0; i < natDays.length; i++) {
		if (date.getMonth() == natDays[i][0] - 1 && date.getDate() == natDays[i][1]) {
        return [false, natDays[i][2] + '_day'];
      }
    }
	var day = date.getDay();
	return [(day != 0 && day != 1), ''];
	return [true, ''];
}

function sabadoDomingo(date) {
	for (i = 0; i < natDays.length; i++) {
		if (date.getMonth() == natDays[i][0] - 1 && date.getDate() == natDays[i][1]) {
        return [false, natDays[i][2] + '_day'];
      }
    }
	var day = date.getDay();
	return [(day != 1  && day != 2 && day != 3 && day != 4 && day != 5), ''];
	return [true, ''];
}

function domingoLunes(date) {
	for (i = 0; i < natDays.length; i++) {
		if (date.getMonth() == natDays[i][0] - 1 && date.getDate() == natDays[i][1]) {
        return [false, natDays[i][2] + '_day'];
      }
    }
	var day = date.getDay();
	return [(day != 2 && day != 3 && day != 4 && day != 5 && day != 6), ''];
	return [true, ''];
}



