@extends('layouts.content')
@section('content')
<!--<dashboard session-status="{{ session('status') }}"></dashboard>	-->
<div class="row row-xs">
	@canany(['user-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0 mx-10 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="user"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Users</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('users.index') }}">Manage User</a></h4>
				</div>
			</div>
			<!-- chart-three -->
		</div>
	</div>
	@endcanany
    <!-- col -->
	@canany(['vendor-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('vendorss.index') }}">Manage Vendors</a></h4>
				</div>
			</div>
			<!-- chart-three -->
		</div>
	</div>

	@endcanany
    <!-- col -->
	@canany(['market-place-list'])

	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('marketplaces.index') }}">Manage Market Places</a></h4>
				</div>
			</div>
			<!-- chart-three -->
		</div>
	</div>

	@endcanany
    <!-- col -->
	@canany(['supplier-list'])

	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('suppliers.index') }}">Manage Shipper</a></h4>
				</div>
			</div>
		</div>
	</div>
	
	@endcanany
    <!-- col -->
	@canany(['binding-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('bindings.index') }}">Manage Bindings</a></h4>
				</div>
			</div>
		</div>
	</div>
	
	@endcanany
    <!-- col -->
	@canany(['currencies-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('currencies.index') }}">Manage Currencies</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
	 <!-- col -->
	@canany(['warehouse-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('warehouse.index') }}">Manage Warehouse</a></h4>
				</div>
			</div>
		</div>
	</div>
	
	@endcanany
    <!-- col -->
	@canany(['sku-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="settings"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Settings</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('skudetails.index') }}">Manage Sku Code</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
    <!-- col -->
	@canany(['vendor-stock-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="database"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Stocks</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('vendorstocks.index') }}">Manage Vendor Stock</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
    <!-- col -->
	@canany(['tjw-stock-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="database"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Stocks</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('stocklist') }}">Manage TJW Stock</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
    <!-- col -->
	@canany(['customer-order-list'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="shopping-cart"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Orders</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('customerorders.index') }}">Manage Cus. Order</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
    
	<!-- col -->
	@canany(['shipment-report'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Reports</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('shipmentreport') }}">Shipment report</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
	
	<!-- col -->
	@canany(['shipment-track-import'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Reports</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('shipment-track-import') }}">Shipment Track Import</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
	
	<!-- col -->
	@canany(['stock-pull-report'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Reports</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('stockpullreport') }}">Stock Pull Report</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany	
	<!-- col -->
	@canany(['multi-packaging-report'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Reports</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('multipackagingreport') }}">Multi Packaging Report</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
	<!-- col -->
	@canany(['purchase-report'])
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0 mg-b-10">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Reports</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="{{ route('purchasereports.index') }}">Purchase report</a></h4>
				</div>
			</div>
		</div>
	</div>
	@endcanany
</div>
<!-- row -->
@endsection