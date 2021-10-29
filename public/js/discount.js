$(document).ready(function(){
	
	// autocomplete on product code
	autocomplete('product_code', 'stocks', 'product_code');
	autocomplete('sku_code', 'stocks', 'sku_code');
	autocomplete('brand', 'stocks', 'brand');
	autocomplete('category', 'stocks', 'category');
	autocomplete('gender', 'stocks', 'gender');
	autocomplete('colour', 'stocks', 'colour');
	autocomplete('lotno', 'stocks', 'lotno');
	
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
			alert("Please select atleast one discount item for delete."); 
			return false;
		}else{ 
			var check = confirm("Are you sure you want to delete selected discount item?");  
			if(check == true)
				$('#selectedval').val(allVals.join(","));
			else
				return false;
		}  
	});
		
	// load vendor details for edit form 
	if($('#selvendor').val() != undefined && $('#type').val() != ""){
		if($('#type').val() == "Aggregator")
			$( ".aggregatorcontainer" ).show(); // add validate prop true
		loadvendor($('#type').val(), $('#selvendor').val());
	}
		
	// load agg vendor details for edit form 
	if($('#selaggvendor').val() != undefined && $('#selvendor').val() != "")	
		loadaggregatorvendor($('#selvendor').val(), $('#type').val(), $('#selaggvendor').val());
	
	// on change of type load vendor details
	$("#type").change(function () {
		var val = $(this).val();
		var appenddata = "<option value = '' >-- Select --</option>";
		$("#vendor").html(appenddata);
		$("#aggregator_vendor").html(appenddata);
		// for aggregator
		if($(this).val() == 'Aggregator'){
			// add validate prop true
			$( ".aggregatorcontainer" ).show();
		}else{
			// add validate prop false
			$( ".aggregatorcontainer" ).hide();
		}
		if(val != ""){		
			// load vendor details
			loadvendor(val, "");
		}
	});
	
	// onlick of type 
	$("#vendor").change(function () {
		var val = $(this).val();
		var appenddata = "<option value = '' >-- Select --</option>";
		$("#aggregator_vendor").html(appenddata);
		if(val != ""){
			var type = $("#type").val();
			// load aggregatorvendor details			
			loadaggregatorvendor(val, type, "");
		}
	});
	
	// on click add button
	$('#adddiscount').on('click',function(){
		$('#errorlist').hide();
		var type = $('#type').val();
		var vendor = $('#vendor').val();
		var aggregator_vendor = $('#aggregator_vendor').val();
		var product_code = $('#product_code').val();
		var discount = $('#discount').val();
		var valid_from_date = $('#valid_from_date').val();
		var valid_to_date = $('#valid_to_date').val();
		
		// check validation
		var flag = true;
		var error = [];
		if(type == "")
			error.push("Please select type.");
		if(vendor == "")
			error.push("Please select vendor.");
		if(aggregator_vendor == "" && type == "Aggregator")
			error.push("Please select aggregator vendor.");
		if(product_code == "")
			error.push("Please enter product_code.");
		if(discount == "" || discount < 0 || discount > 100)
			error.push("Please enter valid discount percent.");
		if(valid_from_date == "")
			error.push("Please select valid from date.");
		if(valid_to_date == "")
			error.push("Please select valid to date.");
		
		if(valid_from_date > valid_to_date)
			error.push("Please select valid discount dates.");
			
		if(error.length > 0){
			$('#errorlist').show();
			$('#errorlist').html(error.join("<br/>"));
			return false;	
		}
		
		
		// check duplicate discount
		if($('#discountlist tbody tr').length > 0){
			flag = false;
			$('#discountlist tbody tr').each(function () {
				ttype = $(this).find('td').eq(0).text();
				tvendor = $(this).find('td').eq(1).text();
				tagvendor = $(this).find('td').eq(2).text();
				tpcode = $(this).find('td').eq(3).text();
				tdis = $(this).find('td').eq(4).text();
				tvfdate = $(this).find('td').eq(5).text();
				tvtdate = $(this).find('td').eq(6).text();
				
				if(type == ttype && vendor == tvendor && aggregator_vendor == tagvendor && product_code == tpcode && valid_from_date == tvfdate && valid_to_date == tvtdate){
					flag = true;
				}
			});
			if(flag == true){
				$('#errorlist').show();
				$('#errorlist').html("Discount already added.");
				return false;
			}
		}
		
		if($('#discountlist tbody tr:eq(0)').text() == "No data added.")
			$('#discountlist tbody').html('');
		
		$('#discountlist tbody').append('<tr><td>'+type+'</td><td>'+vendor+'</td><td>'+aggregator_vendor+'</td><td>'+product_code+'</td><td>'+discount+'</td><td>'+valid_from_date+'</td><td>'+valid_to_date+'</td><td><button type="button" class="btn btn-primary btn-sm deletelist">Delete</button></td></tr>');
		
		// submit button show
		$('#save_discount').show();
		
		// delete bind row
		$('.deletelist').unbind();
		$('.deletelist').on('click',function(){
			$(this).parents('tr').remove();
			if($('#discountlist tbody tr').length == 0){
				$('#discountlist tbody').html('<tr><td colspan="8">No data added.</td></tr>');
				// submit button show
				$('#save_discount').hide();
				$('#rowitem').val('');
			}	
		});
	});
	
	// save discount button click
	$('#save_discount').on('click',function(){
		if($('#discountlist tbody tr:eq(0)').text() == "No data added."){
			$('#errorlist').show();
			$('#errorlist').html("Please add atlest one discount item into the list.");
			return false;
		}
		var rawitem = [];
		// check duplicate discount
		if($('#discountlist tbody tr').length > 0){
			$('#discountlist tbody tr').each(function () {
				var obj = {};
				obj.type = $(this).find('td').eq(0).text();
				obj.vendor = $(this).find('td').eq(1).text();
				obj.agvendor = $(this).find('td').eq(2).text();
				obj.pcode = $(this).find('td').eq(3).text();
				obj.dis = $(this).find('td').eq(4).text();
				obj.vfdate = $(this).find('td').eq(5).text();
				obj.vtdate = $(this).find('td').eq(6).text();
				rawitem.push(obj);
			});
		}
		
		$('#rowitem').val(JSON.stringify(rawitem));
		// form submit
		$('#discountform').submit();
	});
});

// function call for load vendor
function loadvendor(val, selval){
	// AJAX request 
	$.ajax({
		url: "/loadvendor/"+val,
		type: 'get',
		dataType: 'json',
		success: function(response){
			var appenddata = "<option value = '' >-- Select --</option>";
			if(response != null){
				$.each(response, function (key, value) {
					appenddata += "<option value = '" + value + "'>" + value + " </option>";
				});
			}
			$("#vendor").html(appenddata);
			if(selval != "")
				$("#vendor").val(selval);
			else
				$("#vendor").focus();
		}
	});
}

// function call load aggregatorvendor
function loadaggregatorvendor(val, type, selval){
	// AJAX request 
	$.ajax({
		url: "/loadaggregatorvendor/"+val+"/"+type,
		type: 'get',
		dataType: 'json',
		success: function(response){
			var appenddata = "<option value = '' >-- Select --</option>";
			if(response != null){
				$.each(response, function (key, value) {
					appenddata += "<option value = '" + value.aggregator_vendor_name + "'>" + value.aggregator_vendor_name + " </option>";
				});
			}
			$("#aggregator_vendor").html(appenddata);
			if(selval != "")
				$("#aggregator_vendor").val(selval);
			else
				$("#aggregator_vendor").focus();
		}
	});
}