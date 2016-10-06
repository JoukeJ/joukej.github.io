@extends('ttc.frontend.smart.layout.master')

@section('content')
	<h3>{{ trans('frontend/survey/complete.title') }}</h3>

	<a class="button" href="{{ URL::route('profile.show',$profile->identifier) }}">
		{{ trans('frontend/survey/complete.button') }}
	</a>
@endsection
