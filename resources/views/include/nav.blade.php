<header class="navbar navbar-header navbar-header-fixed"> <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
	<div class="navbar-brand"> <a href="{{route('home')}}" class="df-logo">Kult<span>prit</span></a> </div>
	<!-- navbar-brand -->
	<div id="navbarMenu" class="navbar-menu-wrapper">
		<div class="navbar-menu-header"> 
			<a href="/" class="df-logo">Kult<span>prit</span></a> 
			<a id="mainMenuClose" href=""><i data-feather="x"></i></a> 
		</div>
		<!-- navbar-menu-header -->
		<ul class="nav navbar-menu"> 
			@auth 
				<li class="nav-label pd-l-20 pd-lg-l-25 d-lg-none">Main Navigation</li>
				@can('show-dashboard')
					<li class="nav-item"> <a href="{{route('home')}}" class="nav-link"><i data-feather="pie-chart"></i>Dashboard</a></li>
				@endcan
				@canany(['role-list','user-list'])
					<li class="nav-item with-sub"> <a href="" class="nav-link"><i data-feather="user"></i> Users</a>
						<ul class="navbar-menu-sub"> 
							@role('Super admin')
								<li class="nav-sub-item">
									<a href="{{ route('permissions.index') }}" class="nav-sub-link">Manage Permission</a>
								</li>
							@endrole 
							@can('role-list')
								<li class="nav-sub-item"><a href="{{ route('roles.index') }}" class="nav-sub-link">Manage Role</a></li> 
							@endcan 
							@can('user-list')
								<li class="nav-sub-item"><a href="{{ route('users.index') }}" class="nav-sub-link">Manage User</a></li> 
							@endcan
						</ul>
					</li> 
				@endcanany 
				@canany(['manufacturer-list','vendor-list','discount-list','stock-list','sale-list','performances-list', 'gstslab-list'])
					<li class="nav-item with-sub"> <a href="" class="nav-link"><i data-feather="package"></i> Settings</a>
						<ul class="navbar-menu-sub"> 
							@can('manufacturer-list')
								<li class="nav-sub-item"><a href="{{ route('buyers.index') }}" class="nav-sub-link">Manage Manufacturer</a></li>
							@endcan 
							@can('vendor-list')
								<li class="nav-sub-item"><a href="{{ route('vendorss.index') }}" class="nav-sub-link">Manage Vendors</a></li> 
							@endcan 
							@can('discount-list')
								<li class="nav-sub-item"><a href="{{ route('discounts.index') }}" class="nav-sub-link">Manage Discounts</a></li> 
							@endcan 
							@can('gstslab-list')
								<li class="nav-sub-item"><a href="{{ route('gstslab.index') }}" class="nav-sub-link">Manage GST Slabs</a></li> 
							@endcan 
							@can('stock-list')
								<li class="nav-sub-item"><a href="{{ route('stocks.index') }}" class="nav-sub-link">Manage Stocks</a></li> 
							@endcan 
							@can('sale-list')
								<li class="nav-sub-item"><a href="{{ route('sales.index') }}" class="nav-sub-link">Manage Sales</a></li> 
							@endcan 
							@can('performances-list')
								<li class="nav-sub-item"><a href="{{ route('performances.index') }}" class="nav-sub-link">Manage Performances</a></li>
							@endcan 
						</ul>
					</li>
				@endcanany 
				@canany(['commission-report-list','discount-report-list','stock-report-list', 'performancereport'])
					<li class="nav-item with-sub"> 
						<a href="" class="nav-link"><i data-feather="layers"></i> Reports</a>
						<div class="navbar-menu-sub">
							<div class="d-lg-flex">
								<ul> 
									@can('commission-report-list')
										<li class="nav-sub-item"><a href="{{ route('commissionreport') }}" class="nav-sub-link"> Commission report</a></li>
									@endcan 
									@can('discount-report-list')
										<li class="nav-sub-item"><a href="{{ route('discountreport') }}" class="nav-sub-link"> Discount report</a></li>
									@endcan 
									@can('stock-report-list')
										<li class="nav-sub-item"><a href="{{ route('stockreport') }}" class="nav-sub-link"> Stock report</a></li> 
									@endcan 
									@can('performancereport')
										<li class="nav-sub-item"><a href="{{ route('performancereport') }}" class="nav-sub-link"> Performance report</a></li> 
									@endcan
								</ul>
							</div>
						</div>
						<!-- nav-sub -->
					</li>
				@endcanany
			@endauth
		</ul>
	</div>
	<!-- navbar-menu-wrapper -->
	<div class="navbar-right">
		<div class="dropdown dropdown-profile">
			<a href="" class="dropdown-link" data-toggle="dropdown" data-display="static">
				<div class="avatar avatar-sm avatar-online"><img src="{{asset('assets/assets/img/profile.png')}}" class="rounded-circle" alt=""></div>
			</a>
			<!-- dropdown-link -->
			<div class="dropdown-menu dropdown-menu-right tx-13">
				<div class="avatar avatar-lg mg-b-15 avatar-online">
					<img src="{{asset('assets/assets/img/profile.png')}}" class="rounded-circle" alt="">
				</div>
				<h6 class="tx-semibold mg-b-5">{{ Auth::user()->name }}</h6>
				<p class="mg-b-25 tx-12 tx-color-03"> </p>
				<a href="{{ route('profile',Auth::user()->id) }}" class="dropdown-item">
					<i data-feather="user"></i> View Profile
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="{{ route('logged-in-devices.list') }}">
					<i data-feather="log-out"></i>{{ __('Logged in devices list') }}
				</a>
				<a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
					<i data-feather="log-out"></i>Sign Out
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form>
			</div>
			<!-- dropdown-menu -->
		</div>
		<!-- dropdown -->
	</div>
	<!-- navbar-right -->
</header>
<!-- navbar -->