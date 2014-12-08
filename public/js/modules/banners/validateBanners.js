$().ready(function() {
	$("#FormData").validate({
        rules: {
            inputName       : "required",
            inputDesc       : "required",
            inputInicio     : "required",
            inputFin        : "required",
            inputEstatus    : "required"
        },        
        // Se especifica el texto del mensaje a mostrar
        messages: {
            inputName       : "Campo Requerido",
            inputDesc       : "Campo Requerido",
            inputInicio     : "Campo Requerido",
            inputFin        : "Campo Requerido",
            inputEstatus    : "Campo Requerido"
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

    if($("#inputFechaIn").val()==""){
      $("#inputFechaIn").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay);      
    }

    if($("#inputFechaFin").val()==""){
      $("#inputFechaFin").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay);    
    }
    
    var checkin = $('#inputFechaIn').datetimepicker({
        format: "yyyy-mm-dd",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01",
        minView:2,
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() > $('#inputFechaFin').datetimepicker('getDate').valueOf()){
        $('#inputFechaFin').datetimepicker('setDate', ev.date);   
      }

      $('#inputFechaFin').datetimepicker('setStartDate', ev.date);      
      $('#inputFechaFin').prop('disabled', false);
      $('#inputFechaFin')[0].focus();      
    });

    var checkout = $('#inputFechaFin').datetimepicker({
        format: "yyyy-mm-dd",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01",
        minView:2,
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() < $('#inputFechaIn').datetimepicker('getDate').valueOf()){
        $('#inputFechaIn').datetimepicker('setDate', ev.date);   
      }
      $('#inputFechaIn').datetimepicker('setEndDate', ev.date);
    });
});

function backToModule(){
	var mainPage = $("#hRefLinkMain").val();
	location.href= mainPage;
}