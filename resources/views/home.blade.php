@extends('layouts.content')
@section('content')
<!--<dashboard session-status="{{ session('status') }}"></dashboard>	-->
<div class="row row-xs">
	<div class="col-sm-6 col-lg-3 mg-t-10 mg-sm-t-0">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart-2"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Stock Report</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="#">View report</a></h4>
				</div>
			</div>
			<!-- chart-three -->
		</div>
	</div>

    <!-- col -->

	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart-2"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Discount Report</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="#">View report</a></h4>
				</div>
			</div>
			<!-- chart-three -->
		</div>
	</div>

<!-- col -->

	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart-2"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Performance Report</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="#">View report</a></h4>
				</div>
			</div>
			<!-- chart-three -->
		</div>
	</div>

<!-- col -->

	<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
		<div class="card card-body">
			<div class="media">
				<div class="wd-40 wd-md-50 ht-40 ht-md-50 bg-teal tx-white mg-r-10 mg-md-r-10 d-flex align-items-center justify-content-center rounded op-6">
					<i data-feather="bar-chart-2"></i>
				</div>
				<div class="media-body">
					<h6 class="tx-sans tx-uppercase tx-10 tx-spacing-1 tx-color-03 tx-semibold tx-nowrap mg-b-5 mg-md-b-8">Commission/Margin Report</h6>
					<h4 class="tx-20 tx-sm-18 tx-md-24 tx-normal tx-rubik mg-b-0"><a href="#">View report</a></h4>
				</div>
			</div>
		</div>
	</div>

	
    <div class="col-md-12 col-xl-12 mg-t-10 order-md-1 order-xl-0">
		 <div class="card ht-100p">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mg-b-0">Last Year Month Wise Sale Chart</h6>
            </div>
			<div class="card card-body">
				<div data-label="Example" class="df-example">
				  <div id="salechart" class="morris-wrapper-demo"></div>
				</div><!-- df-example -->
			</div>
            <!-- card-footer -->
        </div>
        <!-- card -->
    </div>
</div>
<!-- row -->
@endsection

@section('footer-script')
	<script src="{{ asset('js/dashboard.js') }}" defer></script>
@endsection	