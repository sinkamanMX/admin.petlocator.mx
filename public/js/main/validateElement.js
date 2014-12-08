$().ready(function() { 
  $("#FormData").validate({
        rules: {
            inputDesc   : "required",
            inputTipo   : "required",
            inputEstatus: "required",
            inputValores: "required"
        },
        messages: {
            inputDesc   : "Campo Requerido",
            inputValores: "Campo Requerido",
            inputTipo   : "Debe seleccionar una opción",
            inputEstatus: "Debe seleccionar una opción"
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    }); 


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

    if($("#req_ops").val() == 0){
        $("#inputValores").rules("remove", "required");
    }
});

function validateAdds(idObject){
  var valRequerido = idObject.split("|");
  console.log(valRequerido[1]);
  value = valRequerido[1];
  if(valRequerido[1]==0){
    $("#divValues").hide('slow');
    $("#inputValores").rules("remove", "required");
  }else{
    $("#divValues").show('slow');
    $("#inputValores").rules("add", {required:true});
  }
}