@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Stock Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('stocks.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>
				
                <div class="card-body">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Manufacturer name:</strong>
								{{ $stock->manufacturer_name }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Country:</strong>
								{{ $stock->country }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Manufacture date:</strong>
								{{ \Carbon\Carbon::parse($stock->manufacture_date)->format('d-m-Y')}}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Stock date:</strong>
								{{ \Carbon\Carbon::parse($stock->stock_date)->format('d-m-Y')}}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Brand:</strong>
								{{ $stock->brand }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Category:</strong>
								{{ $stock->category }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Gender:</strong>
								{{ $stock->gender }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Colour:</strong>
								{{ $stock->colour }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Size:</strong>
								{{ $stock->size }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Lotno:</strong>
								{{ $stock->lotno }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Sku code:</strong>
								{{ $stock->sku_code }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Product code:</strong>
								{{ $stock->product_code }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Hsn code:</strong>
								{{ $stock->hsn_code }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Online mrp:</strong>
								{{ $stock->online_mrp }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Offline mrp:</strong>
								{{ $stock->offline_mrp }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Quantity:</strong>
								{{ $stock->quantity }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<strong>Product image:</strong>
								@if(!empty($stock->img_url))
									<img src="{{ asset($stock->img_url) }}" class="img-thumbnail">
								@endif
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<strong>Description:</strong>
								{{ $stock->description }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection