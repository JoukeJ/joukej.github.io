@extends('ttc.frontend.smart.layout.master')

@section('content')

	<img src="{{ $entity->getUrl() }}" class="img-responsive"/>

	<form method="post" action="{{ $entity->getRouteUrl($profile) }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
		<input type="submit" value="next"/>
	</form>

@endsection
