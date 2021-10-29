<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kultprit') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	
	<link rel="stylesheet" href="{{ asset('font-awesome/css/font-awesome.min.css') }}">
	
	<!--<script src="{{ asset('js/jquery.min.js') }}" defer></script>-->
	
	<!-- custom js script -->
	<script src="{{ asset('js/common.js') }}" defer></script>

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Kultprit') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
						@guest

                        @else
							@canany(['role-list','user-list'])
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Users
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									@role('Super admin')
										<a class="dropdown-item" href="{{ route('permissions.index') }}">Manage Permission</a>
										@can('role-list')
											<a class="dropdown-item" href="{{ route('roles.index') }}">Manage Role</a>
										@endcan
									@endrole
									@can('user-list')
										<a class="dropdown-item" href="{{ route('users.index') }}">Manage User</a>
									@endcan
								</div>
							</li>
							@endcanany
							@canany(['manufacturer-list','vendor-list','discount-list','stock-list','sale-list','performances-list'])
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Settings
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									@can('manufacturer-list')
										<a class="dropdown-item" href="{{ route('buyers.index') }}">Manage Manufacturer</a>
									@endcan
									@can('vendor-list')
										<a class="dropdown-item" href="{{ route('vendorss.index') }}">Manage Vendors</a>
									@endcan
									@can('discount-list')
										<a class="dropdown-item" href="{{ route('discounts.index') }}">Manage Discounts</a>
									@endcan
									@can('stock-list')
										<a class="dropdown-item" href="{{ route('stocks.index') }}">Manage Stocks</a>
									@endcan
									<!--@can('bcstock-list')
										<a class="dropdown-item" href="{{ route('bcstocks.index') }}">Manage Stocks Add/Update</a>
									@endcan-->
									@can('sale-list')
										<a class="dropdown-item" href="{{ route('sales.index') }}">Manage Sales</a>
									@endcan
									@can('performances-list')
										<a class="dropdown-item" href="{{ route('performances.index') }}">Manage Performances</a>
									@endcan
								</div>
							</li>
							@endcanany
							@canany(['discount-report-list','stock-report-list'])
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Reports
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown">
									@can('discount-report')
										<a class="dropdown-item" href="{{ route('discountreport') }}">Discount report</a>
									@endcan
									@can('stock-report')
										<a class="dropdown-item" href="{{ route('stockreport') }}">Stock report</a>
									@endcan
									@can('performancereport')
										<a class="dropdown-item" href="{{ route('performancereport') }}">Performance report</a>
									@endcan
								</div>
							</li>
							@endcanany
						@endguest
                    </ul>
					
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else							
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
									<a class="dropdown-item" href="{{ route('profile',Auth::user()->id) }}">{{ __('Profile') }}</a>
									<a class="dropdown-item" href="{{ route('logged-in-devices.list') }}">{{ __('Logged in devices list') }}</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
	@yield('footer-script')
</body>
</html>