$().ready(function() { 
	$("#FormData").validate({
        rules: {
            inputTitulo	: "required",
            inputDesc   : "required",
            inputEstatus: "required"
        },
        messages: {
            inputTitulo	: "Campo Requerido",
            inputDesc	: "Campo Requerido",
            inputEstatus: "Debe seleccionar una opción"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });	

    $(".chzn-select").chosen();

    $('#dataTable').dataTable( {
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "bDestroy": true,
        "bLengthChange": false,
        "bPaginate": true,
        "bFilter": true,
        "bSort": true,
        "bJQueryUI": true,
        "iDisplayLength": 10,      
        "bProcessing": true,
        "bAutoWidth": true,
        "bSortClasses": false,
          "oLanguage": {
              "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
              "sEmptyTable": "Sin registros.",
              "sInfoEmpty" : "Sin registros.",
              "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
              "sLoadingRecords": "Leyendo información",
              "sProcessing": "Procesando",
              "sSearch": "Buscar:",
              "sZeroRecords": "Sin registros",
              "oPaginate": {
                "sPrevious": "Anterior",
                "sNext": "Siguiente"
              }          
          }
    } );  

});

function backToMain(){
	var mainPage = $("#hRefLinkMain").val();
	location.href= mainPage;
}

function backToMainModule(){
    var url = $("#hRefLinkMain").val();
    location.href=url;    
}

function openEditElement(idObject){
    $('#iFrameElement').html("cargando la información...");
    var idItem = $("#catId").val();    
    $('#iFrameElement').attr('src','/main/forms/newelement?dataElement='+idObject+'&catId='+idItem);
    $("#MyModalElement").modal("show");
}

function closeElement(){
    $("#MyModalElement").modal("hide");
}

function reloadData(){
    var idItem = $("#catId").val();     
    location.href = '/main/forms/getinfo?catId='+idItem+'&eventAction=true';
}