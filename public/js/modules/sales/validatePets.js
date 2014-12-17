var aDataTrackers = Array();
$().ready(function() {
  $('#dataTable').dataTable( {
    "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
    "sPaginationType": "bootstrap",
    "bDestroy": true,
    "bLengthChange": false,
    "bPaginate": true,
    "bFilter": true,
    "bSort": false,
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

function changeCbo(){
   $("#divpError").hide(); 
   $("#divpErrorDouble").hide();    
}


function sendDataProducts(){
  var aErrorDouble = 0;
   aDataTrackers = Array();
   $("#divpError").hide();  
   $("#divpErrorDouble").hide();    
  var valTotal      = $("#inputTotal").val();
  var validateCount = 0;  
  $('tbody tr .selectDbo').each(function () {
    var idValue = $(this).val();  
    if(idValue!=""){
        var found = $.inArray(idValue, aDataTrackers) > -1;
        if(!found){
          aDataTrackers.push(idValue);
          validateCount++;    
        }else{
          aErrorDouble++;          
        }
    }  
  });  
  
  if(validateCount==0 || validateCount!=valTotal){
      $("#divpError").show();
  }else if(aErrorDouble>0){
      $("#divpErrorDouble").show();      
  }else{
      $("#FormData").submit();
  }
}