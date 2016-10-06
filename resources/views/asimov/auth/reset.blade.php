@extends('asimov.layout.auth')

@section('content')
	<!-- Login -->
	<div class="lc-block toggled" id="l-login">
		<h1>
			Set new password
		</h1>
		<form method="post" action="{{ URL::route('password.reset') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<input type="hidden" name="token" value="{{ $token }}" />

			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="md md-person"></i></span>

				<div class="fg-line">
					<input type="text" class="form-control" name="email" placeholder="Email" />
				</div>
			</div>
			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="md md-lock"></i></span>

				<div class="fg-line">
					<input type="password" class="form-control" name="password" placeholder="{{ trans('auth.password') }}" />
				</div>
			</div>
			<div class="input-group m-b-20">
				<span class="input-group-addon"><i class="md md-lock"></i></span>

				<div class="fg-line">
					<input type="password" class="form-control" name="password_confirmation" placeholder="{{ trans('auth.password_confirmation') }}" />
				</div>
			</div>

			<button class="btn btn-login btn-danger btn-float">
				<i class="md md-arrow-forward"></i>
			</button>
		</form>
	</div>
@endsection
