@extends('layouts.content')
@section('content')
<?php 
	// use name space controller for access method of controller.
	use \App\Http\Controllers\CommissionreportController;
?>
<div class="container-fluid">
    <div class="row justify-content-center mb-2">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('Commission Report') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="card-body">
					<form action="{{ route('searchcommissionreport') }}" id="searchform" name="searchform" method="GET">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Type*') }}</label>
									
									<select class="form-control" id="type" name="type" required>
										<option value="">-- Select --</option>
										@foreach ($vtype as $key => $val)
											<option value="{{ $val }}" {{ $val == $type ? 'selected' : '' }}>{{ $val }}</option>
										@endforeach
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="vendor" class="col-form-label text-md-right">{{ __('Vendor') }}</label>
									<input type="hidden" value="{{$vendor}}" id="selvendor" name="selvendor">
									<select class="form-control" id="vendor" name="vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3 aggregatorcontainer"  style="display:none;">
								<div class="form-group">
									<label for="aggregator_vendor" class="col-form-label text-md-right">{{ __('Aggregator vendor') }}</label>
									<input type="hidden" value="{{ $aggregator_vendor }}" name="selaggvendor" id="selaggvendor">
									<select class="form-control" id="aggregator_vendor" name="aggregator_vendor">
										<option value="">-- Select --</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="ctype" class="col-form-label text-md-right">{{ __('Commsion Type*') }}</label>									
									<select class="form-control" id="ctype" name="ctype" required>
									
										<option value="" {{ '' == $ctype ? 'selected' : '' }}>-- Select --</option>
										<option value="Commission Based" {{ 'Commission Based' == $ctype ? 'selected' : '' }}>Commissioin Based</option>
										<option value="NOT Based" {{ 'NOT Based' == $ctype ? 'selected' : '' }}>NOT Based</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="row">							
							<div class="col-md-3">
								<div class="form-group">
									<label for="brand" class="col-form-label text-md-right">{{ __('Brand') }}</label>
									<input id="brand" type="text" class="form-control" name="brand" value="{{ $brand }}"  autocomplete="brand" >
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="category" class="col-form-label text-md-right">{{ __('Category') }}</label>
									<input id="category" type="text" class="form-control" name="category" value="{{ $category }}"  autocomplete="category" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="type" class="col-form-label text-md-right">{{ __('Product code') }}</label>
									<input id="product_code" type="text" class="form-control" name="product_code" value="{{ $product_code }}"  autocomplete="product_code" >
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="skucode" class="col-form-label text-md-right">{{ __('Sku Code') }}</label>
									<input id="skucode" type="text" class="form-control" name="skucode" value="{{ $skucode }}"  autocomplete="skucode" >
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="from_date" class="col-form-label text-md-right">{{ __('Sale from date') }}</label>
									<input id="from_date" type="date" class="form-control" name="from_date" value="{{ $from_date }}"  autocomplete="from_date">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="to_date" class="col-form-label text-md-right">{{ __('Sale to date') }}</label>
									<input id="to_date" type="date" class="form-control" name="to_date" value="{{ $to_date }}"  autocomplete="to_date">
								</div>
							</div>
						</div>
						<div class="form-group row mb-1">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" id="searchreport">
                                    {{ __('Search') }}
                                </button>
								@if(!empty($request->input()))
									<a href="{{ route('commissionreport') }}" class="btn btn-info">{{ __('Reset') }}
									</a>
								@endif
                            </div>
                        </div>
					</form>
				</div>
			</div>
		</div>
    </div>
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<div class="float-left">
						{{ __('List') }}
					</div>
					<div class="float-right">
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="card-body">
					@can('commission-report-download')
						@if($ctype == 'Commission Based' and $type !='Aggregator')
							<form action="{{ route('downloadcommissionreport') }}" method="POST">
							@csrf
								<div class="form-group row">
									<div class="col-md-2">
										<label for="exporttype" class="">{{ __('File Type*') }}</label> 
										<select class="form-control" id="exporttype" name="exporttype">
											<option value="csv">CSV</option>
											<option value="xls">XLS</option>
											<option value="xlsx">XLSX</option>
										</select>
									</div>
									<div class="col-md-1">
										<input type="hidden" value="" id="formval" name="formval">
										<button type="submit" class="btn btn-primary" style="margin-top: 30px !important;" id="downloadreport">
											{{ __('Download') }}
										</button>
									</div>
								</div>
							</form>						
						@elseif($ctype == 'Commission Based' and $type =='Aggregator')
							<form action="{{ route('downloadaggercommissionreport') }}" method="POST">
							@csrf
								<div class="form-group row">
									<div class="col-md-2">
										<label for="exportfile2" class="">{{ __('File Type*') }}</label> 
										<select class="form-control" id="exportfile2" name="exportfile2">
											<option value="csv">CSV</option>
											<option value="xls">XLS</option>
											<option value="xlsx">XLSX</option>
										</select>
									</div>
									<div class="col-md-1">
										<input type="hidden" value="" id="formval" name="formval">
										<button type="submit" class="btn btn-primary" style="margin-top: 30px !important;" id="downloadreport">
											{{ __('Download') }}
										</button>
									</div>
								</div>
							</form>						
						@elseif($ctype == 'NOT Based')
							<form action="{{ route('downloadnotbasedreport') }}" method="POST">
							@csrf
								<div class="form-group row">
									<div class="col-md-2">
										<label for="exportfile2" class="">{{ __('File Type*') }}</label> 
										<select class="form-control" id="exportfile3" name="exportfile3">
											<option value="csv">CSV</option>
											<option value="xls">XLS</option>
											<option value="xlsx">XLSX</option>
										</select>
									</div>
									<div class="col-md-1">
										<input type="hidden" value="" id="formval" name="formval">
										<button type="submit" class="btn btn-primary" style="margin-top: 30px !important;" id="downloadreport">
											{{ __('Download') }}
										</button>
									</div>
								</div>
							</form>
						@endif
					@endcan
					<div class="row mb-1">
						<div class="col-sm-8">	
						
							Showing {{($commissionreports->currentPage()-1)* $commissionreports->perPage()+($commissionreports->total() ? 1:0)}} to {{($commissionreports->currentPage()-1)*$commissionreports->perPage()+count($commissionreports)}}  of  {{$commissionreports->total()}}  Results
						
						</div>
						<div class="col-sm-4">
						</div>
					</div>
					<div class="table-responsive">
					{{-- show commission based table with Aggregator vendor --}}
					@if($ctype == 'Commission Based' && $type =='Aggregator')
						<table class="table table-bordered">					
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
						@if($commissionreports->total() > 0)
							@foreach ($commissionreports as $key => $commission)		
								<?php
									//columns variables
									$basicprice = 0;
									$finallistprice = 0;								
									$commissionamt = 0;
									$gstoncomm = 0;
									$finalgstbyseller = 0;
									$gstonproduct = 0;
									$gstcredit = 0;
									$payablebyseller = 0;
									$realisedvalue_inctax = 0;
									$realisedvalue_exctax = 0;
									$aggerven_gstoncomm = 0;
									$aggrevencomm_amt = 0;
									$finalgstby_aggreseller = 0;								
									$aggre_gstcredit = 0;
									$aggre_gst_payable = 0;
									$aggre_realisedvalue_inctax = 0;
									$aggre_realisedvalue_exctax = 0;
									$invoic_for_aggreven = 0;
									//condition start ---
									$commissionamt = $commission->commvalue;
									$gstoncomm = round($commission->commvalue*18/100,2);
									$finalgstbyseller =round(($commission->gst)-$gstoncomm,2);
									$realisedvalue_inctax = ($commission->saleamt - $commissionamt -  $gstoncomm) ;
									$realisedvalue_exctax = ( $commission->saleamt -  $commissionamt -  $gstoncomm - $finalgstbyseller );
									//aggregator_vendor
									$aggrevencomm_amt = round((( $commission->saleamt)-($commissionamt))*$commission->aggvencomm/100,2);
									$aggerven_gstoncomm = round($aggrevencomm_amt*18/100,2);
									$finalgstby_aggreseller = round($commission->gst -$aggerven_gstoncomm ,2);
									$aggre_realisedvalue_inctax = (($commission->saleamt)-($aggrevencomm_amt)
											- ($aggerven_gstoncomm)) ;
									$aggre_realisedvalue_exctax = (($commission->saleamt)- ($aggrevencomm_amt)
											- ( $aggerven_gstoncomm ) - ( $finalgstby_aggreseller ) );											
									$invoic_for_aggreven = ($aggrevencomm_amt + $aggerven_gstoncomm) ;
								?>
								
								<tr>
									<td>{{ ($commissionreports->currentPage()-1) * $commissionreports->perPage() + $loop->index + 1 }}</td>
									<td> {{ \Carbon\Carbon::parse($commission->sale_date)->format('d-m-Y')}}</td>			
									<td>{{ $commission->Type }}</td>
									<td>{{ $commission->Venname }}</td>	
									@if($type == 'Aggregator')
									<td>{{ $commission->avname }}</td>
									@endif									
									<td>{{ $commission->Brand}}</td>
									<td>{{ $commission->category}}</td>						
									<td>{{ $commission->pcode}}</td>
									<td>{{ $commission->SkuCode}}</td>
									<td>{{ $commission->commission }}</td>
									<td>{{ $commission->saleamt}}</td>
									<td>{{ $commission->commvalue}}</td>
									<td>18 %</td>
									<td>{{$gstoncomm}}</td>
									<td>{{round($commission->gst,2)}}</td>
									<td>{{$finalgstbyseller}}</td>
									<td>{{$realisedvalue_inctax}}</td>
									<td>{{$realisedvalue_exctax}}</td>
									<td>{{($commission->commvalue)+$gstoncomm}}</td>
									<td>{{ $commission->aggvencomm }}</td>
									<td>{{$aggrevencomm_amt}}</td>
									<td>18 %</td>
									<td>{{$aggerven_gstoncomm }}</td>
									<td>{{$finalgstby_aggreseller}}</td>
									<td>{{$aggre_realisedvalue_inctax}}</td>
									<td>{{$aggre_realisedvalue_exctax}}</td>
									<td>{{$invoic_for_aggreven}}</td>								
								</tr>
							@endforeach
							@else
								<tr><td colspan="27">No records found.</td></tr>
							@endif
						</table>		
					{{-- show commission based table without Aggregator vendor --}}
					@elseif($ctype == 'Commission Based' && $type !='Aggregator')
						<table class="table table-bordered">					
							<tr>
								<th>No</th>
								<th>Invoice Date</th>
								<th>Type</th>
								<th>Vendor name</th>				
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
							</tr>							
						@if($commissionreports->total() > 0)
							@foreach ($commissionreports as $key => $commission)		
								<?php
									//columns variables
									$basicprice = 0;
									$finallistprice = 0;								
									$commissionamt = 0;
									$gstoncomm = 0;
									$finalgstbyseller = 0;
									$gstonproduct = 0;
									$gstcredit = 0;
									$payablebyseller = 0;
									$realisedvalue_inctax = 0;
									$realisedvalue_exctax = 0;
									
									//condition start ---
									$commissionamt = $commission->commvalue;
									$gstoncomm = round($commission->commvalue*18/100,2);
									$finalgstbyseller =round(($commission->gst)-$gstoncomm,2);
									$realisedvalue_inctax = ($commission->saleamt - $commissionamt -  $gstoncomm) ;
									$realisedvalue_exctax = ( $commission->saleamt -  $commissionamt -  $gstoncomm - $finalgstbyseller );
								?>
								
								<tr>
									<td>{{ ($commissionreports->currentPage()-1) * $commissionreports->perPage() + $loop->index + 1 }}</td>
									<td> {{ \Carbon\Carbon::parse($commission->sale_date)->format('d-m-Y')}}</td>			
									<td>{{ $commission->Type }}</td>
									<td>{{ $commission->Venname }}</td>		
									<td>{{ $commission->Brand}}</td>
									<td>{{ $commission->category}}</td>						
									<td>{{ $commission->pcode}}</td>
									<td>{{ $commission->SkuCode}}</td>
									<td>{{ $commission->commission }}</td>
									<td>{{ $commission->saleamt}}</td>
									<td>{{ $commission->commvalue}}</td>
									<td>18 %</td>
									<td>{{$gstoncomm}}</td>
									<td>{{round($commission->gst,2)}}</td>
									<td>{{$finalgstbyseller}}</td>
									<td>{{$realisedvalue_inctax}}</td>
									<td>{{$realisedvalue_exctax}}</td>
									<td>{{($commission->commvalue)+$gstoncomm}}</td>
								</tr>
							@endforeach
							@else
								<tr><td colspan="18">No records found.</td></tr>
							@endif
						</table>							
					{{-- show NOT Based table--}}
					@elseif($ctype == 'NOT Based')
						<table class="table table-bordered">
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
							@if($commissionreports->total() > 0)
								@foreach ($commissionreports as $key => $commission)		
									@php
									$gstamt=0;
									$basiccosttovendor=0;
									$saleamtwithtax=0;
									$saleamtwithouttax = 0;
									$taxamt=0;
									$vendorshare= 0;
									$payableamt = 0;
									$finalpay = 0;
									$taxamtinmrp = 0;
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
									<td>{{ ($commissionreports->currentPage()-1) * $commissionreports->perPage() + $loop->index + 1 }}</td>
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
									<td>{{ $saleamtwithtaxgstper.'%' }}</td>
									<td>{{ $taxamtinmrp }}</td>
									<td>{{ round($saleamtwithouttax,2) }}</td>
									<td>{{ $taxamt }}</td>
									<td>{{ $vendorshare }}</td>
									<td>{{ round($saleamtwithtax,2) }}</td>
									<td>{{ $commission->commission.'%'  }}</td>
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
						@endif
					</div>			
					{{ $commissionreports->render() }}
				</div>			
			</div>
		</div>
    </div>
</div>
@endsection
@section('footer-script')
<script src="{{ asset('js/discount.js') }}" defer></script>
@endsection