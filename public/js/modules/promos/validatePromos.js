$().ready(function() {
	$("#FormData").validate({
        rules: { 
            inputProduct    : "required",    
            inputName       : "required",            
            inputDesc       : "required",
            inputDiscount: {
              required: true,
              number:    true
            },
            inputMins: {
              required: true,
              number: true
            },
            inputMax: {
              required: true,
              number: true
            },
            inputPeriodo: {
              required: true,
              number: true
            },
            inputPerGratis  : {
              required: true,
              number: true
            },
            inputInicio     : "required",
            inputFin        : "required",
            inputEstatus    : "required"
        },        
        // Se especifica el texto del mensaje a mostrar
        messages: {
            inputName       : "Campo Requerido",
            inputProduct    : "Campo Requerido", 
            inputName       : "Campo Requerido",           
            inputDesc       : "Campo Requerido",
            inputDiscount: {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            },
            inputMins: {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            },
            inputMax: {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            },
            inputPeriodo: {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            },
            inputPerGratis  : {
              required: "Campo Requerido",
              number:   "Este campo solo permite digitos."
            },
            inputInicio     : "Campo Requerido",
            inputFin        : "Campo Requerido",
            inputEstatus    :"Campo Requerido"
        },        
        submitHandler: function(form) {
            form.submit();
        }
    });	

    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var dateInter  = parseInt(nowTemp.getMonth())+1;  
    var todayMonth = (dateInter<10) ? "0"+dateInter : dateInter;
    var todayDay   = (nowTemp.getDate()<10) ? "0"+nowTemp.getDate(): nowTemp.getDate();        

    if($("#inputInicio").val()==""){
      $("#inputInicio").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+" 00:00");      
    }

    if($("#inputFin").val()==""){
      $("#inputFin").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+" 23:59");    
    }
    
    var checkin = $('#inputInicio').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01 00:00",
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() > $('#inputFin').datetimepicker('getDate').valueOf()){
        $('#inputFin').datetimepicker('setDate', ev.date);   
      }

      $('#inputFin').datetimepicker('setStartDate', ev.date);      
      $('#inputFin').prop('disabled', false);
      $('#inputFin')[0].focus();      
    });

    var checkout = $('#inputFin').datetimepicker({
        format: "yyyy-mm-dd hh:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01 23:59",
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() < $('#inputInicio').datetimepicker('getDate').valueOf()){
        $('#inputInicio').datetimepicker('setDate', ev.date);   
      }
      $('#inputFechaIn').datetimepicker('setEndDate', ev.date);
    });
});

function backToModule(){
	var mainPage = $("#hRefLinkMain").val();
	location.href= mainPage;
}