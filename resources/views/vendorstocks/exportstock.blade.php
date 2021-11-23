<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>stock_date</th>
				<th>vendor_id</th>
				<th>isbnno</th>
				<th>name</th>
				<th>author</th>
				<th>publisher</th>
				<th>binding_id</th>
				<th>currency_id</th>
				<th>price</th>
				<th>discount</th>
				<th>quantity</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res->stock_date }}</td>
					<td >{{ $res->vendor_name }}</td>
					<td >{{ $res->isbnno }}</td>
					<td >{{ $res->name }}</td>
					<td >{{ $res->author }}</td>
					<td >{{ $res->publisher }}</td>
					<td >{{ $res->binding_type }}</td>
					<td >{{ $res->currency }}</td>
					<td >{{ $res->price }}</td>
					<td >{{ $res->discount }}</td>
					<td >{{ $res->quantity }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>