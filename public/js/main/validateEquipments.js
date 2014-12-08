$.validator.addMethod('IP4Checker', function(value) {
    var ip = "^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$";
    return value.match(ip);
}, 'IP inválida');

$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputImei   : {
                required: true,
                number: true,
                minlength: 12,
                maxlength: 20
            },
            inputIp:{
                required: true,
                IP4Checker: true
            },
            inputCodePais: "required",
            inputDistribuidor: "required",
            inputNoSerie: "required",
            inputTel:{
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10                
            }
        },
        
        // Se especifica el texto del mensaje a mostrar
        messages: {
            inputCodePais : "Campo Requerido",
            inputImei     : {
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El IMEI debe mímimo de 12 dígitos",
                maxlength : "El IMEI debe máximo de 20 dígitos"
            }, 
            inputDistribuidor: "Campo Requerido",
            inputIp       : {
                required  : "Campo Requerido",
                IP4Checker: "IP inválida"
            }, 
            inputNoSerie  : "Campo Requerido",
            inputTel      :{
                required  : "Campo Requerido",
                number    : "Este campo acepta solo números",
                minlength : "El IMEI debe mímimo de 10 dígitos",
                maxlength : "El IMEI debe máximo de 10 dígitos"            
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