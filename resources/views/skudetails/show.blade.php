@extends('layouts.content')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
		<div class="col-md-12">
            <div class="card">
                <div class="card-header">
					<div class="float-left">
						{{ __('Show Sku Code Details') }}
					</div>
					<div class="float-right">
						<a class="btn btn-primary btn-sm" href="{{ route('skudetails.index') }}"> Back to list</a>
					</div>
					<div class="clearfix"></div>
				</div>				
                <div class="card-body">
					<div class="row">					
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Market Place:</strong><br/>
								{{ $skudetail[0]->mplace }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Warehouse:</strong><br/>
								{{ $skudetail[0]->warehouse }}
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>ISBN13:</strong><br/>
								{{ $skudetail[0]->isbn13 }}
							</div>
						</div>
					</div>				
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>ISBN10:</strong><br/>
								{{ $skudetail[0]->isbn10 }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Sku Code:</strong><br/>
								{{ $skudetail[0]->sku_code }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>MRP:</strong><br/>
								{{ $skudetail[0]->mrp }}
							</div>
						</div>
					</div>	
                    <div class="row">
						<div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Disc:</strong><br/>
								{{ $skudetail[0]->disc }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Wght(kg):</strong><br/>
								{{ $skudetail[0]->wght }}
							</div>
						</div>
                        <div class="col-xs-12 col-sm-4 col-md-4">
							<div class="form-group">
								<strong>Pkg-wght(kg):</strong><br/>
								{{ $skudetail[0]->pkg_wght }}
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection