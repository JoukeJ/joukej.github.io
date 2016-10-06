<html>
	<head>
		<title>
			@if(isset($survey))
				{{ $survey->name }}
			@endif
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>

		@if($device === 'smart')
			<link href="/frontend/css/smart.css" rel="stylesheet" type="text/css"/>
		@else
			<link href="/frontend/css/feature.css" rel="stylesheet" type="text/css"/>
		@endif
	</head>
	<body>
		@yield('content')

		<script type="text/javascript" src="/frontend/js/frontend.min.js"></script>
	</body>
</html>
