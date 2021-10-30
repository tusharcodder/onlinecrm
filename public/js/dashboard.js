$(function(){
	'use strict';
	var saledata = [];
	// Fetch data of last 12 month sale report
	$.ajax({
		url: "/salechartdetails",
		type: 'GET',
		async:false,
		dataType: "json",
		success: function( data ) {
			//  using ajax get details of chart
			saledata = data;
		}
	});
	new Morris.Bar({
		element: 'salechart',
		data: saledata,
		xkey: 'monyear',
		ykeys: ['totalqty'],
		labels: ['Total Sale Qty'],
		barColors: ['#00cccc'],
		gridLineColor: '#e5e9f2',
		gridStrokeWidth: 1,
		gridTextSize: 11,
		hideHover: 'auto',
		resize: true
	});
});