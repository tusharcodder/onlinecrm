<DOCTYPE html>
<html lang='en-US'>
<head>
	<meta charset='utf-8'>
</head>
<body>
	<h1>Welcome to Kultprit</h1>
	Hello {{ $data['name'] }},<br><br>

	Thank you for your registration.<br><br>
	Your login details.<br>
	<b>Email: </b>{{ $data['email'] }}<br>
	<b>Password: </b>{{ $data['password'] }}<br><br>
	
	Thank You,<br> 
	Regard's,<br>
	{{ config('app.name') }}
</body>
</html>