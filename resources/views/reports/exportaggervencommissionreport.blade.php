<html> 
<body>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th>No</th>
			<th>Invoice Date</th>
			<th>Type</th>
			<th>Vendor name</th>
			<th>Agg vendor</th>	
			<th>Brand</th>
			<th>Category</th>								
			<th>Product code</th>
			<th>Sku Code</th>
			<th>Ven Commission(%)</th>
			<th>Total Sale Amount</th>	
			<th>Commission Val</th>								
			<th>GST Charged to seller @ 18%</th>
			<th>GST on Commission</th>
			<th>GST on Product</th>
			<th>Payable GST by seller</th>
			<th>Realised Value to Bella Casa(Partner)(Tax incl)</th>
			<th>Realised Value to Bella Casa(Partner)(Tax Excl)</th>
			<th>Invoice for commission</th>
			<th>Aggre Ven Commission(%)</th>
			<th>Aggre Commission Val</th>
			<th>GST Charged to aggre seller @ 18%</th>
			<th>GST on aggre ven Commission</th>
			<th>Payable GST by agger ven</th>
			<th>Realised Value to Bella Casa(Partner)(Tax incl)(agger ven)</th>
			<th>Realised Value to Bella Casa(Partner)(Tax Excl)(agger ven)</th>
			<th>Invoice for agger ven commission</th>
		</tr>
		</thead>
		@if(count($results) > 0)
			@foreach ($results as $key => $commission)								
			<tr>
				<td>{{ ++$key }}</td>
				<td> {{ \Carbon\Carbon::parse($commission->sale_date)->format('d-m-Y')}}</td>				
				<td>{{ $commission->Type }}</td>
				<td>{{ $commission->Venname }}</td>					
				<td>{{ $commission->avname }}</td>													
				<td>{{ $commission->Brand}}</td>
				<td>{{ $commission->category}}</td>
				<td>{{ $commission->pcode}}</td>
				<td>{{ $commission->SkuCode}}</td>				
				<td>{{ $commission->commission }}</td>
				<td>{{ $commission->saleamt}}</td>
				<td>{{ $commission->commvalue}}</td>
				<td>18 %</td>
				<td>{{round($commission->commvalue*18/100,2)}}</td>
				<td>{{round($commission->gst,2)}}</td>
				<td>{{round(($commission->gst)-($commission->commvalue*18/100),2)}}</td>
				<td>{{($commission->saleamt)-($commission->commvalue)-(round($commission->commvalue*18/100,2))}}</td>									
				<td>{{($commission->saleamt)-($commission->commvalue)-(round($commission->commvalue*18/100,2))-(round(($commission->gst)-($commission->commvalue*18/100),2))}}</td>
				<td>{{($commission->commvalue)+(round($commission->commvalue*18/100,2))}}</td>									
				<td>{{ $commission->aggvencomm }}</td>
				<td>{{round((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100,2)}}</td>
				<td>18 %</td>
				<td>{{round(((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)*18/100,2) }}</td>		
				<td>{{round(($commission->gst)-(((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)*18/100),2)}}</td>
				<td>{{($commission->saleamt)-((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)
				-(((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)*18/100)}}</td>									
				<td>{{($commission->saleamt)-((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)
				-(((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)*18/100)-
				round(($commission->gst)-(((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)*18/100),2)}}</td>
				<td>{{((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)+
				round(((( $commission->saleamt)-($commission->commvalue))*$commission->aggvencomm/100)*
				18/100,2)}}</td>
			</tr>
		@endforeach
		@else
			<tr><td colspan="27">No records found.</td></tr>
		@endif
	</table>
</body> 
</html>