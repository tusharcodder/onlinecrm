<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 <!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Online CRM') }}</title>
		
	<!-- Meta -->
	<meta name="description" content="Online CRM">
	<meta name="author" content="Tushar Gupta">

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/assets/img/favicon.PNG')}}"> 

	<!-- vendor css -->
	<link href="{{asset('assets/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">

	<!-- DashForge CSS -->
	<link rel="stylesheet" href="{{asset('assets/assets/css/dashforge.css')}}">
	<link rel="stylesheet" href="{{asset('assets/assets/css/dashforge.auth.css')}}">
</head>
<body> 
	<header class="navbar navbar-header navbar-header-fixed">
      <a href="" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
      <div class="navbar-brand">
        <a href="{{route('home')}}" class="df-logo"><img src="{{asset('assets/assets/img/logo.png')}}" width="100px"/></a>
      </div><!-- navbar-brand -->
      <div id="navbarMenu" class="navbar-menu-wrapper">
        </div>
    </header><!-- navbar -->
	 <div class="content content-fixed content-auth">
      <div class="container">			
			@yield('indexcontent')			
		</div>
	</div>
	  <footer class="footer">
      <div>
        <span>&copy; 2021 </span>
        <span>Powered by <a href="{{route('home')}}">Online CRM</a></span>
      </div>
      <div style="display:none">
        <nav class="nav">
          <a href="#" class="nav-link">Licenses</a>
          <a href="#" class="nav-link">Change Log</a>
          <a href="#" class="nav-link">Get Help</a>
        </nav>
      </div>
    </footer>
</body>
</html>