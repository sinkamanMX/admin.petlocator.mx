$( document ).ready(function() {

	$('.dataTable').dataTable( {
		"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
		"sPaginationType": "bootstrap",
		"bDestroy": true,
		"bLengthChange": false,
		"bPaginate": true,
		"bFilter": true,
		"bSort": true,
		"bJQueryUI": true,
		"iDisplayLength": 5,      
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

    $('#iFrameSearch').on('load', function () {        
        $('#loader1').hide();
        $('#iFrameSearch').show();
    }); 
});	

function showDetail(idDate){
	$('#loader1').show();
	$('#iFrameSearch').hide(); 
    $("#myModalinfoVis").modal("show");        
    $('#iFrameSearch').attr('src','/distribuitor/sales/moreinfo?catId='+idDate);    
}