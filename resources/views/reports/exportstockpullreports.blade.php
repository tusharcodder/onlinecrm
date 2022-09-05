<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Warehouse</th>
				<!--<th>Box Isbn</th>-->
				<th>Isbn 13</th>
				<th>Title</th>
				<th>Stock</th>
				<th>Quantity</th>
				<th>Rack Details</th>
			</tr>
		</thead>
		@if(count($results) > 0)
			@php $i = 0; @endphp
			@foreach ($results as $key => $stockpull)
				@php
					$stockpull->purqty = empty($stockpull->purqty) ? 0 : $stockpull->purqty;
					$stockpull->shipingqty = empty($stockpull->shipingqty) ? 0 : $stockpull->shipingqty;
					$actualstock = $stockpull->purqty;
					$actualstock = empty($actualstock) ? 0 : $actualstock;
				@endphp
								
				<tr>
					<td>{{ ++$i }}</td>
					<td>{{ $stockpull->warehouse_name }}</td>
					<!--<td>"{{ $stockpull->box_isbn }}"</td>-->
					<td>"{{ $stockpull->isbnno }}"</td>
					<td>{{ $stockpull->bookname }}</td>
					<td>{{ $actualstock }}</td>
					<td>{{ $stockpull->shipingqty }}</td>
					<td>{{ $stockpull->rack_details }}</td>
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>