$(document).ready(function(){
	
	function isEmpty(value){
		return (value == null || value.length === 0);
	}
	
	var tblSale = $('#tblSaleData').DataTable();
	/*var skuval = '';
	// initialise data table
	tblSale = $('#tblSaleData').DataTable({
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
				title: 'Sale Data',
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
			url: "bcsalescreate",
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
			{ mData: 'vendor_type'},
			{ mData: 'aggregator_vendor_name'},
			{ mData: 'hsn_code'},
			{ mData: 'sku_code'},
			{ mData: 'product_code'},
			{ mData: 'brand'},
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
		tblSale.clear().destroy().draw();
		loadSaleData(skuval);
	});
	
	// Send message on enter key
	$("#updatesale").on('click',function (e) {
		var skuval = $('#skucode').val();
		if( !isEmpty(skuval) ){
			$.ajax({
				type: 'post',
				url: "bcsalescreate",
				data: {
					skuval: skuval,
					//_token: "{{csrf_token()}}"
				},	
				success: function(result){
					
					var data = jQuery.parseJSON(result);
					if( !isEmpty(data) ){
						var data0 = data.aaData[0];
						$('#invoice_no').val(data0.invoice_no);
						$('#po_no').val(data0.po_no);
						$('#vendor_type').val(data0.vendor_type);
						$('#vendor_name').val(data0.vendor_name);
						$('#aggregator_vendor_name').val(data0.aggregator_vendor_name);
						$('#sale_date').val(data0.sale_date);
						$('#hsn_code').val(data0.hsn_code);
						$('#sku_code').val(data0.sku_code);
						$('#product_code').val(data0.product_code);
						$('#brand').val(data0.brand);
						$('#category').val(data0.category);
						//$('#colour').val(data0.colour);
						//$('#size').val(data0.size);
						//$('#quantity').val(data0.quantity);
						$('#mrp').val(data0.mrp);
						$('#before_tax_amount').val(data0.before_tax_amount);
						$('#state').val(data0.state);
						$('#igst').val(data0.igst);
						$('#sgst').val(data0.sgst);
						$('#cgst').val(data0.cgst);
						$('#sale_price').val(data0.sale_price);
						$('#total_sale_amount').val(data0.total_sale_amount);
						$('#cost_price').val(data0.cost_price);
						$('#total_cost_amount').val(data0.total_cost_amount);
						$('#receivable_amount').val(data0.receivable_amount);
					}else{
						$('#invoice_no').val('');
						$('#po_no').val('');
						$('#vendor_type').val('');
						$('#vendor_name').val('');
						$('#aggregator_vendor_name').val('');
						$('#sale_date').val('');
						$('#hsn_code').val('');
						$('#sku_code').val('');
						$('#product_code').val('');
						$('#brand').val('');
						$('#category').val('');
						//$('#colour').val('');
						//$('#size').val('');
						//$('#quantity').val('');
						$('#mrp').val('');
						$('#before_tax_amount').val('');
						$('#state').val('');
						$('#igst').val('');
						$('#sgst').val('');
						$('#cgst').val('');
						$('#sale_price').val('');
						$('#total_sale_amount').val('');
						$('#cost_price').val('');
						$('#total_cost_amount').val('');
						$('#receivable_amount').val('');
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
	/*$("#saleform").validate({
		
		rules: {
			invoice_no: 	"required",
			//po_no: 	"required",
			vendor_type: 	"required",
			vendor_name: 	"required",
			sale_date: 	"required",
			aggregator_vendor_name: 	"required",
			hsn_code: 	"required",
			//sku_code: 	"required",
			//product_code: 	"required", 
			brand: 	"required",
			category: 	"required",
			sku_code: 	"required",
			product_code: 	"required",
			//hsn_code: 	"required",
			colour: 	"required",
			size: 	"required",
			//description: 	"required",
		},
		messages: {
			invoice_no: 	'This field is required',
			vendor_type: 	'This field is required',
			vendor_name: 	'This field is required',
			sale_date: 	'This field is required',
			aggregator_vendor_name: 	'This field is required',
			hsn_code: 	'This field is required',
			brand: 	'This field is required',
			category: 	'This field is required',
			sku_code: 	'This field is required',
			product_code: 	'This field is required',
			colour: 	'This field is required',
			size: 	'This field is required',
		}
	});
	*/
	// Send message on enter key
	$("#save_sale").on('click',function (e) {

		// validate form fields
		/*if(!$("#saleform").valid()){
			$('#invoice_no').focus();
			return false;
		}*/

		var sale_date = $('#sale_date').val();
		var invoice_no = $('#invoice_no').val();
		var po_no = $('#po_no').val();
		var vendor_type = $('#vendor_type').val();
		var vendor_name = $('#vendor_name').val();
		var aggregator_vendor_name = $('#aggregator_vendor_name').val();
		var hsn_code = $('#hsn_code').val();
		var sku_code = $('#sku_code').val();
		var product_code = $('#product_code').val();
		var brand = $('#brand').val();
		var category = $('#category').val();
		var colour = $('#colour').val();
		var size = $('#size').val();
		var quantity = $('#quantity').val();
		var mrp = $('#mrp').val();
		var before_tax_amount = $('#before_tax_amount').val();
		var state = $('#state').val();
		var igst = $('#igst').val();
		var sgst = $('#sgst').val();
		var cgst = $('#cgst').val();
		var sale_price = $('#sale_price').val();
		var total_sale_amount = $('#total_sale_amount').val();
		var cost_price = $('#cost_price').val();
		var total_cost_amount = $('#total_cost_amount').val();
		var receivable_amount = $('#receivable_amount').val();
		
		var fd = new FormData();
        //var files = $('#product_image')[0].files[0];
		//fd.append('product_image',files);
        fd.append('sale_date',sale_date);
        fd.append('invoice_no',invoice_no);
        fd.append('po_no',po_no);
        fd.append('vendor_type',vendor_type);
        fd.append('vendor_name',vendor_name);
        fd.append('aggregator_vendor_name',aggregator_vendor_name);
        fd.append('hsn_code',hsn_code);
        fd.append('sku_code',sku_code);
        fd.append('product_code',product_code);
        fd.append('brand',brand);
        fd.append('category',category);
        fd.append('colour',colour);
        fd.append('size',size);
        fd.append('quantity',quantity);
        fd.append('mrp',mrp);
        fd.append('before_tax_amount',before_tax_amount);
        fd.append('state',state);
        fd.append('igst',igst);
        fd.append('sgst',sgst);
        fd.append('cgst',cgst);
        fd.append('sale_price',sale_price);
        fd.append('total_sale_amount',total_sale_amount);
        fd.append('cost_price',cost_price);
        fd.append('total_cost_amount',total_cost_amount);
        fd.append('receivable_amount',receivable_amount);
        //fd.append('_token',"{{csrf_token()}}");
		
		$.ajax({
			type: 'post',
			//async: false,
			url: "savebcsales",
			data: fd,
			processData: false,
			contentType: false,
			success: function(result){
				
				if(result == 1){
					$('#invoice_no').val('');
					$('#po_no').val('');
					$('#vendor_type').val('');
					$('#vendor_name').val('');
					$('#aggregator_vendor_name').val('');
					$('#sale_date').val('');
					$('#hsn_code').val('');
					$('#sku_code').val('');
					$('#product_code').val('');
					$('#brand').val('');
					$('#category').val('');
					//$('#colour').val('');
					//$('#size').val('');
					//$('#quantity').val('');
					$('#mrp').val('');
					$('#before_tax_amount').val('');
					$('#state').val('');
					$('#igst').val('');
					$('#sgst').val('');
					$('#cgst').val('');
					$('#sale_price').val('');
					$('#total_sale_amount').val('');
					$('#cost_price').val('');
					$('#total_cost_amount').val('');
					$('#receivable_amount').val('');
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
	$("#Salelist").val();
	
});

// Loads data when search
function loadSaleData(skuval){
	
	// initialise data table
	tblSale = $('#tblSaleData').DataTable({
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
				title: 'Sale Data',
				exportOptions: {
					// choose column number to export.
					columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ,10 ,11, 12, 13, 14 ], // Columns
				}
			},
		],
		"columnDefs": [
			{ "width": "7%", "targets": [0] },
			{ "width": "7%", "targets": [1] },
			{ "width": "7%", "targets": [2] },
			{ "width": "7%", "targets": [3] },
			{ "width": "7%", "targets": [5] },
			{ "width": "7%", "targets": [6] },
			{ "width": "7%", "targets": [7] },
			{ "width": "7%", "targets": [8] },
			{ "width": "7%", "targets": [9] },
			{ "width": "7%", "targets": [7] },
			{ "width": "7%", "targets": [11] },
			{ "width": "8%", "targets": [12] },
			{ "width": "8%", "targets": [13] },
			{ "width": "7%", "targets": [14] },
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
					url: "deletebcsale",
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
			url: "bcsalescreate",
			type: 'POST',
			data:{
				skuval: skuval,
				//_token: "{{csrf_token()}}"
			}
		},
		"columns": [
			{ mData: 'id',
				"render": function ( data, type, row, meta ) {
					debugger;
					return meta.row + 1;
				}
			},
			{ mData: 'sale_date'},
			{ mData: 'invoice_no'},
			{ mData: 'po_no'},
			{ mData: 'brand'},
			{ mData: 'category'},
			{ mData: 'vendor_type'},
			{ mData: 'vendor_name'},
			{ mData: 'aggregator_vendor_name'},
			{ mData: 'state'},
			{ mData: 'colour'},
			{ mData: 'size'},
			{ mData: 'product_code'},
			{ mData: 'quantity'},
			//{ mData: 'id'},
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