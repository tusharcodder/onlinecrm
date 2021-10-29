$(document).ready(function(){
	
	function isEmpty(value){
		return (value == null || value.length === 0);
	}
	
	var tblStock = $('#tblStockData').DataTable();
	/*var skuval = '';
	// initialise data table
	tblStock = $('#tblStockData').DataTable({
		"iDisplayLength": 10,	
		"aLengthMenu": [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, "All"]],
		'paging'      : true,
		"pagingType"  : "full_numbers",
		'searching'   : true,
		'ordering'    : true,
		'lengthChange': true,
		'info'        : true,
		'bautoWidth'  : true,
		"bprocessing" : true,
		"bserverSide" : true,
		"scrollX": false,
		"cache": false,
		"dom": 'Bfrtip',
		"buttons": [
			'pageLength',
			{
				extend: 'excelHtml5',
				title: 'Stock Data',
				exportOptions: {
					// choose column number to export.
					columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ,10 ], // Columns
				}
			},
		],
		"columnDefs": [
			{ "width": "10%", "targets": [0] },
			{ "width": "10%", "targets": [1] },
			{ "width": "10%", "targets": [2] },
			{ "width": "10%", "targets": [3] },
			{ "width": "10%", "targets": [5] },
			{ "width": "10%", "targets": [6] },
			{ "width": "10%", "targets": [7] },
			{ "width": "10%", "targets": [8] },
			{ "width": "10%", "targets": [9] },
			{ "width": "10%", "targets": [10] },
		],
		"language": {
			"lengthMenu": "Display _MENU_ records per page",
			"zeroRecords": "Nothing found - sorry",
			"info": "Showing page _PAGE_ of _PAGES_",
			"infoEmpty": "No records available",
			"infoFiltered": "(filtered from _MAX_ total records)"
		},
		"drawCallback": function( settings ) {
			
			// call on completetask click
			$('.completetask').unbind();
			$('.completetask').on('click',function(){
				id = $(this).attr('data-taskid');
				$.ajax({
					type: 'post',
					url: "{{ url('completetask') }}/"+id,
					data: {
						id: id,
						_token: "{{csrf_token()}}"
					},	
					success: function(result){
						if(result == 1){
							$.toast({
								heading: 'Successfully Completed!',
								text: '',
								position: 'mid-center',
								loaderBg:'#fec107',
								icon: 'success',
								hideAfter: 1500, 
								stack: 6
							});
							location.reload(true);
						}else{
							$.toast({
								heading: 'Something wrong!',
								text: '',
								position: 'mid-center',
								loaderBg:'#fec107',
								icon: 'alert',
								hideAfter: 2500, 
								stack: 6
							});	
						}			
					},
					error: function (e) {
						if( e.status == 419 ){
							location.reload(true);
						}else{
							alert(e.status+' '+e.statusText, "error");			
						}			
					}
				});
			});
			
		},
		'initComplete': function(){
		},
		"ajax": {
			url: "bcstockscreate",
			type: 'POST',
			data:{
				skuval: skuval,
				//_token: "{{csrf_token()}}"
			}
		}, 
		"columns": [
			{ mData: 'id',
				"render": function ( data, type, row, meta ) {
					return meta.row + 1;
				}
			},
			{ mData: 'manufacture_date'},
			{ mData: 'brand'},
			{ mData: 'category'},
			{ mData: 'gender'},
			{ mData: 'colour'},
			{ mData: 'size'},
			{ mData: 'product_code'},
			{ mData: 'quantity'},
			{ mData: 'id'},
			{ mData: 'id'},
		],
	});
	*/
	
	// Send message on enter key
	$('#skucode').keyup(function(e){
		var skuval = $('#skucode').val();
		tblStock.clear().destroy().draw();
		loadStockData(skuval);
	});
	
	// Send message on enter key
	$("#updatestock").on('click',function (e) {
		var skuval = $('#skucode').val();
		if( !isEmpty(skuval) ){
			$.ajax({
				type: 'post',
				url: "bcstockscreate",
				data: {
					skuval: skuval,
					//_token: "{{csrf_token()}}"
				},	
				success: function(result){
					
					var data = jQuery.parseJSON(result);
					if( !isEmpty(data) ){
						var data0 = data.aaData[0];
						
						$('#manufacturer_name').val(data0.manufacturer_name);
						$('#country').val(data0.country);
						$('#manufacture_date').val(data0.manufacture_date);
						$('#cost').val(data0.cost);
						$('#stock_date').val(data0.stock_date);
						$('#brand').val(data0.brand);
						$('#category').val(data0.category);
						$('#gender').val(data0.gender);
						$('#colour').val(data0.colour);
						$('#size').val(data0.size);
						$('#lotno').val(data0.lotno);
						$('#sku_code').val(data0.sku_code);
						$('#product_code').val(data0.product_code);
						$('#hsn_code').val(data0.hsn_code);
						//$('#online_mrp').val(data0.online_mrp);
						//$('#offline_mrp').val(data0.offline_mrp);
						//$('#quantity').val(data0.quantity);
						//$('#product_image').val(data0.product_image);
						//$('#product_image').val(data0.product_image);
						$('#description').val(data0.description);
					}else{
						$('#manufacturer_name').val('');
						$('#country').val('');
						$('#manufacture_date').val('');
						$('#cost').val('');
						$('#stock_date').val('');
						$('#brand').val('');
						$('#category').val('');
						$('#gender').val('');
						$('#colour').val('');
						$('#size').val('');
						$('#lotno').val('');
						$('#sku_code').val('');
						$('#product_code').val('');
						$('#hsn_code').val('');
						//$('#online_mrp').val('');
						//$('#offline_mrp').val('');
						//$('#quantity').val('');
						//$('#product_image').val('');
						//$('#product_image').val('');
						$('#description').val('');
					}
				},
				error: function (e) {
					if( e.status == 419 ){
						location.reload(true);
					}else{
						alert(e.status+' '+e.statusText, "error");			
					}			
				}
			});
		}else{
			
		}
	});
	
	// form validate
	/*$("#stockform").validate({
		
		rules: {
			manufacturer_name: 	"required",
			//country: 	"required",
			manufacture_date: 	"required",
			cost: 	"required",
			stock_date: 	"required",
			brand: 	"required",
			category: 	"required",
			//gender: 	"required",
			//colour: 	"required", 
			size: 	"required",
			lotno: 	"required",
			sku_code: 	"required",
			product_code: 	"required",
			//hsn_code: 	"required",
			online_mrp: 	"required",
			offline_mrp: 	"required",
			//description: 	"required",
		},
		messages: {
			manufacturer_name: 	'This field is required',
			manufacture_date: 	'This field is required',
			cost: 	'This field is required',
			stock_date: 	'This field is required',
			brand: 	'This field is required',
			category: 	'This field is required',
			size: 	'This field is required',
			lotno: 	'This field is required',
			sku_code: 	'This field is required',
			product_code: 	'This field is required',
			online_mrp: 	'This field is required',
			offline_mrp: 	'This field is required',
		}
	});
	*/
	// Send message on enter key
	$("#save_stock").on('click',function (e) {

		// validate form fields
		/*if(!$("#stockform").valid()){
			$('#manufacturer_name').focus();
			return false;
		}*/

		var skuval = $('#skucode').val();
		var manufacturer_name = $('#manufacturer_name').val();
		var country = $('#country').val();
		var manufacture_date = $('#manufacture_date').val();
		var cost = $('#cost').val();
		var stock_date = $('#stock_date').val();
		var brand = $('#brand').val();
		var category = $('#category').val();
		var gender = $('#gender').val();
		var colour = $('#colour').val();
		var size = $('#size').val();
		var lotno = $('#lotno').val();
		var sku_code = $('#sku_code').val();
		var product_code = $('#product_code').val();
		var hsn_code = $('#hsn_code').val();
		var online_mrp = $('#online_mrp').val();
		var offline_mrp = $('#offline_mrp').val();
		var quantity = $('#quantity').val();
		//var product_image = $('#product_image').val();
		//var product_image = $('#product_image').val();
		var description = $('#description').val();
		
		var fd = new FormData();
        var files = $('#product_image')[0].files[0];
		fd.append('product_image',files);
        fd.append('skuval',skuval);
        fd.append('manufacturer_name',manufacturer_name);
        fd.append('country',country);
        fd.append('manufacture_date',manufacture_date);
        fd.append('cost',cost);
        fd.append('stock_date',stock_date);
        fd.append('brand',brand);
        fd.append('category',category);
        fd.append('gender',gender);
        fd.append('colour',colour);
        fd.append('size',size);
        fd.append('lotno',lotno);
        fd.append('sku_code',sku_code);
        fd.append('product_code',product_code);
        fd.append('hsn_code',hsn_code);
        fd.append('online_mrp',online_mrp);
        fd.append('offline_mrp',offline_mrp);
        fd.append('quantity',quantity);
        fd.append('description',description);
        //fd.append('_token',"{{csrf_token()}}");
		
		$.ajax({
			type: 'post',
			//async: false,
			url: "savebcstocks",
			data: fd,
			processData: false,
			contentType: false,
			success: function(result){
				
				if(result == 1){
					
					$('#manufacturer_name').val('');
					$('#country').val('');
					$('#manufacture_date').val('');
					$('#cost').val('');
					$('#stock_date').val('');
					$('#brand').val('');
					$('#category').val('');
					$('#gender').val('');
					$('#colour').val('');
					$('#size').val('');
					$('#lotno').val('');
					$('#sku_code').val('');
					$('#product_code').val('');
					$('#hsn_code').val('');
					$('#online_mrp').val('');
					$('#offline_mrp').val('');
					$('#quantity').val('');
					$('#product_image').val('');
					//$('#product_image').val('');
					$('#description').val('');
					alert('data inserted');
				}
			},
			error: function (e) {
				
				if( e.status == 419 ){
					//location.reload(true);
				}else{
					alert(e.status+' '+e.statusText, "error");			
				}			
			}
		});
	});
	
	// 
	$("#stocklist").val();
	
});

// Loads data when search
function loadStockData(skuval){
	
	// initialise data table
	tblStock = $('#tblStockData').DataTable({
		"iDisplayLength": 10,	
		"aLengthMenu": [[5, 10, 25, 50, 100, 500, -1], [5, 10, 25, 50, 100, 500, "All"]],
		'paging'      : true,
		"pagingType"  : "full_numbers",
		'searching'   : true,
		'ordering'    : true,
		'lengthChange': true,
		'info'        : true,
		'bautoWidth'  : true,
		"bprocessing" : true,
		"bserverSide" : true,
		"scrollX": false,
		"cache": false,
		"dom": 'Bfrtip',
		"buttons": [
			'pageLength',
			{
				extend: 'excelHtml5',
				title: 'Stock Data',
				exportOptions: {
					// choose column number to export.
					columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ,10 ], // Columns
				}
			},
		],
		"columnDefs": [
			{ "width": "10%", "targets": [0] },
			{ "width": "10%", "targets": [1] },
			{ "width": "10%", "targets": [2] },
			{ "width": "10%", "targets": [3] },
			{ "width": "10%", "targets": [5] },
			{ "width": "10%", "targets": [6] },
			{ "width": "10%", "targets": [7] },
			{ "width": "10%", "targets": [8] },
			{ "width": "10%", "targets": [9] },
			{ "width": "10%", "targets": [10] },
		],
		"language": {
			"lengthMenu": "Display _MENU_ records per page",
			"zeroRecords": "Nothing found - sorry",
			"info": "Showing page _PAGE_ of _PAGES_",
			"infoEmpty": "No records available",
			"infoFiltered": "(filtered from _MAX_ total records)"
		},
		"drawCallback": function( settings ) {
			
			// call on delete click
			$('.delete').unbind();
			$('.delete').on('click',function(){
				id = $(this).attr('data-id');
				$.ajax({
					type: 'post',
					url: "deletebcstock",
					data: {
						id: id,
						_token: "{{csrf_token()}}"
					},	
					success: function(result){
						if(result == 1){
							alert('Successfully Deleted!');
							location.reload(true);
						}else{
							alert('Something wrong!');
						}			
					},
					error: function (e) {
						if( e.status == 419 ){
							//location.reload(true);
						}else{
							alert(e.status+' '+e.statusText, "error");			
						}			
					}
				});
			});
		},
		'initComplete': function(){
		},
		"ajax": {
			url: "bcstockscreate",
			type: 'POST',
			data:{
				skuval: skuval,
				//_token: "{{csrf_token()}}"
			}
		},
		"columns": [
			{ mData: 'id',
				"render": function ( data, type, row, meta ) {
					return meta.row + 1;
				}
			},
			{ mData: 'manufacture_date'},
			{ mData: 'brand'},
			{ mData: 'category'},
			{ mData: 'gender'},
			{ mData: 'colour'},
			{ mData: 'size'},
			{ mData: 'product_code'},
			{ mData: 'quantity'},
			{ mData: 'id'},
			{ mData: 'id',
				"render": function ( data, type, row, meta ) {
					icons = '';
					// Delete for all task.
					icons = '<i style="cursor:pointer;" class="delete" data-id="'+row.id+'" title="Delete">Delete</i> ';
					return icons;
				}
				
			},
		],
	});
}