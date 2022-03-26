<html> 
<body>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>S No</th>
				<th>warehouse</th>
				<th>Isbn</th>
				<th>Book Title</th>
				<th>Stock</th>				
			</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $val)									
				<tr>
					<td>{{ ++$key }}</td>
					<td>{{ $val->name }}</td>
					<td>{{ $val->isbn13 }}</td>
					<td>{{ $val->book_title }}</td>
					<td>{{ $val->stock }}</td>					
				</tr>
			@endforeach
		@endif
	</table>
</body> 
</html>