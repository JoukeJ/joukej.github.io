<p>
	U ontvangt deze mail omdat u een nieuw wachtwoord heeft aangevraagd voor <b>{{ $user->email }}</b>.
	<br/>
	Met onderstaande link past u uw wachtwoord aan.
</p>
<a href="{{ URL::route('password.token', [$token]) }}">{{ URL::route('password.token', [$token]) }}</a>
<p>
	U kunt deze mail negeren als u dit niet heeft aangevraagd.
</p>
