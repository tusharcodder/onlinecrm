<!DOCTYPE html>
<html lang="en">
	<head>
		@include('include.head')
	</head>
	<body class="page-profile">
		@include('include.nav')

		<div class="content content-fixed">
			<div class="pd-x-0 pd-lg-x-10 pd-xl-x-0">
				<div class="row row-xs">
					@yield('content')
				</div>
			</div>
		</div>
		
		@include('include.footer')
		
		@include('include.foot')
		@yield('footer-script')  
	</body>
</html>