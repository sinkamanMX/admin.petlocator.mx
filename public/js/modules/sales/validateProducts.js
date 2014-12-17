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

    jQuery('#dataTable').on('change', 'tbody tr .checkboxes', function(){
         $(this).parents('tr').toggleClass("active");
    });

    $('.checkboxes').change(function() {
      $("#divpError").hide();      
      var idValue = $(this).val();
      if($(this).is(':checked')){
          $("#selectCant"+idValue).prop('disabled', false);
      }else{
          $("#selectCant"+idValue).prop('disabled', true);
          $("#span"+idValue).html("$ 0.00");
          $("#selectCant"+idValue).val(0);
      }
      reCalculate()
    });
});

function reCalculate(){
  $("#divpError").hide();       
  $("#totalPrice").val(0);
  var price = $("#priceDist").val();
  var total = 0;

  $('tbody tr .checkboxes').each(function () {
    var idValue = $(this).val();
    if($(this).is(':checked')){
      var count = $("#selectCant"+idValue).val();
      var totalProd =  price * count;    
      $("#span"+idValue).html("$ "+totalProd.toFixed(2));
      total = total + totalProd;
    }       
  });

  $("#totalPrice").val(total);
  $("#spanTotal").html("$ "+total.toFixed(2));
}

function sendDataProducts(){
  $("#divpError").hide();  
  var validateCount = 0;  
  $('tbody tr .checkboxes').each(function () {
    var idValue = $(this).val();    
    if($(this).is(':checked')){
      var count = $("#selectCant"+idValue).val();
      validateCount +=count;
    }       
  });  

  if(validateCount==0){
      $("#divpError").show();
  }else{
      $("#FormData").submit();
  }
}