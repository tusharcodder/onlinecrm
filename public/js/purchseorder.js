$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('bill_no', 'purchase_orders', 'bill_no');
	autocomplete('isbn13', 'purchase_orders', 'isbn13');
    autocomplete('purchase_by', 'purchase_orders', 'purchase_by');
	autocomplete('bookname', 'book_details', 'name');
	
	// on import with update click 
	$('#importtype').on('click', function(e) {
		if($(this).val() == "importwithupdate")  
		{
			$(".importupdate").show();
			$("#import_from_date").prop('required', true);  
			$("#import_to_date").prop('required', true);  
		} else {
			$(".importupdate").hide();
			$("#import_from_date").prop('required',false);  
			$("#import_to_date").prop('required',false);
		}  
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
			alert("Please select atleast one sale item for delete."); 
			return false;
		}else{ 
			var check = confirm("Are you sure you want to delete selected sale item?");  
			if(check == true)
				$('#selectedval').val(allVals.join(","));
			else
				return false;
		}  
	});
});