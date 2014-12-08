$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputDivisa    : "required",
            inputTextDivisa: "required",
			inputEstatus   : "required",
			inputPais      : "required"
        },
        
        // Se especifica el texto del mensaje a mostrar
        messages: {
        	inputDivisa    : "Campo Requerido",
            inputTextDivisa: "Campo Requerido",
			inputEstatus   : "Campo Requerido",
			inputPais      : "Campo Requerido"
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
