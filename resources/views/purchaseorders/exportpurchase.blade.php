<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Bill_no</th>
				<th>isbn13</th>
				<th>vendor</th>              
				<th>quantity</th>
				<th>mrp</th>
				<th>discount</th>				
				<th>purchase_by</th>
				<th>purchase_date</th>				
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $res)
				<tr>
					<td >{{ $res->bill_no }}</td>
					<td >{{ $res->isbn13 }}</td>
					<td >{{ $res->vendor }}</td>				
					<td >{{ $res->quantity }}</td>
					<td >{{ $res->mrp }}</td>
					<td >{{ $res->discount }}</td>					
					<td >{{ $res->purchase_by }}</td>
					<td >{{ $res->purchase_date }}</td>				
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>