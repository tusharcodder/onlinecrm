$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('isbnno', 'vendor_stocks', 'isbnno');
	autocomplete('vendor_name', 'vendor_stocks', 'vendor_name');
	autocomplete('name', 'vendor_stocks', 'name');
	autocomplete('author', 'vendor_stocks', 'author');
	autocomplete('publisher', 'vendor_stocks', 'publisher');
	autocomplete('binding_type', 'vendor_stocks', 'binding_type');
	autocomplete('currency', 'vendor_stocks', 'currency');
	
	// download report button on click
	// on import with update click 
	$('#downloadreport').on('click', function(e) {
		// get form values and set into the hidden field
		var values = {};
		$.each($('#searchform').serializeArray(), function(i, field) {
			values[field.name] = field.value;
		});
		$('#formval').val(JSON.stringify(values));
	});
	
	// check box checked js
	$('#master').on('click', function(e) {
		if($(this).is(':checked',true))  
		{
			$(".sub_chk").prop('checked', true);  
		} else {  
			$(".sub_chk").prop('checked',false);  
		}  
	});
	
	// delete selected row item
	$('.delete_all').on('click', function(e) {
		var allVals = [];  
		$(".sub_chk:checked").each(function() {  
			allVals.push($(this).attr('data-id'));
		});
		if(allVals.length <=0){  
			alert("Please select atleast one stock item for delete."); 
			return false;
		}else{ 
			var check = confirm("Are you sure you want to delete selected vendor stock item?");  
			if(check == true)
				$('#selectedval').val(allVals.join(","));
			else
				return false;
		}  
	});
});