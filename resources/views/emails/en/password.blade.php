<p>
	You're receiving this email because you requested a password reset for the user <b>{{ $user->email }}</b>.
	<br/>
	With the following link you can change your password.
</p>
<a href="{{ URL::route('password.token', [$token]) }}">{{ URL::route('password.token', [$token]) }}</a>
<p>
	You can ignore this email when you did not request a password reset.
</p>
