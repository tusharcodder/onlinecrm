<html> 
<body>
<?php use \App\Http\Controllers\CommissionreportController;?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Invoice Date</th>
				<th>Type</th>
				<th>Vendor name</th>								
				<th>Brand</th>
				<th>Category</th>								
				<th>Product code</th>
				<th>Sku Code</th>
				<th>Ven discount(%)</th>
				<th>MRP/PC</th>	
				<th>Sale Qty</th>	
				<th>MRP value</th>	
				<th>GST(%) on MRP</th>	
				<th>TD-Item level Discount/Trade Discount(vendor funded)</th>								
				<th>Net Sales with Tax</th>
				<th>Output GST Rate_Net Sales</th>
				<th>Tax Amount including in MRP</th>
				<th>Net Sales without Tax</th>
				<th>Output GST Rate_Net Sales</th>	
				<th>Discount Vendor funded</th>
				<th>Net sale Price after vendor discount</th>
				<th>Vendor Margin %</th>
				<th>Vendor Margin Amount</th>
				<th>Output GST Rate to vendor billing</th>
				<th>Basic Cost to vendor on MRP/Total COGS</th>
				<th>Input GST Rate</th>
				<th>Input GST Rate Amt</th>
				<th>Koovs Billing</th>
				<th>Actual Agreed Margin Value</th>
				<th>Net sale rate/pcs</th>
				<th>Tax rate</th>
				<th>Tax amount</th>
				<th>Payable</th>
				<th>Final Payble</th>
			</tr>	
		</thead>
		@if(count($results) > 0)						
			@foreach ($results as $key => $commission)	
				@php
				$gstamt=0;
				$inputgst=0;
				$saleamtwithtax=0;
				$saleamtwithouttax = 0;
				$taxamt=0;
				$vendorshare= 0;
				$payableamt = 0;
				$finalpay = 0;
				@endphp
				<?php
					//gst percantage for MRP
					$mrpgstper =CommissionreportController::getGstFromPriceRange($commission->mrp);
					
					$taxamtinmrp =round($commission->mrp *$mrpgstper/(100+$mrpgstper),2);
					
					if(!empty($commission->vendor_discount))
					{
						$vendorshare =  $commission->mrp*$commission->vendor_discount/100;
					}
					else{
						$commission->vendor_discount=0;
					}
					
					//gst percantage for vendor commission
					$gstperoncommission = CommissionreportController::getGstFromPriceRange($vendorshare);
					
					$saleamtwithtax = ($commission->mrp) -($vendorshare);
					
					//gst percantage for sale amount with tax
					$saleamtwithtaxgstper =CommissionreportController::getGstFromPriceRange($saleamtwithtax);
					
					
					$basiccosttovendor=( $commission->mrp-($taxamtinmrp)-$commission->commvalue );
					
					//gst percantage for basic cost to vendor 
					$gstper =CommissionreportController::getGstFromPriceRange($basiccosttovendor);									
					
					$gstamt = $basiccosttovendor*$gstper/100;
					
															
					$taxamt = round($saleamtwithtax *$saleamtwithtaxgstper/(100+$saleamtwithtaxgstper),2);
															
					$saleamtwithouttax = ($saleamtwithtax*100)/(100+$saleamtwithtaxgstper);
					
					$payableamt = $saleamtwithtax - $taxamt -round($saleamtwithouttax*38/100,2);
					$finalpay= round($saleamtwithtax - $taxamt -round($saleamtwithouttax*38/100,2)+$gstamt,2);
				?>
				<tr>
					<td>{{ ++$key }}</td>
					<td> {{ \Carbon\Carbon::parse($commission->sale_date)->format('d-m-Y')}}</td>			
					<td>{{ $commission->Type }}</td>
					<td>{{ $commission->Venname }}</td>
					<td>{{ $commission->Brand}}</td>
					<td>{{ $commission->category}}</td>	
					<td>{{ $commission->pcode}}</td>
					<td>{{ $commission->SkuCode}}</td>									
					<td>{{ $commission->vendor_discount.'%' }}</td>
					<td>{{ $commission->mrp }}</td>
					<td>{{ $commission->qty }}</td>	
					<td>{{ $commission->mrpvalue }}</td>	
					<td>{{ $mrpgstper.'%' }}</td>									
					<td>{{ $vendorshare }}</td>
					<td>{{ round(($commission->mrp) -($vendorshare),2) }}</td>
					<td>{{ $saleamtwithtaxgstper.'%'}}</td>
					<td>{{ $taxamtinmrp }}</td>
					<td>{{ round($saleamtwithouttax,2) }}</td>
					<td>{{ $taxamt }}</td>
					<td>{{ $vendorshare }}</td>
					<td>{{ round($saleamtwithtax,2) }}</td>
					<td>{{ $commission->commission.'%'}}</td>
					<td>{{ $commission->commvalue}}</td>
					<td>{{ $gstperoncommission.'%' }}</td>
					<td>{{ $basiccosttovendor }}</td>
					<td>{{ $gstper.'%' }} </td>									
					<td>{{ round($gstamt,2) }}</td>
					<td>{{ round($basiccosttovendor+$gstamt,2) }}</td>
					<td>{{ round($saleamtwithouttax*38/100,2) }}</td>
					<td>{{ $saleamtwithtax }}</td>
					<td>{{ $saleamtwithtaxgstper.'%' }}</td>
					<td>{{ $taxamt }}</td>										
					<td>{{ $payableamt*$commission->qty }}</td>
					<td>{{ $finalpay*$commission->qty }}</td>
				</tr>
			@endforeach
		@else
			<tr><td colspan="34">No records found.</td></tr>
		@endif
	</table>
</body> 
</html>