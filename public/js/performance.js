$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('brand', 'stocks', 'brand');
	autocomplete('category', 'stocks', 'category');
	autocomplete('gender', 'stocks', 'gender');
	autocomplete('colour', 'stocks', 'colour');
	autocomplete('size', 'stocks', 'size');
	autocomplete('lotno', 'stocks', 'lotno');
	autocomplete('sku_code', 'stocks', 'sku_code');
	autocomplete('product_code', 'stocks', 'product_code');
	autocomplete('sku_code', 'stocks', 'sku_code');
	autocomplete('hsn_code', 'stocks', 'hsn_code');
	
	// download report button on click
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
			alert("Please select atleast one performance item for delete."); 
			return false;
		}else{ 
			var check = confirm("Are you sure you want to delete selected performance item?");  
			if(check == true)
				$('#selectedval').val(allVals.join(","));
			else
				return false;
		}  
	});
	
	// on click add button
	$('#addperformance').on('click',function(){
		$('#errorlist').hide();
		var category = $('#category').val();
		var product_code = $('#product_code').val();
		var salethrough = $('#salethrough').val();
		
		// check validation
		var flag = true;
		var error = [];
		if(category == "")
			error.push("Please select category.");
		if(product_code == "")
			error.push("Please enter product_code.");
		if(salethrough == "" || salethrough < 0 || salethrough > 100)
			error.push("Please enter valid sale through percent.");
		
		if(error.length > 0){
			$('#errorlist').show();
			$('#errorlist').html(error.join("<br/>"));
			return false;	
		}
		
		// check duplicate performance
		if($('#performancelist tbody tr').length > 0){
			flag = false;
			$('#performancelist tbody tr').each(function () {
				tpcode = $(this).find('td').eq(0).text();
				tcategory = $(this).find('td').eq(1).text();
				tsthrough = $(this).find('td').eq(2).text();
				
				if(product_code == tpcode && category == tcategory){
					flag = true;
				}
			});
			if(flag == true){
				$('#errorlist').show();
				$('#errorlist').html("Performance already added.");
				return false;
			}
		}
		
		if($('#performancelist tbody tr:eq(0)').text() == "No data added.")
			$('#performancelist tbody').html('');
		
		$('#performancelist tbody').append('<tr><td>'+product_code+'</td><td>'+category+'</td><td>'+salethrough+'</td><td><button type="button" class="btn btn-primary btn-sm deletelist">Delete</button></td></tr>');
		
		// submit button show
		$('#save_performance').show();
		
		// delete bind row
		$('.deletelist').unbind();
		$('.deletelist').on('click',function(){
			$(this).parents('tr').remove();
			if($('#performancelist tbody tr').length == 0){
				$('#performancelist tbody').html('<tr><td colspan="4">No data added.</td></tr>');
				// submit button show
				$('#save_performance').hide();
				$('#rowitem').val('');
			}	
		});
	});
	
	// save performance button click
	$('#save_performance').on('click',function(){
		if($('#performancelist tbody tr:eq(0)').text() == "No data added."){
			$('#errorlist').show();
			$('#errorlist').html("Please add atlest one performance item into the list.");
			return false;
		}
		var rawitem = [];
		// check duplicate performance
		if($('#performancelist tbody tr').length > 0){
			$('#performancelist tbody tr').each(function () {
				var obj = {};
				obj.pcode = $(this).find('td').eq(0).text();
				obj.category = $(this).find('td').eq(1).text();
				obj.sthrough = $(this).find('td').eq(2).text();
				rawitem.push(obj);
			});
		}
		
		$('#rowitem').val(JSON.stringify(rawitem));
		// form submit
		$('#performanceform').submit();
	});
});