<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 <!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Kultprit') }}</title>
	
<!-- Meta -->
<meta name="description" content="Kultprit Stock Management">
<meta name="author" content="Tushar Gupta">

<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/assets/img/favicon.PNG')}}">

<!--  vendor css -->
<link href="{{asset('assets/lib/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/typicons.font/typicons.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/prismjs/themes/prism-vs.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/datatables.net-dt/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/datatables.net-responsive-dt/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/lib/morris.js/morris.css')}}" rel="stylesheet">
<link href="{{asset('css/jquery-ui.css')}}" rel="stylesheet">


<!-- DashForge CSS -->
<link rel="stylesheet" href="{{asset('assets/assets/css/dashforge.css')}}">
<link rel="stylesheet" href="{{asset('assets/assets/css/dashforge.dashboard.css')}}">
