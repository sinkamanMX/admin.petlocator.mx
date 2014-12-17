$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputDivisa    : "required",
            inputTextDivisa: "required",
			inputEstatus   : "required",
			inputPais      : "required",
            inputDiferencia: {
              required: true,
              number  : true
            }
        },
        messages: {
        	inputDivisa    : "Campo Requerido",
            inputTextDivisa: "Campo Requerido",
			inputEstatus   : "Campo Requerido",
			inputPais      : "Campo Requerido",
            inputDiferencia: {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            }
        },        
        submitHandler: function(form) {
            form.submit();
        }
    });	
});

function backToModule(){
	var mainPage = $("#hRefLinkMain").val();
	location.href= mainPage;
}

function onChangeCountry(codeCountry){
	$("#txtClave").val(codeCountry);
	$("#catId").val(codeCountry);	
}
