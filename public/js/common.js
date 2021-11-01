$(document).ready(function(){
});
// function call load aggregatorvendor
function autocomplete(textid, table, column){
	// auto complete function
	$('#'+textid).autocomplete({
		source: function( request, response ) {
			// Fetch data
			$.ajax({
				url: "../autocomplete/"+table+"/"+column,
				type: 'GET',
				data: {
				   search: request.term
				},
				dataType: "json",
				success: function( data ) {
					response( data );
				}
			});
		},
		select: function (event, ui) {
			 // Set selection
			 $('#'+textid).val(ui.item.value); // save selected id to input
			 return false;
		},
		focus: function(event, ui){
			 $('#'+textid).val( ui.item.value );
			 return false;
		},
    });
	
}