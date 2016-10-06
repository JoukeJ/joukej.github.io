<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Asimov</title>
		<link href="/frontend/dist/frontend.min.css" rel="stylesheet">
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<a class="navbar-brand" href="#">Asimov</a>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="/">Home</a></li>
					</ul>
					<ul class="nav navbar-nav pull-right">
						@if(Auth::check())
							<li><a href="{{ URL::route('frontend.profile.me') }}">{{ \Auth::user()->first_name }}</a>
							</li>
							<li><a href="{{ URL::route('auth.logout') }}">Logout</a></li>
						@else
							<li><a href="{{ URL::route('auth.login') }}">Login</a></li>
						@endif
					</ul>
				</div>
			</div>
		</nav>

		<div class="container">

			<div class="content">
				@yield('content')
			</div>

		</div>
		<!-- /.container -->
		<script src="/frontend/dist/frontend.min.js"></script>
	</body>
</html>
