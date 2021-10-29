<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>sale_date</th>
				<th>invoice_no</th>
				<th>po_no</th>
				<th>brand</th>
				<th>category</th>
				<th>vendor_type</th>
				<th>vendor_name</th>
				<th>aggregator_vendor_name</th>
				<th>hsn_code</th>
				<th>sku_code</th>
				<th>product_code</th>
				<th>colour</th>
				<th>size</th>
				<th>quantity</th>
				<th>vendor_discount</th>
				<th>mrp</th>
				<th>before_tax_amount</th>
				<th>state</th>
				<th>cgst</th>
				<th>sgst</th>
				<th>igst</th>
				<th>sale_price</th>
				<th>total_sale_amount</th>
				<th>cost_price</th>
				<th>total_cost_amount</th>
				<th>receivable_amount</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ \Carbon\Carbon::parse($res->sale_date)->format('d-m-Y') }}</td>
					<td >{{ $res->invoice_no }}</td>
					<td >{{ $res->po_no }}</td>
					<td >{{ $res->brand }}</td>
					<td >{{ $res->category }}</td>
					<td >{{ $res->vendor_type }}</td>
					<td >{{ $res->vendor_name }}</td>
					<td >{{ $res->aggregator_vendor_name }}</td>
					<td >{{ $res->hsn_code }}</td>
					<td >{{ $res->sku_code }}</td>
					<td >{{ $res->product_code }}</td>
					<td >{{ $res->colour }}</td>
					<td >{{ $res->size }}</td>
					<td >{{ $res->quantity }}</td>
					<td >{{ $res->vendor_discount }}</td>
					<td >{{ $res->mrp }}</td>
					<td >{{ $res->before_tax_amount }}</td>
					<td >{{ $res->state }}</td>
					<td >{{ $res->cgst }}</td>
					<td >{{ $res->sgst }}</td>
					<td >{{ $res->igst }}</td>
					<td >{{ $res->sale_price }}</td>
					<td >{{ $res->total_sale_amount }}</td>
					<td >{{ $res->cost_price }}</td>
					<td >{{ $res->total_cost_amount }}</td>
					<td >{{ $res->receivable_amount }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>