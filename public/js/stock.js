$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('manufacturer_name', 'buyers', 'name');
	autocomplete('country', 'buyers', 'country');
	autocomplete('brand', 'stocks', 'brand');
	autocomplete('category', 'stocks', 'category');
	autocomplete('gender', 'stocks', 'gender');
	autocomplete('colour', 'stocks', 'colour');
	autocomplete('size', 'stocks', 'size');
	autocomplete('lotno', 'stocks', 'lotno');
	autocomplete('sku_code', 'stocks', 'sku_code');
	autocomplete('product_code', 'stocks', 'product_code');
	autocomplete('hsn_code', 'stocks', 'hsn_code');
	
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
	
	// preview image
	$("#product_image").change(function() {
		var val = $(this).val();
		$('#preview').hide();
		switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
			case 'gif': case 'jpg': case 'png': case 'jpeg': case 'svg':
				readURL(this);
				break;
			default:
				$(this).val('');
				// error message here
				alert("not an image");
				break;
		}
	});

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
			alert("Please select atleast one stock item for delete."); 
			return false;
		}else{ 
			var check = confirm("Are you sure you want to delete selected stock item?");  
			if(check == true)
				$('#selectedval').val(allVals.join(","));
			else
				return false;
		}  
	});
});
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader(); 
    reader.onload = function(e) {
		$('#preview').show();
		$('#preview').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}