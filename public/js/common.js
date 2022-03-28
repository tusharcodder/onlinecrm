$(document).ready(function(){
	//add isbn to second drowdown at double click on first dropdown
    $('#box_isbns').dblclick(function(){
        exists = false;
        isbn = $('#box_isbns option:selected').val();

        $('#book_isbns_to option').each(function(){
            if (this.value == isbn) {
                exists = true;     //already added           
            }
        });
        //add new isbn
        if(!exists){
            $('#book_isbns_to').append('<option value='+isbn+' selected>'+isbn+'</option>');
        }
            
    });

    //remove isbn from second dropdown at double click 
    $('#book_isbns_to').dblclick(function(){        
        isbn = $('#book_isbns_to option:selected').remove();
    });

    $('#isbnsearch').on('input',function(){
        
        $('#box_isbns').empty();
        search = $(this).val();
        $.ajax({
            url:'/get-isbn',
            type:'get',
            dataType:'json',
            data:{
                search :search
            },
            success:function(data){
               
                $.each(data, function(key , value){
                    
                   $('#box_isbns').append('<option value='+value.isbn13+'>'+value.isbn13+'</option>'); 
                });
            }
        });
    });
});
// function call load aggregatorvendor
function autocomplete(textid, table, column){
	// auto complete function
	$('#'+textid).autocomplete({
		source: function( request, response ) {
			// Fetch data
			$.ajax({
				url: "./autocomplete/"+table+"/"+column,
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