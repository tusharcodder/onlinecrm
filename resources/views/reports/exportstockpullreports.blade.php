<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Isbn 13</th>
				<th>Title</th>
				<th>Stock</th>
				<th>Quantity</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $stockpull)
				@php
					$stockpull->purqty = empty($stockpull->purqty) ? 0 : $stockpull->purqty;
					$stockpull->shiped_qty = empty($stockpull->shiped_qty) ? 0 : $stockpull->shiped_qty;
					$stockpull->shipingqty = empty($stockpull->shipingqty) ? 0 : $stockpull->shipingqty;
					$actualstock = $stockpull->purqty - $stockpull->shiped_qty;
					$actualstock = empty($actualstock) ? 0 : $actualstock;
				@endphp
								
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $stockpull->isbnno }}</td>
					<td>{{ $stockpull->bookname }}</td>
					<td>{{ $actualstock }}</td>
					<td>{{ $stockpull->shipingqty }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>