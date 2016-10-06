@extends('asimov/layout/auth')

@section('content')
<div class="login-logo">
	<img src="/backend/img/ttc.png" />
</div>

<div class="lc-block toggled" id="l-login">
	<h1>
		Reset password
	</h1>
	<form method="post" action="{{ URL::route('password.email') }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />

		<div class="input-group m-b-20">
			<span class="input-group-addon"><i class="md md-person"></i></span>

			<div class="fg-line">
				<input type="text" class="form-control" placeholder="Email" name="email">
			</div>
		</div>

		<div class="clearfix"></div>

		<button id="login-button" class="btn btn-login btn-danger btn-float">
			<i class="md md-arrow-forward"></i>
		</button>
	</form>
</div>
@endsection
