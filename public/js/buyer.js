$(document).ready(function(){
    $("#add").click(function(){
		var i = $("#dynamicTable").find('tr').find('td:eq(0)').find('input').length - 1;
        ++i;

        $("#dynamicTable").append('<tr><td><input type="text" name="addmore['+i+'][cname]" placeholder="Enter contact person name" class="form-control" required/></td><td><input type="email" name="addmore['+i+'][cemail]" placeholder="Enter contact person email ID" class="form-control" multiple/></td><td><input type="text" name="addmore['+i+'][cphone]" placeholder="Enter contact person number" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');

    });
	
    $(document).on('click', '.remove-tr', function(){  
         $(this).parents('tr').remove();
    }); 
});