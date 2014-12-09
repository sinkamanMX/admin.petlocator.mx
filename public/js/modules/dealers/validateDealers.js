$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputName       : "required", 
            inputEstatus    : "required",
            inputCalle      : "required",
            InputNoExt      : "required",
            inputColonia    : "required",
            inputMuni       : "required",
            inputCodePais   : "required",
            inputEstado     : "required",
            inputCp         : "required",
            inputLatitud    : {
              required: true,
              number  : true
            },
            inputLongitud   : {
              required: true,
              number  : true         
            }, 
            //inputWeb
            //inputNamContacto
            //inputEmContacto
            //inputTelContacto
            //inputTelContacto2
            //inputHorario
            //inputDias

        },        
        // Se especifica el texto del mensaje a mostrar
        messages: {
            inputName       : "Campo Requerido",
            inputEstatus    : "Campo Requerido",
            inputCalle      : "Campo Requerido",
            InputNoExt      : "Campo Requerido",
            inputColonia    : "Campo Requerido",
            inputMuni       : "Campo Requerido",
            inputCodePais   : "Campo Requerido",
            inputEstado     : "Campo Requerido",
            inputCp         : "Campo Requerido",
            inputLatitud    : {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            },
            inputLongitud   : {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."      
            },             
        },        
        submitHandler: function(form) {
            form.submit();
        }
    });	

});