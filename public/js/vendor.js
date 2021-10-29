$(document).ready(function(){
	
	// onlick of type 
	$("#type").change(function () {
		$("#dynamicTable").find('input').val('');
		$(".remove-tr").click();
		if($(this).val() == 'Aggregator'){
			// add validate prop true
			$("#dynamicTable").find('input').prop('required',true);
			$( ".addmorecontainer" ).show();
		}else{
			// add validate prop false
			$("#dynamicTable").find('input').prop('required',false);
			$( ".addmorecontainer" ).hide();
		}
	});
	
    $("#add").click(function(){
		var i = $("#dynamicTable").find('tr').find('td:eq(0)').find('input').length - 1;
        ++i;

        $("#dynamicTable").append('<tr><td><input type="text" name="addmore['+i+'][vname]" placeholder="Enter vendor name" class="form-control" required/></td><td><input type="number" name="addmore['+i+'][vcomm]" placeholder="Enter vendor commission(%)" class="form-control" step="any" min="0" max="100" required/></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');

    });
	
    $(document).on('click', '.remove-tr', function(){  
         $(this).parents('tr').remove();
    }); 
});