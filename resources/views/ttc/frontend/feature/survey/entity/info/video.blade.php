@extends('ttc.frontend.smart.layout.master')

@section('content')
	<h3>{{ $entity->description }}</h3>

	<div class="videoWrapper">
		<iframe src="{{ sprintf(config('ttc.survey.entity.video.services.'.$entity->service),$entity->url) }}" frameborder="0" allowfullscreen></iframe>
	</div>

	<form method="post" action="{{ $entity->getRouteUrl($profile) }}">
		<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
		<input type="submit" value="next"/>
	</form>
@endsection
