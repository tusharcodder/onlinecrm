$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('invoice_no', 'sales', 'invoice_no');
	autocomplete('po_no', 'sales', 'po_no');
	autocomplete('vendor_type', 'vendors', 'type');
	autocomplete('vendor_name', 'vendors', 'vendor_name');
	autocomplete('aggregator_vendor_name', 'aggregator_has_vendors', 'aggregator_vendor_name');
	autocomplete('category', 'stocks', 'category');
	autocomplete('gender', 'stocks', 'gender');
	autocomplete('colour', 'stocks', 'colour');
	autocomplete('size', 'stocks', 'size');
	autocomplete('lotno', 'stocks', 'lotno');
	autocomplete('sku_code', 'stocks', 'sku_code');
	autocomplete('product_code', 'stocks', 'product_code');
	autocomplete('hsn_code', 'stocks', 'hsn_code');
	autocomplete('state', 'sales', 'state');
	
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