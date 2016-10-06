@extends('asimov.layout.auth')

@section('content')
	<!-- Login -->
	<div class="login-logo">
		<img src="/backend/img/ttc.png" />
	</div>

	<div class="lc-block toggled" id="l-login">

		<form method="post" action="{{ URL::route('auth.login') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />

			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="md md-person"></i></span>

				<div class="fg-line">
					<input type="text" class="form-control" placeholder="Email" name="email">
				</div>
			</div>

			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="md md-lock"></i></span>

				<div class="fg-line">
					<input type="password" class="form-control" placeholder="Password" name="password">
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="checkbox">
				<label>
					<input type="checkbox" value="">
					<i class="input-helper"></i>
					Keep me signed in
				</label>
			</div>

			<div class="pull-right">
				<a href="{{ route('password.email') }}">Reset password</a>
			</div>

			<button id="login-button" class="btn btn-login btn-danger btn-float">
				<i class="md md-arrow-forward"></i>
			</button>
		</form>
	</div>
@endsection
