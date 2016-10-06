<!DOCTYPE html>
<!--[if IE 9 ]>
<html class="ie9"><![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ \Config::get('asimov.common.name') }}</title>

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link href="/asimov/dist/asimov.min.css" rel="stylesheet"/>
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>

	@yield('css_block')
</head>
<body class="sw-toggled">
<header id="header">
	<ul class="header-inner">
		<li id="menu-trigger" data-trigger="#sidebar">
			<div class="line-wrap">
				<div class="line top"></div>
				<div class="line center"></div>
				<div class="line bottom"></div>
			</div>
		</li>

		<li class="logo hidden-xs hidden-sm">
			<a href="{{ URL::route('home') }}" class="logo-link">
				<img src="/backend/img/ttc.png" class="img-responsive" />
			</a>
		</li>
		<li class="logo pull-right">
			<a href="{{ URL::route('auth.logout') }}"><i class="md md-history"></i> Logout</a>
		</li>
		<li class="logo pull-right">
			<a href="">
				{{ Auth::user()->email }}
			</a>
		</li>
	</ul>

	<!-- Top Search Content -->
	<div id="top-search-wrap">
		<form action="{{ URL::route('search') }}">
			<input type="text" name="q" value="{{ Input::get('q', '') }}">
		</form>
		<i id="top-search-close">&times;</i>
	</div>
</header>

<section id="main">
	<aside id="sidebar">
		<div class="sidebar-inner">
			<div class="si-inner">
				<ul class="main-menu">
					<li><a href="/"><i class="md md-home"></i> Home</a></li>

					{!! $main_menu_items !!}

				</ul>
			</div>
		</div>
	</aside>

	<section id="content">
		<div id="notification-container"></div>
		<div class="container">
			<div class="block-header">
				<h2>@yield('page.title')</h2>
			</div>

			@section('content')
				<div class="card">
					<div class="card-header">
						<h2>Asimov
							<small>/resources/view/asimov/layout/master.blade.php</small>
						</h2>
					</div>

					<div class="card-body">
						<p>Override 'content' section</p>
					</div>
				</div>
			@show

		</div>
	</section>
</section>


<script src="/asimov/dist/asimov.min.js"></script>

@yield('script_block')
@include('asimov.partials.notifications')

</body>
</html>
