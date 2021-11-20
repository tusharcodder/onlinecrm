@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Purchase Order Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('purchaseorders.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>				
                <div class="card-body">
					<div class="row">					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Bill no:</strong><br/>
								{{ $purchaseorders[0]->bill_no }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>ISBN 13:</strong><br/>
								{{ $purchaseorders[0]->isbn13 }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Vendor name:</strong><br/>
								{{ $purchaseorders[0]->vendor }}
							</div>
						</div>						
						
					</div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-8">
							<div class="form-group">
								<strong>Book Title:</strong><br/>
								{{ $purchaseorders[0]->name }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>MRP:</strong><br/>
								{{ $purchaseorders[0]->mrp }}
							</div>
						</div>
					</div>
					<div class="row">
					
						<div class="col-xs-12 col-sm-3 col-md-3">
							<div class="form-group">
								<strong>Discount(%):</strong><br/>
								{{ $purchaseorders[0]->sku }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-3 col-md-3">
							<div class="form-group">
								<strong>Cost Price name:</strong><br/>
								{{ $purchaseorders[0]->product_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-3 col-md-3">
							<div class="form-group">
								<strong>Purchase By:</strong><br/>
								{{ $purchaseorders[0]->purchase_by }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-3 col-md-3">
							<div class="form-group">
								<strong>Purchase date:</strong><br/>
								{{ \Carbon\Carbon::parse($purchaseorders[0]->purchase_date)->format('d-m-Y')}}
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection