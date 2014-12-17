$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputName       : "required", 
            inputApps       : "required",  
            inputUser       : {
              required: true,
              email:    true
            },
            inputPass       : "required",
            inputTel        : {
              required: true,
              number  : true,
              maxlength: 10,
              minlength: 10
            },
            inputEstado     : "required"
        },        
        // Se especifica el texto del mensaje a mostrar
        messages: {
            inputName       : "Campo Requerido",
            inputApps       : "Campo Requerido",
            inputUser       : {
              required      : "Campo Requerido",
              email         : "Favor de ingresar un email válido."
            },
            inputPass       : "Campo Requerido",
            inputTel        : {
              required : "Campo Requerido",
              number   : "Este campo solo permite digitos.",
              maxlength: "El teléfono debe de ser de 10 digitos.",
              minlength: "El teléfono debe de ser de 10 digitos."
            },
            inputEstado     : "required"
        },        
        submitHandler: function(form) {
            form.submit();
        }
    });	

});