<!DOCTYPE html>
<!--[if IE 9 ]>
<html class="ie9">
<![endif]-->
<!--[if gte IE 9]><!-->
<html>
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
		<title>{{ \Config::get('asimov.common.name') }}</title>

		<link href="/asimov/dist/asimov.min.css" rel="stylesheet"/>
	</head>
	<body class="login-content">
		<div class="container">
			@if (count($errors) > 0)
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			{!! Notification::showAll() !!}
			@yield('content')
		</div>
		<script src="/asimov/dist/asimov.min.js"></script>
	</body>
</html>
