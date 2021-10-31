<header class="navbar navbar-header navbar-header-fixed"> <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
	<div class="navbar-brand"> <a href="{{route('home')}}" class="df-logo">Online<span>CRM</span></a> </div>
	<!-- navbar-brand -->
	<div id="navbarMenu" class="navbar-menu-wrapper">
		<div class="navbar-menu-header"> 
			<a href="/" class="df-logo">Online<span>CRM</span></a> 
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
				@canany(['vendor-list','market-place-list','supplier-list','binding-list','currencies-list'])
					<li class="nav-item with-sub"> <a href="" class="nav-link"><i data-feather="package"></i> Settings</a>
						<ul class="navbar-menu-sub"> 
							@can('vendor-list')
								<li class="nav-sub-item"><a href="{{ route('vendorss.index') }}" class="nav-sub-link">Manage Vendors</a></li> 
							@endcan
							@can('market-place-list')
								<li class="nav-sub-item"><a href="{{ route('marketplaces.index') }}" class="nav-sub-link">Manage Market Places</a></li> 
							@endcan
							@can('supplier-list')
								<li class="nav-sub-item"><a href="{{ route('suppliers.index') }}" class="nav-sub-link">Manage Suppliers</a></li> 
							@endcan
							@can('binding-list')
								<li class="nav-sub-item"><a href="{{ route('bindings.index') }}" class="nav-sub-link">Manage Bindings</a></li> 
							@endcan
							@can('currencies-list')
								<li class="nav-sub-item"><a href="{{ route('currencies.index') }}" class="nav-sub-link">Manage Currencies</a></li> 
							@endcan
						</ul>
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