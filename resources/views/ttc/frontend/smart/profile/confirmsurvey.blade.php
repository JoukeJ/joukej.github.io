@extends('ttc.frontend.smart.layout.master')

@section('content')
	@errors
	<p>
		{{ trans('frontend/profile/match.found_survey') }}
	</p>

	<strong>
		{{ $survey->name }}
	</strong>

	<br>
	<br>

	<a class="button button_success"
	   href="{{ route('survey', [$profile->identifier, $survey->identifier, $entity->identifier]) }}">{{ trans('frontend/profile/match.start') }}</a>

	<a class="button button_edit"
	   href="{{ URL::route('profile.show',$profile->identifier) }}">{{ trans('frontend/profile/match.cancel') }}</a>
@endsection
